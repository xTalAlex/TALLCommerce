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

class Product extends Model implements Buyable , HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    const PATH = "products";
    
    protected $fillable = [
        'name',
        'short_description',
        'description',
        'original_price',
        'selling_price',
        'tax',
        'quantity',
        'featured',
        'hidden',
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
        $query->with('categories');

        $query->when($filters['category'] ?? false, fn ($query) =>
            $query->whereHas('categories', fn($query) =>
                $query->where('category_id',$filters['category'])
            )
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

    public function orders(){
        return $this->belongsToMany(Order::class)->withPivot('price','quantity');
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

    public function discount()
    {
        $difference = $this->selling_price && ($this->selling_price < $this->original_price) ? $this->original_price - $this->selling_price : 0;
        if($difference) 
            $percent = round($this->original_price / $difference, 2);
        else 
            $percent = 0;
        return $percent;
    }

    public function pricePerQuantity(int $quantity, float $newPrice = null )
    {
        return number_format( ($newPrice ?? $this->price) * $quantity , 2) ;
    }

}
