<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\{ Order, OrderStatus, Address };
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class StripeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function checkout(Request $request)
    {
        $shipping_address = new Address([
            'email' => $request->email,
            'phone' => $request->phone,
            'full_name' => $request->full_name,
            'company' => $request->company,
            'address' => $request->address,
            'address2' => $request->address2,
            'city' => $request->city,
            'province' => $request->province,
            'country_region' => $request->country_region,
            'postal_code' => $request->postal_code,
        ]);

        $billing_address = new Address([
            'full_name' => $request->same_address ? $request->full_name : $request->billing_full_name,
            'company' => $request->same_address ? $request->billing_company : $request->billing_company,
            'address' => $request->same_address ? $request->billing_address : $request->billing_address,
            'address2' => $request->same_address ? $request->billing_address2 : $request->billing_address2,
            'city' => $request->same_address ? $request->billing_city : $request->billing_city,
            'province' => $request->same_address ? $request->billing_province : $request->billing_province,
            'country_region' => $request->same_address ? $request->billing_country_region : $request->billing_country_region,
            'postal_code' => $request->same_address ? $request->billing_postal_code : $request->billing_postal_code,
        ]);

        $order = Order::create([
            'shipping_address' => $shipping_address->toJson(),
            'billing_address' => $billing_address->toJson(),
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,    
            'total' => 0,        
            'order_status_id' => OrderStatus::where('name','pending')->first()->id,
            'payment_type' => 'stripe',
            'user_id' => Auth::user() ? Auth::user()->id : null,

        ]);

        $line_items = [];
        foreach( Cart::content() as $product){
            array_push($line_items, [ 
                'price_data' => [
                    'currency' => 'EUR',
                    'product_data' => [
                        'name' => $product->model->name,
                        'images' => [
                            $product->model->image,
                        ],
                    ],
                    'unit_amount' => $product->model->price * 100,
                ],
                'quantity' => $product->qty,
            ]);

            $order->products()->attach($product->model->id, [
                'price' => $product->model->price,
                'quantity' => $product->qty,
            ]);

            $order->total = $order->total + ($product->model->price * $product->qty );
        }

        $order->save();

        $checkout_session = \Stripe\Checkout\Session::create([
            'customer_email' => $request->email,
            'line_items' => $line_items,
            'metadata' => [
                'order' => $order->id,
            ],
            'mode' => 'payment',
            'success_url' => route('stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel').'?session_id={CHECKOUT_SESSION_ID}',
          ]);

        Cart::instance('default')->destroy();
        
        return redirect($checkout_session->url);
          
    }

    public function resume(Request $request, Order $order)
    {
        if($order->status->name !== 'pending')
            return abort(403);

        $line_items = [];
        foreach( $order->products as $product){
            array_push($line_items, [ 
                'price_data' => [
                    'currency' => 'EUR',
                    'product_data' => [
                        'name' => $product->name,
                        'images' => [
                            $product->image,
                        ],
                    ],
                    'unit_amount' => $product->pivot->price * 100,
                ],
                'quantity' => $product->pivot->quantity,
            ]);
        }

        $checkout_session = \Stripe\Checkout\Session::create([
            'customer_email' => Auth::user()->email ?? null,
            'line_items' => $line_items,
            'metadata' => [
                'order' => $order->id,
            ],
            'mode' => 'payment',
            'success_url' => route('stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel').'?session_id={CHECKOUT_SESSION_ID}',
          ]);
        
        return redirect($checkout_session->url);
          
    }

    public function success(Request $request)
    {
        $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));

        Order::find($session->metadata['order'])->update([
            'payment_id' => $session->payment_intent,
            'order_status_id' => OrderStatus::where('name','paied')->first()->id,
        ]);
        
        $request->session()->flash('flash.banner', 'Payment succeeded.');
        $request->session()->flash('flash.bannerStyle', 'success');

        return Auth::user() ? redirect()->route('order.index') : redirect()->route('product.index');
    }

    public function cancel(Request $request)
    {
        $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));

        // Order::find($session->metadata['order'])->delete();
        
        // $request->session()->flash('flash.banner', 'Payment cancelled.');
        // $request->session()->flash('flash.bannerStyle', 'danger');

        return Auth::user() ? redirect()->route('order.index') : redirect()->route('cart.index');
    }
}
