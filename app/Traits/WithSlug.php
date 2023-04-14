<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait WithSlug
{
    protected function slug(): Attribute
    {
        return Attribute::make(
            set: function(string|null $value) {
                if (static::whereNot('id',$this->id)->whereSlug($slug = Str::slug($value))->exists())
                    $slug = "{$slug}-{$this->id}";
                return [ 'slug' => $slug ];
            }
        );
    }
}