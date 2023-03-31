<?php

namespace App\Http\Livewire\Wishlist;

use App\Models\Product;
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Traits\Livewire\WithShoppingLists;

class Index extends Component
{
    use WithShoppingLists;
    
    public $content;
    public $count;
    public $randomProduct;

    protected $listeners = [
        'updatedWishlist' => 'mount',
    ];

    public function mount()
    {  
        $this->checkProducts();
        $this->content = Cart::instance('wishlist')->content();
        $this->count = Cart::instance('wishlist')->count();
        $this->randomProduct = Product::inRandomOrder()->first();
    }

    public function checkProducts()
    {
        foreach(Cart::instance('wishlist')->content() as $key=>$item)
        {
            if (!$item->model)
            {
                Cart::remove($key);
                $this->count = Cart::instance('wishlist')->count();
                //notify user item no more avaiable
            }
        }
    }

    public function render()
    {
        return view('wishlist.index');
    }
}
