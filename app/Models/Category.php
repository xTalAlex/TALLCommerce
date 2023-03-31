<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const PATH = "categories";

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'featured',
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

    public function scopeMain($query){
        $query->whereDoesntHave('parent');
    }

    public function scopeFeatured($query)
    {
        $query->where('featured', true);
    }

    public function scopeFilterByProducts($query, array $filters)
    {
        $query->whereHas('products', function ($query) use ($filters) {
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
                    $filters['collection'] ?? false,
                    fn ($query) =>
                    $query->whereHas(
                        'collections',
                        fn ($query) =>
                        $query->whereIn('collections.id',  collect($filters['collection'])->map( fn($i) => (int)$i )->toArray() )
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

    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public function parent(){
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function children(){
        return $this->hasMany(Category::class,'parent_id');
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

    public function hierarchyPath()
    {
        $path = $this->name;
        $parent = $this->parent;
    
        while($parent) {
            $path=$parent->name.'>'.$path;
            $parent = $parent->parent;
        }
    
        return $path;
    }
}