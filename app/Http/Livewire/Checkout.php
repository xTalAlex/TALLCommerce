<?php

namespace App\Http\Livewire;

use App\Models\Coupon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Cartalyst\Stripe\Laravel\Facades\Stripe;

class Checkout extends Component
{
    public $update;

    public $intent;
    public $confirmingPayment;
    public $total;
    public $submitDisabled;
    public $gateway;

    protected $listeners = [
        'orderCreated',
        'orderUpdated',
    ];

    public function mount($total, $update = false)
    {
        $this->update = $update;
        $this->total = $total;
        $this->confirmingPayment = false;
        $this->submitDisabled = false;
    }

    public function orderCreated()
    {
        $this->emit('paymentConfirmed');
    }

    public function orderUpdated()
    {
        $this->emit('paymentConfirmed');
    }

    public function confirmPayment()
    {
        $this->confirmingPayment = true;
        $this->gateway = 'stripe';

        $metadata = array();

        $this->intent = Stripe::paymentIntents()->create([
            'amount' => $this->total,
            'currency' => 'eur',
            'automatic_payment_methods' => [
                'enabled' => 'true',
            ],
            'metadata' => $metadata,
        ]);
    }

    public function submitPayment()
    {
        $this->submitDisabled = true;
        if($this->update)
            $this->emit('updateOrder',$this->intent['id'],$this->gateway);
        else
            $this->emit('createOrder',$this->intent['id'],$this->gateway);
    }

    public function redirectToSuccess()
    {
        $route_name=Auth::user() ? 'order.index' : 'cart.index';

        $banner_message=__('banner_notifications.payment.succeeded');
        $banner_style="success";
        session()->flash('flash.banner', $banner_message);
        session()->flash('flash.bannerStyle', $banner_style);

        return redirect()->route($route_name);
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
