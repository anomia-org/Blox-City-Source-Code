<?php

namespace App\Http\Controllers;

use App\Events\UserNotification;
use App\Models\Category;
use App\Models\ForumLike;
use App\Models\Reply;
use App\Models\Setting;
use App\Models\ThreadView;
use App\Models\Topic;
use App\Models\Thread;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        if(Setting::where('forum_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }
        $sort = request('sort');
        $top = request('topic');

        $threads = Thread::orderBy('stuck', 'DESC')->orderBy('pinned', 'DESC')->orderBy('last_reply', 'DESC')->where('deleted', '=', '0');

        if($sort)
        {
            switch ($sort) {
                case 'recent':
                    break;
                case 'trending':
                    $threads = $threads->where('views', '>', '49');
                    break;
                case 'official':
                    $threads = $threads->where('topic_id', '=', '1');
                    break;
            }
        }

        if($top && $sort != 'official') {
            $threads = $threads->where('topic_id', '=', request('topic'));
        }

        $categories = Category::orderBy('id')->get();
        $topics = Topic::orderBy('id')->get();
        $threads = $threads->paginate(10);

        if($request->ajax())
        {
            $view = view('components.load_threads', compact('threads'))->render();
            return response()->json(['html' => $view]);
        }

        return view('forum.index', compact('topics', 'categories', 'threads'));
    }

    public function show_topic(Request $request, Topic $topic)
    {
        if(Setting::where('forum_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }

        if ($topic->exists) {

            // $threads = $topic->threads()->latest()->paginate(15);
 
            // $threads = DB::table('threads')->where('topic_id', '=', $topic->id)->orderBy('pinned', 'DESC')->orderBy('updated_at', 'DESC')->get();
 
            //$threads = DB::select(DB::raw("SELECT threads.id, replies.thread_id  FROM threads INNER JOIN replies ON threads.id = replies.thread_id ORDER BY stuck DESC, pinned DESC"));
 
            $threads = $topic->threads()->orderBy('stuck', 'DESC')->orderBy('pinned', 'DESC')->orderBy('last_reply', 'DESC')->paginate(15);
 
             return view('forum.topic', compact(['threads', 'topic']));
         } else {
             return abort(404);
         }
    }

    public function thread_like(Request $request, Thread $thread)
    {
        $getLike = ForumLike::where('target_id', '=', $thread->id)->where('target_type', '=', 1)->where('user_id', '=', auth()->user()->id)->get()->first();

        if($thread->exists)
        {
            if($request->ajax())
            {
                $type = 1;
                if(!$getLike) {
                    $like = ForumLike::create([
                        'user_id' => auth()->id(),
                        'target_id' => $thread->id,
                        'target_type' => $type,
                    ]);
                    return response()->json(['success' => $like]);
                } elseif($getLike) {
                    $like = $getLike->delete();
                    return response()->json(['success' => $like]);
                }
            } else {
                return back();
            }
        } else {
            return back();
        }
    }

    public function reply_like(Request $request, Reply $reply)
    {
        $getLike = ForumLike::where('target_id', '=', $reply->id)->where('target_type', '=', 2)->where('user_id', '=', auth()->user()->id)->get()->first();

        if($reply->exists)
        {
            if($request->ajax())
            {
                $type = 2;
                if(!$getLike) {
                    $like = ForumLike::create([
                        'user_id' => auth()->id(),
                        'target_id' => $reply->id,
                        'target_type' => $type,
                    ]);
                    return response()->json(['success' => $like]);
                } elseif($getLike) {
                    $like = $getLike->delete();
                    return response()->json(['success' => $like]);
                }
            } else {
                return back();
            }
        } else {
            return back();
        }
    }

    public function show_thread(Request $request, Thread $thread)
    {
        if(Setting::where('forum_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }

        if(!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        if($thread->exists)
        {
            if($thread->deleted && Auth::user()->power == 0)
            { return abort(404); }

            $checkView = ThreadView::where('thread_id', $thread->id)->where('ip', $_SERVER['REMOTE_ADDR'])->where('user_id', auth()->id());

            if(!$checkView->exists() && Auth::user()) {
                $thread->increment('views');
                ThreadView::insert(['thread_id' => $thread->id, 'ip' => $_SERVER['REMOTE_ADDR'], 'user_id' => auth()->id()]);
            }

            $topic = Topic::where('id', $thread->topic_id)->get()->first();
            $category = Category::where('id', $topic->category_id)->get()->first();

            $replies = Reply::where('thread_id', '=', $thread->id)->orderBy('created_at', 'ASC')->paginate(5);

            if($request->ajax())
            {
                $view = view('components.load_replies', compact('replies'))->render();
                return response()->json(['html' => $view]);
            }

            return view('forum.thread', compact('thread', 'topic', 'category', 'replies'));
        } else {
            abort('403');
        }
    }

    public function create_thread(Request $request)
    {
        if(Setting::where('forum_enabled', '0')->get()->first())
        {
            return back()->with('error', 'The forum is currently disabled.');
        }

        if(Setting::where('posts_enabled', '0')->get()->first())
        {
            return back()->with('error', 'Posts are currently disabled.');
        }

        if(auth()->user()->power > 0)
        {
            $topics = Topic::all();
        } else {
            $topics = Topic::where('admin', '=', '0')->get();
        }

        return view('forum.new', compact(['topics']));
    }

    public function store_thread(Request $request)
    {
        // Acquire lock
        $lockKey = 'lock:store_thread:' . auth()->id();
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5); // Lock expires in 5 seconds

        if (!$lockAcquired) {
            return back()->with('error', 'Please wait before sending another friend request.');
        }
        
        try {

            DB::beginTransaction();

            if(Setting::where('forum_enabled', '0')->get()->first())
            {
                return back()->with('error', 'The forum is currently disabled.');
            }

            if(Setting::where('posts_enabled', '0')->get()->first())
            {
                return back()->with('error', 'Posts are currently disabled.');
            }

            if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
            {
                return back()->withInput()->with('error', 'Please wait '. env('FLOOD_GATE') . ' seconds before making another post.');
            }

            $this->validate($request, [
                'topic' => ['required'],
                'title' => ['required', 'max:50', 'min:3', 'strictly_profane', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&]+$/i'],
                'body' => ['required', 'max:3000', 'min:3', 'strictly_profane', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&\t\n\r]+/im'],
            ]);

            $topic = Topic::where('id', '=', request('topic'))->get()->first();

            if($topic->exists)
            {
                if($topic->admin == 1)
                {
                    if(auth()->user()->power > 0)
                    {

                        $thread = Thread::create([
                            'user_id' => auth()->id(),
                            'topic_id' => $topic->id,
                            'title' => request('title'),
                            'body' => request('body'),
                            'last_reply' => Carbon::now(),
                        ]);

                        $flood = Auth::user();
                        $flood->flood_gate = Carbon::now();
                        $flood->save();

                        DB::commit();

                        return redirect($thread->path());
                    } else {
                        return back()->with('error', 'You do not have permission to post in this sub-forum.');
                    }
                } else {

                    $thread = Thread::create([
                        'user_id' => auth()->id(),
                        'topic_id' => $topic->id,
                        'title' => request('title'),
                        'body' => request('body'),
                        'last_reply' => Carbon::now(),
                    ]);

                    $flood = Auth::user();
                    $flood->flood_gate = Carbon::now();
                    $flood->save();

                    DB::commit();

                    return redirect($thread->path());
                }
            } else {

                return abort('404');
            }
        } catch(\Exception $e) {
            Redis::del($lockKey);
            DB::rollBack();
            return back()->with('error', 'An error occurred while processing your request.');
        }
    }

    public function lock_thread(Thread $thread)
    {
        if(Auth::user()->power > 0)
        {
            $thread->lock();
            return back();
        } else {
            return back()->with('error', 'You do not have permission to perform this action.');
        }
    }

    public function scrub_thread(Thread $thread)
    {
        if(Auth::user()->power > 0)
        {
            $thread->scrub();
            return back();
        } else {
            return back()->with('error', 'You do not have permission to perform this action.');
        }
    }

    public function scrub_reply(Reply $reply)
    {
        if(Auth::user()->power > 0)
        {
            $reply->scrub();
            return back();
        } else {
            return back()->with('error', 'You do not have permission to perform this action.');
        }
    }

    public function delete_thread(Thread $thread)
    {
        if(Auth::user()->power > 0)
        {
            $thread->delete();
            return back();
        } else {
            return back()->with('error', 'You do not have permission to perform this action.');
        }
    }

    public function pin_thread(Thread $thread)
    {
        if(Auth::user()->power > 0)
        {
            $thread->pin();
            return back();
        } else {
            return back()->with('error', 'You do not have permission to perform this action.');
        }
    }

    public function move_thread(Request $request, Thread $thread)
    {
        if(Auth::user()->power > 0)
        {
            if(Setting::where('forum_enabled', '0')->get()->first())
            {
                return back()->with('error', 'The forum is currently disabled.');
            }
            if(!$thread->exists)
            {
                return back()->with('error', 'The thread you requested does not exist.');
            }

            $this->validate(request(), [
                'topic' => ['required', 'numeric']
            ]);

            if ($request->topic == $thread->topic->id)
            {
                return back()->with('error', 'You cannot move a thread to the same topic.');
            }

            $thread->timestamps = false;
            $thread->topic_id = $request->topic;
            $thread->update();

            return redirect()->route('forum.thread', $thread->id)->with('success', 'Thread has been moved!');
        } else {
            return back()->with('error', 'You do not have permission to perform this action.');
        }
    }

    public function show_move(Request $request, Thread $thread)
    {
        if(Auth::user()->power > 0)
        {
            if(Setting::where('forum_enabled', '0')->get()->first())
            {
                return back()->with('error', 'The forum is currently disabled.');
            }
            if(!$thread->exists)
            {
                return back()->with('error', 'The thread you requested does not exist.');
            }

            $topics = Topic::orderBy('id')->get();
            return view('forum.move', compact(['thread', 'topics']));
        } else {
            return back()->with('error', 'You do not have permission to perform this action.');
        }
    }

    public function show_quote(Thread $thread, $quote_id, $quote_type)
    {
        if(Setting::where('forum_enabled', '0')->get()->first())
        {
            return back()->with('error', 'The forum is currently disabled.');
        }

        if(Setting::where('posts_enabled', '0')->get()->first())
        {
            return back()->with('error', 'Posts are currently disabled.');
        }

        if($thread->exists)
        {
            $quote = null;
            if($thread->locked)
            {
                return back()->with('error', 'This thread is locked and cannot be replied to.');
            }

            if($quote_type == 1)
            {
                $quote = $thread;

                if($quote_id != $thread->id)
                {
                    return back()->with('error', 'Invalid data.');
                }
            }

            if($quote_type == 2)
            {
                $quote = Reply::where('id', $quote_id)->get()->first();

                if($quote->thread_id != $thread->id)
                {
                    return back()->with('error', 'Invalid data.');
                }
            }

            if(!$quote->exists)
            {
                return abort('404');
            }

            $topic = Topic::where('id', $thread->topic_id)->get()->first();

            return view('forum.quote', compact(['thread', 'topic', 'quote', 'quote_type']));
        } else {
            return abort('404');
        }
    }

    public function store_quote(Request $request, Thread $thread, $quote_id, $quote_type)
    {
        // Acquire lock
        $lockKey = 'lock:store_quote:' . auth()->id();
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5); // Lock expires in 5 seconds

        if (!$lockAcquired) {
            return back()->with('error', 'Please wait before sending another friend request.');
        }

        try {

            DB::beginTransaction();

            if(Setting::where('forum_enabled', '0')->get()->first())
            {
                return back()->with('error', 'The forum is currently disabled.');
            }

            if(Setting::where('posts_enabled', '0')->get()->first())
            {
                return back()->with('error', 'Posts are currently disabled.');
            }

            if($thread->locked || $thread->deleted)
            {
                Redis::del($lockKey);
                return back()->with('error', 'This thread is locked and cannot be replied to.');
            }

            if($quote_type == 1)
            {
                $quote = $thread;

                if($quote_id != $thread->id)
                {
                    return back()->with('error', 'Invalid data.');
                }
            }

            if($quote_type == 2)
            {
                $quote = Reply::where('id', $quote_id)->get()->first();

                if($quote->thread_id != $thread->id)
                {
                    return back()->with('error', 'Invalid data.');
                }
            }

            if(!$quote->exists)
            {
                return abort('404');
            }

            if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
            {
                return back()->withInput()->with('error', 'Please wait '. env('FLOOD_GATE') . ' seconds before making another post.');
            }

            $this->validate($request, [
                'body' => ['required', 'max:3000', 'min:3', 'strictly_profane', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&\t\n\r]+/im'],
            ]);

            if($thread->exists)
            {
                $thread->addReply([
                    'body' => request('body'),
                    'user_id' => auth()->id(),
                    'topic_id' => $thread->topic_id,
                    'quote_id' => $quote->id,
                    'quote_type' => $quote_type,
                ]);

                $flood = Auth::user();
                $flood->flood_gate = Carbon::now();
                $flood->save();

                $update = $thread;
                $update->last_reply = Carbon::now();
                $update->save();

                if($quote->owner->username != auth()->user()->username)
                {
                    $message = auth()->user()->username . " quoted you in a post.";

                    $quote->owner->push_notification($message, 5, $thread->path(), auth()->user());

                    UserNotification::dispatch($message, 'forum-quote', $thread->path(), $quote->owner);
                }

                DB::commit();

                return redirect($thread->path());
            } else {
                return back();
            }
        } catch(\Exception $e) {
            Redis::del($lockKey);
            DB::rollBack();
            return back()->with('error', 'An error occurred while processing your request.');
        }
    }

    public function create_reply(Thread $thread)
    {
        if(Setting::where('forum_enabled', '0')->get()->first())
        {
            return back()->with('error', 'The forum is currently disabled.');
        }

        if(Setting::where('posts_enabled', '0')->get()->first())
        {
            return back()->with('error', 'Posts are currently disabled.');
        }

        if($thread->exists)
        {
            if($thread->locked)
            {
                return back()->with('error', 'This thread is locked and cannot be replied to.');
            }

            $topic = Topic::where('id', $thread->topic_id)->get()->first();

            return view('forum.reply', compact(['thread', 'topic']));
        } else {
            return abort('404');
        }
    }

    public function store_reply(Request $request, Thread $thread)
    {
        // Acquire lock
        $lockKey = 'lock:store_reply:' . auth()->id();
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5); // Lock expires in 5 seconds

        if (!$lockAcquired) {
            return back()->with('error', 'Please wait before sending another friend request.');
        }

        try {

            DB::beginTransaction();

            if(Setting::where('forum_enabled', '0')->get()->first())
            {
                return back()->with('error', 'The forum is currently disabled.');
            }

            if(Setting::where('posts_enabled', '0')->get()->first())
            {
                return back()->with('error', 'Posts are currently disabled.');
            }

            if($thread->locked)
            {
                return back()->with('error', 'This thread is locked and cannot be replied to.');
            }

            if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
            {
                return back()->withInput()->with('error', 'Please wait '. env('FLOOD_GATE') . ' seconds before making another post.');
            }

            $this->validate($request, [
                'body' => ['required', 'max:3000', 'min:3', 'strictly_profane', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&\t\n\r]+/i'],
            ]);

            if($thread->exists)
            {
                $thread->addReply([
                    'body' => request('body'),
                    'user_id' => auth()->id(),
                    'topic_id' => $thread->topic_id,
                ]);

                $flood = Auth::user();
                $flood->flood_gate = Carbon::now();
                $flood->save();

                $update = $thread;
                $update->last_reply = Carbon::now();
                $update->save();

                if($thread->owner->username != auth()->user()->username)
                {
                    $message = auth()->user()->username . " replied to your thread.";

                    $thread->owner->push_notification($message, 4, $thread->path(), auth()->user());

                    UserNotification::dispatch($message, 'forum-reply', $thread->path(), $thread->owner);
                }

                DB::commit();

                return redirect($thread->path());
            } else {

                return back();
            }
        } catch(\Exception $e) {
            Redis::del($lockKey);
            DB::rollBack();
            return back()->with('error', 'An error occurred while processing your request.');
        }
    } 
}
