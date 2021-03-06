<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public $timestamps = false;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getNameAttribute($value)
    {
        return strtolower($value);
    }

    public function getLabelAttribute()
    {
        return __('general.order_statuses.'.($this->name));
    }

}
