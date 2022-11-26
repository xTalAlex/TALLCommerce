<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\OrderStatus;
use App\Models\{Order,Address};
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Update extends Component
{
    use AuthorizesRequests;
    
    public Order $order;
    public Address $shipping_address;
    public Address $billing_address;
    
    public $email;
    public $same_address;
    public $addresses_confirmed;

    protected $listeners = [
        'updateOrder',
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
        ];
    }
    
    public function mount(Order $order)
    {
        $this->authorize('update', $this->order);
        
        $this->order = $order;
        $this->same_address = false;
        $this->shipping_address = $order->shippingAddress();
        $this->billing_address = $order->billingAddress();
        $this->addresses_confirmed = true;

        $this->email = Auth::user() ? Auth::user()->email : null;
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

    public function updateAddresses()
    {
        $this->authorize('update', $this->order);

        if($this->addresses_confirmed){
            $this->addresses_confirmed=false;
        }
        else{
            $this->validate();
            if($this->same_address) $this->billing_address = $this->shipping_address;
            if (Order::find($this->order->id)->canBeEdited()) {
                $this->order->update([
                    'shipping_address' => $this->shipping_address->toJson(),
                    'billing_address' => $this->billing_address->toJson(),
                    'email' => $this->email,
                ]);
                $this->order->history()->create([
                    'order_status_id' => $this->order->order_status_id,
                    'description' => 'Addresses Updated',
                ]);
                $this->addresses_confirmed=true;
            }
        }
    }

    public function updateOrder($payment_id)
    {
        if(Order::find($this->order->id)->canBePaied()) {
            $pending_id = OrderStatus::where('name', 'pending')->first()->id;
            $this->order->update([
                'payment_gateway' => 'stripe',
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
        return view('order.update');
    }
}
