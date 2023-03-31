<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Sitemap\Tags\Url;
use Laravel\Scout\Searchable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Scopes\NotHiddenScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Contracts\Sitemapable;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements Buyable, HasMedia, Sitemapable
{
    use HasFactory, InteractsWithMedia, SoftDeletes, Searchable, HasSEO;

    const PATH = "products";

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'original_price',
        'selling_price',
        'discount_is_fixed_amount',
        'discount_amount',
        'tax_rate',
        'quantity',
        'weight',
        'low_stock_threshold',
        'featured',
        'hidden',
        'variant_id',
        'brand_id',
        'avaiable_from'
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'price'         => 'decimal:2',
        'taxed_price' => 'decimal:2',
        'taxed_original_price' => 'decimal:2',
        'taxed_selling_price' => 'decimal:2',
        'avg_rating' => 'decimal:1',
        'tags' => 'array',
        'hidden' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'avaiable_from' => 'date',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image',
        'gallery',
        'taxed_price',
        'taxed_original_price',
        'taxed_selling_price',
        'discount'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->useDisk(config('media-library.disk_name'));
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if (config('custom.use_watermark')) {
            $this->addMediaConversion('watermarked')
                ->watermark(public_path('img/watermark.png'))
                ->watermarkOpacity(50)
                ->watermarkHeight(128, Manipulations::UNIT_PIXELS)
                ->watermarkPadding(20)
                ->performOnCollections('gallery');
        }
    }

    public function getDynamicSEOData(): SEOData
    {
        $description = $this->seo->description ?? $this->description;
        return new SEOData(
            title: $this->seo->title ?? $this->name,
            description: strip_tags($description ?? ""),
            image: $this->image
        );
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

        $query->with(['categories','brand','collections']);

        $query->when(
            $filters['category'] ?? false,
            fn ($query) =>
            $query->whereHas(
                'categories',
                fn ($query) =>
                $query->where('categories.id', (int) $filters['category'] )
                    ->orWhere('categories.slug', insensitiveLike(), $filters['category'])
            )
        );

        $query->when(
            $filters['brand'] ?? false,
            fn ($query) =>
            $query->whereHas(
                'brand',
                fn ($query) =>
                $query->whereIn('brands.id', collect($filters['brand'])->map( fn($i) => (int)$i )->toArray() )
                    ->orWhereIn('brands.slug', $filters['brand'])
            )
        );

        $query->when(
            $filters['collection'] ?? false,
            fn ($query) =>
            $query->whereHas(
                'collections',
                fn ($query) =>
                $query->whereIn('collections.id', collect($filters['collection'])->map( fn($i) => (int)$i )->toArray() )
                    ->orWhereIn('collections.slug', $filters['collection'])
            )
        );

        $query->when(
            $filters['query'] ?? false,
            fn ($query) =>
            $query->where(fn($query) => 
                $query->where('name', insensitiveLike(), '%' . $filters['query'] . '%')
                ->orWhere('short_description', insensitiveLike(), '%' . $filters['query'] . '%')
                ->orWhere('description', insensitiveLike(), '%' . $filters['query'] . '%')
                ->orWhereHas('tags', fn($query) => $query->where('name', insensitiveLike(), '%' . $filters['query'] . '%' ))
                ->orWhereHas('categories', fn($query) => $query->where('name', insensitiveLike(), '%' . $filters['query'] . '%' ))
                ->orWhereHas('collections', fn($query) => $query->where('name', insensitiveLike(), '%' . $filters['query'] . '%' ))
                ->orWhereHas('brand', fn($query) => $query->where('name', insensitiveLike(), '%' . $filters['query'] . '%' ))
            )
        );

        if ($filters['orderby'] ?? false) {
            switch ($filters['orderby']) {
                case ('price_asc'):
                    $query->orderBy('selling_price', 'asc');
                    break;
                case ('price_desc'):
                    $query->orderBy('selling_price', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('price', 'quantity', 'discount','tax_rate');
    }

    public function paidOrders()
    {
        $validStatuses = OrderStatus::whereIn('name',['paid','completed'])->get()->pluck('id');
        return $this->belongsToMany(Order::class)->whereIn('order_status_id', $validStatuses)->withPivot('price', 'quantity', 'discount', 'tax_rate');
    }

    public function variants()
    {
        return $this->hasMany(Product::class, 'variant_id');
    }

    public function defaultVariant()
    {
        return $this->belongsTo(Product::class, 'variant_id')->withoutGlobalScopes();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
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

    public function getBuyableIdentifier($options = null)
    {
        return $this->id;
    }

    public function getBuyableDescription($options = null)
    {
        return $this->name;
    }

    public function getBuyablePrice($options = null)
    {
        return $this->price;
    }

    public function getBuyableWeight($options = null)
    {
        return $this->weight ?? 0;
    }

    public function setSlugAttribute($value)
    {
        if (static::withoutGlobalScopes()->whereNot('id', $this->id)->whereSlug($slug = Str::slug($value))->exists())
            $slug = "{$slug}-{$this->id}";
        $this->attributes['slug'] = $slug;
    }

    public function isLowStock()
    {
        $low_stock_threshold = $this->low_stock_threshold ?? config('custom.stock_threshold');
        return $this->quantity >= 1 && $this->quantity <= $low_stock_threshold;
    }

    public function getStockStatusAttribute()
    {

        if ($this->quantity < 1) {
            $status = __('Out of Stock');
        } elseif ($this->isLowStock()) {
            $status = __('Low Stock');
        } else {
            $status = trans_choice('Avaiable', 1);
        }

        return $status;
    }

    public function getAvgRatingAttribute()
    {
        $defaultVariant = $this->variant_id ?  $this->defaultVariant : $this;
        $avg = Review::where('product_id', $defaultVariant->id)->orWhereIn('product_id',$defaultVariant->variants()->pluck('id'))->avg('rating');
        return $avg ? round($avg, 1) : null;
    }

    public function getImageAttribute()
    {
        // $image = null;
        // if($this->getFirstMediaUrl('gallery') !="")
        // {
        //     $image =  $this->getFirstMediaUrl('gallery');
        // }
        // else
        // {
        //     $this->defaultVariant()->exists() ?
        //         $image = $this->defaultVariant->image : $image = asset('img/no_image.jpg');
        // }

        // ( Storage::disk(config('media-library.disk_name'))->exists('data/import/immagini/'.$this->sku.'.jpg') ? Storage::disk(config('filesystem.default'))->url('data/import/immagini/'.$this->sku.'.jpg') : 

        return $this->hasImage() ? $this->getFirstMediaUrl('gallery', config('custom.use_watermark') ? 'watermarked' : 'default') 
            : asset('img/no_image.webp');
    }

    public function getGalleryAttribute()
    {
        // $gallery = null;
        // if($this->getMedia('gallery')->count() || $this->defaultVariant()->doesntExist())
        // {
        //     $gallery =  $this->getMedia('gallery')->map( fn($media) => $media->getFullUrl() );
        // }
        // else
        // {
        //     $gallery = $this->defaultVariant->gallery;
        // }

        return $this->getMedia('gallery')->map(fn ($media) => $media->getAvailableFullUrl(config('custom.use_watermark') ? ['watermarked', 'default'] : ['default']));
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

    public function hasImage()
    {
        return $this->getFirstMediaUrl('gallery') != "";
    }

    protected function originalPrice(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return number_format($value, 2);
            },
        );
    }

    protected function variantId(): Attribute
    {
        return Attribute::make(
            set: function ($value, $attributes) {
                return $value == $attributes['id'] ? null : $value;
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
                    number_format($value, 2)
                    : $attributes['original_price'];
            },
        );
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $attributes['selling_price'] && $attributes['selling_price'] != $attributes['original_price'] ?
                    $attributes['selling_price'] : $attributes['original_price'];
            },
        );
    }

    public function applyTax($price, $tax_rate = null)
    {
        if($tax_rate === null) $tax_rate = $this->attributes['tax_rate'] ?? config('cart.tax');
        return number_format(round($price + round($price * ($tax_rate / 100), 2),2),2);
    }

    public function removeTax($price, $tax_rate = null)
    {
        if($tax_rate === null) $tax_rate = $this->attributes['tax_rate'] ?? config('cart.tax');
        return number_format(round($price / (1 + ($tax_rate/100)),2),2);
    }

    protected function taxedOriginalPrice(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $attributes['taxed_original_price'] ?? $this->applyTax($attributes['original_price']);
            },
        );
    }

    protected function taxedSellingPrice(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $attributes['taxed_selling_price'] ?? $this->applyTax($attributes['selling_price']);
            },
        );
    }

    protected function taxedPrice(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $this->applyTax($this->price);
            },
        );
    }

    public function getDiscountAttribute($value)
    {
        $difference = $this->selling_price && ($this->selling_price < $this->original_price) ? $this->original_price - $this->selling_price : 0;
        if ($difference)
            $percent = round($this->original_price / $difference, 2);
        else
            $percent = 0;
        return $percent;
    }

    public function variantsAttributeValues()
    {
        $defaultVariant = $this->variant_id ?  $this->defaultVariant : $this;
        if ($defaultVariant->variants()->exists()) {
            $attributeValues = $defaultVariant->deleted_at || $defaultVariant->attributes['hidden'] ? [] : $defaultVariant->attributeValues
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
            $attributeSet = $defaultVariant->deleted_at || $defaultVariant->attributes['hidden'] ? [] : $defaultVariant->attributeValues
                ->pluck('id', 'attribute_id')->unique()->sort()->toArray();
            $attributeSet = array($attributeSet);
            foreach ($defaultVariant->variants as $variant) {
                $variantsAttributeSet = $variant->attributeValues
                    ->pluck('id', 'attribute_id')->unique()->sort()->toArray();
                $attributeSet = array_merge($attributeSet, array($variantsAttributeSet));
            }

            return $attributeSet;
        }
    }

    public function setAsDefaultVariant()
    {
        $old = $this->defaultVariant;
        $this->defaultVariant->variants()->withTrashed()->whereNot('id',$this->id)->update([
            'variant_id' => $this->id
        ]);
        $old->defaultVariant()->associate($this->id);
        $old->save();
        $this->variant_id = null;
        $this->save();
    }

    public function hierarchicalCategories()
    {
        $allCategories = $this->categories;
        $hierarchicalCategories = array();
        $hasMoreLevels = $allCategories != null ? true : false;
        $level = 0;
        $hierarchicalCategories[$level] = array();

        while ($hasMoreLevels) {
            $levelCategories = array();
            $hasMoreLevels = false;
            foreach ($allCategories as $category) {
                if ($level != 0) {
                    if ($hierarchicalCategories[$level - 1][$category->parent_id] ?? false) {
                        $levelCategories[$category->id] = [
                            $category->name,
                            $category->parent_id,
                            $hierarchicalCategories[$level - 1][$category->parent_id][2] . ">" . $category->name
                        ];
                        $hasMoreLevels = true;
                    }
                } else {
                    if ($category->parent_id == null) {
                        $levelCategories[$category->id] = [$category->name, $category->parent_id, $category->name];
                        $hasMoreLevels = true;
                    }
                }
            }
            if ($hasMoreLevels) {
                $hierarchicalCategories[$level] = $levelCategories;
                $level++;
            }
        };

        return $hierarchicalCategories;
    }

    public function pricePerQuantity(int $quantity, float $newPrice = null)
    {
        return number_format(($newPrice ?? $this->price) * $quantity, 2);
    }

    public function shouldBeSearchable()
    {
        //&& (!$this->variant_id || ($this->variant_id == $this->id))
        return (isset($this->attributes['hidden']) && !$this->attributes['hidden']);
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
        $array['price'] =  $this->price;
        $array['taxed_original_price'] = $this->applyTax($this->original_price);
        $array['taxed_selling_price'] = $this->applyTax($this->selling_price);
        $array['taxed_price'] =  $this->applyTax($this->price);
        $array['original_price_float'] = (float) $this->original_price;
        $array['selling_price_float'] = (float) $this->selling_price;
        $array['price_float'] =  (float) $this->price;
        $array['featured'] = $this->featured;
        $array['quantity'] = $this->quantity;
        $array['low_stock_threshold'] = $this->low_stock_threshold;
        $array['stock_status'] = $this->stock_status;
        $array['image'] = $this->image;
        $array['variant_id'] = $this->defaultVariant->id ?? $this->id;
        $array['has_variants'] = $this->defaultVariant()->exists() || $this->variants()->exists();
        $array['avg_rating'] = $this->avg_rating;
        $array['brand'] = $this->brand ? collect($this->brand->toArray())->only(['id', 'name', 'logo']) : null;
        $array['collections'] = $this->collections->map(function ($collection) {
            return $collection['name'];
        })->toArray();

        $array['hierarchicalCategories'] = [];
        foreach ($this->hierarchicalCategories() as $level => $categories) {
            $array['hierarchicalCategories']['lvl' . $level] = array();
            foreach ($categories as $category) {
                array_push($array['hierarchicalCategories']['lvl' . $level], $category[2]);
            }
        };

        $array['url'] = route('product.show', $this);

        return $array;
    }

    public function toSitemapTag(): Url | string | array
    {
        return route('product.show', $this);
    }
}
