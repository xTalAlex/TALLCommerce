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
    public $tax;
    public $total;

    protected $listeners = [
        'updatedCart' => 'mount',
    ];

    public function mount()
    {
        $this->content = Cart::instance('default')->content();
        $this->count = Cart::instance('default')->count();
        $this->updatePrices();
        $this->subtotal =  Cart::instance('default')->subtotal();
        $this->tax =  Cart::instance('default')->tax();
        $this->total =  Cart::instance('default')->total();
    }

    public function updatePrices()
    {
        $price_changed = false;
        foreach(Cart::instance('default')->content() as $key=>$item)
        {
            if ($item->model)
            {
                if ($item->model->price != $item->price) {
                    $price_changed = true;
                }
                Cart::instance('default')->update($key, $item->model);
            }
            else
            {
                Cart::remove($key);
                $this->count = Cart::instance('default')->count();
                //notify user item no more avaiable
            }
        }
        return $price_changed;
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
