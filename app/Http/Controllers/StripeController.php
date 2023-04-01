<?php

namespace App\Http\Controllers;

use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                    $banner_message=__('banner_notifications.payment.succeeded');
                    $banner_style="success";
                    break;
                
                    case 'processing':
                    $banner_message=__('banner_notifications.payment.processing');
                    $banner_style="success";
                    break;
                
                    case 'requires_payment_method':
                    $banner_message=__('banner_notifications.payment.failed');
                    $route_name=Auth::user() ? 'order.show' : 'cart.index';
                    $banner_style="danger";
                    break;
                
                    default:
                    $banner_message=__('banner_notifications.payment.error');
                    $banner_style="danger";
                    break;
                }
            }
            else
            {
                $banner_message=__('banner_notifications.payment.not_found');
                $banner_style="danger";
                $route_name='';
            }

                session()->flash('flash.banner', $banner_message);
                session()->flash('flash.bannerStyle', $banner_style);
        }
        
        return $route_name ? redirect()->route($route_name) : redirect()->back();
    }
}
