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
        'note',
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
        'shipping_price_id',
        'shipping_price'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'shipping_label',
        'billing_label',
        'number ',
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
        'shipping_price' => 'decimal:2',
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

    public function history()
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function shippingPrice()
    {
        return $this->belongsTo(ShippingPrice::class);
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

    public function getNumberAttribute()
    {
        return '#'. ($this->id + 1000) ;
    }

    public function statusCanBecome(String $status)
    {
        $can = false;

        switch(strtolower($status))
        {
            case('payment_failed'):
                $can = $this->status->name == 'pending';
                break;
            case('paied'):
                $can = $this->status->name == 'pending';
                break;
            case('preparing'):
                $can = $this->status->name == 'paied';
                break;
            case('shipped'):
                $can = $this->status->name == 'paied' || $this->status->name == 'preparing';
                break;
            case('completed'):
                $can = $this->status->name == 'paied' || $this->status->name == 'shipped';
                break;
            case('refunded'):
                $can = $this->status->name == 'completed' || $this->status->name == 'shipped'  || $this->status->name == 'paied';
                break;
            case('cancelled'):
                $can = $this->status->name == 'paid';
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
        return strtolower($this->status->name) !='pending' && strtolower($this->status->name) !='payment_failed';
    }

    public function restock()
    {
        foreach($this->products as $product)
        {
            $product->quantity += $product->pivot->quantity;
            $product->save();
        }

        if($this->coupon)
        {
            $this->coupon->redemptions --;
            $this->coupon->save();
        }
        
    }
}
