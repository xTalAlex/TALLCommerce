<?php

namespace App\Observers;

use App\Models\Brand;
use Illuminate\Support\Str;

class BrandObserver
{
    /**
     * Handle the Brand "created" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function created(Brand $brand)
    {
        $brand->update(['slug' => Str::slug($brand->name)]);

        $brand->products->filter(function ($item) {
            return $item->shouldBeSearchable();
        })->searchable();
    }

    /**
     * Handle the Brand "updated" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function updated(Brand $brand)
    {
        $brand->slug = Str::slug($brand->name);
        $brand->saveQuietly();
    }

    /**
     * Handle the Brand "deleted" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function deleted(Brand $brand)
    {
        //
    }

    /**
     * Handle the Brand "restored" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function restored(Brand $brand)
    {
        //
    }

    /**
     * Handle the Brand "force deleted" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function forceDeleted(Brand $brand)
    {
        //
    }
}
