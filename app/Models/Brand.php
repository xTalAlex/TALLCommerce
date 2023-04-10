<?php

namespace App\Models;

use App\Traits\WithSlug;
use App\Traits\Featurable;
use App\Traits\WithHeroAndLogo;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\WithProductsFilterQuery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model implements HasMedia
{
    use HasFactory, WithProductsFilterQuery, Featurable, WithSlug, WithHeroAndLogo;

    const MEDIA_PATH = "brands";

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

    // Relationships

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
}
