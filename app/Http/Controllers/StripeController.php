<?php

namespace App\Http\Controllers;

use App\Models\{ Order, OrderStatus, Address, Coupon};
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class StripeController extends Controller
{
    public function handleCheckoutResponse(Request $request)
    {
        $route_name=null;

        if ($request->payment_intent) {
            $paymentIntent = Stripe::paymentIntents()->find($request->payment_intent);

            $banner_message="";
            $banner_style="danger";
            $route_name=Auth::user() ? 'order.index' : 'cart.index';

            if ($paymentIntent) {
                switch ($paymentIntent['status']) {
                    case 'succeeded':
                    $banner_message='Success! Payment received.';
                    $banner_style="success";
                    break;
                
                    case 'processing':
                    $banner_message="Payment processing. We'll update you when payment is received.";
                    $banner_style="success";
                    break;
                
                    case 'requires_payment_method':
                    $banner_message='Payment failed. Please try another payment method.';
                    $route_name=Auth::user() ? 'order.edit' : 'cart.index';
                    $banner_style="danger";
                    break;
                
                    default:
                    $banner_message='Something went wrong.';
                    $banner_style="danger";
                    break;
                }
            }
            else
            {
                $banner_message="Eroor while fetching payment info";
                $banner_style="danger";
                $route_name='';
            }

                session()->flash('flash.banner', $banner_message);
                session()->flash('flash.bannerStyle', $banner_style);
        }
        
        return $route_name ? redirect()->route($route_name) : redirect()->back();
    }
}
