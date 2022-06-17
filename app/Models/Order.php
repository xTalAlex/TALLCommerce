<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_address',
        'billing_address',
        'message',
        'tracking_number',
        'payment_gateway',
        'payment_id',
        'email',
        'phone',
        'order_status_id',
        'user_id',
        'subtotal',
        'tax',
        'total',
        'coupon_id',
        'coupon_discount',
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

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
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

    public function getIdAttribute($value)
    {
        return $value;
    }

    public function statusCanBecome(String $status)
    {
        $can = false;

        switch(ucfirst(strtolower($status)))
        {
            case('Payment_failed'):
                $can = $this->status->name == 'Pending';
                break;
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

    public function canBeDeleted()
    {
        $deletable_statuses = OrderStatus::whereIn('name',['payment_failed'])->get();

        return $deletable_statuses->contains($this->status);
    }

    public function canBePaied()
    {
        $payable_statuses = OrderStatus::whereIn('name',['payment_failed'])->get();

        return $payable_statuses->contains($this->status);
    }

    public function canBeEdited()
    {
        $editabled_statuses = OrderStatus::whereIn('name',['pending','payment_failed','paied'])->get();

        return $editabled_statuses->contains($this->status);
    }

    public function canBeInvoiced()
    {
        strtolower($this->status->name) !='pending' && strtolower($this->status->name) !='payment_failed';
    }
}
