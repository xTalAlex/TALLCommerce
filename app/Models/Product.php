<?php

namespace App\Models;

use App\Traits\WithSlug;
use App\Traits\Featurable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Scopes\NotHiddenScope;
use Illuminate\Database\Eloquent\Model;
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
    use HasFactory, InteractsWithMedia, SoftDeletes, HasSEO, Featurable, WithSlug;

    const PATH = "products";

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'original_price',
        'selling_price',
        'tax_rate',
        'quantity',
        'low_stock_threshold',
        'weight',
        'featured',
        'hidden',
        'avaiable_from',
        'variant_id',
        'brand_id',
    ];

    protected $appends = [
        'image',
        'gallery',
        'taxed_price',
        'taxed_original_price',
        'taxed_selling_price',
        'discount'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'avaiable_from' => 'date',
        'original_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'price'         => 'decimal:2',
        'taxed_price' => 'decimal:2',
        'taxed_original_price' => 'decimal:2',
        'taxed_selling_price' => 'decimal:2',
        'avg_rating' => 'decimal:1',
        'tags' => 'array',
        'hidden' => 'boolean',
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

    public function toSitemapTag(): Url | string | array
    {
        return route('product.show', $this);
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

    protected static function booted()
    {
        static::addGlobalScope(new NotHiddenScope);
    }

    // Scopes

    public function scopeFilter($query, array $filters)
    {
        $query->with(['categories','brand','collections'])
            ->whereNull('variant_id');

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

    // Relationships

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

    // Accessors & Mutators

    protected function stockStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->quantity < 1) {
                    $status = __('Out of Stock');
                } elseif ($this->isLowStock()) {
                    $status = __('Low Stock');
                } else {
                    $status = trans_choice('Avaiable', 1);
                }
        
                return $status;
            },
        );
    }

    protected function avgRating(): Attribute
    {
        return Attribute::make(
            get: function () {
                $defaultVariant = $this->variant_id ?  $this->defaultVariant : $this;
                $avg = Review::where('product_id', $defaultVariant->id)->orWhereIn('product_id',$defaultVariant->variants()->pluck('id'))->avg('rating');
                return $avg ? round($avg, 1) : null;
            },
        );
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn() =>  $this->hasImage() ? 
                $this->getFirstMediaUrl(
                    'gallery', 
                    config('custom.use_watermark') ? 'watermarked' : 'default'
                ) : asset('img/no_image.webp')
        );
    }

    protected function gallery(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getMedia('gallery')->map(fn ($media) => 
                    $media->getAvailableFullUrl(config('custom.use_watermark') ? ['watermarked', 'default'] : ['default'])
                ),
            set: function (mixed $value) {
                if ($value) {
                    $fileAdders = $this->addMultipleMediaFromRequest($value);
                    foreach ($fileAdders as $fileAdder) {
                        $fileAdder->toMediaCollection('gallery');
                    }
                }
            } 
        );
    }

    protected function originalPrice(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => number_format($value, 2),
        );
    }

    protected function variantId(): Attribute
    {
        return Attribute::make(
            set: fn ($value, $attributes) => $value == $attributes['id'] ? null : $value,
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
                return isset($attributes['selling_price']) && $attributes['selling_price'] && $attributes['selling_price'] != $attributes['original_price'] ?
                    $attributes['selling_price'] : $attributes['original_price'];
            },
        );
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
                return $attributes['taxed_selling_price'] ?? $this->applyTax($attributes['selling_price'] ?? $attributes['original_price']);
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

    protected function discount(): Attribute
    {
        return Attribute::make(
            get: function () {
                $difference = $this->selling_price && ($this->selling_price < $this->original_price) ? $this->original_price - $this->selling_price : 0;
                if ($difference)
                    $percent = round($this->original_price / $difference, 2);
                else
                    $percent = 0;
                return $percent;
            },
        );
    }

    // Utility

    public function isLowStock()
    {
        $low_stock_threshold = $this->low_stock_threshold ?? config('custom.stock_threshold');
        return $this->quantity >= 1 && $this->quantity <= $low_stock_threshold;
    }

    public function hasImage()
    {
        return $this->getFirstMediaUrl('gallery') != "";
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

    public function pricePerQuantity(int $quantity, float $newPrice = null)
    {
        return number_format(($newPrice ?? $this->price) * $quantity, 2);
    }

    public function attributes()
    {
        $attributeIds = $this->attributeValues()->with('attribute')->get()->pluck('attribute_id');
        return \App\Models\Attribute::findMany($attributeIds);
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
}