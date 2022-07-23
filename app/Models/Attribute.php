<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            \App\Models\Product::whereHas('attributeValues', fn($query) => 
                $query->where('attribute_id', $model->id) )
                ->get()
                ->filter(function ($item) {
                return $item->shouldBeSearchable();
            })->searchable();
        });
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
