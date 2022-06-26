<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_status_id',
        'description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function status()
    {
       return $this->belongsTo(OrderStatus::class,'order_status_id'); 
    }

    public function getDescriptionAttribute($value)
    {
        /**
         * 
         *  Addresses Updated
         *  New Payment Intent
         * 
         * **/

        return __($value);
    }
}

