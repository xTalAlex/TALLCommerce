<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use App\Traits\Livewire\WithShoppingLists;

class Show extends Component
{
    use WithShoppingLists;

    public function render()
    {
        return view('product.show');
    }
}
