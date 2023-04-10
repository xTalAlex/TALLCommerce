<?php

namespace App\Models;

use App\Traits\WithSlug;
use App\Traits\Featurable;
use App\Traits\WithHeroAndLogo;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\WithProductsFilterQuery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model implements HasMedia
{
    use HasFactory, Featurable, WithProductsFilterQuery, WithSlug, WithHeroAndLogo;

    const MEDIA_PATH = "categories";

    protected $fillable = [
        'name',
        'slug',
        'description',
        'order',
        'featured',
        'parent_id',
    ];

    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // Scopes

    public function scopeMain($query){
        $query->whereDoesntHave('parent');
    }

    // Relationships

    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public function parent(){
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function children(){
        return $this->hasMany(Category::class,'parent_id');
    }

    // Utility

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