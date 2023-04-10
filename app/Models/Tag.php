<?php

namespace App\Models;

use App\Traits\WithSlug;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory, WithSlug;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // Relationships

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
