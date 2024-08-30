<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class UpgradesController extends Controller
{
    public function index()
    {
        if(Setting::where('upgrades_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }
        return view('user.upgrade.index');
    }

    public function show($plan)
    {
        if (!settings('upgrades_enabled')) {
            abort(404);
        }

        if (auth()->user()->membership > 0 && $plan != 'cash')
        {
            return redirect(route('dashboard'))->with('error', 'You already have a membership plan.');
        }

        $allowedPlans = ['bronze-vip', 'silver-vip', 'gold-vip', 'cash'];

        if (!in_array($plan, $allowedPlans)) {
            abort(404);
        }

        switch ($plan) {
            case 'bronze-vip':
                $title = 'Bronze VIP';
                $plans = [
                    [
                        'name' => '1 Month',
                        'price' => 3.99,
                        'billing_product_id' => 1,
                    ],
                    [
                        'name' => '6 Months',
                        'price' => 19.99,
                        'billing_product_id' => 2,
                    ],
                    [
                        'name' => '12 Months',
                        'price' => 39.99,
                        'billing_product_id' => 3,
                    ]
                ];
                break;
            case 'silver-vip':
                $title = 'Silver VIP';
                $plans = [
                    [
                        'name' => '1 Month',
                        'price' => 7.99,
                        'billing_product_id' => 4,
                    ],
                    [
                        'name' => '6 Months',
                        'price' => 39.99,
                        'billing_product_id' => 5,
                    ],
                    [
                        'name' => '12 Months',
                        'price' => 69.99,
                        'billing_product_id' => 6,
                    ]
                ];
                break;
            case 'gold-vip':
                $title = 'Gold VIP';
                $plans = [
                    [
                        'name' => '1 Month',
                        'price' => 14.99,
                        'billing_product_id' => 7,
                    ],
                    [
                        'name' => '6 Months',
                        'price' => 74.99,
                        'billing_product_id' => 8,
                    ],
                    [
                        'name' => '12 Months',
                        'price' => 139.99,
                        'billing_product_id' => 9,
                    ]
                ];
                break;
            case 'cash':
                $title = 'Cash';
                $plans = [
                    [
                        'name' => '100 Cash',
                        'price' => 0.95,
                        'billing_product_id' => 11,
                    ],
                    [
                        'name' => '250 Cash',
                        'price' => 2.95,
                        'billing_product_id' => 12,
                    ],
                    [
                        'name' => '500 Cash',
                        'price' => 4.95,
                        'billing_product_id' => 13,
                    ],
                    [
                        'name' => '750 Cash',
                        'price' => 6.95,
                        'billing_product_id' => 14,
                    ],
                    [
                        'name' => '1,000 Cash',
                        'price' => 9.95,
                        'billing_product_id' => 15,
                    ],
                    [
                        'name' => '2,500 Cash',
                        'price' => 23.95,
                        'billing_product_id' => 16,
                    ],
                    [
                        'name' => '5,000 Cash',
                        'price' => 46.95,
                        'billing_product_id' => 17,
                    ],
                    [
                        'name' => '10,000 Cash',
                        'price' => 89.95,
                        'billing_product_id' => 18,
                    ],
                    [
                        'name' => '25,000 Cash',
                        'price' => 194.95,
                        'billing_product_id' => 19,
                    ],
                ];
                break;
        }

        return view('user.upgrade.show')->with([
            'plan' => $plan,
            'title' => $title,
            'plans' => $plans
        ]);
    }
}
