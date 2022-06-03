<?php

namespace App\Http\Livewire\Wishlist;

use Livewire\Component;
use App\Traits\Livewire\WithCart;
use Gloudemans\Shoppingcart\Facades\Cart;

class DestroyForm extends Component
{
    use WithCart;
    
    public $confirmingWishlistDeletion = false;

    public function delete()
    {
        $this->deleteWishlist();
        $this->confirmingWishlistDeletion = false;
    }

    public function render()
    {
        return view('wishlist.destroy-form');
    }
}
