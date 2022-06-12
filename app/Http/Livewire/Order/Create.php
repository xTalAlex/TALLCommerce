<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use Livewire\Component;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

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
    public $discountedSubtotal;
    public $tax;
    public $total;
    public $coupon_code;
    public $coupon;
    public $coupon_error;

    public $order;

    protected $listeners = ['createOrder'];

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
            $this->discountedSubtotal = Cart::instance('default')->subtotal() - $this->coupon->discount(Cart::instance('default')->subtotal());
            $this->tax = round(config('cart.tax')/100);
            $this->total = $this->discountedSubtotal + $this->tax;
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

    public function createOrder($payment_id)
    {
        $shipping_address = new Address([
            'email' => $this->email,
            //'phone' => $this->phone,
            'full_name' => $this->full_name,
            'company' => $this->company,
            'address' => $this->address,
            'address2' => $this->address2,
            'city' => $this->city,
            'province' => $this->province,
            'country_region' => $this->country_region,
            'postal_code' => $this->postal_code,
        ]);

        $billing_address = new Address([
            'full_name' => $this->same_address ? $this->full_name : $this->billing_full_name,
            'company' => $this->same_address ? $this->billing_company : $this->billing_company,
            'address' => $this->same_address ? $this->billing_address : $this->billing_address,
            'address2' => $this->same_address ? $this->billing_address2 : $this->billing_address2,
            'city' => $this->same_address ? $this->billing_city : $this->billing_city,
            'province' => $this->same_address ? $this->billing_province : $this->billing_province,
            'country_region' => $this->same_address ? $this->billing_country_region : $this->billing_country_region,
            'postal_code' => $this->same_address ? $this->billing_postal_code : $this->billing_postal_code,
        ]);

        $this->order = Order::create([
            'shipping_address' => $shipping_address->toJson(),
            'billing_address' => $billing_address->toJson(),
            'email' => $this->email,
            //'phone' => $this->phone,
            'message' => $this->message,    
            'total' => $this->total,        
            'order_status_id' => OrderStatus::where('name','pending')->first()->id,
            'payment_type' => 'stripe',
            'payment_id' => $payment_id,
            'user_id' => auth()->user() ? auth()->user()->id : null,
        ]);
    }
   
    public function render()
    {
        return view('order.create');
    }
}
