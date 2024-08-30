<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Item;

class CreatorPanelController extends Controller
{
    public function index()
    {
        return redirect()->route('user.creator-area.shirts');
    }

    public function shirts()
    {
        $items = Item::where('creator_id', '=', auth()->user()->id)->where('type', '=', '4')->latest()->get();
        return view('user.creator-area.shirts', compact(['items']));
    }

    public function pants()
    {
        $items = Item::where('creator_id', '=', auth()->user()->id)->where('type', '=', '5')->latest()->get();
        return view('user.creator-area.pants', compact(['items']));
    }

    public function ads()
    {
        return view('user.creator-area.ads');
    }
}
