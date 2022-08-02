<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use \Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaObserver
{
    /**
     * Handle the Media "created" event.
     *
     * @param  \Spatie\MediaLibrary\MediaCollections\Models\Media  $media
     * @return void
     */
    public function created(Media $media)
    {
        if( $media->model && $media->model::class == "App\Models\Product" && $media->model->shouldBeSearchable() )
            $media->model->searchable();
            
    }

    /**
     * Handle the Media "updated" event.
     *
     * @param  \Spatie\MediaLibrary\MediaCollections\Models\Media  $media
     * @return void
     */
    public function updated(Media $media)
    {
        if( $media->model && $media->model::class == "App\Models\Product" && $media->model->shouldBeSearchable() )
            $media->model->searchable();
    }

    /**
     * Handle the Media "deleted" event.
     *
     * @param  \Spatie\MediaLibrary\MediaCollections\Models\Media  $media
     * @return void
     */
    public function deleted(Media $media)
    {
        if( $media->model && $media->model::class == "App\Models\Product" && $media->model->shouldBeSearchable() )
            $media->model->searchable();
    }

    /**
     * Handle the Media "restored" event.
     *
     * @param  \Spatie\MediaLibrary\MediaCollections\Models\Media  $media
     * @return void
     */
    public function restored(Media $media)
    {
        //
    }

    /**
     * Handle the Media "force deleted" event.
     *
     * @param  \Spatie\MediaLibrary\MediaCollections\Models\Media  $media
     * @return void
     */
    public function forceDeleted(Media $media)
    {
        //
    }
}
