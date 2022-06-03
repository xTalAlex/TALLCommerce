<?php

namespace App\Http\Livewire\Cart;

use App\Models\Product;
use Livewire\Component;
use App\Traits\Livewire\WithCart;
use Gloudemans\Shoppingcart\CartItem;

class ItemRow extends Component
{
    use WithCart;

    public $item;

    public function mount($item)
    {
        $this->item = $item;
        $this->product = Product::find($item['id']);
        $this->dispatchBrowserEvent('banner-message', [
            'message' => 'mount called',
            'style' => 'success',
        ]);
    }

    public function updatedItemQty()
    {
        $this->updateCartProductQty($this->item['rowId'], $this->item['qty']);
        $this->emitUp('updatedTotal');
    }

    public function render()
    {
        return view('cart.item-row');
    }
}
