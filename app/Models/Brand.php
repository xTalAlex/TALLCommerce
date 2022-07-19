<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const PATH = "brands";

    protected $fillable = [
        'name',
        'link',
        'featured',
    ];

    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
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

    public function products()
    {
        return $this->hasMany(Product::class);
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

    public function setLogoAttribute($value)
    {
        if($value) $this->addMedia($value)->toMediaCollection('logo');
    }
}
