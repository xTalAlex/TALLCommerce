<?php

namespace App\Observers;

use App\Models\Collection;
use Illuminate\Support\Str;

class CollectionObserver
{
    /**
     * Handle the Collection "created" event.
     *
     * @param  \App\Models\Collection  $collection
     * @return void
     */
    public function created(Collection $collection)
    {
        $collection->update(['slug' => Str::slug($collection->name)]);

        $collection->products->filter(function ($item) {
            return $item->shouldBeSearchable();
        })->searchable();
    }

    /**
     * Handle the Collection "updated" event.
     *
     * @param  \App\Models\Collection  $collection
     * @return void
     */
    public function updated(Collection $collection)
    {
        $collection->slug = Str::slug($collection->name);
        $collection->saveQuietly();
    }

    /**
     * Handle the Collection "deleted" event.
     *
     * @param  \App\Models\Collection  $collection
     * @return void
     */
    public function deleted(Collection $collection)
    {
        //
    }

    /**
     * Handle the Collection "restored" event.
     *
     * @param  \App\Models\Collection  $collection
     * @return void
     */
    public function restored(Collection $collection)
    {
        //
    }

    /**
     * Handle the Collection "force deleted" event.
     *
     * @param  \App\Models\Collection  $collection
     * @return void
     */
    public function forceDeleted(Collection $collection)
    {
        //
    }
}
