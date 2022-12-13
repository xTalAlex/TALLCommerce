<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_address',
        'billing_address',
        'fiscal_code',
        'vat',
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
        'shipping_price',
        'avaiable_from'
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
        'invoice_serial_number',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'avaiable_from' => 'date',
        'total'      => 'decimal:2',
        'shipping_price' => 'decimal:2',
    ];

    public function status()
    {
        return $this->belongsTo(OrderStatus::class,'order_status_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('price', 'quantity', 'discount')->withoutGlobalScopes();
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
        return $this->belongsTo(ShippingPrice::class)->withoutGlobalScopes();
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

    protected function couponDiscount(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return number_format( $value , 2);
            },
        );
    }
    
    public function getNumberAttribute($value)
    {
        return $this->id + 1000;
    }

    public function getInvoiceSerialNumberAttribute($value)
    {
        return $this->invoice_sequence ? str_pad($this->invoice_sequence, config('invoices.serial_number.sequence_padding') , '0', STR_PAD_LEFT) 
            . config('invoices.serial_number.delimiter') 
            . $this->invoice_series : null;
    }

    // protected function number(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ( $value , $attributes ) => '#'. ($attributes['id'] + 1000),
    //     );
    // }

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

    public function setAsPaied()
    {
        $res = false;

        DB::transaction(function () {
            $status = OrderStatus::where('name','paied')->first();
            if ($status) {
                $lastInvoiceSequence= Order::where('invoice_series', today()->format('y'))->max('invoice_sequence') ?? 0;
                $this->status()->associate($status);
                $this->invoice_sequence = $lastInvoiceSequence + 1;
                $this->invoice_series = today()->format('y');
                $this->save();
                $this->history()->create([
                    'order_status_id' => $status->id,
                ]);
                $res = true;
            }
        });

        return $res;
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
