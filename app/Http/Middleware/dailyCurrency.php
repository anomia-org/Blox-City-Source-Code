<?php

namespace App\Http\Middleware;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Item;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class dailyCurrency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {

            $user = auth()->user();

            if(Carbon::parse($user->membership_expires)->isPast())
            {
                $user->membership = 0;
                $user->save();
            }

            $date = time();

            $currentCash = $user->cash;
            $currentCoins = $user->coins;
            $newCash = $user->cash;
            $newCoins = $user->coins;

            if($user->membership == 1 && !$user->owns(Item::find(48)))
            {
                $user->grant_item(Item::find(48));
            } elseif($user->membership == 2 && !$user->owns(Item::find(49)))
            {
                $user->grant_item(Item::find(49));
            } elseif($user->membership == 3 && (!$user->owns(Item::find(50)) && !$user->owns(Item::find(51))))
            {
                $user->grant_item(Item::find(50));
                $user->grant_item(Item::find(51));
            }

            if($date > $user->last_currency + 86400) {

                if($user->membership == 0) {
                    $newCash = $currentCash + 5;
                    $newCoins = $currentCoins + 10;
                    $cash = 5;
                    $coins = 10;
                } elseif($user->membership == 1) {
                    $newCash = $currentCash + 15;
                    $newCoins = $currentCoins + 30;
                    $cash = 15;
                    $coins = 30;
                } elseif($user->membership == 2) {
                    $newCash = $currentCash + 30;
                    $newCoins = $currentCoins + 60;
                    $cash = 30;
                    $coins = 60;
                } elseif($user->membership == 3) {
                    $newCash = $currentCash + 60;
                    $newCoins = $currentCoins + 120;
                    $cash = 60;
                    $coins = 120;
                }
                User::where('id', $user->id)->update(['last_currency' => $date]);
                Transaction::create([
                    'user_id' => $user->id,
                    'source_id' => '1',
                    'source_user' => '1',
                    'source_type' => '4',
                    'cash' => $cash,
                    'coins' => $coins,
                    'type' => '4',
                ]);
            }

            if($user->released_transactions->count() > 0) {
                foreach($user->released_transactions as $transaction) {
                    if($transaction->cash > 0) {
                        $newCash = $newCash + $transaction->cash;
                    }
                    if($transaction->coins > 0) {
                        $newCoins = $newCoins + $transaction->coins;
                    }
                    Transaction::where('id', $transaction->id)->update(['released' => 1]);
                }
            }

            if($user->cash != $newCash || $user->coins != $newCoins)
            {
                $user->cash = $newCash;
                $user->coins = $newCoins;
                $user->save();
            }

        }
        return $next($request);
    }
}
