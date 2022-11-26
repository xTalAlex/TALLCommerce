<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use Livewire\Component;
use App\Models\OrderStatus;
use App\Models\ShippingPrice;
use Illuminate\Support\Facades\Auth;
use App\Traits\Livewire\WithCartTotals;
use Gloudemans\Shoppingcart\Facades\Cart;

class Create extends Component
{
    use WithCartTotals;

    public Address $shipping_address;
    public Address $billing_address;
    public $same_address;
    public $addresses_confirmed;

    public Order $order;

    public $email;
    public $note;

    public $shipping_prices;

    protected $listeners = [
        'createOrder',
    ];

    protected function rules()
    {
        return [
            'email' => 'required|email'. ( auth()->user() ? '' : '|unique:users,email'),
    
            'shipping_address.full_name' => '',        
            'shipping_address.company' => 'required_without:shipping_address.full_name',        
            'shipping_address.address' => 'required',        
            'shipping_address.address2' => '',        
            'shipping_address.city' => 'required',
            'shipping_address.province' => 'required',
            'shipping_address.country_region' => 'required',
            'shipping_address.postal_code' => 'required|min:5',

            'same_address' => '',
    
            'billing_address.full_name' => 'exclude_if:same_address,true',        
            'billing_address.company' => 'exclude_if:same_address,true|required_without:billing_address.full_name',        
            'billing_address.address' => 'exclude_if:same_address,true|required',        
            'billing_address.address2' => 'exclude_if:same_address,true|',        
            'billing_address.city' => 'exclude_if:same_address,true|required',
            'billing_address.province' => 'exclude_if:same_address,true|required',
            'billing_address.country_region' => 'exclude_if:same_address,true|required',
            'billing_address.postal_code' => 'exclude_if:same_address,true|required|min:5',

            'shipping_price.id' => 'required|exists:shipping_prices,id', 
    
        ];
    }

    public function mount()
    {
        if(!Cart::instance('default') || !Cart::instance('default')->count())
            $this->redirect(route('cart.index'));

        if($this->updatePrices())
        {
            session()->flash('flash.banner', __('shopping_cart.prices_changed') );
            session()->flash('flash.bannerStyle', 'danger');

            $this->redirect(route('cart.index'));
        }

        $this->addresses_confirmed = false;
        $this->email = Auth::user() ? Auth::user()->email : null;

        $this->shipping_prices = ShippingPrice::active()->get();
        if(!count($this->shipping_prices))
        {
            session()->flash('flash.banner', __('general.unexpected_error') );
            session()->flash('flash.bannerStyle', 'danger');

            $this->redirect(route('cart.index'));
        } 

        $this->shipping_price = session()->get('shipping_price') ? 
            $this->shipping_prices->where('id', session()->get('shipping_price') )->first() 
            : $this->shipping_prices->first();

        if(session()->get('shipping_address')){
            $this->shipping_address = session()->get('shipping_address');
            if(session()->get('billing_address')){
                $this->billing_address = session()->get('billing_address');
                $this->same_address = $this->shipping_address == $this->billing_address; 
            }
            else{
                $this->billing_address = $this->shipping_address;
                $this->same_address = true; 
            }
            $this->confirmAddresses();
        }
        else{
            if(Auth::user() && Auth::user()->defaultAddress){
                $this->shipping_address = Auth::user()->defaultAddress;
            }
            else $this->shipping_address = new Address();
            $this->same_address = true; 
            $this->billing_address = $this->shipping_address;
            
            if((Auth::user() && Auth::user()->defaultAddress)){
                $this->confirmAddresses();
            }
        }

        $this->refreshTotals();
        
    }

    public function updatedShippingPriceId($value)
    {
        $this->shipping_price = $this->shipping_prices->where('id',$value)->first();
        session()->put('shipping_price', $this->shipping_price->id);
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

        if($this->same_address) $this->billing_address = $this->shipping_address;
        
        session()->put('shipping_address', $this->shipping_address);
        session()->put('billing_address', $this->shipping_address);

        $this->addresses_confirmed = true;
        $this->emit('addressesConfirmed');
    }

    public function updateDefaultAddress()
    {
        $defaultAddress = Auth::user()->defaultAddress()->updateOrCreate([
            'user_id' => Auth::user()->id,
        ],[
            'full_name' => $this->shipping_address->full_name,
            'company' => $this->shipping_address->company,
            'address' => $this->shipping_address->address,
            'address2' => $this->shipping_address->address2,
            'city' => $this->shipping_address->city,
            'province' => $this->shipping_address->province,
            'country_region' => $this->shipping_address->country_region,
            'postal_code' => $this->shipping_address->postal_code,
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

    public function createOrder($payment_id, $gateway)
    {
        $validated = $this->validate([
            'shipping_price.id' => 'required|exists:shipping_prices,id',
            'shipping_price.price' => 'required|min:0',          
            'shipping_price.name' => 'required'
        ]);

        //CHECK PRODUCT AVAIABILITY
        $avaiable = true;
        $max_avaiable_from = null;
        foreach(Cart::instance('default')->content() as $key=>$item)
        {
            if($item->model->quantity < $item->qty) $avaiable = false;
            if($item->model->avaiable_from > $max_avaiable_from && $item->model->avaiable_from > today()) $max_avaiable_from = $item->model->avaiable_from;
        }
        if(!$avaiable)
        {
            $this->redirect(route('cart.index'));
        }
        
        if ($avaiable) {
            $status_id = OrderStatus::where('name', 'pending')->first()->id;
            $this->order = Order::firstOrCreate([
                'payment_gateway' => $gateway,
                'payment_id' => $payment_id,
            ],[
                'shipping_address' => $this->shipping_address->toJson(),
                'billing_address' => $this->billing_address->toJson(),
                'email' => $this->email,
                //'phone' => $this->phone,
                'note' => $this->note,
                'subtotal' => $this->subtotal,
                'tax'   => $this->tax,
                'total' => $this->total + $this->shipping_price->price,
                'coupon_id' => $this->coupon ? $this->coupon->id : null,
                'coupon_discount' => $this->coupon ? $this->coupon->discount(Cart::instance('default')->subtotal()) : null,
                'order_status_id' => $status_id,
                'user_id' => auth()->user() ? auth()->user()->id : null,
                'shipping_price_id' => $this->shipping_price->id,
                'shipping_price' => $this->shipping_price->price,
                'avaiable_from' => $max_avaiable_from,
            ]);

            $pivots = [];
            foreach (Cart::instance('default')->content() as $item) {
                $pivots[$item->id] = [
                'price' => $item->price,
                'quantity' => $item->qty,
            ];
                $product=\App\Models\Product::find($item->id);
                $product->quantity = ($product->quantity >$item->qty) ? $product->quantity-$item->qty : 0;
                $product->save();
            }
            $this->order->products()->attach($pivots);

            if($this->coupon)
            {
                $this->coupon->redemptions++;
                $this->coupon->save();
            }

            $this->order->history()->create([
                'order_status_id' => $status_id,
            ]);

            Cart::instance('default')->destroy();
            if(Auth::check())
                Cart::instance('default')->erase(auth()->user()->email);
            session()->forget('coupon');
            session()->forget('shipping_price');
            session()->forget('shipping_address');
            session()->forget('billing_address');

            if($gateway == 'paypal')
                $this->order->setAsPaied();

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
