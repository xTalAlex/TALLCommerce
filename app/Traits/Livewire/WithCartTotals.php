<?php

namespace App\Traits\Livewire;

use App\Models\Coupon;
use Illuminate\Support\Str;
use Gloudemans\Shoppingcart\Facades\Cart;

trait WithCartTotals
{
    public $subtotal;
    public $discounted_subtotal;
    public $original_total;
    public $tax;
    public $shipping_price;
    public $total;

    public $coupon;
    public $coupon_code;
    public $coupon_error;

    public function checkCoupon()
    {
        $this->coupon_code = $this->coupon_code ?? session()->get('coupon');
        if(Str::of($this->coupon_code)->trim()->isNotEmpty())
        {
            $coupon = Coupon::where('code', $this->coupon_code)->first();
            $error = null;

            if ($coupon) {
                if ( 
                    ($coupon->max_redemptions && $coupon->max_redemptions <= $coupon->redemptions) ||
                    ($coupon->once_per_user && auth()->check() && $coupon->wasUsedBy(auth()->user()) )
                )
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
                elseif($coupon->once_per_user && !auth()->check())
                {
                    $this->coupon=null;
                    $error=__('Invalid Coupon');
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

            $this->coupon_error = $error;
            if($error)
                $this->dispatchBrowserEvent('banner-message', [
                    'message' => $error,
                    'style' => 'danger',
                ]);
            return $error;
        }
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
        $this->checkCoupon();

        $this->subtotal = Cart::instance('default')->subtotal(null,null,'');
        if($this->coupon && !$this->coupon->applyBeforeTax() ){
            $this->tax = Cart::instance('default')->tax();
            $this->original_total = Cart::instance('default')->total(null,null,'');
            $this->total = $this->original_total - round($this->coupon->discount($this->original_total),2);
        }
        elseif ($this->coupon && $this->coupon->applyBeforeTax() ) {
            $this->discounted_subtotal = round( (float) $this->subtotal - $this->coupon->discount(Cart::instance('default')->subtotal(null,null,'')) , 2);
            $tax_total = 0;
            foreach(Cart::instance('default')->content() as $item)
            {
                $tax_total += round( ( $item->price - $this->coupon->discount($item->price) ) * $item->qty * $item->taxRate/100 , 2 );
            }
           
            $this->tax = round( $tax_total , 2);
            $this->total = round( $this->discounted_subtotal + $this->tax, 2);
        }
        else {
            $this->tax = Cart::instance('default')->tax();
            $this->total = Cart::instance('default')->total(null,null,'');
        }
    }
}