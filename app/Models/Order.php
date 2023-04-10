<?php

namespace App\Models;

use App\Traits\WithOrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, WithOrderStatus;

    protected $guarded = ['*'];

    protected $appends = [
        'shipping_label',
        'billing_label'
    ];

    protected $casts = [
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
        'avaiable_from'   => 'date',
        'subtotal'        => 'decimal:2',
        'tax'             => 'decimal:2',
        'total'           => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'shipping_price'  => 'decimal:2',
    ];

    // Scopes
    
    public function scopePlaced($query)
    {
        $excluded_statuses = [ 'draft' ];
        
        $query->whereDoesntHave('status', fn($query) => $query->whereIn('name', $excluded_statuses) );
    }

    // Relationships

    public function status()
    {
        return $this->belongsTo(OrderStatus::class,'order_status_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('price', 'quantity', 'discount','tax_rate')->withoutGlobalScopes();
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

    // Accessors & Mutators

    protected function shippingLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->shippingAddress()?->toString(),
        );
    }

    protected function billingLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->billingAddress()?->toString(),
        );
    }

    protected function number(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->id + 1000,
        );
    }

    protected function province(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return strtoupper($value);
            },
        );
    }

    protected function fiscalCode(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return strtoupper($value);
            },
        );
    }
    
    protected function couponDiscount(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return number_format( $value , 2);
            },
        );
    }

    protected function invoiceSerialNumber(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->invoice_sequence ? str_pad($this->invoice_sequence, config('invoices.serial_number.sequence_padding') , '0', STR_PAD_LEFT) 
            . config('invoices.serial_number.delimiter') 
            . $this->invoice_series : null,
        );
    }

    public function shippingAddress()
    {
        return Address::make([
            'full_name' => $this->shipping_address_full_name,
            'address' => $this->shipping_address_address,
            'city' => $this->shipping_address_city,
            'province' => $this->shipping_address_province,
            'country_region' => $this->shipping_address_country_region,
            'postal_code' => $this->shipping_address_postal_code,
        ]);
    }

    public function billingAddress()
    {
        return Address::make([
            'full_name' => $this->billing_address_full_name,
            'address' => $this->billing_address_address,
            'city' => $this->billing_address_city,
            'province' => $this->billing_address_province,
            'country_region' => $this->billing_address_country_region,
            'postal_code' => $this->billing_address_postal_code,
        ]);
    }
    
    public function invoiceBuyer()
    {
        return [
            'name'          => $this->billingAddress()->full_name,
            'custom_fields' => [  
                'order_number' => '#'.$this->number,
                'email' => $this->billing_address_full_name,
                'address' => $this->billing_address_address.', '.
                    $this->billing_address_city. ' ('.$this->billing_address_province.'), '.
                    $this->billing_address_postal_code.', '.$this->billing_address_country_region,
                'fiscal_code' => $this->fiscal_code ? $this->fiscal_code : '-',
                'vat' => $this->vat ? $this->vat : '-',
            ],
        ];
    }

    public function restock()
    {
        if(config('custom.skip_quantity_checks')) {
            foreach ($this->products as $product) {
                $product->quantity += $product->pivot->quantity;
                $product->save();
            }
        }

        if($this->coupon)
        {
            $this->coupon->redemptions --;
            $this->coupon->save();
        }
        
    }
}
