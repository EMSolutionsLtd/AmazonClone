<?php

namespace App\Http\Controllers;

use App\Mail\OrderShipped;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /// composer require stripe/stripe-php in console window
        $stripe = new \Stripe\StripeClient('sk_test_51NFfAdSE2SUluQF52vjW9viBdxytbR7M7CxJ7oQ2u8P1EdIIJEyg3u6a2jAqxxQ53T2d88NxvtN5TWxgoHx3004b00IIPv2jdI');

        $order = Order::where('user_id', '=', auth()->user()->id)
            ->where('payment_intent', null)
            ->first();

        // if(is_null($order)) {
        //     return redirect()->route('checkout_success.index');
        // }

        $intent = $stripe->paymentIntents->create([
            'description' => 'Software development services',
            'shipping' => [
                'name' => 'Jenny Rosen',
                'address' => [
                    'line1' => '510 Townsend St',
                    'postal_code' => '98140',
                    'city' => 'San Francisco',
                    'state' => 'CA',
                    'country' => 'US',
                ],
            ],
            'amount' => (int) $order->total,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'description' => 'Software development services',
        ]);

        return Inertia::render('Checkout', [
            'intent' => $intent,
            'order' => $order
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // print_r($request);
        $res = Order::where('user_id', '=', auth()->user()->id)
            ->where('payment_intent', null)
            ->first();

        if(!is_null($res)) {
            $res->total = $request->total;
            $res->total_decimal = $request->total_decimal;
            $res->items = json_encode($request->items);
            $res->save();
        } else {
            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->total = $request->total;
            $order->total_decimal = $request->total_decimal;
            $order->items = json_encode($request->items);
            $order->save();
        }

        return redirect()->route('checkout.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->all());

        $order = Order::where('user_id', '=', auth()->user()->id)
            ->where('payment_intent', null)
            ->first();
        $order->payment_intent = $request['payment_intent'];
        $order->save();

        Mail::to($request->user())->send(new OrderShipped($order));
        // Mail::to('emsoftwareltd@gmail.com')->send(new OrderShipped($order));

        return redirect()->route('checkout_success.index');
    }
}
