<?php

namespace App\Http\Livewire\Stripe;

use App\Models\Coupon;
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use Cartalyst\Stripe\Laravel\Facades\Stripe;

class Checkout extends Component
{
    public $intent;
    public $confirmingPayment = false;
    public $total;

    public function mount($total)
    {
        $this->total = $total;
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
        $this->emit('createOrder',$this->intent['id']);
        $this->emit('paymentConfirmed');
    }

    public function render()
    {
        return view('livewire.stripe.checkout');
    }
}
