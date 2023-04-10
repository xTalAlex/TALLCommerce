<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_status_id',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function status()
    {
       return $this->belongsTo(OrderStatus::class,'order_status_id'); 
    }

    // Accessors & Mutators

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn($value) => __($value),
        );
    }
}

