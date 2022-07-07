<?php

namespace App\Models;

use App\Scopes\NotHiddenScope;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model implements Buyable , HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes, Searchable;

    const PATH = "products";
    
    protected $fillable = [
        'name',
        'sku',
        'short_description',
        'description',
        'original_price',
        'selling_price',
        'tax',
        'quantity',
        'low_stock_threshold',
        'featured',
        'hidden',
        'unique_name',
        'variant_id',
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'price'         => 'decimal:2',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image',
        'gallery',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->useDisk(config('media-library.disk_name'));
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new NotHiddenScope);
    }

    public function scopeFeatured($query)
    {
        $query->where('featured', true);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->whereNull('variant_id');
        
        $query->with('categories');

        $query->when($filters['category'] ?? false, fn ($query) =>
            $query->whereHas('categories', fn($query) =>
                $query->where('category_id',$filters['category'])
            )
        );

        $query->when($filters['keyword'] ?? false, fn ($query) =>
            $query->where('name', 'like', '%'.$filters['keyword'].'%')
                ->orWhere('short_description', 'like', '%'.$filters['keyword'].'%')
                ->orWhere('description', 'like', '%'.$filters['keyword'].'%')
        );

        if($filters['orderby'] ?? false){
            switch($filters['orderby']){    
                case('price_asc'):
                    $query->orderBy('selling_price','asc');
                    break;
                case('price_desc'):
                    $query->orderBy('selling_price','desc');
                    break;
                default:
                    $query->orderBy('created_at','desc');
                    break;
            }
        }
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('price','quantity');
    }

    public function variants()
    {
        return $this->hasMany(Product::class,'variant_id');
    }

    public function defaultVariant()
    {
        return $this->belongsTo(Product::class,'variant_id');
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class);
    }

    public function attributes()
    {
        $attributeIds = $this->attributeValues()->with('attribute')->get()->pluck('attribute_id');
        return \App\Models\Attribute::findMany($attributeIds);
    }

    public function getBuyableIdentifier($options = null){
        return $this->id;
    }

    public function getBuyableDescription($options = null){
        return $this->name;
    }

    public function getBuyablePrice($options = null){
        return $this->price;
    }

    public function getBuyableWeight($options = null){
        return $this->weight;
    }

    public function setUniqueNameAttribute($value)
    {
        if(!$value)
            $value = $this->attributes['id'].'-'.$this->attributes['name'];
        $this->attributes['unique_name'] = $value;
    }

    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('gallery') !="" ? $this->getFirstMediaUrl('gallery') : asset('img/no_image.jpg');
    }

    public function getGalleryAttribute()
    {
        return $this->getMedia('gallery')->map( fn($media) => $media->getFullUrl() );
    }

    public function setGalleryAttribute($value)
    {
        if ($value) {
            $fileAdders = $this->addMultipleMediaFromRequest($value);
            foreach ($fileAdders as $fileAdder) {
                $fileAdder->toMediaCollection('gallery');
            }
        }
    }

    protected function originalPrice(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return number_format( $value , 2);
            },
        );
    }


    protected function sellingPrice(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $value ?? ($attributes['original_price'] ?? null);
            },
            set: function ($value, $attributes) {
                
                return $value ?
                    number_format( $value , 2)
                    : $attributes['original_price'];
            },
        );
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: function ($value,$attributes) {
                return $attributes['selling_price'] && $attributes['selling_price']!=$attributes['original_price'] ?
                            $attributes['selling_price'] : $attributes['original_price'];
            },
        );
    }

    public function getDiscountAttribute($value)
    {
        $difference = $this->selling_price && ($this->selling_price < $this->original_price) ? $this->original_price - $this->selling_price : 0;
        if($difference) 
            $percent = round($this->original_price / $difference, 2);
        else 
            $percent = 0;
        return $percent;
    }

    public function variantsAttributeValues()
    {
        $defaultVariant = $this->variant_id ?  $this->defaultVariant : $this;
        if ($defaultVariant->variants()->exists()) {
            $attributeValues = $defaultVariant->attributeValues
                                        ->pluck('id')->unique()->sort()->toArray();
            $variantsAttributeValues = $defaultVariant->variants()->with('attributeValues')->get()
                                                ->pluck('attributeValues.*.id')->collapse()->unique()->sort()->toArray();
                            
            $attributeValues = array_merge($attributeValues, $variantsAttributeValues);

            return AttributeValue::findMany($attributeValues)->sortBy('attribute_id');
        }
    }

    public function variantsAttributeSets()
    {
        $defaultVariant = $this->variant_id ?  $this->defaultVariant : $this;
        if ($defaultVariant->variants()->exists()) {
            $attributeSet = $defaultVariant->attributeValues
                                        ->pluck('id','attribute_id')->unique()->sort()->toArray();
            $attributeSet = array($attributeSet);
            foreach($defaultVariant->variants as $variant)
            {
                $variantsAttributeSet = $variant->attributeValues
                                            ->pluck('id','attribute_id')->unique()->sort()->toArray();
                $attributeSet = array_merge($attributeSet, array($variantsAttributeSet));
            }

            return $attributeSet;
        }
    }

    public function pricePerQuantity(int $quantity, float $newPrice = null )
    {
        return number_format( ($newPrice ?? $this->price) * $quantity , 2) ;
    }

    public function shouldBeSearchable()
    {
        return !$this->variant_id || ($this->variant_id == $this->id);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = array();

        $array['id'] = $this->id;
        $array['name'] = $this->name;
        $array['short_description'] = $this->short_description;
        $array['description'] = $this->description;
        $array['original_price'] = $this->original_price;
        $array['selling_price'] = $this->selling_price;
        $array['discount'] = $this->discount;
        $array['price'] = $this->price;
        $array['featured'] = $this->featured;
        $array['quantity'] = $this->quantity;
        $array['low_stock_threshold'] = $this->low_stock_threshold;
        $array['image'] = $this->image;

        $array['url'] = route('product.show', $this);
 
        return $array;
    }

}
