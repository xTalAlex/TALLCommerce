<?php

namespace App\Http\Livewire\Cart;

use Livewire\Component;
use App\Traits\Livewire\WithCart;
use Gloudemans\Shoppingcart\Facades\Cart;

class DestroyForm extends Component
{
    use WithCart;
    
    public $confirmingCartDeletion = false;

    public function delete()
    {
        $this->deleteCart();
        $this->confirmingCartDeletion = false;
    }

    public function render()
    {
        return view('cart.destroy-form');
    }
}
