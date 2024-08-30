<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdvertisementCreateRequest;
use App\Http\Requests\BidRequest;
use App\Models\Ad;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class AdvertismentController extends Controller
{
    public function advertise($id, AdvertisementCreateRequest $request) {
        if(Setting::where('ads_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }

        if(auth()->user()->cash < request()->bid) {
            return redirect()->back()->with("error", "You don't have enough currency to bid this amount.");
        }

        $item = Item::findOrFail($id);
        if($item->creator_id != auth()->id()) abort(403);

        if($item->pending != 0) return back()->with('error', 'Item is not approved!');

        $realName = bin2hex(random_bytes(32));
        $imageName = $realName.'.'.request()->file->getClientOriginalExtension();

        $disk = Storage::build([
            'driver' => 'local',
            'root' => '/var/www/cdn',
        ]);

        $disk->putFileAs('', $request->file, $imageName);
        
        Ad::create([
            "creator_id" => auth()->id(),
            "item_id" => $id,
            "image_path" => 'https://cdn.bloxcity.com/'.$imageName, // temporary unless not in CDN?
            "bid" => request('bid'),
            "total_bids" => request('bid'),
            "bid_at" => Carbon::now(),
        ]);

        $user = auth()->user();
        $user->cash -= request()->bid;
        $user->save();

        return redirect("/creator-area");
    }

    public function advertise_view($id) {
        if(Setting::where('ads_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }

        $item = Item::findOrFail($id);
        if(!$item->creator_id == auth()->id()) abort(403);

        return view("ads.create", compact("item"));
    }

    public function bid(Ad $id, BidRequest $request) {
        if(Setting::where('ads_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }

        if(auth()->user()->cash < request()->bid) {
            return redirect()->back()->with("error", "You don't have enough currency to bid this amount.");
        }

        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->withInput()->with('error', 'Please wait '. env('ACTION_FLOOD_GATE') . ' seconds before making another post.');
        }

        $ad = $id;

        $new = false;

        if($ad->bid == 0) 
        { 
            $new = true; 
        }

        $ad->bid += request()->bid;
        $ad->total_bids += request()->bid;
        if($new) 
        { 
            $ad->bid_at = Carbon::now(); 
        }
        $ad->save();

        $user = auth()->user();
        $user->cash -= request()->bid;
        $user->save();

        $flood = auth()->user();
        $flood->action_flood_gate = Carbon::now();
        $flood->save();

        return redirect()->back()->with('success', 'Successfully bid ' . ' on advertisement!');
    }

    public function bid_view(Ad $id) {
        if(Setting::where('ads_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }

        if(!$id->exists) abort(404);
        $ad = $id;

        return view("ads.manage", compact(['ad']));
    }

    public function takedown(Ad $id) {
        if(Setting::where('ads_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }
        
        if(!$id->exists) abort(404);

        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->withInput()->with('error', 'Please wait '. env('ACTION_FLOOD_GATE') . ' seconds before making another post.');
        }

        $id->delete();

        $flood = auth()->user();
        $flood->action_flood_gate = Carbon::now();
        $flood->save();

        return redirect()->back()->with('success', 'Successfully removed advertisement.');
    }

    public function show_ad() {
        $ads = Ad::running()->inRandomOrder()->take(5)->get();

        $randomizedAds = [];
        foreach ($ads as $ad) {
            $bid = $ad->bid;
            
            $sumOfBids = $ads->sum("bid");
            $probability = $bid / $sumOfBids * 100;

            $randomizedAds[] = [
                "ad" => $ad,
                "probability" => $probability,
            ];
        }
 
        $sumOfProbabilities = 0;
        foreach ($randomizedAds as $ad) {
            $sumOfProbabilities += $ad["probability"];
        }

        $randomNumber = rand(0, $sumOfProbabilities);

        foreach ($randomizedAds as $randomizedAd) {
            if ($randomNumber < $randomizedAd["probability"]) {
                $randomAd =  $randomizedAd;
                break;
            }
        }

        if(!isset($randomAd)) {
            usort($randomizedAds, function($a, $b) {
                return $b["probability"] <=> $a["probability"];
            });

            $randomAd = $randomizedAds[0] ?? null;
        }

        if($randomAd == null) return;

        return response()->json(['ad' => $randomAd["ad"], 'target_url' => $randomAd['ad']->get_url(), 'report_url' => route('report.ads', $randomAd["ad"]->id)]);
    }
}
