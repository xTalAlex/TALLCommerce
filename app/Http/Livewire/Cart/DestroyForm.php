<?php

namespace App\Http\Livewire\Cart;

use Livewire\Component;
use App\Traits\Livewire\WithShoppingLists;

class DestroyForm extends Component
{
    use WithShoppingLists;
    
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
