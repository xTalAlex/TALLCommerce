<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use App\Traits\Livewire\WithShoppingLists;

class Show extends Component
{
    use WithShoppingLists;

    public $variantsAttributeValues;
    public $variantsAttributeSets;
    public $attributes;
    public $selection;
    public $reviews;
    public $gallery;

    public function mount()
    {
        $this->variantsAttributeValues = $this->product->variantsAttributeValues();
        $this->variantsAttributeSets = $this->product->variantsAttributeSets();
        if ($this->variantsAttributeValues) {
            $this->attributes = $this->variantsAttributeValues->pluck('attribute.name','attribute.id')->unique();
            foreach ($this->product->attributeValues as $attributeValue) {
                $this->selection[$attributeValue->attribute->id] = $attributeValue->id;
            }
        }
        if($this->product->hasImage())
        {
            $this->gallery = $this->product->gallery;
        }
        else
        {
            if($this->product->defaultVariant()->exists())
            {
                $this->gallery = $this->product->defaultVariant->gallery;
            }
            else
            {
                $this->gallery = $this->product->image;
            }
        }
        $this->reviews = $this->product->defaultVaraint ? $this->product->defaultVariant->reviews : $this->product->reviews;
    }

    public function variantExists($attribute,$value)
    {
        $possibleSelection = $this->selection;
        $possibleSelection[$attribute] = $value;
        return in_array($possibleSelection ,$this->variantsAttributeSets);
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
            $this->selection[$attributeValue->attribute->id] = $attributeValue->id;
        }
    }

    public function render()
    {
        return view('product.show');
    }
}
