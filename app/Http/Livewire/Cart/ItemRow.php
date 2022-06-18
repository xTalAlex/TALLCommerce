<?php

namespace App\Http\Livewire\Cart;

use App\Models\Product;
use Livewire\Component;
use App\Traits\Livewire\WithShoppingLists;
use Gloudemans\Shoppingcart\CartItem;

class ItemRow extends Component
{
    use WithShoppingLists;

    public $item;
    public $invalid;

    public function mount($item, $invalid)
    {
        $this->item = $item;
        $this->invalid = $invalid;
        $this->product = Product::find($item['id']);
    }

    public function updatedItemQty()
    {
        if ($this->item['qty'] && $this->item['qty'] > 0) 
        {
            $newQty = $this->updateCartProductQty($this->item['rowId'], $this->item['qty']);
            $this->item['qty'] = $newQty;
        }
        else $this->item['qty'] = 1;
    }

    public function render()
    {
        return view('cart.item-row',['invalid' => $this->invalid]);
    }
}
