<?php

namespace App\Http\Livewire\Stripe;

use App\Models\Coupon;
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use Cartalyst\Stripe\Laravel\Facades\Stripe;

class Checkout extends Component
{
    public $update;

    public $intent;
    public $confirmingPayment;
    public $total;
    public $submitDisabled;

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
            $this->emit('updateOrder',$this->intent['id']);
        else
            $this->emit('createOrder',$this->intent['id']);
    }

    public function render()
    {
        return view('livewire.stripe.checkout');
    }
}
