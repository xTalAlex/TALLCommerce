<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use App\Traits\Livewire\WithShoppingLists;

class Show extends Component
{
    use WithShoppingLists;

    public $attributeSet;
    public $attributes;
    public $selection;

    public function mount()
    {
        $this->attributeSet = $this->product->attributeSet();
        if ($this->attributeSet) {
            $this->attributes = $this->attributeSet->pluck('attribute.name')->unique();
            foreach ($this->product->attributeValues as $attributeValue) {
                $this->selection[$attributeValue->attribute->name] = $attributeValue->id;
            }
        }
    }

    public function updatedSelection($value)
    {
        $product = Product::withCount(['attributeValues' => fn($query) => $query->whereIn('id',$this->selection)])
                        ->having('attribute_values_count','=',count($this->selection))->first();
        if(!$product)
            $product = Product::whereHas('attributeValues', fn($query) => $query->where('id',$value))->first();

        $this->product = $product;
            
        foreach($this->product->attributeValues as $attributeValue)
        {
            $this->selection[$attributeValue->attribute->name] = $attributeValue->id;
        }
    }

    public function render()
    {
        return view('product.show');
    }
}
