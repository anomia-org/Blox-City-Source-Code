<?php

namespace App\Http\Controllers;

use App\Events\UserNotification;
use App\Helpers\GhostBlog;
use App\Models\Blurb;
use App\Models\Friend;
use App\Models\Notification;
use App\Models\Privacy;
use App\Models\ProfileView;
use App\Models\Setting;
use App\Models\Inventory;
use App\Models\User;
use App\Models\UsernameHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Helpers\helpers;
use App\Models\Item;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    public function index()
    {
        if(auth()->user()) {
            return redirect(route('dashboard'));
        } else {
            return view('welcome');
        }
    }

    public function dashboard(Request $request)
    {
        $blurbs = auth()->user()->get_feed();

        try {
            $posts = GhostBlog::latest(4);
        } catch (Exception $e) {
            $posts = [];
        }

        if($request->ajax() && !$blurbs->isEmpty())
        {
            $view = view('components.load_user_feed', compact('blurbs'))->render();
            return response()->json(['html' => $view]);
        }

        return view('dashboard', compact(['blurbs', 'posts']));
    }

    public function post_blurb(Request $request)
    {
        try {
            // Acquire lock
            $lockKey = 'lock:post_blurb:' . auth()->id();
            $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5); // Lock expires in 5 seconds

            if (!$lockAcquired) {
                return back()->withInput()->with('error', 'Please wait before making another post.');
            }
            
            DB::beginTransaction();

            if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(3)))
            {
                return back()->withInput()->with('error', 'Please wait 3 seconds before making another post.');
            }

            $request->validate([
                'text' => ['required', 'max:140', 'min:3', 'strictly_profane', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&]+$/i'],
            ]);

            Blurb::create([
                'author_id' => auth()->id(),
                'author_type' => 1,
                'text' => request('text'),
            ]);

            $flood = auth()->user();
            $flood->flood_gate = Carbon::now();
            $flood->save();

            DB::commit();

            return back()->with('success', 'Successfully updated status!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while posting your status.');
        }
    }

    public function maintenance()
    {
        return view('maintenance.index');
    }

    public function achievements()
    {
        $special = [];
        $membership = [];
        $general = [];

        foreach (config('blox.achievements') as $achievement) {
            switch ($achievement['type']) {
                case 'special':
                    $special[] = $achievement;
                    break;
                case 'membership':
                    $membership[] = $achievement;
                    break;
                case 'general':
                    $general[] = $achievement;
                    break;
            }
        }

        return view('misc.achievements')->with([
            'special' => $special,
            'membership' => $membership,
            'general' => $general
        ]);
    }

    public function profile($id)
    {
        $user = User::where('id', '=', $id)->firstOrFail();

        if(!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        

        if($user->deleted == 0)
        {
            if(auth()->user())
            {
                $checkView = ProfileView::where('target_id', $user->id)->where('ip', $_SERVER['REMOTE_ADDR'])->where('user_id', auth()->id());


                if(!$checkView->exists()) {
                    $user->increment('views');
                    ProfileView::insert(['target_id' => $user->id, 'ip' => $_SERVER['REMOTE_ADDR'], 'user_id' => auth()->id()]);
                }
            }
            $badges = $user->badges();
            return view('user.profile', compact(['user', 'badges']));
        } else {
            return abort(404);
        }
    }

    public function friends(Request $request, User $user)
    {
        if($user && $user->deleted == 0)
        {
            $friends = $user->getFriends(18);

            return view('user.friends', compact(['user', 'friends']));
        } else {
            return abort(404);
        }
    }

    public function notifs_read(Request $request)
    {
        Notification::where('user_id', auth()->id())->where('read', 0)->update(['read' => 1]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function notifications()
    {
        return view('user.notifications');
    }

    public function my_friends(Request $request)
    {
        $requests = Auth::user()->getFriendRequests(18);

        return view('user.myfriends', compact(['requests']));
    }

    public function add_friend(User $user)
    {
        // Acquire lock
        $lockKey = 'lock:send_friend_request:' . auth()->id();
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5); // Lock expires in 5 seconds

        if (!$lockAcquired) {
            return back()->with('error', 'Please wait before sending another friend request.');
        }

        try {
            DB::beginTransaction();

            // Check action flood gate
            if (!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE')))) {
                Redis::del($lockKey);
                return back()->with('error', 'You\'re doing that too fast!');
            }
    
            // Check if user is trying to add themselves as a friend
            if (auth()->user()->is($user)) {
                Redis::del($lockKey);
                return back()->with('error', 'You can\'t add yourself as a friend!');
            }
    
            // Attempt to befriend the user
            if (auth()->user()->befriend($user)) {
                $message = auth()->user()->username . " sent you a friend request.";
    
                // Send push notification
                $user->push_notification($message, 1, '/user/' . auth()->user()->id, auth()->user());
    
                // Dispatch user notification
                UserNotification::dispatch($message, 'friend-request', '/user/' . auth()->user()->id, $user);
    
                // Update action flood gate
                $flood = auth()->user();
                $flood->action_flood_gate = Carbon::now();
                $flood->save();

                DB::commit();

                Redis::del($lockKey);
    
                return back()->with('success', 'Sent friend request to ' . $user->username . '.');
            } else {
                return back()->with('error', 'Friend request already exists!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while sending the friend request.');
        } finally {
            // Release lock
            Redis::del($lockKey);
        }
    }

    public function remove_friend(User $user)
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        if(auth()->user()->unfriend($user)) {
            $flood = auth()->user();
            $flood->action_flood_gate = Carbon::now();
            $flood->save();

            return back()->with('success', 'Removed ' . $user->username . ' from your friends.');
        } else {
            return back()->with('error', 'You\'re not friends with that user!');
        }
    }

    public function accept_friend(User $user)
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        if(auth()->user()->acceptFriendRequest($user))
        {
            $message = auth()->user()->username . " accepted your friend request.";

            $user->push_notification($message, 2, '/user/'.auth()->user()->id, auth()->user());

            UserNotification::dispatch($message, 'friend-accepted', '/user/'.auth()->user()->id, $user);

            $flood = auth()->user();
            $flood->action_flood_gate = Carbon::now();
            $flood->save();

            return back()->with('success', $user->username . ' is now your friend!');
        } else {
            return back()->with('error', 'Friend request does not exist!');
        }
    }
    public function decline_friend(User $user)
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        if(auth()->user()->denyFriendRequest($user)) {
            $flood = auth()->user();
            $flood->action_flood_gate = Carbon::now();
            $flood->save();

            return back()->with('success', 'Declined friend request from ' . $user->username . '.');
        } else {
            return back()->with('error', 'Friend request does not exist!');
        }
    }
    public function accept_all_friends()
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        foreach(auth()->user()->getFriendRequests() as $request)
        {
            if(auth()->user()->acceptFriendRequest($request->sender)) {
                $message = auth()->user()->username . " accepted your friend request.";

                $request->sender->push_notification($message, 2, '/user/'.auth()->user()->id, auth()->user());

                UserNotification::dispatch($message, 'friend-accepted', '/user/'.auth()->user()->id, $request->sender);
            }
        }

        $flood = auth()->user();
        $flood->action_flood_gate = Carbon::now();
        $flood->save();

        return back()->with('success', 'Accepted all friend requests!');
    }
    public function decline_all_friends()
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        foreach(auth()->user()->getFriendRequests() as $request)
        {
            auth()->user()->denyFriendRequest($request->sender);
        }

        $flood = auth()->user();
        $flood->action_flood_gate = Carbon::now();
        $flood->save();

        return back()->with('success', 'Declined all friend requests.');
    }

    public function settings()
    {
        if (Setting::where('settings_enabled', '0')->get()->first()) {
            return view('maintenance.disabled');
        }
        
        return view('user.settings');
    }

    public function money(Request $request)
    {
        $transactions = auth()->user()->transactions()->paginate(10);

        if($request->ajax() && $transactions->count() > 0)
        {
            $view = view('components.load_user_transactions', compact('transactions'))->render();
            return response()->json(['html' => $view]);
        }

        return view('user.money', compact(['transactions']));
    }

    public function trade_currency(Request $request)
    {
        $allowedCurrencies = ['coins', 'cash'];

        if (!in_array($request->currency, $allowedCurrencies)) {
            abort(404);
        }

        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        $myU = auth()->user();

        if ($request->currency == 'coins') {
            $this->validate(request(), [
                'amount' => ['required', 'numeric', 'min:10']
            ], [
                'amount.min' => 'Amount must be divisible by 10!'
            ]);

            if ($request->amount / 10 != (int) ($request->amount / 10)) {
                return back()->withErrors(['Amount must be divisible by 10!']);
            }

            $newCoins = $myU->coins - $request->amount;
            $newCash = $myU->cash + (int) ($request->amount / 10);

            if ($newCoins >= 0 && $newCash >= 0) {
                $myU->action_flood_gate = Carbon::now();
                $myU->coins = $newCoins;
                $myU->cash = $newCash;
                $myU->save();

                return back()->with('success', 'Currency converted successfully!');
            } else {
                return back()->withErrors(['Insufficient Coins!']);
            }
        } else if ($request->currency == 'cash') {
            $this->validate(request(), [
                'amount' => ['required', 'numeric', 'min:1']
            ]);

            $newCoins = $myU->coins + 10 * $request->amount;
            $newCash = $myU->cash - $request->amount;

            if ($newCoins >= 0 && $newCash >= 0) {
                $myU->action_flood_gate = Carbon::now();
                $myU->coins = $newCoins;
                $myU->cash = $newCash;
                $myU->save();

                return back()->with('success', 'Currency converted successfully!');
            } else {
                return back()->withErrors(['Insufficient Cash!']);
            }
        }
    }

    public function settings_update(Request $request)
    {
        if (Setting::where('settings_enabled', '0')->get()->first()) {
            return view('maintenance.disabled');
        }

        $allowedSettings = ['account', 'privacy', 'password'];

        if (!in_array($request->setting, $allowedSettings)) {
            abort(404);
        }

        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
            {
                return back()->with('error', 'You\'re doing that too fast!');
            }

        $myU = Auth::user();

        if ($request->setting == 'account') {

            $allowedThemes = ['1', '2'];

            if ($request->username != $myU->username) {
                if ($myU->cash < 250) {
                    return back()->with('error', 'You need at least 250 Cash to change your username.');
                }

                $this->validate(request(), [
                    'username' => ['min:3', 'max:20', 'strictly_profane', 'regex:/\\A[a-z\\d]+(?:[.-][a-z\\d]+)*\\z/i', 'unique:users']
                ], [
                    'username.unique' => 'Username has already been taken.'
                ]);

                $usernameHistory = UsernameHistory::where('username', '=', $request->username)->first();

                if (UsernameHistory::where('username', '=', $request->username)->exists() && $usernameHistory->user_id != $myU->id) {
                    return back()->with('error', 'Username has already been taken.');
                }

                $usernameHistory = UsernameHistory::create([
                    'user_id' => $myU->id,
                    'username' => $myU->username
                ]);

                $myU->username = $request->username;
                $myU->cash -= 250;
                $myU->save();
            }

            if (!in_array($request->theme, $allowedThemes)) {
                return back()->with('error', 'Invalid theme.');
            }

            $this->validate(request(), [
                'description' => ['max:1000', 'strictly_profane'],
                'signature' => ['max:100', 'strictly_profane']
            ]);

            $myU->biography = $request->description;
            $myU->signature = $request->signature;
            $myU->theme = $request->theme;
            $myU->action_flood_gate = Carbon::now();
            $myU->save();

            return back()->with('success', 'Account Settings have been updated!');
        } else if ($request->setting == 'privacy') {
            
            
            $privacy = Privacy::where('user_id', auth()->user()->id)->first();
            $privacy->message = $request['message'];
            $privacy->inventory = $request['inventory'];
            $privacy->blurb = $request['blurb'];
            $privacy->trade = $request['trade'];
            $privacy->save();
            $myU->action_flood_gate = Carbon::now();
            $myU->save();

            return back()->with('success', 'Privacy Settings have been updated!');
        } else if ($request->setting == 'password') {
            $this->validate(request(), [
                'current_password' => ['required'],
                'new_password' => ['required', 'confirmed', 'min:6', 'max:255']
            ]);

            if (!Auth::once(['username' => auth()->user()->username, 'password' => $request->current_password])) {
                return back()->with('error', 'Incorrect password.');
            }

            $myU->password = bcrypt($request->new_password);
            $myU->action_flood_gate = Carbon::now();
            $myU->save();

            return back()->with('success', 'Password has been updated!');
        }
    }
   
    public function logout_other_sessions(Request $request)
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        $this->validate($request, [
            'password' => 'required',
        ]);

       try {
           Auth::logoutOtherDevices($request->get('password'));
           return back()->with('success', 'Logged out of all other sessions.');
       } catch (\Exception) {
           return back()->with('error', 'Incorrect password.');
       }
    }

    public function creator_area() {
        if(Setting::where('creator_area_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }

        $shirts = \App\Models\Item::where("type", 4)->where("creator_id", auth()->id())->orderBy('created_at', 'DESC')->paginate(6);
        $pants = \App\Models\Item::where("type", 5)->where("creator_id", auth()->id())->orderBy('created_at', 'DESC')->paginate(6);
        $ads = \App\Models\Ad::where("creator_id", auth()->id())->orderBy('created_at', 'DESC')->paginate(6);

        return view("user.creator-area", ["shirts" => $shirts, "pants" => $pants, "ads" => $ads]);
    }

    public function avatar_update(Request $request)
    {
        $allowedActions = ['wear', 'unwear', 'color', 'angle'];

        if (!in_array($request->action, $allowedActions)) {
            return response()->json(['success' => false, 'message' => 'Invalid action.']);
        }

        $myU = auth()->user();

        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return response()->json(['success' => false, 'message' => 'You\'re doing that too fast!']);
        }

        if ($request->action == 'wear') {
            if (!isset($request->item_id)) {
                return response()->json(['success' => false, 'message' => 'No item ID provided.']);
            }

            if (!Item::find($request->item_id)->exists()) {
                return response()->json(['success' => false, 'message' => 'Item does not exist.']);
            }

            $item = Item::find($request->item_id);

            if (!$myU->owns($item)) {
                return response()->json(['success' => false, 'message' => 'You do not own this item.']);
            }

            if ($item->pending != '0') {
                return response()->json(['success' => false, 'message' => 'This item is not approved.']);
            }

            switch ($item->type) {
                case 1:
                    if (empty($myU->avatar->hat1_id)) {
                        $column = 'hat1_id';
                    } else if (empty($myU->avatar->hat2_id)) {
                        $column = 'hat2_id';
                    } else if (empty($myU->avatar->hat3_id)) {
                        $column = 'hat3_id';
                    } else {
                        $column = 'hat1_id';
                    }
                    break;
                case 2:
                    $column = 'face_id';
                    break;
                case 3:
                    $column = 'tool_id';
                    break;
                case 6:
                    $column = 'tshirt_id';
                    break;
                case 4:
                    $column = 'shirt_id';
                    break;
                case 5:
                    $column = 'pants_id';
                    break;
                case 7:
                    $column = 'head_id';
                    break;
            }

            if ($column == 'hat1_id' || $column == 'hat2_id' || $column == 'hat3_id') {
                if ($myU->avatar->hat1_id == $request->item_id || $myU->avatar->hat2_id == $request->item_id || $myU->avatar->hat3_id == $request->item_id) {
                    return response()->json(['success' => true]);
                }
            }

            if ($myU->avatar->{$column} == $request->item_id) {
                return response()->json(['success' => true]);
            }

            $myU->avatar->{$column} = $request->item_id;
            $myU->action_flood_gate = Carbon::now();
            $myU->avatar->save();

            app('App\Http\Controllers\API\AvatarsController')->render($myU);

            return response()->json(['success' => true]);
        } else if ($request->action == 'unwear') {
            $allowedTypes = ['hat1', 'hat2', 'hat3', 'face', 'tool', 'tshirt', 'shirt', 'pants', 'head'];

            if (!in_array($request->type, $allowedTypes)) {
                return response()->json(['success' => false, 'message' => 'Invalid type.']);
            }

            if (!isset($request->type)) {
                return response()->json(['success' => false, 'message' => 'No type provided.']);
            }

            $requesttype = $request->type . '_id';

            $myU->avatar->$requesttype = null;
            $myU->action_flood_gate = Carbon::now();
            $myU->avatar->save();

            app('App\Http\Controllers\API\AvatarsController')->render($myU);

            return response()->json(['success' => true]);
        } else if ($request->action == 'color') {
            $allowedParts = ['head', 'torso', 'larm', 'rarm', 'lleg', 'rleg'];

            $allowedColors = [
                'brown'                         => '#8d5524',
                'light-brown'                   => '#c68642',
                'lighter-brown'                 => '#e0ac69',
                'lighter-lighter-brown'         => '#f1c27d',
                'bloxcity-yellow'               => '#faf123',

                'salmon'                        => '#f19d9a',
                'blue'                          => '#769fca',
                'light-blue'                    => '#a2d1e6',
                'purple'                        => '#a08bd0',
                'dark-purple'                   => '#312b4c',

                'dark-green'                    => '#046306',
                'green'                         => '#1b842c',
                'yellow'                        => '#f7b155',
                'orange'                        => '#f79039',
                'red'                           => '#ff0000',

                'light-pink'                    => '#f8a3d5',
                'pink'                          => '#ff0e9a',
                'white'                         => '#f1efef',
                'gray'                          => '#7d7d7d',
                'black'                         => '#000'
            ];

            if (!in_array($request->part, $allowedParts)) {
                return response()->json(['success' => false, 'message' => 'Invalid part.']);
            }

            if (!array_key_exists($request->color, $allowedColors)) {
                return response()->json(['success' => false, 'message' => 'Invalid color.']);
            }

            if ($myU->avatar->{'hex_' . $request->part} == $allowedColors[$request->color]) {
                return response()->json(['success' => true]);
            }

            $myU->avatar->{'hex_' . $request->part} = $allowedColors[$request->color];
            $myU->action_flood_gate = Carbon::now();
            $myU->avatar->save();

            app('App\Http\Controllers\API\AvatarsController')->render($myU);

            return response()->json(['success' => true]);
        } else if ($request->action == 'angle') {
            $allowedAngles = ['1', '2'];

            if (!in_array($request->angle, $allowedAngles)) {
                return response()->json(['success' => false, 'message' => 'Invalid angle.']);
            }

            if ($myU->avatar->orient == $request->angle) {
                return response()->json(['success' => true]);
            }

            $myU->avatar->orient = $request->angle;
            $myU->action_flood_gate = Carbon::now();
            $myU->avatar->save();

            app('App\Http\Controllers\API\AvatarsController')->render($myU);

            return response()->json(['success' => true]);
        }
    }

    public function avatar_wearing()
    {
        $myU = Auth::user();

        if (empty($myU->avatar->hat1_id) && empty($myU->avatar->hat2_id) && empty($myU->avatar->hat3_id) && empty($myU->avatar->face_id) && empty($myU->avatar->tool_id) && empty($myU->avatar->tshirt_id) && empty($myU->avatar->shirt_id) && empty($myU->avatar->pants_id) && empty($myU->avatar->head_id)) {
            return response()->json(['success' => false, 'message' => 'You are not wearing any items.']);
        }

        $json = [];

        if (!empty($myU->avatar->hat1_id)) {
            $item = Item::where('id', '=', $myU->avatar->hat1_id)->first();
            $json[] = ['id' => $item->id, 'name' => $item->name, 'thumbnail_url' => $item->get_render(), 'type' => 'hat1'];
        }

        if (!empty($myU->avatar->hat2_id)) {
            $item = Item::where('id', '=', $myU->avatar->hat2_id)->first();
            $json[] = ['id' => $item->id, 'name' => $item->name, 'thumbnail_url' => $item->get_render(), 'type' => 'hat2'];
        }

        if (!empty($myU->avatar->hat3_id)) {
            $item = Item::where('id', '=', $myU->avatar->hat3_id)->first();
            $json[] = ['id' => $item->id, 'name' => $item->name, 'thumbnail_url' => $item->get_render(), 'type' => 'hat3'];
        }

        if (!empty($myU->avatar->face_id)) {
            $item = Item::where('id', '=', $myU->avatar->face_id)->first();
            $json[] = ['id' => $item->id, 'name' => $item->name, 'thumbnail_url' => $item->get_render(), 'type' => 'face'];
        }

        if (!empty($myU->avatar->tool_id)) {
            $item = Item::where('id', '=', $myU->avatar->tool_id)->first();
            $json[] = ['id' => $item->id, 'name' => $item->name, 'thumbnail_url' => $item->get_render(), 'type' => 'tool'];
        }

        if (!empty($myU->avatar->tshirt_id)) {
            $item = Item::where('id', '=', $myU->avatar->tshirt_id)->first();
            $json[] = ['id' => $item->id, 'name' => $item->name, 'thumbnail_url' => $item->get_render(), 'type' => 'tshirt'];
        }

        if (!empty($myU->avatar->shirt_id)) {
            $item = Item::where('id', '=', $myU->avatar->shirt_id)->first();
            $json[] = ['id' => $item->id, 'name' => $item->name, 'thumbnail_url' => $item->get_render(), 'type' => 'shirt'];
        }

        if (!empty($myU->avatar->pants_id)) {
            $item = Item::where('id', '=', $myU->avatar->pants_id)->first();
            $json[] = ['id' => $item->id, 'name' => $item->name, 'thumbnail_url' => $item->get_render(), 'type' => 'pants'];
        }

        if (!empty($myU->avatar->head_id)) {
            $item = Item::where('id', '=', $myU->avatar->head_id)->first();
            $json[] = ['id' => $item->id, 'name' => $item->name, 'thumbnail_url' => $item->get_render(), 'type' => 'head'];
        }

        return response()->json($json);
    }

    public function avatar_inventory(Request $request)
    {
        $myU = User::find($request->id);

        // if($myU->id != auth()->user()->id)
        // {
        //     return response()->json(['success' => false, 'message' => 'You can only view your own inventory.']);
        // }
        
        $allowedCategories = [1 => 'hats', 2 => 'faces', 3 => 'accessories', 6 => 't-shirts', 4 => 'shirts', 5 => 'pants', 7 => 'heads'];

        if (!in_array($request->category, $allowedCategories)) {
            return response()->json(['success' => false, 'message' => 'Invalid category.']);
        }

        $type = array_search($request->category, $allowedCategories);
        $items = Item::where([['items.type', '=', $type], ['items.pending', '=', '0']])
            ->join('inventories', 'inventories.item_id', '=', 'items.id')
            ->where('inventories.user_id', '=', $myU->id)
            ->orderBy('inventories.created_at', 'DESC')
            ->paginate(8);

        if ($items->count() == 0) {
            return response()->json(['success' => false, 'message' => 'No ' . $allowedCategories[$type] . ' found.']);
        }

        $json = [
            'current_page' => $items->currentPage(),
            'total_pages' => $items->lastPage(),
            'items' => []
        ];

        foreach ($items as $item) {
            $json['items'][] = [
                'id' => $item->item_id,
                'name' => $item->name,
                'thumbnail_url' => $item->get_render()
            ];
        }

        return response()->json($json);
    }

    public function avatar_src()
    {
        return response()->json([
            'avatar' => auth()->user()->get_avatar(),
            'headshot' => auth()->user()->get_headshot()
        ]);
    }

    public function search(Request $request)
    {
        $totalUsers = User::all()->count();
        $search = $request->search ?? '';

        $users = User::where([
            ['username', 'LIKE', '%' . $search . '%'],
            ['deleted', '=', '0']
        ])->orderBy('id', 'ASC')->paginate(10);

        return view('user.search')->with([
            'totalUsers' => $totalUsers,
            'users' => $users
        ]);
    }

    public function online(Request $request)
    {
        $totalUsers = User::all()->count();

        $users = User::where([
            ['last_online', '>', '' . Carbon::now()->subMinutes(2) . ''], 
            ['deleted', '=', '0']
        ])->orderBy('last_online', 'ASC')->paginate(10);

        return view('user.online')->with([
            'totalUsers' => $totalUsers,
            'users' => $users
        ]);
    }
}
