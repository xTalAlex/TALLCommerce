<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    protected $appends = [
        'label',
    ];

    public $timestamps = false;

    // Relationships

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Accessors & Mutators

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn($value) => strtolower($value),
        );
    }

    protected function label(): Attribute
    {
        return Attribute::make(
            get: fn() => __('general.order_statuses.'.($this->name)),
        );
    }
}
