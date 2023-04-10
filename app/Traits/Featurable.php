<?php

namespace App\Traits;

trait Featurable
{
    public function scopeFeatured($query)
    {
        $query->where('featured', true);
    }
}