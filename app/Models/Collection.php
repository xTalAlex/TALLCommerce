<?php

namespace App\Models;

use App\Traits\WithSlug;
use App\Traits\Featurable;
use App\Traits\WithHeroAndLogo;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Scopes\NotHiddenScope;
use App\Traits\WithProductsFilterQuery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collection extends Model implements HasMedia
{
    use HasFactory, WithSlug, Featurable, WithProductsFilterQuery, WithHeroAndLogo;

    const MEDIA_PATH = "collections";

    protected $fillable = [
        'name',
        'slug',
        'description',
        'featured',
        'hidden',
        'brand_id',
    ];

    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new NotHiddenScope);
    }

    // Relationships

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
