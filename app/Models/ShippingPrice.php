<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $appends = [
        'delivery_time_label'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];

    // Scopes

    public function scopeActive($query)
    {
        $query->where('active', true);
    }

    public function scopeFast($query)
    {
        $query->where('max_days','!=',null)->where('max_days','<=',2);
    }

    // Relationships

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    //  Accessors & Mutators

    protected function deliveryTimeLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $label = null;
                if($this->min_days)
                {
                    $label = $this->min_days == $this->max_days ? 
                        $this->min_days . ' ' . ($this->min_days == 1 ? strtolower(__('Day')) : strtolower(__('Days')))  : 
                        $this->min_days . '-' . $this->max_days . ' ' . strtolower(__('Days')) ;
                }
                return $label;
            },
        );
    }

    // Utility

    public function isFast()
    {
        return $this->max_days != null && $this->max_days <= 2;
    }
}
