<?php

namespace App\Traits;

use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait WithHeroAndLogo
{
    use InteractsWithMedia;
    
    public function initializeWithHeroAndLogo()
    {
        $this->append('hero');
        $this->append('logo');
        $this->append('logo_gray');
    }

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

    public function scopeLogoed($query)
    {
        $query->whereHas('media', fn ($query) => $query->whereCollectionName('logo'));
    }

    protected function hero(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl('hero'),
            set: fn (string|UploadedFile $value) => $this->addMedia($value)->toMediaCollection('hero'),
        );
    }

    protected function logo(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getFirstMediaUrl('logo'),
            set: fn (string|UploadedFile $value) => $this->addMedia($value)->toMediaCollection('logo'),
        );
    }

    protected function logoGray(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getFirstMediaUrl('logo','logo-gray')
        );
    }
}
