<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use App\Traits\Livewire\WithCart;

class Show extends Component
{
    use WithCart;

    public function render()
    {
        return view('product.show');
    }
}
