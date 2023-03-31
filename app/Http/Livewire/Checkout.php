<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Coupon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Cartalyst\Stripe\Laravel\Facades\Stripe;

class Checkout extends Component
{
    public $update;

    public Order $order;
    public $intent;
    public $confirmingPayment;
    public $total;
    public $submitDisabled;
    public $gateway;

    protected $listeners = [
        'orderPlaced' => 'redirectToSuccess',
        'orderUpdated' => 'redirectToSuccess',
    ];

    public function mount(Order $order, $update = false)
    {
        $this->update = $update;
        $this->total = $order->total;
        $this->confirmingPayment = false;
        $this->submitDisabled = false;
    }

    public function attemptSubmit()
    {
        $this->submitDisabled = true;
        $this->order->update([
            'payment_gateway' => $this->gateway,
            'payment_id' => $this->intent['id'],
        ]);
        $this->emit('submitPayment');
    }

    public function confirmPayment()
    {
        $this->confirmingPayment = true;
        $this->gateway = 'stripe';

        $metadata = [
            'order_id' => $this->order->id,
            'order_number' => $this->order->number
        ];

        $this->intent = Stripe::paymentIntents()->create([
            'amount' => $this->total,
            'currency' => 'eur',
            'automatic_payment_methods' => [
                'enabled' => 'true',
            ],
            'metadata' => $metadata,
        ]);
    }

    public function storePayment()
    {
        if($this->update)
            $this->emit('updateOrder',$this->intent['id'],$this->gateway);
        else
            $this->emit('placeOrder',$this->intent['id'],$this->gateway);
    }

    public function redirectToSuccess()
    {
        if($this->gateway == 'stripe')
        {
            return redirect()->route('stripe.handle.checkout.response',[
                'payment_intent' => $this->intent['id']
            ]);
        }
        else
        {
            $route_name=Auth::user() ? 'order.index' : 'cart.index';

            $banner_message=__('banner_notifications.payment.succeeded');
            $banner_style="success";
            session()->flash('flash.banner', $banner_message);
            session()->flash('flash.bannerStyle', $banner_style);

            return redirect()->route($route_name);
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
