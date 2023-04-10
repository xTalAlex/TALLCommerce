<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'is_fixed_amount',
        'amount',
        'redemptions',
        'max_redemptions',
        'expires_on',
        'min_total',
        'once_per_user'
    ];

    protected $appends = [
        'label',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'amount' => 'decimal:2',
        'min_total' => 'decimal:2',
    ];


    // Relationships

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Accessors & Mutators

    protected function code(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtoupper($value),
            set: fn ($value) => strtoupper($value),
        );
    }

    protected function label(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->is_fixed_amount ? '€' : '').$this->amount.($this->is_fixed_amount ? '' : '%'),
        );
    }

    // Utility

    public function appliesBeforeTax()
    {
        return config('custom.discount_before_tax') && !$this->is_fixed_amount;
    }

    public function discount($total)
    {
        $discount = 0;

        if($this->is_fixed_amount)
        {
            $discount = $total > $this->amount ? $this->amount : $total;
        }
        else
        {
            $discount = round($total * ($this->amount/100),2);
        }

        return $discount;
    }

    public function wasUsedBy(User $user)
    {
        return $this->orders()
            ->whereHas('status', fn($query) => $query->whereNotIn('name',['draft','cancelled']) )
            ->whereHas('user', fn($query) => $query->where('id',$user->id))->exists();
    }
}
