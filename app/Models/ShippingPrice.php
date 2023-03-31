<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingPrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'min_spend',
        'min_days',
        'max_days',
        'active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        $query->where('active', true);
    }

    public function scopeFast($query)
    {
        $query->where('max_days','!=',null)->where('max_days','<=',2);
    }

    public function isFast()
    {
        return $this->max_days != null && $this->max_days <= 2;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function deliveryTimeLabel()
    {
        $label = null;
        if($this->min_days)
        {
            $label = $this->min_days == $this->max_days ? 
                $this->min_days . ' ' . ($this->min_days == 1 ? strtolower(__('Day')) : strtolower(__('Days')))  : 
                $this->min_days . '-' . $this->max_days . ' ' . strtolower(__('Days')) ;
        }
        return $label;
    }
}
