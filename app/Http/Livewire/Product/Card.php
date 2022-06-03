<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Traits\Livewire\WithCart;

class Card extends Component
{
    use WithCart;
    
    public function render()
    {
        return view('livewire.product.card');
    }
}
