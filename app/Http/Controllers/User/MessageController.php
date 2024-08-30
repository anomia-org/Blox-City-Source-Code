<?php

namespace App\Http\Controllers\User;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Models\Message;
use \App\Models\MessageReply;
use \App\Http\Requests\MessageCreateRequest;
use \App\Http\Requests\MessageReplyRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class MessageController extends Controller
{
    public function view_all($type = 'received', $read = 'all') { //read unread
        switch($type) {
            case 'sent':
                $messages = Message::where('from', auth()->user()->id)->latest()->paginate(8);
                $count = Message::where("to", auth()->user()->id)->where("read", 0)->count();
                break;
            case 'received':
                if($read != 'all')
                {
                    if($read == 'read')
                    {
                        $messages = Message::where("to", auth()->user()->id)->where('read', '1')->latest()->paginate(8);
                    } elseif ($read == 'unread') {
                        $messages = Message::where("to", auth()->user()->id)->where('read', '0')->latest()->paginate(8);
                    }
                } else {
                    $messages = Message::where("to", auth()->user()->id)->latest()->paginate(8);
                }
                $count = Message::where("to", auth()->user()->id)->where("read", 0)->count();
                break;
            default:
                return;
        }

        return view("messages.index", ["messages" => $messages, "count" => $count, "type" => $type, "read" => $read]);
    }

    public function view($id) {
        $message = Message::findOrFail($id);

        if(!$message->hasAccess()) return abort(404);
        if($message->to == auth()->user()->id) $message->read = true;
        $message->save();

        return view("messages.view", ["message" => $message]);
    }

    public function compose_view($id) {
        $user = \App\Models\User::findOrFail($id);
        if($user->id == auth()->user()->id) 
        {
            return back()->with('error', 'You can\'t send messages to yourself!');
        }
        return view("messages.create", ["user" => $user]);
    }

    public function compose(MessageCreateRequest $request) {
        if(request('to') == auth()->user()->id)
        {
            return back()->with('error', 'You can\'t send messages to yourself!');
        }

        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
            return back()->withInput()->with('error', 'Please wait '. env('FLOOD_GATE') . ' seconds before sending another message.');
        }

        $lockKey = 'lock:message:' . request('to');
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);

        if (!$lockAcquired) {
            return back()->with('error', 'You are trying too fast. Please wait and try again.');
        }

        try {
            DB::beginTransaction();

            $message = Message::create($request->validated());
            $message->from = auth()->user()->id;
            $message->save();

            $flood = auth()->user();
            $flood->flood_gate = Carbon::now();
            $flood->save();

            DB::commit();

            return redirect("/messages/$message->id");
        } catch (\Exception $e) {
            DB::rollBack();
            Redis::del($lockKey);
            return back()->with('error', 'An error occurred while sending the message.');
        }
    }

    public function reply_view($id) {
        $message = Message::findOrFail($id);
        if(!$message->hasAccess()) return abort(404);

        if($message->from == auth()->user()->id)
        {
            return back()->with('error', 'You can\'t send messages to yourself!');
        }
     
        return view("messages.reply", ["message" => $message]);
    }

    public function reply(MessageReplyRequest $request) {
        $message = Message::findOrFail($request->input("message_id"));
        if(!$message->hasAccess()) return abort(404);

        if($message->from == auth()->user()->id)
        {
            return back()->with('error', 'You can\'t send messages to yourself!');
        }

        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
            return back()->withInput()->with('error', 'Please wait '. env('FLOOD_GATE') . ' seconds before sending another message.');
        }

        $lockKey = 'lock:message_reply:' . $message->from;
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);

        if (!$lockAcquired) {
            return back()->with('error', 'You are trying too fast. Please wait and try again.');
        }

        try {

            DB::beginTransaction();
            
            $re = (strlen($message->subject) >= 120) ? [] : ["subject" => "RE: $message->subject"];

            $reply = Message::create($request->validated() + $re);
            $reply->from = auth()->user()->id;
            $reply->to = $message->from;
            $reply->reply_to = $message->id;
            $reply->save();

            $flood = auth()->user();
            $flood->flood_gate = Carbon::now();
            $flood->save();

            DB::commit();
            Redis::del($lockKey);

            return redirect("/messages/$reply->id");
        } catch (\Exception $e) {
            DB::rollBack();
            Redis::del($lockKey);
            return back()->with('error', 'An error occurred while sending the message.');
        }
    }
}
