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
    public $addresses_confirmed;

    public $email;
   
    public Address $addressShipping;
    public $full_name;
    public $company;
    public $address;
    public $address2;
    public $city;
    public $province;
    public $country_region;
    public $postal_code;

    public Address $addressBilling;
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
    public $content;

    protected $listeners = ['createOrder'];

    protected function rules()
    {
        return [
            'email' => 'required|email'. ( auth()->user() ? '' : '|unique:users,email'),
    
            'full_name' => '',        
            'company' => 'required_without:full_name',        
            'address' => 'required',        
            'address2' => '',        
            'city' => 'required',
            'province' => 'required',
            'country_region' => 'required',
            'postal_code' => 'required|min:5',

            'same_address' => '',
    
            'billing_full_name' => 'exclude_if:same_address,true',        
            'billing_company' => 'exclude_if:same_address,true|required_without:billing_full_name',        
            'billing_address' => 'exclude_if:same_address,true|required',        
            'billing_address2' => 'exclude_if:same_address,true|',        
            'billing_city' => 'exclude_if:same_address,true|required',
            'billing_province' => 'exclude_if:same_address,true|required',
            'billing_country_region' => 'exclude_if:same_address,true|required',
            'billing_postal_code' => 'exclude_if:same_address,true|required|min:5',
    
        ];
    }

    public function mount()
    {
        if(!Cart::instance('default') || !Cart::instance('default')->count())
        {
            $this->redirect(route('cart.index'));
        }

        if($this->updatePrices())
        {
            session()->flash('flash.banner', __('shopping_cart.prices_changed') );
            session()->flash('flash.bannerStyle', 'danger');

            $this->redirect(route('cart.index'));
        }

        $this->content = Cart::instance('default')->content();
        $this->addresses_confirmed = false;
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

        if((Auth::user() && Auth::user()->defaultAddress)){
            $this->confirmAddresses();
        }

        $this->coupon_code = session()->get('coupon');
        $this->coupon = Coupon::where('code',$this->coupon_code)->first();
        
        if ($this->coupon) {
            $this->subtotal = Cart::instance('default')->subtotal(); 
            $this->discountedSubtotal = Cart::instance('default')->subtotal() - $this->coupon->discount(Cart::instance('default')->subtotal());
            $this->tax = round(config('cart.tax')/100 * $this->discountedSubtotal, 2);
            $this->total = $this->discountedSubtotal + $this->tax;
        }
        else{
            $this->subtotal = Cart::instance('default')->subtotal();
            $this->tax = Cart::instance('default')->tax();
            $this->total = Cart::instance('default')->total();
        }
        
    }

    public function updatePrices()
    {
        $price_changed = false;
        foreach(Cart::instance('default')->content() as $key=>$item)
        {
            if($item->model->price != $item->price) $price_changed = true;
            Cart::instance('default')->update($key,$item->model);
        }
        return $price_changed;
    }

    public function confirmAddresses()
    {
        $this->validate();

        $this->addressShipping = new Address([
            'email' => Auth::user() ? Auth::user()->email : $this->email,
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

        $this->addressBilling = new Address([
            'full_name' => $this->same_address ? $this->full_name : $this->billing_full_name,
            'company' => $this->same_address ? $this->company : $this->billing_company,
            'address' => $this->same_address ? $this->address : $this->billing_address,
            'address2' => $this->same_address ? $this->address2 : $this->billing_address2,
            'city' => $this->same_address ? $this->city : $this->billing_city,
            'province' => $this->same_address ? $this->province : $this->billing_province,
            'country_region' => $this->same_address ? $this->country_region : $this->billing_country_region,
            'postal_code' => $this->same_address ? $this->postal_code : $this->billing_postal_code,
        ]);

        $this->addresses_confirmed = true;
    }

    public function updateDefaultAddress()
    {
        $defaultAddress = Auth::user()->defaultAddress()->updateOrCreate([
            'user_id' => Auth::user()->id,
        ],[
            'full_name' => $this->full_name,
            'company' => $this->company,
            'address' => $this->address,
            'address2' => $this->address2,
            'city' => $this->city,
            'province' => $this->province,
            'country_region' => $this->country_region,
            'postal_code' => $this->postal_code,
            'default' => true,
        ]);

        if($defaultAddress)
        {
            $banner_message = __('banner_notifications.address.saved') ;
            $banner_style = 'success';
        }
        else
        {
            $banner_message = __('banner_notifications.address.not_saved');
            $banner_style = 'danger';
        }
        
        $this->dispatchBrowserEvent('banner-message', [
            'message' => $banner_message,
            'style' => $banner_style,
        ]);
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
                if ($coupon->max_redemptions && $coupon->max_redemptions <= $coupon->redemptions)
                {    
                    $this->coupon=null;
                    $this->coupon_error=__('Coupon Already Redeemed');
                }
                elseif($coupon->expires_on && $coupon->expires_on<now())
                {
                    $this->coupon=null;
                    $this->coupon_error=__("Expired Coupon");
                }
                elseif($coupon->min_total && $this->total<$coupon->min_total)
                {
                    $this->coupon=null;
                    $this->coupon_error=__("Required minimum total of").' '.($coupon->min_total).'€';
                }
                else
                {
                    $this->coupon=$coupon;
                    $this->coupon_error=null;
                    session()->put('coupon', $this->coupon->code);   
                }
            } else {
                $this->coupon=null;
                $this->coupon_error=__('Invalid Coupon');
            }

            if ($this->coupon) {
                $this->subtotal = Cart::instance('default')->subtotal() - $this->coupon->discount(Cart::instance('default')->subtotal());
                $this->tax = round(config('cart.tax')/100 * $this->subtotal, 2 );
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
        session()->forget('coupon');

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
        $this->addressShipping = new Address([
            'email' => Auth::user() ? Auth::user()->email : $this->email,
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

        $this->addressBilling = new Address([
            'full_name' => $this->same_address ? $this->full_name : $this->billing_full_name,
            'company' => $this->same_address ? $this->company : $this->billing_company,
            'address' => $this->same_address ? $this->address : $this->billing_address,
            'address2' => $this->same_address ? $this->address2 : $this->billing_address2,
            'city' => $this->same_address ? $this->city : $this->billing_city,
            'province' => $this->same_address ? $this->province : $this->billing_province,
            'country_region' => $this->same_address ? $this->country_region : $this->billing_country_region,
            'postal_code' => $this->same_address ? $this->postal_code : $this->billing_postal_code,
        ]);

        //CHECK PRODUCT AVAIABILITY
        $avaiable = true;
        foreach(Cart::instance('default')->content() as $key=>$item)
        {
            if($item->model->quantity < $item->qty) $avaiable = false;
        }
        if(!$avaiable)
        {
            $this->redirect(route('cart.index'));
        }
        
        if ($avaiable) {
            $this->order = Order::firstOrCreate([
            'payment_gateway' => 'stripe',
            'payment_id' => $payment_id,
            ],[
            'shipping_address' => $this->addressShipping->toJson(),
            'billing_address' => $this->addressBilling->toJson(),
            'email' => $this->email,
            //'phone' => $this->phone,
            'message' => $this->message,
            'subtotal' => $this->subtotal,
            'tax'   => $this->tax,
            'total' => $this->total,
            'coupon_id' => $this->coupon ? $this->coupon->id : null,
            'coupon_discount' => $this->coupon ? $this->coupon->discount(Cart::instance('default')->subtotal()) : null,
            'order_status_id' => OrderStatus::where('name', 'pending')->first()->id,
            'user_id' => auth()->user() ? auth()->user()->id : null,
            ]);

            $pivots = [];
            foreach ($this->content as $item) {
                $pivots[$item['id']] = [
                'price' => $item['price'],
                'quantity' => $item['qty'],
            ];
                $product=\App\Models\Product::find($item['id']);
                $product->quantity = ($product->quantity >$item['qty']) ? $product->quantity-$item['qty'] : 0;
                $product->save();
            }
            $this->order->products()->attach($pivots);

            if($this->coupon)
            {
                $this->coupon->redemptions++;
                $this->coupon->save();
            }

            Cart::instance('default')->destroy();
            if(Auth::check())
                Cart::instance('default')->erase(auth()->user()->email);
            session()->forget('coupon');

            $this->emit('orderCreated');
        }
        else
        {
            redirect()->route('cart.index');
        }
    }
   
    public function render()
    {
        if($this->addresses_confirmed)
        {
            $this->confirmAddresses();
        }

        return view('order.create');
    }
}
