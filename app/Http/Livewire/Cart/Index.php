<?php

namespace App\Http\Livewire\Cart;

use App\Models\Product;
use Livewire\Component;
use App\Traits\Livewire\WithCart;
use Gloudemans\Shoppingcart\Facades\Cart;

class Index extends Component
{
    use WithCart;
    
    public $content;
    public $count;

    public $subtotal;
    public $total;

    protected $listeners = [
        'updatedCart' => 'mount',
    ];

    public function mount()
    {
        $this->content = Cart::instance('default')->content();
        $this->count = Cart::instance('default')->count();
        $this->subtotal = Cart::instance('default')->subtotal();
        $this->total = Cart::instance('default')->total();
    }

    public function render()
    {       
        return view('cart.index');
    }
}
