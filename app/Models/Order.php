<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_address',
        'billing_address',
        'message',
        'tracking_number',
        'payment_type',
        'payment_id',
        'email',
        'phone',
        'order_status_id',
        'user_id',
        'total',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'shipping_label',
        'billing_label',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'total'      => 'decimal:2',
    ];

    public function status()
    {
        return $this->belongsTo(OrderStatus::class,'order_status_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('price', 'quantity');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress()
    {
        return Address::make(
            collect(
                json_decode($this->shipping_address)
            )->toArray()
        );
    }

    public function billingAddress()
    {
        return Address::make(
            collect(
                json_decode($this->billing_address)
            )->toArray()
        );
    }

    public function getShippingLabelAttribute()
    {
        return $this->shippingAddress()->label;
    }

    public function getBillingLabelAttribute()
    {
        return $this->billingAddress()->label;
    }

    public function statusCanBecome(String $status)
    {
        $can = false;

        switch(ucfirst(strtolower($status)))
        {
            case('Paied'):
                $can = $this->status->name == 'Pending';
                break;
            case('Shipped'):
                $can = $this->status->name == 'Paied';
                break;
            case('Completed'):
                $can = $this->status->name == 'Paied' || $this->status->name == 'Shipped';
                break;
            case('Refunded'):
                $can = $this->status->name == 'Completed' || $this->status->name == 'Shipped'  || $this->status->name == 'Paied';
                break;
            case('Cancelled'):
                $can = $this->status->name == 'Paid';
                break;
        }

        return $can;
    }
}
