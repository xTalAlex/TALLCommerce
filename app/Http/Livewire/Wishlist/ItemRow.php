<?php

namespace App\Http\Livewire\Wishlist;

use App\Models\Product;
use Livewire\Component;
use App\Traits\Livewire\WithShoppingLists;
use Gloudemans\Shoppingcart\CartItem;

class ItemRow extends Component
{
    use WithShoppingLists;

    public $item;
    public $invalid;

    public function mount($item)
    {
        $this->item = $item;
        $this->product = Product::find($item['id']);
    }

    public function render()
    {
        return view('wishlist.item-row');
    }
}
