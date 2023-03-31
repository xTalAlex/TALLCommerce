<?php

namespace App\Models;

use App\Models\Scopes\NotHiddenScope;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Brand extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const PATH = "brands";

    protected $fillable = [
        'name',
        'slug',
        'link',
        'featured',
    ];

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
        'logo',
        'logo_gray',
        'hero',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->useDisk(config('media-library.disk_name'))
            ->singleFile();

        $this->addMediaCollection('hero')
            ->useDisk(config('media-library.disk_name'))
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('logo-gray')
            ->greyscale()
            ->performOnCollections('logo');
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

                return $query;
            }
        );

    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function getHeroAttribute()
    {
        return $this->getFirstMediaUrl('hero');
    }

    public function setHeroAttribute($value)
    {
        if($value) $this->addMedia($value)->toMediaCollection('hero');
    }

    public function getLogoAttribute()
    {
        return $this->getFirstMediaUrl('logo');
    }

    public function getLogoGrayAttribute()
    {
        return $this->getFirstMediaUrl('logo','logo-gray');
    }

    public function setLogoAttribute($value)
    {
        if($value) $this->addMedia($value)->toMediaCollection('logo');
    }

    public function setSlugAttribute($value)
    {
        if (static::whereNot('id',$this->id)->whereSlug($slug = Str::slug($value))->exists())
            $slug = "{$slug}-{$this->id}";
        $this->attributes['slug'] = $slug;
    } 
}
