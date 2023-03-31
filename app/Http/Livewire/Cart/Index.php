<?php

namespace App\Http\Livewire\Cart;

use App\Models\Product;
use Livewire\Component;
use App\Traits\Livewire\WithCartTotals;
use App\Traits\Livewire\WithShoppingLists;
use Gloudemans\Shoppingcart\Facades\Cart;

class Index extends Component
{
    use WithShoppingLists, WithCartTotals;
    
    public $invalid_quantity_row_ids = [];
    public $content;
    public $count;
    public $randomProduct;

    protected $listeners = [
        'updatedCart' => 'mount',
    ];

    public function mount()
    {
        $this->content = Cart::instance('default')->content();
        $this->count = Cart::instance('default')->count();
        $this->updatePrices();
        $this->refreshTotals();
        $this->randomProduct = Product::inRandomOrder()->first();
    }

    public function updatePrices()
    {
        $price_changed = false;
        foreach($this->content as $rowId=>$item)
        {
            if ($item->model)
            {
                if ($item->model->price != $item->price) {
                    $price_changed = true;
                }
                Cart::instance('default')->update($rowId, $item->model);
            }
            else
            {
                Cart::remove($rowId);
                $this->count = Cart::instance('default')->count();
                //notify user item no more avaiable
            }
        }
        return $price_changed;
    }

    public function checkProductsQuantity()
    {
        $this->content = Cart::instance('default')->content();
        $this->invalid_quantity_row_ids = array();
        foreach($this->content as $rowId=>$item)
        {
            if($item->model->quantity < $item->qty)
            {
                array_push($this->invalid_quantity_row_ids, $rowId);
            }
        }
    }

    public function render()
    {       
        if(!config('custom.skip_quantity_checks')) $this->checkProductsQuantity();

        return view('cart.index');
    }
}
