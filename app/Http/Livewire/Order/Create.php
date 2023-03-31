<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Traits\Livewire\WithCartTotals;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Traits\Livewire\WithShoppingLists;
use App\Models\{Order, OrderStatus, Address, ShippingPrice};

class Create extends Component
{
    use WithCartTotals, WithShoppingLists;

    public Order $order;
    public $email;
    public $phone;
    public Address $shipping_address;
    public $note;
    public Address $billing_address; 
    public $fiscal_code;
    public $vat;
    public $shipping_prices;
    public $shipping_price;
    public $addresses_confirmed;
    public $cartContent;
    public $shownStep;

    protected function rules(){
        return [
            'email' => 'required|email'. (auth()->user() ? '' : '|unique:users,email'),
            'phone' => 'nullable|numeric|digits_between:10,13',
            'shipping_address.full_name' => 'required',
            'shipping_address.address' => 'required',
            'shipping_address.city' => 'required',
            'shipping_address.province' => 'required|size:2',
            'shipping_address.country_region' => 'required',
            'shipping_address.postal_code' => 'required|min:5|max:5',
            'note' => 'nullable|max:255',

            'vat' => 'nullable|numeric|required_without:fiscal_code|digits:11',
            'fiscal_code' => 'nullable|required_without:vat|alpha_num|min:11|max:16',
            'billing_address.full_name' => 'required',
            'billing_address.address' => 'required',
            'billing_address.city' => 'required',
            'billing_address.province' => 'required|size:2',
            'billing_address.country_region' => 'required',
            'billing_address.postal_code' => 'required|min:5|max:5',

            'shipping_price.id' => 'required|exists:shipping_prices,id',
            'shipping_price.price' => 'required|min:0',          
            'shipping_price.name' => 'required'
        ];
    }

    protected $listeners = [
        'placeOrder',
    ];

    public function mount()
    {
        if(!Cart::instance('default') || !Cart::instance('default')->count())
            $this->redirect(route('cart.index'));

        if($this->updatePrices()){
            session()->flash('flash.banner', __('shopping_cart.prices_changed') );
            session()->flash('flash.bannerStyle', 'danger');
            $this->redirect(route('cart.index'));
        }

        if(!count($this->shipping_prices = ShippingPrice::active()->get())){
            session()->flash('flash.banner', __('general.unexpected_error') );
            session()->flash('flash.bannerStyle', 'danger');
            $this->redirect(route('cart.index'));
        }

        $this->cartContent = Cart::instance('default')->content()->map(function($item){
            $pricePerQuantity = $item->model->applyTax($item->model->pricePerQuantity($item->qty!='' ? $item->qty : 1, $item->price));
            return collect([
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'tax_rate' => $item->model->tax_rate ?? config('cart.tax'),
                'qty' => $item->qty,
                'pricePerQuantity' => $pricePerQuantity,
            ]);
        });
        $this->order = new Order();
        $this->addresses_confirmed = false;
        $this->retrieveData();
        $this->refreshTotals();
        $this->setShownStep();
    }  

    public function updatedShippingPriceId($value)
    {
        $this->shipping_price = $this->shipping_prices->where('id',$value)->first();
        session()->put('shipping_price', $this->shipping_price->id);
    }

    public function updateShippingAddressProvince($value)
    {
        $this->shipping_price->province = strtoupper($value);
    }

    public function updateBillingAddressProvince($value)
    {
        $this->billing_address->province = strtoupper($value);
    }

    public function setShownStep()
    {
        $step = 1;
        if( $this->shippingInfoIsDefault() )  $step = 2;
        if($step == 2 && $this->billingInfoIsDefault()) $step = 3;      
        $this->shownStep = $step;
    }

    public function shippingInfoIsDefault()
    {
        return $this->shipping_address != new Address() && 
            Auth::user()?->defaultAddress && $this->shipping_address->sameAddress(Auth::user()->defaultAddress);
    }

