<?php

namespace App\Http\Controllers\AIS;

use App\Models\Item;
use App\Models\Guild;
use App\Models\Game;
use App\Models\AntelopeLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

class AssetApprovalController extends Controller
{
    public function index(Request $request)
    {
        if((auth()->user()->power <= 0) || (empty(Cookie::get('ais'))) || (Cookie::get('ais') != auth()->user()->ais))
        {
            return redirect(route('login'));
        }

        $totalItems = Item::where('pending', '=', '1')->whereIn('type', ['4', '5', '6'])->count();
        $totalLogos = Guild::where('is_thumbnail_pending', '=', true)->count();
        //$totalAds
        //$totalThumbnails = Game::where('is_thumbnail_pending' ,'=', true)->count();

        switch ($request->category) {
            case '':
            case 'items':
                $category = 'items';
                $type = 'item';
                $assets = Item::where('pending', '=', '1')->whereIn('type', ['4', '5', '6'])->orderBy('created_at', 'DESC')->paginate(12);

                foreach ($assets as $asset) {
                    $asset->image = "https://cdn.bloxcity.com/".$asset->hash.".png";
                    $asset->source = "https://cdn.bloxcity.com/".$asset->source.".png";
                    $asset->url = route('market.item', $asset->id);
                    $asset->creator_url = route('user.profile', $asset->owner->id);
                    $asset->creator_name = $asset->owner->username;
                }
                break;
            case 'logos':
                $category = 'logos';
                $type = 'group';
                $assets = Guild::where('is_thumbnail_pending', '=', true)->orderBy('created_at', 'DESC')->paginate(12);

                foreach ($assets as $asset) {
                    $asset->image = $asset->raw_thumbnail();
                    $asset->url = route('groups.view', $asset->id);
                    $asset->creator_url = route('user.profile', $asset->owner->id);
                    $asset->creator_name = $asset->owner->username;
                }
                break;
            /* case 'thumbnails':
                $category = 'thumbnails';
                $type = 'game';
                $assets = Game::where('is_thumbnail_pending', '=', true)->orderBy('created_at', 'DESC')->paginate(12);

                foreach ($assets as $asset) {
                    $asset->image = "{$storage}/thumbnails/games/{$asset->thumbnail}.png";
                    $asset->url = route('games.view', $asset->id);
                    $asset->creator_url = route('users.profile', $asset->creator->id);
                    $asset->creator_name = $asset->creator->username;
                }
                break; */
            default:
                abort(404);
        }

        return view('ais.assets.approval')->with([
            'totalItems' => $totalItems,
            'totalLogos' => $totalLogos,
            //'totalThumbnails' => $totalThumbnails,
            'category' => $category,
            'type' => $type,
            'assets' => $assets
        ]);
    }

    public function update(Request $request)
    {
        if((auth()->user()->power <= 0) || (empty(Cookie::get('ais'))) || (Cookie::get('ais') != auth()->user()->ais))
        {
            return redirect(route('login'));
        }
        
        switch ($request->type) {
            case 'item':
                $item = Item::where('id', '=', $request->id)->whereIn('type', ['4', '5', '6'])->firstOrFail();
                $item->timestamps = false;
                $url = route('market.item', $item->id);

                if ($item->pending != '1')
                    return back()->withErrors(['This item has already been moderated.']);

                switch ($request->action) {
                    case 'approve':

                        $item->pending = '0';
                        $item->save();

                        AntelopeLog::create([
                            'user_id' => auth()->id(),
                            'action' => auth()->user()->username . ' has approved asset ID# ' . $item->id,
                        ]);

                        return back()->with('success', 'Item has been approved.');
                    case 'deny':
                        $item->pending = '2';
                        $item->save();

                        AntelopeLog::create([
                            'user_id' => auth()->id(),
                            'action' => auth()->user()->username . ' has denied asset ID# ' . $item->id,
                        ]);

                        return back()->with('success', 'Item has been declined.');
                    default:
                        abort(404);
                }
            case 'group':
                $clan = Guild::where('id', '=', $request->id)->firstOrFail();
                $clan->timestamps = false;

                if (!$clan->is_thumbnail_pending)
                    return back()->withErrors(['This logo has already been moderated.']);

                switch ($request->action) {
                    case 'approve':
                        $clan->is_thumbnail_pending = false;
                        $clan->save();

                        AntelopeLog::create([
                            'user_id' => auth()->id(),
                            'action' => auth()->user()->username . ' has approved logo for group ID# ' . $item->id,
                        ]);

                        return back()->with('success', 'Logo has been approved.');
                    case 'deny':
                        $clan->is_thumbnail_pending = false;
                        $clan->thumbnail_url = 'declined.png';
                        $clan->save();

                        AntelopeLog::create([
                            'user_id' => auth()->id(),
                            'action' => auth()->user()->username . ' has denied logo for group ID# ' . $item->id,
                        ]);

                        return back()->with('success', 'Logo has been declined.');
                    default:
                        abort(404);
                }
            /* case 'game':
                //$game = Game::where('id', '=', $request->id)->firstOrFail();
                $game->timestamps = false;

                if (!$game->is_thumbnail_pending)
                    return back()->withErrors(['This thumbnail has already been moderated.']);

                switch ($request->action) {
                    case 'approve':
                        $game->is_thumbnail_pending = false;
                        $game->save();

                        return back()->with('success_message', 'Thumbnail has been approved.');
                    case 'deny':

                        $game->is_thumbnail_pending = false;
                        $game->thumbnail = 'declined';
                        $game->save();

                        return back()->with('success_message', 'Thumbnail has been declined.');
                    default:
                        abort(404);
                } */
            default:
                abort(404);
        }
    }
}
