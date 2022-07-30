<?php

namespace App\Traits\Livewire;

use App\Models\Coupon;
use Gloudemans\Shoppingcart\Facades\Cart;

trait WithCartTotals
{
    public $subtotal;
    public $discounted_subtotal;
    public $tax;
    public $shipping_price;
    public $total;

    public $coupon;
    public $coupon_code;
    public $coupon_error;

    public function checkCoupon()
    {
        $coupon = Coupon::where('code', $this->coupon_code)->first();
        $error = null;

        if ($coupon) {
            if ($coupon->max_redemptions && $coupon->max_redemptions <= $coupon->redemptions)
            {    
                $this->coupon=null;
                $error=__('Coupon Already Redeemed');
            }
            elseif($coupon->expires_on && $coupon->expires_on<now())
            {
                $this->coupon=null;
                $error=__("Expired Coupon");
            }
            elseif($coupon->min_total && $this->total<$coupon->min_total)
            {
                $this->coupon=null;
                $error=__("Required minimum total of").' '.($coupon->min_total).'€';
            }
            else
            {
                $this->coupon=$coupon;
                $error=null;
                session()->put('coupon', $this->coupon->code);   
            }
        } else {
            $this->coupon=null;
            $error=__('Invalid Coupon');
        }

        $this->refreshTotals();

        $this->coupon_error = $error;
        if($error)
            $this->dispatchBrowserEvent('banner-message', [
                'message' => $error,
                'style' => 'danger',
            ]);
        return $error;
    }

    public function removeCoupon()
    {
        $this->coupon = null;
        $this->coupon_code = null;
        session()->forget('coupon');

        $this->refreshTotals();
    }

    public function refreshTotals()
    {
        $this->coupon_code = $this->coupon_code ?? session()->get('coupon');
        $this->coupon = Coupon::where('code',$this->coupon_code)->first();
        
        if ($this->coupon) {
            $this->subtotal = Cart::instance('default')->subtotal(); 
            $this->discounted_subtotal = Cart::instance('default')->subtotal() - $this->coupon->discount(Cart::instance('default')->subtotal());
            $this->tax = round(config('cart.tax')/100 * $this->discounted_subtotal, 2);
            $this->total = $this->discounted_subtotal + $this->tax;
        }
        else{
            $this->subtotal = Cart::instance('default')->subtotal();
            $this->tax = Cart::instance('default')->tax();
            $this->total = Cart::instance('default')->total();
        }
    }
}