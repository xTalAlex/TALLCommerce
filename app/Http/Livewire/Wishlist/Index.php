<?php

namespace App\Http\Livewire\Wishlist;

use App\Models\Product;
use Livewire\Component;
use App\Traits\Livewire\WithShoppingLists;
use Gloudemans\Shoppingcart\Facades\Cart;

class Index extends Component
{
    use WithShoppingLists;
    
    public $content;
    public $count;

    protected $listeners = [
        'updatedWishlist' => 'mount',
    ];

    public function mount()
    {  
        $this->content = Cart::instance('wishlist')->content();
        $this->count = Cart::instance('wishlist')->count();
    }

    public function render()
    {
        return view('wishlist.index');
    }
}
