<?php

namespace App\Http\Livewire\Product;

use App\Models\AttributeValue;
use App\Models\Review;
use App\Models\Product;
use Livewire\Component;
use App\Traits\Livewire\WithShoppingLists;

class Show extends Component
{
    use WithShoppingLists;

    public $variant_id;
    public $variantsAttributeValues;
    public $variantsAttributeSets;
    public $attributes;
    public $selection;
    public $tab = 0;

    protected $queryString = [
        'tab' => ['except' => 0]
    ];

    public function mount($product)
    {
        if(!$this->product = Product::with(['defaultVariant','variants','attributeValues'])->whereSlug($product)->first())
        {
            session()->flash('flash.banner', __('banner_notifications.product.not_found') );
            session()->flash('flash.bannerStyle', 'danger');

            $this->redirect(url()->previous());
        }
        else{
            $this->variant_id = $this->product->variant_id == null || $this->product->variant_id == $this->id ? $this->product->id :  $this->product->variant_id;
            $this->variantsAttributeValues = $this->product->variantsAttributeValues();
            $this->variantsAttributeSets = $this->product->variantsAttributeSets();
            if ($this->variantsAttributeValues) {
                $this->attributes = $this->variantsAttributeValues->pluck('attribute.name','attribute.id')->unique();
                foreach ($this->product->attributeValues as $attributeValue) {
                    $this->selection[$attributeValue->attribute->id] = $attributeValue->id;
                }
            }
        }
    }

    public function variantExists($attribute,$value)
    {
        $possibleSelection = $this->selection;
        $possibleSelection[$attribute] = $value;
        return in_array($possibleSelection ,$this->variantsAttributeSets);
    }

    public function updatedSelection($value)
    {
        $product = Product::where(fn($query) => $query->where('variant_id',$this->variant_id)->orWhere('id',$this->variant_id))
                        ->withCount(['attributeValues' => fn($query) => $query->whereIn('id',$this->selection)])->get();
        $product = $product->where('attribute_values_count','=',count($this->selection))->first();
        if(!$product)
            $product = Product::where(fn($query) => $query->where('variant_id',$this->variant_id)->orWhere('id',$this->variant_id))
                        ->whereHas('attributeValues', fn($query) => $query->where('id',$value))->first();
        $this->product = $product;
            
        foreach($this->product->attributeValues as $attributeValue)
        {
            $this->selection[$attributeValue->attribute->id] = $attributeValue->id;
        }
    }

    public function getGalleryProperty()
    {
        if($this->product->hasImage())
        {
            $gallery = $this->product->gallery;
        }
        else
        {
            if($this->product->defaultVariant()->exists() && $this->product->defaultVariant->hasImage())
            {
                $gallery = $this->product->defaultVariant->gallery;
            }
            else
            {
                $gallery = [$this->product->image];
            }
        }

        return $gallery;
    }

    public function getDescriptionProperty()
    {
        $description = $this->product->description;
        if(!$description && $this->product->defaultVariant)
            $description = $this->product->defaultVariant->description;
        return $description;
    }

    public function getTagsProperty()
    {
        $tags = $this->product->tags()->get();
        if(!$tags && $this->product->defaultVariant)
            $tags = $this->product->defaultVariant->tags()->get();
        return $tags;
    }

    public function shouldSelectVariantByImage()
    {
        return ($this->product->defaultVariant || $this->product->variants()->count()) 
                && !$this->variantsAttributeSets;
    }

    public function shouldSelectVariantByAttribute()
    {
        return ($this->product->defaultVariant || $this->product->variants()->count()) 
                && $this->variantsAttributeSets;
    }

    public function getAttributeValueLabel($attributeName, $value)
    {
        return AttributeValue::getLabel($attributeName, $value);
    }

    public function render()
    {
        $related_products = Product::when( !$this->product->variant_id, fn($query) => 
                $query->where('id', $this->product->id)
                    ->orWhere('variant_id', $this->product->id)
            )
            ->when( $this->product->variant_id, fn($query) =>
                $query->where('id', $this->product->variant_id)
                    ->orWhere('variant_id', $this->product->variant_id)
            )->get();

        return view('product.show',[
            'reviews' => Review::whereIn('product_id', $related_products->pluck('id'))
        ]);
    }
}
