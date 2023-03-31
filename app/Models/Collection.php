<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Scopes\NotHiddenScope;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collection extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const PATH = "collections";

    protected $fillable = [
        'name',
        'slug',
        'description',
        'brand_id',
        'featured',
        'hidden',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'hero',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero')
            ->useDisk(config('media-library.disk_name'))
            ->singleFile();
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

    public function scopeFilterByProducts($query, array $filters)
    {
        $query->whereHas('products', function ($query) use ($filters) {
                $query->when(
                    $filters['category'] ?? false,
                    fn ($query) =>
                    $query->whereHas(
                        'categories',
                        fn ($query) =>
                        $query->where('categories.id',  (int) $filters['category'])
                            ->orWhere('categories.slug', insensitiveLike(), $filters['category'])
                    )
                );

                $query->when(
                    $filters['brand'] ?? false,
                    fn ($query) =>
                    $query->whereHas(
                        'brand',
                        fn ($query) =>
                        $query->whereIn('brands.id',  collect($filters['brand'])->map( fn($i) => (int)$i )->toArray() )
                            ->orWhereIn('brands.slug', $filters['brand'])
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

                return $query;
            }
        );

    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function getHeroAttribute()
    {
        return $this->getFirstMediaUrl('hero');
    }

    public function setHeroAttribute($value)
    {
        if($value) $this->addMedia($value)->toMediaCollection('hero');
    }

    public function setSlugAttribute($value)
    {
        if (static::whereNot('id',$this->id)->whereSlug($slug = Str::slug($value))->exists())
        {
            $slug = "{$slug}-{$this->id}";
        }
            
        $this->attributes['slug'] = $slug;
    } 
}
