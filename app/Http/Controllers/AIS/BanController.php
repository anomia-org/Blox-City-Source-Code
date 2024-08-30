<?php

namespace App\Http\Controllers\AIS;

use App\Models\Ban;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use App\Models\AntelopeLog;

class BanController extends Controller
{
    public const BAN_LENGTHS = [
        'Warning' => 'warning',
        '12 Hours' => '12_hours',
        '1 Day' => '1_day',
        '3 Days' => '3_days',
        '7 Days' => '7_days',
        '14 Days' => '14_days',
        'Close Account' => 'closed'
    ];

    public const BAN_LENGTH_TIMES = [
        'warning' => 1,
        '12_hours' => 43200,
        '1_day' => 86400,
        '3_days' => 259200,
        '7_days' => 604800,
        '14_days' => 1209600,
        'closed' => 31536000
    ];

    public const BAN_CATEGORIES = [
        'None' => 'none',
        'Spam' => 'spam',
        'Profanity' => 'profanity',
        'Sensitive topics' => 'sensitive_topics',
        'Harassment' => 'harassment',
        'Discrimination' => 'discrimination',
        'Sexual content' => 'sexual_content',
        'Inappropriate content' => 'inappropriate_content',
        'Inappropriate links' => 'inappropriate_links',
        'Coin farming' => 'coin_farming'
    ];

    public function index($id)
    {
        if((auth()->user()->power <= 0) || (empty(Cookie::get('ais'))) || (Cookie::get('ais') != auth()->user()->ais))
        {
            return redirect(route('login'));
        }

        $user = User::where('id', '=', $id)->firstOrFail();
        $lengths = $this::BAN_LENGTHS;
        $categories = $this::BAN_CATEGORIES;

        if ($user->power > 0)
            return back()->withErrors(['This is an active staff member and cannot be banned.']);

        if ($user->deleted > 0)
            return back()->withErrors(['This user is already banned.']);

        return view('ais.ban')->with([
            'user' => $user,
            'lengths' => $lengths,
            'categories' => $categories
        ]);
    }

    public function create(Request $request)
    {
        if((auth()->user()->power <= 0) || (empty(Cookie::get('ais'))) || (Cookie::get('ais') != auth()->user()->ais))
        {
            return redirect(route('login'));
        }

        $user = User::where('id', '=', $request->id)->firstOrFail();

        if ($user->power > 0)
            return back()->withErrors(['This is an active staff member and cannot be banned.']);

        if ($user->deleted > 0)
            return back()->withErrors(['This user is already banned.']);

        if (!in_array($request->length, $this::BAN_LENGTHS))
            return back()->withErrors(['Invalid length.']);

        if (!in_array($request->category, $this::BAN_CATEGORIES))
            return back()->withErrors(['Invalid category.']);

        $ban = new Ban;
        $ban->user_id = $user->id;
        $ban->banned_by = auth()->user()->id;
        $ban->note = $request->note;
        $ban->content = $request->content;
        $ban->reason = $request->category;
        $ban->length = $request->length;
        $ban->expires_at = Carbon::createFromTimestamp(time() + $this::BAN_LENGTH_TIMES[$request->length])->format('Y-m-d H:i:s');
        $ban->save();

        $user->deleted = 1;
        $user->save();

        AntelopeLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->username . ' has created ban ID# ' . $ban->id . ' for user ID# ' . $user->id,
        ]);

        return redirect()->route('user.profile', $user->id);
    }
}
