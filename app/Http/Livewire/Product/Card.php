<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Traits\Livewire\WithShoppingLists;

class Card extends Component
{
    use WithShoppingLists;
    
    public function render()
    {
        return view('livewire.product.card');
    }
}
