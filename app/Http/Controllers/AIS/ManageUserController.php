<?php

namespace App\Http\Controllers\AIS;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use App\Models\AntelopeLog;

class ManageUserController extends Controller
{
    public function index($type, $id)
    {
        if((auth()->user()->power <= 0) || (empty(Cookie::get('ais'))) || (Cookie::get('ais') != auth()->user()->ais))
        {
            return redirect(route('login'));
        }

        $user = User::where('id', '=', $id)->firstOrFail();

        switch ($type) {
            case 'currency':
                $title = "Manage {$user->username}'s Currency";

                break;
            case 'inventory':
                $title = "Manage {$user->username}'s Inventory";

                break;
            default:
                abort(404);
        }

        return view('ais.users.manage')->with([
            'title' => $title,
            'type' => $type,
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        if((auth()->user()->power <= 0) || (empty(Cookie::get('ais'))) || (Cookie::get('ais') != auth()->user()->ais))
        {
            return redirect(route('login'));
        }

        $user = User::where('id', '=', $request->id)->firstOrFail();
        $user->timestamps = false;

        switch ($request->action) {
            case 'give_currency':
                if ($user->id == auth()->user()->id || ($user->power > 0 && auth()->user()->power < 3)) return back()->withErrors(['You cannot give currency to this user.']);

                $this->validate($request, [
                    'amount' => ['required', 'numeric', 'min:1', 'max:1000000'],
                    'currency' => ['required', 'in:coins,cash']
                ]);

                $user->{"{$request->currency}"} += $request->amount;
                $user->save();

                AntelopeLog::create([
                    'user_id' => auth()->id(),
                    'action' => auth()->user()->username . ' has granted ' . $request->amount . ' ' . $request->currency . ' for user ID# ' . $user->id,
                ]);

                return redirect()->route('ais.users.view', $user->id)->with('success', "User has been given {$request->currency} currency.");
            case 'take_currency':
                if ($user->id == auth()->user()->id || ($user->power > 0 && auth()->user()->power < 3)) return back()->withErrors(['You cannot take currency from this user.']);

                $this->validate($request, [
                    'amount' => ['required', 'numeric', 'min:1'],
                    'currency' => ['required', 'in:coins,cash']
                ]);

                if ($request->amount > $user->{"{$request->currency}"})
                    return back()->withErrors(["User does not have this many {$request->currency}."]);

                    AntelopeLog::create([
                        'user_id' => auth()->id(),
                        'action' => auth()->user()->username . ' has revoked ' . $request->amount . ' ' . $request->currency . ' for user ID# ' . $user->id,
                    ]);

                $user->{"{$request->currency}"} -= $request->amount;
                $user->save();

                return redirect()->route('ais.users.view', $user->id)->with('success', "{$request->amount} {$request->currency} have been taken from this user.");
            case 'give_items':
                if ($user->id == auth()->user()->id || ($user->power > 0 && auth()->user()->power < 3) ) return back()->withErrors(['You cannot give items to this user.']);

                $this->validate($request, [
                    'item_id' => ['required', 'numeric', 'min:1']
                ]);

                $item = Item::where('id', '=', $request->item_id);

                if (!$item->exists())
                    return back()->withErrors(['This item does not exist.']);

                $item = $item->first();

                $exceptions = [482, 1031];

                if($item->special && !in_array($item->id, $exceptions))
                    return back()->withErrors(['Collectibles cannot be given to users with this functionality. Please contact your supervisor for further instructions.']);
                //$saintItem = config('site.saint_item_id');

                AntelopeLog::create([
                    'user_id' => auth()->id(),
                    'action' => auth()->user()->username . ' has granted item ID#' . $item->id . ' for user ID# ' . $user->id,
                ]);

                $user->grant_item($item);
                //if ($item->id == $saintItem && !$user->ownsAward(5))
                //    $user->giveAward(5);

                return redirect()->route('ais.users.view', $user->id)->with('success', "User has been given the \"{$item->name}\" item.");
            case 'take_items':
                if ($user->id == auth()->user()->id || ($user->power > 0 && auth()->user()->power < 3) ) return back()->withErrors(['You cannot remove items from this user.']);

                $this->validate($request, [
                    'item_id' => ['required', 'numeric', 'min:1']
                ]);

                $item = Item::where('id', '=', $request->item_id);

                if (!$item->exists())
                    return back()->withErrors(['This item does not exist.']);

                $item = $item->first();
                //$saintItem = config('site.saint_item_id');
                if($item->special && $item->id != 482)
                    return back()->withErrors(['Collectibles cannot be taken from users at the moment. Please contact your supervisor for further instructions.']);

                if (!$user->owns($item))
                    return back()->withErrors(['User does not own this item.']);

                $user->revoke_item($item);

                if ($user->isWearing($item)) {
                    // eventually we will forcefully take the item off and re-render the user's avatar
                    //$user->takeOffItem($item->id);
                    //RenderUser::dispatch($user->id);
                }

                //if ($item->id == $saintItem && $user->ownsAward(5))
                //    $user->removeAward(5);

                AntelopeLog::create([
                    'user_id' => auth()->id(),
                    'action' => auth()->user()->username . ' has revoked item ID#' . $item->id . ' for user ID# ' . $user->id,
                ]);

                return redirect()->route('ais.users.view', $user->id)->with('success', "The \"{$item->name}\" item has been taken from this user.");
            default:
                abort(500);
        }
    }
}
