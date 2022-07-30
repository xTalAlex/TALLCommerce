<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Reviews extends Component
{
    use WithPagination;
    
    public Product $product;
    
    public function mount($product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.product.reviews',[
            'reviews' => $this->product->reviews()->latest()->paginate(2, ['*'], 'reviewsPage'),
        ]);
    }
}
