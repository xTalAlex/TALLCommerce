<?php

namespace App\Http\Livewire\Order;

use App\Models\Coupon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $step;

    public $email;
    public $full_name;
    public $company;
    public $address;
    public $address2;
    public $city;
    public $province;
    public $country_region;
    public $postal_code;

    public $billing_full_name;
    public $billing_company;
    public $billing_address;
    public $billing_address2;
    public $billing_city;
    public $billing_province;
    public $billing_country_region;
    public $billing_postal_code;

    public $same_address;
    public $message;

    public $subtotal;
    public $tax;
    public $total;
    public $coupon_code;
    public $coupon;
    public $coupon_error;

    public function mount()
    {
        $this->step = 'shipping';
        $this->email = Auth::user() ? Auth::user()->email : null;
        if(Auth::user() && Auth::user()->defaultAddress){
            $address = Auth::user()->defaultAddress;
            $this->full_name = $address->full_name;
            $this->company = $address->company;
            $this->address = $address->address;
            $this->address2 = $address->address2;
            $this->city = $address->city;
            $this->province = $address->province;
            $this->country_region = $address->country_region;
            $this->postal_code = $address->postal_code;
            $this->full_name = $address->full_name;
        }
        $this->same_address = true;

        $this->coupon_code = session()->get('coupon');
        $this->coupon = Coupon::where('code',$this->coupon_code)->first();
        
        if ($this->coupon) {
            $this->subtotal = Cart::instance('default')->subtotal(); 
            $this->newSubtotal = Cart::instance('default')->subtotal() - $this->coupon->discount(Cart::instance('default')->subtotal());
            $this->tax = round(config('cart.tax')/100);
            $this->total = $this->newSubtotal + $this->tax;
        }
        else{
            $this->subtotal = Cart::instance('default')->subtotal();
            $this->tax = Cart::instance('default')->tax();
            $this->total = Cart::instance('default')->total();
        }
        
    }

    public function updatedCouponCode($value)
    {
        $this->coupon_code = strtoupper(trim($value));
    }

    public function submitShipping()
    {
        $this->same_address ?
            $this->step = "payment" :
            $this->step = "billing";
    }

    public function submitBilling()
    {
        $this->step = "payment";
    }

    public function checkCoupon()
    {
        if ($this->coupon_code) {
            $coupon = Coupon::where('code', $this->coupon_code)->first();
            if ($coupon) {
                $this->coupon=$coupon;
                $this->coupon_error=null;
                session()->put('coupon', $this->coupon->code);
            } else {
                $this->coupon=null;
                $this->coupon_error="Invalid coupon";
            }

            if ($this->coupon) {
                $this->subtotal = Cart::instance('default')->subtotal() - $this->coupon->discount(Cart::instance('default')->subtotal());
                $this->tax = round(config('cart.tax')/100);
                $this->total = $this->subtotal + $this->tax;
            } else {
                $this->subtotal = Cart::instance('default')->subtotal();
                $this->tax = Cart::instance('default')->tax();
                $this->total = Cart::instance('default')->total();
            }
        }

    }

    public function removeCoupon()
    {
        $this->coupon=null;
        $this->coupon_code=null;
        session()->remove('coupon');

        if ($this->coupon) {
            $this->subtotal = Cart::instance('default')->subtotal() - $this->coupon->discount(Cart::instance('default')->subtotal());
            $this->tax = round(config('cart.tax')/100);
            $this->total = $this->subtotal + $this->tax;
        }
        else{
            $this->subtotal = Cart::instance('default')->subtotal();
            $this->tax = Cart::instance('default')->tax();
            $this->total = Cart::instance('default')->total();
        }
    }
   
    public function render()
    {
        return view('order.create');
    }
}
