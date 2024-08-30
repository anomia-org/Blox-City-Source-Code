<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __invoke(Request $request, string $tier = 'prod_QBfqLTngEAYuTC', string $plan = 'price_1PLIUbLW7Lt5PHKzehsv04LL')
    {
        if (Setting::where('upgrades_enabled', '0')->get()->first()) {
            return view('maintenance.disabled');
        }

        if($request->user()->membership <= 0) {
            return $request->user()
                ->newSubscription($tier, $plan)
                ->allowPromotionCodes()
                ->checkout([
                    'success_url' => route('dashboard'),
                    'cancel_url' => route('dashboard'),
                ]);
        } else {
            return redirect()->route('dashboard');
        }
    }
}
