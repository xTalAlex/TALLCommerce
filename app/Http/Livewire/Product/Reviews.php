<?php

namespace App\Http\Livewire\Product;

use App\Models\Review;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Reviews extends Component
{
    use WithPagination;
    
    public Product $product;
    public $related_products;

    public function mount($product)
    {
        $this->product = $product;
        $this->related_products = Product::when( !$this->product->variant_id, fn($query) => 
                $query->where('id', $this->product->id)
                    ->orWhere('variant_id', $this->product->id)
            )
            ->when( $this->product->variant_id, fn($query) =>
                $query->where('id', $this->product->variant_id)
                    ->orWhere('variant_id', $this->product->variant_id)
            )->get();
    }

    public function render()
    {
        return view('livewire.product.reviews',[
            'reviews' => Review::with('user')->whereIn('product_id', $this->related_products->pluck('id'))
                ->latest()
                ->paginate(5, ['*'], 'reviewsPage')
        ]);
    }
}
