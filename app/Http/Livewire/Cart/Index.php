<?php

namespace App\Http\Livewire\Cart;

use App\Models\Product;
use Livewire\Component;
use App\Traits\Livewire\WithShoppingLists;
use Gloudemans\Shoppingcart\Facades\Cart;

class Index extends Component
{
    use WithShoppingLists;
    
    public $invalid_quantity_row_ids;
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

    public function checkProductsQuantity()
    {
        $this->invalid_quantity_row_ids = array();
        foreach($this->content as $item)
        {
            if($item->model->quantity < $item->qty)
            {
                array_push($this->invalid_quantity_row_ids, $item->rowId);
            }
        }
    }

    public function render()
    {       
        $this->checkProductsQuantity();

        return view('cart.index');
    }
}