    public function billingInfoIsDefault()
    {
        return $this->billing_address != new Address() 
            && Auth::user()->defaultBillingAddress && $this->billing_address->sameAddress(Auth::user()->defaultBillingAddress) 
            &&  (Auth::user()?->vat == $this->vat || Auth::user()?->fiscal_code == $this->fiscal_code);
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

    public function retrieveData()
    {
        $user = Auth::user();
        $this->email =  session()->get('email') ? session()->get('email') : $user?->email;
        $this->phone =  session()->get('phone') ? session()->get('phone') : $user?->phone;
        $this->fiscal_code =  session()->get('fiscal_code') ? session()->get('fiscal_code') : $user?->fiscal_code;
        $this->vat =  session()->get('vat') ? session()->get('vat') : $user?->vat;
        $this->shipping_price = session()->get('shipping_price') ? 
            $this->shipping_prices->where('id', session()->get('shipping_price') )->first() 
            : $this->shipping_prices->first();
        $this->shipping_address = session()->get('shipping_address') ? session()->get('shipping_address') : $user?->defaultAddress ?? new Address();
        $this->billing_address = session()->get('billing_address') ? session()->get('billing_address') : $user?->defaultBillingAddress ?? new Address();
    }

    public function storeData()
    {
        session()->put('email', $this->email);
        session()->put('phone', $this->phone);
        session()->put('shipping_address', $this->shipping_address);
        session()->put('note', $this->note);
        session()->put('fiscal_code', $this->fiscal_code);
        session()->put('vat', $this->vat);
        session()->put('billing_address', $this->shipping_address);
        //shipping price
    }

    public function deleteData()
    {
        session()->forget('email');
        session()->forget('phone');
        session()->forget('shipping_address');     
        session()->forget('note');      
        session()->forget('fiscal_code');
        session()->forget('vat');
        session()->forget('billing_address');
        session()->forget('shipping_price');
    }

    public function updateDefaultShippingAddress()
    {
        $validated = $this->validate([
            'shipping_address.full_name' => 'required',
            'shipping_address.address' => 'required',
            'shipping_address.city' => 'required',
            'shipping_address.province' => 'required|size:2',
            'shipping_address.country_region' => 'required',
            'shipping_address.postal_code' => 'required|min:5|max:5',
            'phone' => 'nullable|numeric|digits_between:10,13',
        ]);

        $user = Auth::user()->update([
            'phone' => $validated['phone'],
        ]);
        
        $defaultAddress = Auth::user()->defaultAddress()->updateOrCreate([
            'user_id' => Auth::user()->id,
            'billing' => false
        ],[
            'full_name' => $validated['shipping_address']['full_name'],
            'address' => $validated['shipping_address']['address'],
            'city' => $validated['shipping_address']['city'],
            'province' => $validated['shipping_address']['province'],
            'country_region' => $validated['shipping_address']['country_region'],
            'postal_code' => $validated['shipping_address']['postal_code'],
            'default' => true,
        ]);
        
        if($defaultAddress && $user)
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

    public function updateDefaultBillingAddress()
    {
        $validated = $this->validate([
            'vat' => 'nullable|numeric|required_without:fiscal_code|digits:11',
            'fiscal_code' => 'nullable|required_without:vat|alpha_num|min:11|max:16',
            'billing_address.full_name' => 'required',
            'billing_address.address' => 'required',
            'billing_address.city' => 'required',
            'billing_address.province' => 'required|size:2',
            'billing_address.country_region' => 'required',
            'billing_address.postal_code' => 'required|min:5|max:5',
        ]);

        $user = Auth::user()->update([
            'fiscal_code' => $validated['fiscal_code'],
            'vat' => $validated['vat']
        ]);
        
        $defaultAddress = Auth::user()->defaultAddress()->updateOrCreate([
            'user_id' => Auth::user()->id,
            'billing' => true
        ],[
            'full_name' => $validated['billing_address']['full_name'],
            'address' => $validated['billing_address']['address'],
            'city' => $validated['billing_address']['city'],
            'province' => $validated['billing_address']['province'],
            'country_region' => $validated['billing_address']['country_region'],
            'postal_code' => $validated['billing_address']['postal_code'],
            'default' => true,
        ]);

        if($defaultAddress && $user)
        {
            $banner_message = __('banner_notifications.billing_info.saved') ;
            $banner_style = 'success';
        }
        else
        {
            $banner_message = __('banner_notifications.billing_info.not_saved');
            $banner_style = 'danger';
        }
        
        $this->dispatchBrowserEvent('banner-message', [
            'message' => $banner_message,
            'style' => $banner_style,
        ]);
    }

    public function copyAddress()
    {
        $this->billing_address = $this->shipping_address;
    }

    public function createOrder()
    {
        $validated = $this->validate();

        $this->fiscal_code = $this->fiscal_code ? $this->fiscal_code : $this->vat;

        $this->storeData();

        if(!$this->areCartProductsAvaiable()) $this->redirect(route('cart.index'));
        else{
            $status_id = OrderStatus::where('name', 'draft')->first()->id;
            $this->order = Order::updateOrCreate([
                'id' => $this->order?->id
            ],[
                'shipping_address' => $this->shipping_address->toJson(),
                'billing_address' => $this->billing_address->toJson(),
                'shipping_address_full_name' => $this->shipping_address->full_name,
                'shipping_address_address' => $this->shipping_address->address,
                'shipping_address_city' => $this->shipping_address->city,
                'shipping_address_province' => $this->shipping_address->province,
                'shipping_address_country_region' => $this->shipping_address->country_region,
                'shipping_address_postal_code' => $this->shipping_address->postal_code,
                'billing_address_full_name' => $this->billing_address->full_name,
                'billing_address_address' => $this->billing_address->address,
                'billing_address_city' => $this->billing_address->city,
                'billing_address_province' => $this->billing_address->province,
                'billing_address_country_region' => $this->billing_address->country_region,
                'billing_address_postal_code' => $this->billing_address->postal_code,
                'fiscal_code' => $this->fiscal_code,
                'vat' => $this->vat,
                'email' => auth()->user()?->email ?? $this->email,
                'phone' => $this->phone,
                'note' => $this->note,
                'subtotal' => $this->subtotal,
                'tax'   => $this->tax,
                'total' => $this->total + $this->shipping_price->price,
                'coupon_id' => $this->coupon ? $this->coupon->id : null,
                'coupon_discount' => $this->coupon ? $this->coupon->discount($this->subtotal) : null,
                'order_status_id' => $status_id,
                'user_id' => auth()->user()?->id,
                'shipping_price_id' => $this->shipping_price->id,
                'shipping_price' => $this->shipping_price->price,
                'avaiable_from' => $this->maxAvaiableFrom(),
            ]);

            $pivots = [];
            foreach ($this->cartContent as $item) {
                $pivots[$item['id']] = [
                    'price' => $item['price'],
                    'quantity' => $item['qty'],
                    'tax_rate' => $item['tax_rate'],
                    'discount' => $this->coupon && !$this->coupon->is_fixed_amount ? round($this->coupon->discount($item['price'] * $item['qty'] ),2) : null,
                ];
            }
            $this->order->products()->sync($pivots);

            $this->order->history()->updateOrCreate([
                'order_status_id' => $status_id,
            ],[]);
        }

        $this->addresses_confirmed = true;
        $this->emit('orderCreated');
    }

    public function placeOrder($payment_id, $gateway)
    {
        // $validated = $this->validate();

        // if(!$this->areCartProductsAvaiable()) $this->redirect(route('cart.index'));
        if(false) {}
        else{
            $status_id = OrderStatus::where('name', 'pending')->first()->id;
            $this->order->update([
                'payment_gateway' => $gateway,
                'payment_id' => $payment_id,
                'order_status_id' => $status_id
            ]);
            $this->order->save();

            foreach ($this->order->products as $product) {
                if(config('custom.skip_quantity_checks')) {
                    $product->quantity = max($product->quantity - $product->pivot->qty , 0);
                    $product->save();
                }
            }

            if($this->order->coupon)
            {
                $this->order->coupon->redemptions++;
                $this->order->coupon->save();
            }

            $this->order->history()->create([
                'order_status_id' => $status_id,
            ]);

            Cart::instance('default')->destroy();
            if(Auth::check())
                Cart::instance('default')->erase(auth()->user()->email);
            session()->forget('coupon');
            $this->deleteData();

            if($gateway == 'paypal')
                $this->order->setAsPaid();

            $this->emit('orderPlaced');
        }
    }
   
    public function render()
    {
        return view('order.create');
    }
}
