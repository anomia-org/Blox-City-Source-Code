<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    Log
};
use Carbon\Carbon;
use Stripe\{
    StripeClient,
    Webhook
};

use App\Models\User;
use App\Models\Membership\{
    BillingProduct,
    Membership,
    Subscription,
    StripeCustomer
};

use App\Exceptions\Custom\APIException;
use App\Models\Item;
use App\Models\Transaction;

class StripeController extends Controller
{
    /**
     * Used to select the proper API keys from config
     * 
     * @var string
     */
    private $mode;

    /**
     * Client the controller uses to call the Stripe API
     * 
     * @var StripeClient
     */
    private $client;

    /**
     * Construct the controller
     * @return void 
     */
    public function __construct()
    {
        $this->mode = 'testing';
        $apiKey = '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';
        $this->client = new StripeClient([
            'api_key' => $apiKey,
            'stripe_version' => '2019-05-16'
        ]);
    }

    /**
     * Create a customer value for a User to use in Stripe Session
     * 
     * @return void 
     */
    public function createAsCustomer()
    {
        // use a lock to prevent a user from creating multiple customers locally
        cache()->lock('stripe_' . Auth::id() . '_customer_lock', 30)->get(function () {
            if (!Auth::user()->stripeCustomer()->exists()) {
                $newCustomer = $this->client->customers->create([
                    'metadata' => ['user_id' => Auth::id()]
                ]);
                Auth::user()->stripeCustomer()->create([
                    'stripe_id' => $newCustomer->id
                ]);
            }
        });
    }

    /**
     * Create a Stripe Session for a given BillingProduct for a user to pay
     * 
     * @param BillingProduct $product  
     * @return array
     * @throws APIException 
     */
    public function createSession(BillingProduct $product)
    {
        if (is_null(Auth::user()->stripeCustomer)) {
            $newCustomer = $this->client->customers->create([
                'metadata' => ['user_id' => Auth::id()]
            ]);
            Auth::user()->stripeCustomer()->create([
                'stripe_id' => $newCustomer->id
            ]);
        }

        if (auth()->user()->membership > 0 && !is_null($product->stripe_plan_id))
        {
            return redirect(route('dashboard'))->with('error', 'You already have a membership plan.');
        }

        $successUrl = 'https://www.bloxcity.com/dashboard?paymentCompleted';
        $cancelUrl = 'https://www.bloxcity.com/upgrade';
        $discounts = [[]];

        if (!Carbon::parse('2024-06-14 23:59:59')->isPast()
        ) {
            $discounts = [[
                'coupon' => 'EIK1zf7H'
            ]];
        }

        $data = [
            'payment_method_types' => ['card'],
            'client_reference_id' => Auth::id(),
            'customer' => Auth::user()->stripeCustomer->stripe_id,
            'customer_update' => [
                'name' => 'auto'
            ],
            'discounts' => $discounts,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ];

        if (!is_null($product->stripe_plan_id)) {
            $data['line_items'] = [[
                'price' => $product->stripe_plan_id,
                'quantity' => 1
            ]];
            $data['subscription_data'] = [
                'metadata' => ['user_id' => Auth::id()]
            ];
            $data['mode'] = 'subscription';
        } else {
            $data['line_items'] = [[
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $product->price_in_cents,
                    'product_data' => [
                        'name' => $product->name,
                    ],
                ],
                'quantity' => 1,
            ]];
            $data['payment_intent_data'] = [
                'metadata' => ['user_id' => Auth::id(), 'billing_product_id' => $product->id],
                'setup_future_usage' => 'on_session'
            ];
            $data['mode'] = 'payment';
        }

