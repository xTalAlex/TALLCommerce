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


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero')
            ->useDisk(config('media-library.disk_name'))
            ->singleFile();
    }

    public function scopeMain($query){
        $query->whereDoesntHave('parent');
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
            $slug = "{$slug}-{$this->id}";
        $this->attributes['slug'] = $slug;
    } 

    public function ancestorsIds()
    {
        $ancestors = collect([]);

        $parent = $this->parent;
    
        while($parent) {
            $ancestors->push($parent);
            $parent = $parent->parent;
        }
    
        return $ancestors->map( fn($model) => $model->id);
    }
}
