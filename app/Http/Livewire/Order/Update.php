<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\{Order, OrderStatus ,Address};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Update extends Component
{
    use AuthorizesRequests;
    
    public Order $order;
    public Address $shipping_address;
    public Address $billing_address; 
    public $addresses_confirmed;
    public $shownStep;

    protected function rules()
    {
        return [
            'order.email' => 'required|email'. (auth()->user() ? '' : '|unique:users,email'),
            'order.phone' => 'nullable|numeric|digits_between:10,13',
            'shipping_address.full_name' => 'required',
            'shipping_address.address' => 'required',
            'shipping_address.city' => 'required',
            'shipping_address.province' => 'required|size:2',
            'shipping_address.country_region' => 'required',
            'shipping_address.postal_code' => 'required|min:5|max:5',
            'order.note' => 'nullable|max:255',

            'order.vat' => 'nullable|numeric|required_without:order.fiscal_code|digits:11',
            'order.fiscal_code' => 'nullable|required_without:order.vat|alpha_num|min:11|max:16',
            'billing_address.full_name' => 'required',
            'billing_address.address' => 'required',
            'billing_address.city' => 'required',
            'billing_address.province' => 'required|size:2',
            'billing_address.country_region' => 'required',
            'billing_address.postal_code' => 'required|min:5|max:5',
        ];
    }

    protected $listeners = [
        'updateOrder',
    ];
    
    public function mount(Order $order)
    {
        $this->authorize('update', $this->order);
        
        $this->order = $order;
        $this->shipping_address = $order->shippingAddress();
        $this->billing_address = $order->billingAddress();
        $this->addresses_confirmed = true;
        $this->setShownStep();
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
        $this->shownStep = $this->addresses_confirmed ? 0 : 1;
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
            'order.phone' => 'nullable|numeric|digits_between:10,13',
        ]);

        $user = Auth::user()->update([
            'phone' => $validated['order']['phone'],
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
            'order.vat' => 'nullable|numeric|required_without:order.fiscal_code|digits:11',
            'order.fiscal_code' => 'nullable|required_without:order.vat|alpha_num|min:11|max:16',
            'billing_address.full_name' => 'required',
            'billing_address.address' => 'required',
            'billing_address.city' => 'required',
            'billing_address.province' => 'required|size:2',
            'billing_address.country_region' => 'required',
            'billing_address.postal_code' => 'required|min:5|max:5',
        ]);

        $user = Auth::user()->update([
            'fiscal_code' => $validated['order']['fiscal_code'],
            'vat' => $validated['order']['vat']
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

    public function updateAddresses()
    {
        $this->authorize('update', $this->order);

        if($this->addresses_confirmed){
            $this->addresses_confirmed=false;
        }
        else{
            $this->validate();
            if (Order::find($this->order->id)->canBeEdited()) {
                $this->order->update([
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
                    'fiscal_code' => $this->order->fiscal_code ? $this->order->fiscal_code : $this->order->vat,
                    'vat' => $this->order->vat,
                    'email' => auth()->user()?->email ?? $this->order->email,
                    'phone' => $this->order->phone,
                    'note' => $this->order->note,
                ]);
                $this->order->history()->create([
                    'order_status_id' => $this->order->order_status_id,
                    'description' => 'Addresses Updated',
                ]);
                $this->addresses_confirmed=true;
            }
        }
    }

    public function updateOrder($payment_id, $gateway)
    {
        if(Order::find($this->order->id)->canBePaid()) {
            $pending_id = OrderStatus::where('name', 'pending')->first()->id;
            $this->order->update([
                'payment_gateway' => $gateway,
                'payment_id' =>  $payment_id,
                'order_status_id' => $pending_id,
            ]);

            $this->order->history()->create([
                'order_status_id' => $pending_id,
                'description' => 'New Payment Intent',
            ]);

            $this->emit('orderUpdated');
        }
    }

    public function render()
    {
        $this->setShownStep();
        
        return view('order.update');
    }
}