        $checkout = $this->client->checkout->sessions->create($data);
        return redirect($checkout->url);
    }

    /**
     * Returns a Stripe Billing Portal object for the user to access
     * 
     * @return \Stripe\BillingPortal\Session 
     */
    public function billingPortal()
    {
        if (!Auth::user()->stripeCustomer()->exists())
            throw new APIException('No customer exists for user');

        return $this->client->billingPortal->sessions->create([
            'customer' => Auth::user()->stripeCustomer->stripe_id
        ]);
    }

    /**
     * Cancels a users Stripe Subscription, or passes the request to the PaypalController to cancel their PayPal Subscription
     * 
     * @return true[] 
     */
    public function cancelSubscription()
    {
        $sub = Subscription::where([['user_id', Auth::id()], ['active', 1]])->firstOrFail();
        if (substr($sub->sub_profile_id, 0, 3) === 'sub')
            $this->client->subscriptions->cancel($sub->sub_profile_id);
        else throw new \App\Exceptions\Custom\InvalidDataException('PayPal is no longer supported');

        return ['success' => true];
    }

    /**
     * Handle webhooks received from Stripe
     * 
     * @param Request $request 
     * @return void 
     */
    public function webhook(Request $request)
    {
        $event = Webhook::constructEvent($request->getContent(), $request->header('Stripe-Signature'), 'whsec_qB4M50i1WKw5P0o2ipKOqF70nX7vmAUT');

        /** @var object $event->data */

        switch ($event->type) {
            case 'checkout.session.completed':
                $data = $event->data->object;
                $user = User::find($data->client_reference_id);
                if (!$user->stripeCustomer()->exists()) {
                    $this->client->customers->update($data->customer, [
                        'metadata' => ['user_id' => $user->id]
                    ]);
                    $user->stripeCustomer()->create([
                        'stripe_id' => $data->customer
                    ]);
                }
                break;
            case 'charge.succeeded':
                $data = $event->data->object;
                if (!is_null($data->metadata?->user_id)) {
                    $user = User::findOrFail($data->metadata->user_id);
                } else {
                    $customer = StripeCustomer::where('stripe_id', $data->customer)->firstOrFail();
                    $user = $customer->user;
                }

                if ($user->payments()->receipt($data->balance_transaction)->exists()) {
                    Log::stack(['webhook_failure'])->error('Receipt already exists', ['data' => $request->all()]);
                    throw new APIException;
                }

                \DB::transaction(function () use ($data, $user) {
                    if (!is_null($data->metadata?->billing_product_id)) {
                        $product = BillingProduct::findOrFail($data->metadata->billing_product_id);

                        //if ($data->amount != $product->price_in_cents)
                        //   throw new APIException;

                        $user->increment('cash', $product->cash_amount);
                        if(!$user->owns(Item::find(315)))
                        {
                            $user->grant_item(Item::find(315));
                        }

                        Transaction::create([
                            'user_id' => $user->id,
                            'source_id' => '1',
                            'source_user' => '1',
                            'source_type' => '4',
                            'cash' => $product->cash_amount,
                            'type' => '5',
                        ]);

                        if ($product->name == "Client Access") {
                            //
                        }
                    }

                    $user->payments()->create([
                        'gross_in_cents' => $data->amount,
                        'email' => $data->billing_details->email,
                        'receipt' => $data->balance_transaction,
                        'billing_product_id' => $data->metadata?->billing_product_id
                    ]);
                });
                break;
                // in testing sometimes this event never gets fired,
                // no idea what the difference between paid and payment_succeeded are or if this could happen in prod
            case 'invoice.payment_succeeded':
                //case 'invoice.paid':
                $data = $event->data->object;
                $sub = $data->lines->data[0];
                // older subscriptions wont have metadata, so still need to support those
                if (!is_null($sub->metadata?->user_id)) {
                    $user = User::findOrFail($sub->metadata->user_id);
                } else {
                    $customer = StripeCustomer::where('stripe_id', $data->customer)->firstOrFail();
                    $user = $customer->user;
                }
                $product = BillingProduct::where('stripe_plan_id', $sub->plan->id)->firstOrFail();

                // TODO: add sale system here too so it can accurately get price
                // TODO: we always set the prices anyway unless our stripe secret leaks which is way worse in other reasons so this isnt really necessary
                //if ($data->amount_paid != $product->price_in_cents)
                //    throw new APIException;

                \DB::transaction(function () use ($data, $product, $sub, $user) {
                    Subscription::updateOrCreate(
                        ['sub_profile_id' => $data->subscription],
                        ['active' => 1, 'user_id' => $user->id, 'expected_bill' => Carbon::createFromTimestamp($sub->period->end)]
                    );

                    $membership = Membership::updateOrCreate(
                        ['user_id' => $user->id, 'membership' => $product->membership],
                        ['active' => 1, 'length' => $product->membership_length, 'date' => Carbon::now()]
                    );

                    $membership->grantMembershipItems();

                    if(!$user->owns(Item::find(315)))
                    {
                        $user->grant_item(Item::find(315));
                    }
                    
                    $user->membership = $product->membership;
                    $user->membership_expires = Carbon::createFromTimestamp($sub->period->end);
                    $user->save();
                });
                
                break;
            case 'customer.subscription.deleted':
                $data = $event->data->object;
                $sub = Subscription::subProfile($data->id)->firstOrFail();
                $sub->active = 0;
                $sub->save();
        }
    }
}