<?php

namespace App\Models;

use App\Traits\Friendable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Blurb;
use App\Models\Membership\Membership;
use App\Models\Membership\Payment;
use App\Models\Membership\StripeCustomer;
use App\Models\Membership\Subscription;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements MustVerifyEmail
{

    use HasFactory, Notifiable, Friendable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'birthday',
        'avatar_url',
        'headshot_url',
        'cash',
        'coins',
        'last_currency',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'last_online', 'flood_gate', 'action_flood_gate'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     /** Functions related to the user are below */

     //Will determine if the user is online
    public function isOnline()
    {
         if($this->last_online->gt(Carbon::now()->subMinutes(2)))
         {
             return true;
         } else {
             return false;
         }
    }

    protected function getOrPaginate($builder, $perPage)
    {
        if ($perPage == 0) {
            return $builder->get();
        }
        return $builder->paginate($perPage);
    }

    public function get_feed()
    {
        $blurbs = Blurb::whereIn('author_id', auth()->user()->getFriends()->pluck('id'))->orWhere('author_id', auth()->user()->id)->latest()->paginate(6);
        return $blurbs;
    }

    public function items()
    {
        $this->hasMany(Item::class, 'creator_id');
    }

    public function comments()
    {
        $this->hasMany(Comment::class);
    }

    public function privacy()
    {
        if($this->hasOne(Privacy::class)->exists())
        {
            return $this->hasOne(Privacy::class);
        } else {
            Privacy::create(['user_id' => $this->id]);
            return $this->hasOne(Privacy::class);
        }
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id')->orderBy('created_at', 'DESC');
    }

    public function released_transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id')->where('release_at', '<', Carbon::now()->subDay())->where('released', '=', 0);
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->where('deleted', '=', '0');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function posts()
    {
        return $this->replies()->count() + $this->threads()->count();
    }

    public function lastThread()
    {
        return $this->hasOne(Thread::class)->where('deleted', '=', '0')->latest();
    }

    public function blurb()
    {
        return $this->hasOne(Blurb::class, 'author_id')->latest();
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, "from");
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, "to");
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'to')->where('read', 0)->orderBy('created_at', 'desc');
    }

    public function owns(Item $item)
    {
        $get = Inventory::where('item_id', '=', "$item->id")->where('user_id', '=', "$this->id")->first();
        if($get)
        {
            return true;
        } else {
            return false;
        }
    }

    public function isWearing(Item $item)
    {
        if($this->avatar->hat1_id == $item->id)
        {
            return true;
        } elseif($this->avatar->hat2_id == $item->id) {
            return true;
        } elseif($this->avatar->hat3_id == $item->id) {
            return true;
        } elseif($this->avatar->shirt_id == $item->id) {
            return true;
        } elseif($this->avatar->pants_id == $item->id) {
            return true;
        } elseif($this->avatar->face_id == $item->id) {
            return true;
        } elseif($this->avatar->tool_id == $item->id) {
            return true;
        } else {
            return false;
        }
    }

    public function specials()
    {
        $get = Inventory::where('user_id', '=', $this->id)->where('special', '=', 1)->get();
        return $get;
    }

    public function getUserValue()
    {
        // Fetch all items of the user where the special column is true
        $specialItems = $this->specials();

        // If there are no special items, return 0
        if ($specialItems->isEmpty()) {
            return 0;
        }

        // Calculate the total resale value
        $totalResaleValue = $specialItems->sum(function ($item) {
            return $item->item->avgResalePrice();
        });

        $totalResaleValue += $this->cash;

        return round($totalResaleValue);
    }

    public function getTotalCashEarningsLastWeek()
    {
        $lastWeek = Carbon::now()->subWeek();

        $totalEarnings = DB::table('user_transactions')
            ->where('type', 2)
            ->where('created_at', '>=', $lastWeek)
            ->where('user_id', '=', $this->id)
            ->sum('cash');

        return $totalEarnings;
    }

    public function getTotalCoinsEarningsLastWeek()
    {
        $lastWeek = Carbon::now()->subWeek();

        $totalEarnings = DB::table('user_transactions')
            ->where('type', 2)
            ->where('created_at', '>=', $lastWeek)
            ->where('user_id', '=', $this->id)
            ->sum('coins');

        return $totalEarnings;
    }

    public function getTotalFutureCash()
    {
        $now = Carbon::now();

        // Fetch total cash from user_transactions where release_at is in the future
        $totalFutureCash = DB::table('user_transactions')
            ->where('user_id', $this->id)
            ->where('release_at', '>', $now)
            ->sum('cash');

        return $totalFutureCash;
    }

    public function getTotalFutureCoins()
    {
        $now = Carbon::now();

        // Fetch total cash from user_transactions where release_at is in the future
        $totalFutureCoins = DB::table('user_transactions')
            ->where('user_id', $this->id)
            ->where('release_at', '>', $now)
            ->sum('coins');

        return $totalFutureCoins;
    }

    public function grant_item(Item $item)
    {
        if($this->owns($item))
        {
            return;
        }
        return Inventory::create([
            'user_id' => $this->id,
            'item_id' => $item->id,
            'type' => $item->type,
            'collection_number' => $this->generateSerial(),
            'special' => $item->special,
        ]);
    }

    public function revoke_item(Item $item)
    {
        return Inventory::where('user_id', '=', $this->id)->where('item_id', '=', $item->id)->delete();
    }

    public function grant_currency(int $amount, $type)
    {
        if($type == 1)
        {
            return $this->update(['cash' => $this->cash + $amount]);
        } elseif($type == 2) {
            return $this->update(['coins' => $this->coins + $amount]);
        }
    }

    public function revoke_currency(int $amount, $type)
    {
        if($type == 1)
        {
            return $this->update(['cash' => $this->cash - $amount]);
        } elseif($type == 2) {
            return $this->update(['coins' => $this->coins - $amount]);
        }
    }

    public function threadRead(Thread $thread)
    {
        $get = ThreadView::where('ip', '=', $_SERVER['REMOTE_ADDR'])->where('thread_id', '=', "$thread->id")->first();
        if($get)
        {
            return true;
        } else {
            return false;
        }
    }

    public function lastIp()
    {
        $lookup = Ip::where('user_id', '=', $_SERVER['REMOTE_ADDR'])->latest();

        return $lookup;
    }

    public function ips()
    {
        $log = Ip::where('user_id', '=', $this->id)->get();
        $ips = [];

        foreach ($log as $l) {
            if (!in_array($l->ip, $ips))
                $ips[] = $l->ip;
        }

        return $ips;
    }

    public function accountsLinkedByIP()
    {
        $log = Ip::where('user_id', '!=', $this->id)->whereIn('ip', $this->ips())->get();
        $users = [];
        $times = [];

        foreach ($log as $l) {
            if (!isset($times[$l->user_id]))
                $times[$l->user_id] = 0;

            $times[$l->user_id]++;

            if (!in_array($l->user_id, $users))
                $users[] = $l->user_id;
        }

        $accounts = User::whereIn('id', $users)->get();

        foreach ($accounts as $account)
            $account->times_linked = $times[$account->id];

        return $accounts;
    }

    public function get_avatar()
    {
        if($this->hasOne(Avatar::class)->exists())
        {
            if($this->avatar_url == 1)
            {
                app('App\Http\Controllers\API\AvatarsController')->render($this);
            }
            $url = "https://cdn.bloxcity.com/". $this->avatar_url . ".png";
            return $url;
        } else {
            Avatar::create(['user_id' => $this->id, 'shirt_id' => 1, 'pants_id' => 2]);
            //$this->grant_item(Item::find(1));
            //$this->grant_item(Item::find(2));
            if($this->avatar_url != "122e78ae562c138c215a2cb45b5e615783061bbfa5fc19faacffa0bcf82a9543")
                app('App\Http\Controllers\API\AvatarsController')->render($this);
            $url = "https://cdn.bloxcity.com/". $this->avatar_url . ".png";
            return $url;
        }
    }

    public function get_headshot()
    {
        $this->get_avatar();
        if($this->headshot_url != null)
        {
            $url = "https://cdn.bloxcity.com/". $this->headshot_url . ".png";
            return $url;
        } else {
            app('App\Http\Controllers\API\AvatarsController')->headshot($this);
            $url = "https://cdn.bloxcity.com/". $this->headshot_url . ".png";
            return $url;
        }
    }

    public function avatar()
    {
        return $this->hasOne(Avatar::class, 'user_id');
    }

    public function get_short_num($num) {
        if ($num < 999) {
            return $num;
        }
        else if ($num > 999 && $num <= 9999) {
            $new_num = substr($num, 0, 1);
            return $new_num.'K+';
        }
        else if ($num > 9999 && $num <= 99999) {
            $new_num = substr($num, 0, 2);
            return $new_num.'K+';
        }
        else if ($num > 99999 && $num <= 999999) {
            $new_num = substr($num, 0, 3);
            return $new_num.'K+';
        }
        else if ($num > 999999 && $num <= 9999999) {
            $new_num = substr($num, 0, 1);
            return $new_num.'M+';
        }
        else if ($num > 9999999 && $num <= 99999999) {
            $new_num = substr($num, 0, 2);
            return $new_num.'M+';
        }
        else if ($num > 99999999 && $num <= 999999999) {
            $new_num = substr($num, 0, 3);
            return $new_num.'M+';
        }
        else {
            return $num;
        }
    }

    public function get_membership()
    {
        $membership = "";
        if($this->membership > 0)
        {
            if($this->membership == 1)
            {
                $membership = "Bronze";
                $color = "danger";
            } elseif($this->membership == 2) {
                $membership = "Silver";
                $color = "secondary";
            } elseif($this->membership == 3) {
                $membership = "Gold";
                $color = "warning";
            }
        }
        return $membership;
    }

    public function guilds()
    {
        $members = GuildMember::where('user_id', '=', $this->id)->get();
        $guilds = [];

        foreach ($members as $member)
            $guilds[] = $member->guild->id;

        return Guild::whereIn('id', $guilds)->get();
    }

    public function guildsCount()
    {
        return GuildMember::where('user_id', '=', $this->id)->count();
    }

    public function guildsLimit()
    {
        if($this->membership == 0)
        {
            return 5;
        } elseif($this->membership == 1) {
            return 15;
        } elseif($this->membership == 2) {
            return 30;
        } elseif($this->membership == 3) {
            return 60;
        }
    }

    public function isGuildCapped()
    {
        if($this->guildsCount() >= $this->guildsLimit())
        {
            return true;
        } else {
            return false;
        }
    }

    public function isInGuild($guildId)
    {
        return GuildMember::where([
            ['user_id', '=', $this->id],
            ['guild_id', '=', $guildId]
        ])->exists();
    }

    public function sentRequest($guildId)
    {
        return GuildJoinRequest::where([
            ['user_id', '=', $this->id],
            ['guild_id', '=', $guildId]
        ])->exists();
    }

    public function rankInGuild($guildId)
    {
        return GuildMember::where([
            ['user_id', '=', $this->id],
            ['guild_id', '=', $guildId]
        ])->first()->rank();
    }

    public function primaryGuild()
    {
        if($this->primary_guild != 0)
        {
            return $this->belongsTo(Guild::class, 'primary_guild');
        }
    }

    public function badges()
    {
        $achievements = [];
        $badges = UserBadge::where('user_id', '=', $this->id)->get();

        foreach ($badges as $badge) {
            $data = config('badges')[$badge->badge_id];

            $badge = new \stdClass;
            $badge->name = $data['name'];
            $badge->description = $data['description'];
            $badge->image = asset("img/badges/{$data['image']}.png");

            $achievements[] = $badge;
        }

        if ($this->power > 0) {
            $achievements[] = config('blox.achievements')['admin'];
        }

        if ($this->beta) {
            $achievements[] = config('blox.achievements')['beta'];
        }

        if ($this->membership > 0) {
            $achievements['vip'] = config('blox.achievements')['VIP_' . $this->membership];
            $achievements['vip']['name'] = str_replace(' Membership', '', $achievements['vip']['name']);
        }

        if ($this->cash >= 5000) {
            $achievements[] = config('blox.achievements')['rich'];
        }

        if (strtotime($this->created_at) <= (time() - 31536000)) {
            $achievements[] = config('blox.achievements')['veteran'];
        }

        if ($this->posts() >= 500) {
            $achievements[] = config('blox.achievements')['forumer'];
        }

        if ($this->itemCount() >= 30) {
            $achievements[] = config('blox.achievements')['stockpiler'];
        }

        if ($this->specials()->count() >= 10) {
            $achievements[] = config('blox.achievements')['collector'];
        }

        return $achievements;
    }

    public function itemCount()
    {
        return Inventory::where('user_id', '=', $this->id)->get()->count();
    }

    public function ownsBadge($id)
    {
        return UserBadge::where([
            ['user_id', '=', $this->id],
            ['badge_id', '=', $id]
        ])->exists();
    }

    public function giveBadge($id, $granter = null)
    {
        $badge = new UserBadge;
        $badge->user_id = $this->id;
        $badge->granter_id = $granter;
        $badge->badge_id = $id;
        $badge->save();
    }

    public function removeBadge($id)
    {
        return UserBadge::where([
            ['user_id', '=', $this->id],
            ['badge_id', '=', $id]
        ])->delete();
    }

    public function usernameHistory()
    {
        return UsernameHistory::where('user_id', '=', $this->id)->orderBy('created_at', 'ASC')->get();
    }

    public function membershipColor()
    {
        switch ($this->membership) {
            case 0:
                return 'inherit';
                break;
            case 1:
                return '#6d4c41';
                break;
            case 2:
                return '#9e9e9e';
                break;
            case 3:
                return '#fbc02d';
                break;
            case 4:
                return '#616161';
                break;
        }
    }

    public function membershipLevel()
    {
        switch ($this->membership) {
            case 0:
                return 'None';
                break;
            case 1:
                return 'Bronze VIP';
                break;
            case 2:
                return 'Silver VIP';
                break;
            case 3:
                return 'Gold VIP';
                break;
            case 4:
                return 'Platinum VIP';
                break;
        }
    }

    public function salesTax()
    {
        switch($this->membership)
        {
            case 0:
                return 0.3;
                break; 
            case 1:
                return 0.2;
                break;
            case 2:
                return 0.15;
                break;
            case 3:
                return 0.05;
                break;
        }
    }

    public function adminRank()
    {
        switch ($this->power) {
            case 1:
                return 'Moderator';
                break;
            case 2:
                return 'Moderator';
                break;
            case 3:
                return 'Administrator';
                break;
            case 4:
                return 'Executive Administrator';
                break;
            case 5:
                return 'System';
                break;
        }
    }

    public function forumLikes()
    {
        return $this->hasMany(ForumLike::class);
    }

    public function forumHasLiked($id, $type)
    {
        return $this->hasOne(ForumLike::class)->where('user_id', $this->id)->where('target_id', $id)->where('target_type', $type)->exists();
    }

    public function hasLinkedDiscord()
    {
        return DiscordUser::where('user_id', '=', $this->id)->exists();
    }

    public function discord()
    {
        return $this->hasOne(DiscordUser::class)->where('user_id', $this->id);
    }

    public function bans()
    {
        return $this->hasMany(Ban::class, 'user_id');
    }

    public function ban()
    {
        return $this->hasOne(Ban::class, 'user_id')->where('active', 1)->latest();
    }

    public function generateSerial()
    {
        $randomHash = bin2hex(random_bytes(5));
        return $randomHash;
    }

    public function push_notification($message, $type, $url, User $sender)
    {
        Notification::create([
            'user_id' => $this->id,
            'sender_id' => $sender->id,
            'message' => $message,
            'type' => $type,
            'url' => $url
        ]);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id')->orderBy('created_at', 'desc');
    }

    public function unread_notifications()
    {
        return $this->hasMany(Notification::class, 'user_id')->where('read', 0)->orderBy('created_at', 'desc');
    }

    public function stripeCustomer(): HasOne
    {
        return $this->hasOne(StripeCustomer::class);
    }

    public function membership(): HasOne
    {
        return $this->hasOne(Membership::class)->where('active', 1);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->orderBy('id', 'desc');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
