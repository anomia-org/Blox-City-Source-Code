<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AIS\BanController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class BansController extends Controller
{
    public function index()
    {
        if (auth()->user()->deleted < 1)
            abort(404);
        $ban = auth()->user()->ban;

        $length = array_search($ban->length, BanController::BAN_LENGTHS);
        $category = array_search($ban->reason, BanController::BAN_CATEGORIES);
        $canReactivate = strtotime($ban->banned_until) < time();

        if ($length == 'Close Account')
            $length = 'Terminated';

        return view('user.suspended')->with([
            'ban' => $ban,
            'length' => $length,
            'category' => $category,
            'canReactivate' => $canReactivate
        ]);
    }

    public function reactivate(Request $request)
    {
        if (auth()->user()->deleted < 1)
            abort(404);

        $ban = auth()->user()->ban;
        $ban->active = 0;
        $ban->save();

        $user = auth()->user();
        $user->deleted = 0;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Account has been reactivated!');
    }
}
