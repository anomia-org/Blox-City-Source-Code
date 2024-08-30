<?php

namespace App\Http\Controllers;

use App\Models\Guild;
use App\Models\GuildAnnouncement;
use App\Models\GuildJoinRequest;
use App\Models\GuildMember;
use App\Models\GuildRank;
use App\Models\GuildWall;
use App\Models\Transaction;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class GuildsController extends Controller
{
    public function index()
    {
        if (Setting::where('groups_enabled', '0')->get()->first()) 
        {
            return view('maintenance.disabled');
        }

        if(auth()->user()->guildsCount() < 1)
        {
            return redirect(route('groups.explore')); //change this to groups.explore when that section is ready for release
        }

        $members = GuildMember::where('user_id', '=', auth()->user()->id)->get();
        $guilds = [];

        foreach ($members as $member)
            $guilds[] = $member->guild->id;

        $guilds = Guild::whereIn('id', $guilds)->paginate(9);

        return view('groups.index', compact(['guilds']));
    }

    public function view(Request $request, Guild $guild)
    {
        if (Setting::where('groups_enabled', '0')->get()->first()) 
        {
            return view('maintenance.disabled');
        }

        if($guild && $guild->is_locked == 0)
        {
            $comments = GuildWall::where('guild_id', '=', $guild->id)->where('scrubbed', '=', '0')->orderBy('created_at', 'DESC')->paginate('5');

            if($request->ajax() && $comments->count() > 0)
            {
                $view = view('components.load_guild_wall', compact('comments'))->render();
                return response()->json(['html' => $view]);
            }

            return view('groups.view', compact(['guild', 'comments']));
        } else {
            return abort(404);
        }
    }

    public function search($search = '')
    {
        if (Setting::where('groups_enabled', '0')->get()->first()) 
        {
            return view('maintenance.disabled');
        }

        $search = request('search');

        $guilds = Guild::select(['guilds.id', 'guilds.name', 'guilds.desc', 'guilds.is_locked', 'guilds.is_private', 'guilds.thumbnail_url', 'm.guild_id', 'guilds.is_thumbnail_pending', DB::raw('count(m.id) as total')])
            ->leftJoin('guilds_members as m', function ($join) {
                $join->on('m.guild_id', 'guilds.id');
            })
            ->where([['guilds.name', 'like', "%$search%"], ['guilds.is_locked', '=', 0]])
            ->groupBy('guilds.id')
            ->orderBy('total', 'DESC')
            ->paginate(9);

        return view('groups.search', compact(['guilds', 'search']));
    }

    public function explore($search = '')
    {
        if (Setting::where('groups_enabled', '0')->get()->first()) 
        {
            return view('maintenance.disabled');
        }

        $guilds = Guild::select(['guilds.id', 'guilds.name', 'guilds.desc', 'guilds.is_locked', 'guilds.is_private', 'guilds.thumbnail_url', 'm.guild_id', 'guilds.is_thumbnail_pending', DB::raw('count(m.id) as total')])
            ->leftJoin('guilds_members as m', function ($join) {
                $join->on('m.guild_id', 'guilds.id');
            })
            ->where([['guilds.name', 'like', "%$search%"], ['guilds.is_locked', '=', 0]])
            ->groupBy('guilds.id')
            ->orderBy('total', 'DESC')
            ->paginate(9);

        return view('groups.explore', compact(['guilds']));
    }

    public function edit(Guild $guild)
    {
        if (Setting::where('groups_enabled', '0')->get()->first()) 
        {
            return view('maintenance.disabled');
        }

        if($guild) {
            if(auth()->user()->isInGuild($guild->id) && (auth()->user()->rankInGuild($guild->id)->can_view_audit || auth()->user()->rankInGuild($guild->id)->can_change_ranks || auth()->user()->rankInGuild($guild->id)->can_kick_members || auth()->user()->rankInGuild($guild->id)->can_accept_members || auth()->user()->rankInGuild($guild->id)->can_spend_funds || auth()->user()->rankInGuild($guild->id)->can_create_items || auth()->user()->rankInGuild($guild->id)->can_edit_games))
            {
                return view('groups.edit', compact(['guild']));
            } else {
                return redirect(route('groups.index'));
            }
        } else {
            return redirect(route('groups.index'));
        }
    }

    public function edit_general(Request $request, Guild $guild)
    {
        if (Setting::where('groups_enabled', '0')->get()->first()) 
        {
            return view('maintenance.disabled');
        }

        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        if($guild)
        {
            if(auth()->user()->isInGuild($guild->id) && $guild->owner->id == auth()->user()->id)
            {
                $flood = auth()->user();
                $flood->flood_gate = Carbon::now();
                $flood->save();

                $request->validate([
                    'desc' => 'max:2048|strictly_profane|regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/#$&\t\n\r]*+/i|nullable',
                    'image' => 'image|mimes:png,jpg,jpeg|max:2048',
                ]);

                if(!empty(request()->image))
                {
                    $realName = bin2hex(random_bytes(32));
                    $imageName = $realName.'.'.request()->image->extension();
                
                    $disk = Storage::build([
                        'driver' => 'local',
                        'root' => '/var/www/cdn',
                    ]);
                
                    $disk->putFileAs('', $request->image, $imageName);

                    $img = new \Imagick('/var/www/cdn/'.$imageName);
                    $img->stripImage();
                    $img->writeImage('/var/www/cdn/'.$imageName);
                    $img->clear();
                    $img->destroy();

                } else {
                    $imageName = $guild->thumbnail_url;
                }

                if(request('desc') != $guild->desc) { $guild->desc = request('desc'); }
                if($imageName != $guild->thumbnail_url) { $guild->thumbnail_url = $imageName; $guild->is_thumbnail_pending = 1; }
                $guild->save();
                

                return back()->with('success', 'Successfully updated ' . $guild->name . '\'s general settings!');
            }
        }            
    }

    public function create()
    {
        if (Setting::where('groups_enabled', '0')->get()->first()) 
        {
            return view('maintenance.disabled');
        }

        return view('groups.create');
    }

    public function join_guild(Request $request, Guild $guild)
    {
        if(auth()->user()->isGuildCapped())
        {
            return redirect(route('groups.index'))->with('error', 'You can only be in '. auth()->user()->guildsLimit() .' groups!');
        }

        $lockKey = 'lock:join_group:' . $guild->id;
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);

        if (!$lockAcquired) {
            return back()->with('error', 'You are trying too fast. Please wait and try again.');
        }

        try {
            DB::beginTransaction();

            if (Setting::where('groups_enabled', '0')->get()->first()) 
            {
                DB::rollBack();
                return view('maintenance.disabled');
            }

            if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
            {
                DB::rollBack();
                return back()->with('error', 'You\'re doing that too fast!');
            }

            // Lock the guild row
            $guild = Guild::where('id', $guild->id)->lockForUpdate()->first();

            if($guild)
            {
                if(auth()->user()->sentRequest($guild->id))
                {
                    DB::rollBack();
                    return back()->with('error', 'You\'ve already requested to join this community.');
                }
                if(!$guild->is_private)
                {
                    GuildMember::create([
                    'guild_id' => $guild->id,
                    'user_id' => auth()->user()->id,
                    'rank' => 1,
                    ]); // add member to group

                    $flood = auth()->user();
                    $flood->action_flood_gate = Carbon::now();
                    $flood->save();
                    DB::commit();
                    return back()->with('success', 'Successfully joined ' . $guild->name . '!');

                } elseif($guild->is_private && !auth()->user()->sentRequest($guild->id)) {
                    GuildJoinRequest::create([
                        'guild_id' => $guild->id,
                        'user_id' => auth()->user()->id,
                    ]); // add join request

                    $flood = auth()->user();
                    $flood->action_flood_gate = Carbon::now();
                    $flood->save();
                    DB::commit();
                    return back()->with('success', 'Successfully sent a request to ' . $guild->name . '!');
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Redis::del($lockKey);
            return back()->with('error', 'An error occurred while trying to join this community.');
        }
        
    }

    public function leave_guild(Request $request, Guild $guild)
    {
        $lockKey = 'lock:leave_group:' . $guild->id;
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);

        if (!$lockAcquired) {
            return back()->with('error', 'You are trying too fast. Please wait and try again.');
        }

        try {
            DB::beginTransaction();

            if (Setting::where('groups_enabled', '0')->get()->first()) 
            {
                DB::rollBack();
                return view('maintenance.disabled');
            }

            if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
            {
                DB::rollBack();
                return back()->with('error', 'You\'re doing that too fast!');
            }

            // Lock the guild row
            $guild = Guild::where('id', $guild->id)->lockForUpdate()->first();

            if($guild)
            {
                if(!auth()->user()->isInGuild($guild->id))
                {
                    DB::rollBack();
                    return back()->with('error', 'You\'re not a member of this community.');
                }

                if(auth()->user()->id == $guild->owner->id)
                {
                    DB::rollBack();
                    return back()->with('error', 'You can\'t leave a community you own.');
                }

                if(!$guild->is_private)
                {
                    GuildMember::where([
                    'guild_id' => $guild->id,
                    'user_id' => auth()->user()->id,
                    ])->delete(); // remove member to group

                    DB::commit();
                    return back()->with('success', 'You have left ' . $guild->name . '.');
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while trying to join this community.');
        } finally {
            Redis::del($lockKey);
        }
    }

    public function announce(Request $request, Guild $guild)
    {
        $lockKey = 'lock:group_announce:' . auth()->user()->id;
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);
        
        if (!$lockAcquired) {
            return back()->with('error', 'You are trying too fast. Please wait and try again.');
        }

        try {
            DB::beginTransaction();

            if (Setting::where('groups_enabled', '0')->get()->first()) 
            {
                DB::rollBack();
                return view('maintenance.disabled');
            }

            if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
            {
                DB::rollBack();
                return back()->with('error', 'You\'re doing that too fast!');
            }

            if($guild)
            {
                if(auth()->user()->isInGuild($guild->id) && auth()->user()->rankInGuild($guild->id)->can_post_announcements)
                {
                    $request->validate([
                        'body' => 'required|min:3|max:120|regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&]+$/i|strictly_profane',
                    ]);

                    GuildAnnouncement::create([
                        'guild_id' => $guild->id,
                        'user_id' => auth()->user()->id,
                        'body' => request('body'),
                    ]);

                    $flood = auth()->user();
                    $flood->flood_gate = Carbon::now();
                    $flood->save();
                    DB::commit();
                    return back()->with('success', 'Community announcement has been successfully updated!');

                } else {
                    DB::rollBack();
                    return abort(403);
                }
                DB::rollBack();
                return abort(404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while trying to post an announcement to this community.');
        } finally {
            DB::rollBack();
            Redis::del($lockKey);
        }
    }

    public function wall_post(Request $request, Guild $guild)
    {
        $lockKey = 'lock:group_wall_post:' . auth()->user()->id;
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);
        
        if (!$lockAcquired) {
            return back()->with('error', 'You are trying too fast. Please wait and try again.');
        }

        try {
            DB::beginTransaction();
            if (Setting::where('groups_enabled', '0')->get()->first()) 
            {
                DB::rollBack();
                return view('maintenance.disabled');
            }

            if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
            {
                DB::rollBack();
                return back()->with('error', 'You\'re doing that too fast!');
            }

            if($guild)
            {
                if(auth()->user()->isInGuild($guild->id) && auth()->user()->rankInGuild($guild->id)->can_post_on_wall)
                {
                    $request->validate([
                        'body' => 'required|strictly_profane|min:3|max:120|regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&\t\n\r]+/i',
                    ]);

                    GuildWall::create([
                        'user_id' => auth()->user()->id,
                        'guild_id' => $guild->id,
                        'text' => request('body'),
                    ]);

                    $flood = auth()->user();
                    $flood->flood_gate = Carbon::now();
                    $flood->save();
                    DB::commit();
                    return back()->with('success', 'Successfully posted to community wall!');

                } else {
                    DB::rollBack();
                    return abort(403);
                }
                DB::rollBack();
                return abort(404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while trying to post to this community wall.');
        } finally {
            DB::rollBack();
            Redis::del($lockKey);
        }
    }

    public function create_post(Request $request)
    {
        if(auth()->user()->isGuildCapped())
        {
            return redirect(route('groups.index'))->with('error', 'You can only be in '. auth()->user()->guildsLimit() .' groups!');
        }
        
        $lockKey = 'lock:create_group:' . auth()->user()->id;
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);

        if (!$lockAcquired) {
            return back()->with('error', 'You are trying too fast. Please wait and try again.');
        }

        try {
            DB::beginTransaction();
            if (Setting::where('groups_enabled', '0')->get()->first()) 
            {
                DB::rollBack();
                return view('maintenance.disabled');
            }

            if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
            {
                DB::rollBack();
                return back()->with('error', 'You\'re doing that too fast!');
            }

            if(auth()->user()->membership == 0 && auth()->user()->guildsCount() > 5)
            {
                DB::rollBack();
                return back()->with('error', 'You can only be in 5 guilds!');
            }

            if(auth()->user()->cash < 30)
            {
                DB::rollBack();
                return back()->with('error', 'You need at least 30 Cash to create a guild!');
            }

            $request->validate([
                'name' => 'required|strictly_profane|min:3|max:40|regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&]+$/i|unique:guilds',
                'desc' => 'max:2048|strictly_profane|regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/#$&\t\n\r]*+/i|nullable',
                'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            ]);

            $realName = bin2hex(random_bytes(32));
            $imageName = $realName.'.'.request()->image->extension();

            $disk = Storage::build([
                'driver' => 'local',
                'root' => '/var/www/cdn',
            ]);

            $disk->putFileAs('', $request->image, $imageName);

            $img = new \Imagick('/var/www/cdn/'.$imageName);
            $img->stripImage();
            $img->writeImage('/var/www/cdn/'.$imageName);

            $guild = Guild::create([
                'owner_id' => auth()->user()->id,
                'name' => request('name'),
                'desc' => request('desc'),
                'thumbnail_url' => $imageName,
            ]);

            GuildRank::insert([
                [
                    'guild_id' => $guild->id,
                    'name' => 'Owner',
                    'rank' => 255,
                    'can_view_wall' => true,
                    'can_post_on_wall' => true,
                    'can_moderate_wall' => true,
                    'can_view_audit' => true,
                    'can_advertise' => true,
                    'can_change_ranks' => true,
                    'can_kick_members' => true,
                    'can_accept_members' => true,
                    'can_post_announcements' => true,
                    'can_spend_funds' => true,
                    'can_create_items' => true,
                    'can_edit_games' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'guild_id' => $guild->id,
                    'name' => 'Admin',
                    'rank' => 254,
                    'can_view_wall' => true,
                    'can_post_on_wall' => true,
                    'can_moderate_wall' => true,
                    'can_view_audit' => true,
                    'can_advertise' => true,
                    'can_change_ranks' => true,
                    'can_kick_members' => false,
                    'can_accept_members' => true,
                    'can_post_announcements' => true,
                    'can_spend_funds' => false,
                    'can_create_items' => true,
                    'can_edit_games' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'guild_id' => $guild->id,
                    'name' => 'Member',
                    'rank' => 1,
                    'can_view_wall' => true,
                    'can_post_on_wall' => true,
                    'can_moderate_wall' => false,
                    'can_view_audit' => false,
                    'can_advertise' => false,
                    'can_change_ranks' => false,
                    'can_kick_members' => false,
                    'can_accept_members' => false,
                    'can_post_announcements' => false,
                    'can_spend_funds' => false,
                    'can_create_items' => false,
                    'can_edit_games' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]); // create default ranks
            GuildMember::create([
                'guild_id' => $guild->id,
                'user_id' => auth()->user()->id,
                'rank' => 255,
            ]); // add the owner as the default member of the guild

            $flood = auth()->user();
            $flood->flood_gate = Carbon::now();
            $flood->cash = $flood->cash - 30; // take 30 cash when creating guild
            $flood->save();

            Transaction::create([
                'user_id' => auth()->user()->id,
                'source_id' => '1',
                'source_user' => '1',
                'source_type' => '4',
                'cash' => '30',
                'type' => '6',
            ]);

            DB::commit();
            return redirect(route('groups.view', $guild->id)); // created guild success
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while trying to create this community.');
        } finally {
            Redis::del($lockKey);
        }
        
    }

    public function members(Request $request)
    {
        if (Setting::where('groups_enabled', '0')->get()->first()) 
        {
            return view('maintenance.disabled');
        }
        
        $json = [];
        $guild = Guild::where('id', '=', $request->id);

        if (!$guild->exists())
            return response()->json(['error' => 'This community does not exist.']);

        $guild = $guild->first();
        $members = GuildMember::where([
            ['guild_id', '=', $guild->id],
            ['rank', '=', $request->rank]
        ]);

        if ($members->count() == 0)
            return response()->json(['error' => 'No members found.']);

        $members = $members->orderBy('updated_at', 'DESC')->paginate(6);

        foreach ($members as $member)
            $json[] = [
                'id'        => (int)    $member->user->id,
                'username'  => (string) $member->user->username,
                'thumbnail' => (string) $member->user->get_avatar(),
                'url'       => (string) route('user.profile', $member->user->id)
            ];

        return response()->json(['current_page' => $members->currentPage(), 'total_pages' => $members->lastPage(), 'members' => $json]);
    }
}
