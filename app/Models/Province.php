<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'region',
        'country',
        'is_active'
    ];

    public function scopeActive($query)
    {
        $query->where('is_active',true);
    }

    public function isActive()
    {
        return $this->is_active;
    }
}
