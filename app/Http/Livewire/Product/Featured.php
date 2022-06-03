<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Traits\Livewire\WithCart;

class Featured extends Component
{
    use WithCart;

    public function render()
    {
        return view('livewire.product.featured');
    }
}
