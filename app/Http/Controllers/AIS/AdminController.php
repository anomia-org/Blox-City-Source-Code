<?php

namespace App\Http\Controllers\AIS;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Guild;
use App\Models\Blurb;
use App\Models\Comment;
use App\Models\Ad;
use App\Models\Ban;
use App\Models\User;
use App\Models\Report;
use App\Models\Thread;
use App\Models\Reply;
use App\Models\GuildWall;
use App\Models\GuildAnnouncement;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\AntelopeLog;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                $options = [];
                $user = auth()->user();

                if ($user->power >= 3)
                    $options[] = [route('ais.create_item.index', 'hat'), 'Create Hat', 'fas fa-hat-cowboy', '#0082ff'];

                if ($user->power >= 3)
                    $options[] = [route('ais.create_item.index', 'face'), 'Create Face', 'fas fa-kiss-wink-heart', '#0082ff'];

                if ($user->power >= 3)
                    $options[] = [route('ais.create_item.index', 'tool'), 'Create Tool', 'fas fa-hammer', '#0082ff'];

                if ($user->power >= 1)
                    $options[] = [route('ais.users.index'), 'Users', 'fas fa-user', '#28a745'];

                if ($user->power >= 1)
                    $options[] = [route('ais.items.index'), 'Items', 'fas fa-tshirt', '#28a745'];

                if ($user->power >= 1)
                    $options[] = [route('ais.assets.index', ''), 'Pending Assets', 'fas fa-image', '#ffc107'];

                if ($user->power >= 1)
                    $options[] = [route('ais.reports'), 'Pending Reports', 'fas fa-flag', '#ffc107'];

                if ($user->power >= 4)
                    $options[] = [route('ais.manage.forum_topics.index'), 'Forum Topics', 'fas fa-comments', '#6610f2'];

                if ($user->power >= 4)
                    $options[] = [route('ais.manage.staff.index'), 'Staff', 'fas fa-users', '#6610f2'];

                if ($user->power >= 5 || $user->id == 2)
                    $options[] = [route('ais.manage.site.index'), 'Site Settings', 'fas fa-cog', '#6610f2'];

                return view('ais.index')->with([
                    'options' => $options
                ]);
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function auth()
    {
        if(auth()->user()->power > 0)
        {
            if(empty(Cookie::get('ais')))
            {
                return view('ais.auth');
            } elseif (!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais) {
                return redirect(route('ais.index'));
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function authPost(Request $request)
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(auth()->user()->ais))
            {
                $this->validate($request, [
                    'password' => ['required'],
                ]);
                if(request('password') == auth()->user()->ais) {
                    $cookie = Cookie::make('ais', request('password'), 120);
                    return redirect(route('ais.index'))->withCookie($cookie)->with('success', 'Authenticated. Your Antelope session will end automatically in two hours.');
                } else {
                    return back()->with('error', 'Invalid access code. Please contact your department lead if you need assistance.');
                }
            } else {
                return back()->with('error', 'You do not have an access code yet. Please contact your department lead to generate one.');
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function reports()
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                $reports = Report::latest()->where('active', '1')->paginate('10');

                return view('ais.reports.index', compact(['reports']));
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function report(Report $report)
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                if($report->exists)
                {
                    return view('ais.reports.report', compact(['report']));
                } else {
                    return abort('404');
                }
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function reportsAction(Report $report, Request $request)
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(1)))
                {
                    return back()->withInput()->with('error', 'You\'re doing that too fast!');
                }

                if($report->exists)
                {
                    if($request['actionSelect'] == "dismiss")
                    {
                        $report->ais_id = auth()->user()->id;
                        $report->active = 0;
                        $report->save();
                        return redirect(route('ais.reports'))->with('success', 'Report dismissed.');
                    } elseif($request['actionSelect'] == 'warn') {
                        if($request->filled('scrub'))
                        {
                            if($report->type == 1)
                            {
                                $thread = Thread::find($report->content->id);
                                $thread->scrubbed = 1;
                                $thread->save();
                            } elseif($report->type == 2) {
                                $reply = Reply::find($report->content->id);
                                $reply->scrubbed = 1;
                                $reply->save();
                            } elseif($report->type == 4) {
                                $blurb = Blurb::find($report->content->id);
                                $blurb->scrubbed = 1;
                                $blurb->save();
                            } elseif($report->type == 5) {
                                $item = Item::find($report->content->id);
                                $item->scrubbed = 1;
                                $item->pending = 2;
                                $item->save();
                            } elseif($report->type == 6) {
                                $comment = Comment::find($report->content->id);
                                $comment->scrubbed = 1;
                                $comment->save();
                            } elseif($report->type == 7) {
                                $message = Message::find($report->content->id);
                                $message->scrubbed = 1;
                                $message->save();
                            } elseif($report->type == 8) {
                                $guild = Guild::find($report->content->id);
                                $guild->is_locked = 1;
                                $guild->is_thumbnail_pending = 2;
                                $guild->save();
                            } elseif($report->type == 9) {
                                $wall = GuildWall::find($report->content->id);
                                $wall->scrubbed = 1;
                                $wall->save();
                            } elseif($report->type == 10) {
                                $announce = GuildAnnouncement::find($report->content->id);
                                $announce->scrubbed = 1;
                                $announce->save();
                            } elseif($report->type == 11) {
                                $ad = Ad::find($report->content->id);
                                $ad->scrubbed = 1;
                                $ad->save();
                            }
                        }

                        $report->ais_id = auth()->user()->id;
                        $user = User::find($report->reportedUser->id);
                        $content = $request['content'];
                        $note = $request['note'];
                        if (empty($note))
                            $note = "No note provided.";
                        $internal_note = $request['internal'];
                        $length = "Warning";
                        $expires = Carbon::now();

                        $ban = Ban::create([
                            'user_id' => $report->reportedUser->id,
                            'banned_by' => auth()->user()->id,
                            'length' => $length,
                            'reason' => $report->rule,
                            'note' => $note,
                            'internal_note' => $internal_note,
                            'content' => $content,
                            'expires_at' => $expires,
                            'active' => 1,
                        ]);

                        $flood = auth()->user();
                        $flood->action_flood_gate = Carbon::now();
                        $flood->save();

                        return redirect(route('ais.reports'))->with('success', 'User warned. (Ban ID: '.$ban->id.')');

                    } elseif($request['actionSelect'] == 'ban') {
                        if($report->type == 1)
                            {
                                $thread = Thread::find($report->content->id);
                                $thread->scrubbed = 1;
                                $thread->save();
                            } elseif($report->type == 2) {
                                $reply = Reply::find($report->content->id);
                                $reply->scrubbed = 1;
                                $reply->save();
                            } elseif($report->type == 3) {
                                $user = User::find($report->content->id);
                                $user->deleted = 3;
                            } elseif($report->type == 4) {
                                $blurb = Blurb::find($report->content->id);
                                $blurb->scrubbed = 1;
                                $blurb->save();
                            } elseif($report->type == 5) {
                                $item = Item::find($report->content->id);
                                $item->scrubbed = 1;
                                $item->pending = 2;
                                $item->save();
                            } elseif($report->type == 6) {
                                $comment = Comment::find($report->content->id);
                                $comment->scrubbed = 1;
                                $comment->save();
                            } elseif($report->type == 7) {
                                $message = Message::find($report->content->id);
                                $message->scrubbed = 1;
                                $message->save();
                            } elseif($report->type == 8) {
                                $guild = Guild::find($report->content->id);
                                $guild->is_locked = 1;
                                $guild->is_thumbnail_pending = 2;
                                $guild->save();
                            } elseif($report->type == 9) {
                                $wall = GuildWall::find($report->content->id);
                                $wall->scrubbed = 1;
                                $wall->save();
                            } elseif($report->type == 10) {
                                $announce = GuildAnnouncement::find($report->content->id);
                                $announce->scrubbed = 1;
                                $announce->save();
                            } elseif($report->type == 11) {
                                $ad = Ad::find($report->content->id);
                                $ad->scrubbed = 1;
                                $ad->save();
                            }
                            $length = '';
                            $expires = Carbon::now();

                            switch ($request['length']) {
                                case 1:
                                    $length = '6 hours';
                                    $expires = Carbon::now()->addHours(6);
                                    break;
                                case 2:
                                    $length = '12 hours';
                                    $expires = Carbon::now()->addHours(12);
                                    break;
                                case 3:
                                    $length = '1 day';
                                    $expires = Carbon::now()->addDay();
                                    break;
                                case 4:
                                    $length = '2 days';
                                    $expires = Carbon::now()->addDays(2);
                                    break;
                                case 5:
                                    $length = '3 days';
                                    $expires = Carbon::now()->addDays(3);
                                    break;
                                case 6:
                                    $length = '1 week';
                                    $expires = Carbon::now()->addWeek();
                                    break;
                                case 7:
                                    $length = '1 month';
                                    $expires = Carbon::now()->addMonth();
                                    break;
                                case 8:
                                    $length = '6 months';
                                    $expires = Carbon::now()->addMonths(6);
                                    break;
                                case 9:
                                    $length = '1 year';
                                    $expires = Carbon::now()->addYear();
                                    break;
                                case 10:
                                    $length = 'Permanent';
                                    $expires = Carbon::now()->addYears(100);
                                    break;
                                default:
                                    return abort(404);
                                    break;
                            }

                        $report->ais_id = auth()->user()->id;
                        $user = User::find($report->reportedUser->id);
                        $content = $request['content'];
                        $note = $request['note'];
                        if (empty($note))
                            $note = "No note provided.";
                        $internal_note = $request['internal'];

                        $ban = Ban::create([
                            'user_id' => $report->reportedUser->id,
                            'banned_by' => auth()->user()->id,
                            'length' => $length,
                            'reason' => $report->rule,
                            'note' => $note,
                            'internal_note' => $internal_note,
                            'content' => $content,
                            'expires_at' => $expires,
                            'active' => 1,
                        ]);

                        $flood = auth()->user();
                        $flood->action_flood_gate = Carbon::now();
                        $flood->save();

                        return redirect(route('ais.reports'))->with('success', 'User banned. (Ban ID: '.$ban->id.')');

                    }
                } else {
                    return abort('404');
                }
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function pendingGuilds()
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                $guilds = Guild::latest()->where('is_thumbnail_pending', '1')->orderBy('created_at', 'DESC')->paginate(4);
                return view('ais.guilds.pending', compact(['guilds']));
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function acceptGuild(Guild $guild)
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(1)))
                {
                    return back()->withInput()->with('error', 'You\'re doing that too fast!');
                }

                $guild->is_thumbnail_pending = 0;
                $guild->save();

                $flood = auth()->user();
                $flood->action_flood_gate = Carbon::now();
                $flood->save();

                return back()->with('success', 'Guild thumbnail accepted.');
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function declineGuild(Guild $guild)
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(1)))
                {
                    return back()->withInput()->with('error', 'You\'re doing that too fast!');
                }

                $guild->is_thumbnail_pending = 2;
                $guild->save();

                $flood = auth()->user();
                $flood->action_flood_gate = Carbon::now();
                $flood->save();

                return back()->with('success', 'Guild thumbnail declined.');
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function pendingAds()
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                $ads = Ad::latest()->where('pending', '1')->orderBy('created_at', 'DESC')->paginate(4);
                return view('ais.ads.pending', compact(['ads']));
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function acceptAd(Ad $ad)
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(1)))
                {
                    return back()->withInput()->with('error', 'You\'re doing that too fast!');
                }

                $ad->pending = 0;
                $ad->save();

                $flood = auth()->user();
                $flood->action_flood_gate = Carbon::now();
                $flood->save();

                return back()->with('success', 'Ad accepted.');
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }
    
    public function declineAd(Ad $ad)
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(1)))
                {
                    return back()->withInput()->with('error', 'You\'re doing that too fast!');
                }

                $ad->pending = 2;
                $ad->save();

                $flood = auth()->user();
                $flood->action_flood_gate = Carbon::now();
                $flood->save();

                return back()->with('success', 'Ad declined.');
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function pendingItems()
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                $items = Item::latest()->where('pending', '1')->orderBy('created_at', 'DESC')->paginate(4);
                return view('ais.clothing.pending', compact(['items']));
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function acceptClothing(Item $item)
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(1)))
                {
                    return back()->withInput()->with('error', 'You\'re doing that too fast!');
                }

                $item->pending = 0;
                $item->save();

                $flood = auth()->user();
                $flood->action_flood_gate = Carbon::now();
                $flood->save();

                return back()->with('success', 'Successfully accepted asset!');
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function declineClothing(Item $item)
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(1)))
                {
                    return back()->withInput()->with('error', 'You\'re doing that too fast!');
                }

                $item->pending = 2;
                $item->save();

                $flood = auth()->user();
                $flood->action_flood_gate = Carbon::now();
                $flood->save();

                return back()->with('success', 'Successfully declined asset!');
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function info()
    {
        if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                $serverData = $this->data('server');
                $siteData = $this->data('site');
                $economyData = $this->data('economy');

                return view('ais.info')->with([
                    'siteData' => $siteData,
                    'serverData' => $serverData,
                    'economyData' => $economyData,
                ]);
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function data($type)
    {
        switch ($type) {
            case 'site':
                $totalUsers = number_format(User::count());
                $joinedToday = number_format(User::where('created_at', '>=', Carbon::now()->subDays(1))->count());
                $onlineUsers = number_format(User::where('last_online', '>=', Carbon::now()->subMinutes(2))->count());
                $bannedUsers = number_format(User::where('deleted', '>', 0)->count());

                return [
                    'Total Users' => $totalUsers,
                    'Joined Today' => $joinedToday,
                    'Online Users' => $onlineUsers,
                    'Banned Users' => $bannedUsers,
                ];
            case 'server':
                if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
                    $cpuUsage = sys_getloadavg()[0] . '%';

                    $execFree = explode("\n", trim(shell_exec('free')));
                    $getMem = preg_split("/[\s]+/", $execFree[1]);
                    $ramUsage = round($getMem[2] / $getMem[1] * 100, 0) . '%';

                    $uptime = preg_split("/[\s]+/", trim(shell_exec('uptime')))[2] . ' Days';
                }

                return [
                    'CPU Usage' => $cpuUsage ?? '???',
                    'RAM Usage' => $ramUsage ?? '???',
                    'PHP Version' => phpversion(),
                    'Uptime' => $uptime ?? '???',
                ];
            case 'economy':
                $totalCoins = number_format(User::sum('coins'));
                $totalCash = number_format(User::sum('cash'));
                $totalItems = number_format(Item::count());
                $totalCollectibles = number_format(Item::where('special', 1)->count());

                return [
                    'Total Cash' => $totalCash,
                    'Total Coins' => $totalCoins,
                    'Total Items' => $totalItems,
                    'Total Collectibles' => $totalCollectibles,
                ];
        }
    }

        /* if(auth()->user()->power > 0)
        {
            if(!empty(Cookie::get('ais')) && Cookie::get('ais') == auth()->user()->ais)
            {
                //
            } else {
                return redirect(route('ais.auth'));
            }
        } else {
            return redirect(route('login'));
        } */
}
