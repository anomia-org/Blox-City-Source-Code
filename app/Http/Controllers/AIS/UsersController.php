<?php

namespace App\Http\Controllers\AIS;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\AntelopeLog;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\IpBan;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\UsernameHistory;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        if((auth()->user()->power <= 0) || (empty(Cookie::get('ais'))) || (Cookie::get('ais') != auth()->user()->ais))
        {
            return redirect(route('login'));
        }

        $search = (isset($request->search)) ? trim($request->search) : '';
        $users = User::where('username', 'LIKE', "%{$search}%")->orderBy('created_at', 'ASC')->paginate(25);

        return view('ais.users.index')->with([
            'search' => $search,
            'users' => $users ?? null
        ]);
    }

    public function view($id)
    {
        if((auth()->user()->power <= 0) || (empty(Cookie::get('ais'))) || (Cookie::get('ais') != auth()->user()->ais))
        {
            return redirect(route('login'));
        }

        $user = User::where('id', '=', $id)->firstOrFail();
        $ipBans = IpBan::whereIn('ip', $user->ips())->get();
        $ipBanned = $ipBans->count() > 0;

        return view('ais.users.view')->with([
            'user' => $user,
            'ipBanned' => $ipBanned
        ]);
    }

    public function usersUpdate(Request $request)
    {
        if((auth()->user()->power <= 0) || (empty(Cookie::get('ais'))) || (Cookie::get('ais') != auth()->user()->ais))
        {
            return redirect(route('login'));
        }

        $user = User::where('id', '=', $request->id)->firstOrFail();
        $user->timestamps = false;

        switch ($request->action) {
            case 'unban':
                if (auth()->user()->power < 2) abort(404);

                $user->deleted = 0;            
                $ban = $user->ban;
                $ban->active = 0;
                $ban->save();
                $user->save();

                AntelopeLog::create([
                    'user_id' => auth()->id(),
                    'action' => auth()->user()->username . ' deleted a ban for user ID# ' . $user->id,
                ]);

                return back()->with('success', 'User has been unbanned.');
            case 'password':
                if (auth()->user()->power < 3) abort(404);

                $password = Str::random(25);

                $user->password = bcrypt($password);
                $user->save();

                AntelopeLog::create([
                    'user_id' => auth()->id(),
                    'action' => auth()->user()->username . ' reset password for user ID# ' . $user->id,
                ]);

                return back()->with('success', "User password has been changed to <strong>{$password}</strong>.");
            case 'ip_ban':
                $ipBans = IpBan::whereIn('ip', $user->ips())->get();
                $ipBanned = $ipBans->count() > 0;
                $message = 'User has been IP banned.';

                if ($ipBanned) {
                    $message = 'User is no longer IP banned.';

                    if (auth()->user()->power < 3) abort(404);

                    foreach ($ipBans as $ipBan) {
                        $ipBan->delete();
                    }

                    AntelopeLog::create([
                        'user_id' => auth()->id(),
                        'action' => auth()->user()->username . ' deleted IP ban for user ID# ' . $user->id,
                    ]);

                } else {
                    if (auth()->user()->power < 3) abort(404);

                    foreach ($user->ips() as $ip) {
                        $ipBan = new IpBan;
                        $ipBan->admin_id = auth()->user()->id;
                        $ipBan->ip = $ip;
                        $ipBan->expires_at = Carbon::now()->addMonths(150)->toDateTimeString();
                        $ipBan->save();
                    }

                    AntelopeLog::create([
                        'user_id' => auth()->id(),
                        'action' => auth()->user()->username . ' added IP ban for user ID# ' . $user->id,
                    ]);
                }

                return back()->with('success', $message);
            case 'beta':
                if (auth()->user()->power < 3) abort(404);
                $message = "User no longer has beta access.";
                if($user-> beta != 1)
                {
                    $user->beta = 1;
                    $user->save();
                    $message = "User has been given beta access.";
                    AntelopeLog::create([
                        'user_id' => auth()->id(),
                        'action' => auth()->user()->username . ' added beta for user ID# ' . $user->id,
                    ]);
                } else {
                    $user->beta = 0;
                    $user->save();

                    AntelopeLog::create([
                        'user_id' => auth()->id(),
                        'action' => auth()->user()->username . ' removed beta for user ID# ' . $user->id,
                    ]);
                }
                return back()->with('success', $message);
            case 'scrub_username':
                $newUsername = $this->generateRandomUsername();

                $usernameHistory = UsernameHistory::create([
                    'user_id' => $user->id,
                    'username' => $user->username
                ]);

                $user->username = $newUsername;
                $user->save();

                AntelopeLog::create([
                    'user_id' => auth()->id(),
                    'action' => auth()->user()->username . ' scrubbed username for user ID# ' . $user->id,
                ]);
                return back()->with('success', 'User username has been scrubbed.');
            case 'scrub_all_posts':
                if (auth()->user()->power < 2) abort(404);

                $threads = Thread::where('user_id', $user->id)->get();
                $replies = Reply::where('user_id', $user->id)->get();
                $comments = Comment::where('user_id', $user->id)->get();

                // Update the threads
                foreach ($threads as $thread) {
                    $thread->update(['title' => '[Content Deleted]', 'body' => '[Content Deleted]']);
                }

                // Update the replies
                foreach ($replies as $reply) {
                    $reply->update(['body' => '[Content Deleted]']);
                }

                // Update the comments
                foreach ($comments as $comment) {
                    $comment->update(['text' => '[Content Deleted]']);
                }

                AntelopeLog::create([
                    'user_id' => auth()->id(),
                    'action' => auth()->user()->username . ' scrubbed all posts for user ID# ' . $user->id,
                ]);

                return back()->with('success', 'User posts have been scrubbed.');
            case 'scrub_description':
                $user->biography = '[Content Deleted]';
                $user->save();
                if($user->blurb()->exists())
                {
                    $blurb = $user->blurb;
                    $blurb->text = '[Content Deleted]';
                    $blurb->save();
                }

                AntelopeLog::create([
                    'user_id' => auth()->id(),
                    'action' => auth()->user()->username . ' scrubbed description for user ID# ' . $user->id,
                ]);

                return back()->with('success', 'User description has been scrubbed.');
            case 'scrub_forum_signature':
                $user->signature = '[Content Deleted]';
                $user->save();

                AntelopeLog::create([
                    'user_id' => auth()->id(),
                    'action' => auth()->user()->username . ' scrubbed forum signature for user ID# ' . $user->id,
                ]);

                return back()->with('success', 'User forum signature has been scrubbed.');
            case 'remove_membership':
                if (auth()->user()->power < 3) abort(404);

                if (!$user->hasMembership())
                    return back()->withErrors(['This user does not have a membership.']);

                $user->membership_until = null;
                $user->save();

                AntelopeLog::create([
                    'user_id' => auth()->id(),
                    'action' => auth()->user()->username . ' revoked membership for user ID# ' . $user->id,
                ]);

                return back()->with('success', 'User membership has been removed.');
            case 'grant_membership':
                if (auth()->user()->power < 3) abort(404);

                switch ($request->membership_length) {
                    case '1_month':
                        $months = 1;
                        break;
                    case '3_months':
                        $months = 3;
                        break;
                    case '6_months':
                        $months = 6;
                        break;
                    case '1_year':
                        $months = 12;
                        break;
                    case 'forever':
                        $months = 150;
                        break;
                    default:
                        abort(404);
                }

                switch ($request->membership_type) {
                    case 'bronze':
                        $type = 1;
                        break;
                    case 'silver':
                        $type = 2;
                        break;
                    case 'gold':
                        $type = 3;
                        break;
                    default:
                        abort(404);
                }

                $time = ($request->membership_length == 'forever') ? 'a lifetime' : str_replace('_', ' ', $request->membership_length);

                $user->membership_expires = Carbon::now()->addMonths($months)->toDateTimeString();
                $user->membership = $type;
                $user->save();

                AntelopeLog::create([
                    'user_id' => auth()->id(),
                    'action' => auth()->user()->username . ' added membership for user ID# ' . $user->id,
                ]);

                return back()->with('success', "User has been granted {$time} worth of membership.");
            case 'regen':
                if (auth()->user()->power < 2) abort(404);

                //$user->render();

                AntelopeLog::create([
                    'user_id' => auth()->id(),
                    'action' => auth()->user()->username . ' regenerated user thumbnail for user ID# ' . $user->id,
                ]);

                return back()->with('success', 'User thumbnail has been regenerated.');
            case 'reset':
                if (auth()->user()->power < 2) abort(404);

                //$user->avatar()->reset();

                AntelopeLog::create([
                    'user_id' => auth()->id(),
                    'action' => auth()->user()->username . ' reset user avatar for user ID# ' . $user->id,
                ]);

                return back()->with('success', 'User avatar has been reset.');
            default:
                abort(404);
        }
    }

    function generateRandomUsername() {
    // List of common colors
    $colors = ['Red', 'Blue', 'Green', 'Yellow', 'Purple', 'Orange', 'Pink', 'Black', 'White', 'Gray'];
    
    // List of common animals
    $animals = ['Lion', 'Tiger', 'Bear', 'Wolf', 'Fox', 'Eagle', 'Shark', 'Whale', 'Dolphin', 'Panda'];
    
    // Select a random color
    $randomColor = $colors[array_rand($colors)];
    
    // Select a random animal
    $randomAnimal = $animals[array_rand($animals)];
    
    // Generate a 6-digit random number
    $randomNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    
    // Combine them to create the username
    $username = $randomColor . $randomAnimal . $randomNumber;
    
    return $username;
}
    
}
