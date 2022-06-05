<?php

namespace App\Http\Livewire\Wishlist;

use App\Models\Product;
use Livewire\Component;
use App\Traits\Livewire\WithCart;
use Gloudemans\Shoppingcart\Facades\Cart;

class Index extends Component
{
    use WithCart;
    
    public $content;
    public $count;

    public function mount()
    {  
        $this->content = Cart::instance('wishlist')->content();
        $this->count = Cart::instance('wishlist')->count();
    }

    public function move(Product $product)
    {
        $this->moveToCart($product);
        $this->mount();
    }

    public function render()
    {
        return view('wishlist.index');
    }
}
