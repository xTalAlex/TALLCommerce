<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'address',
        'city',
        'province',
        'country_region',
        'postal_code',
        'billing',
        'default',
        'user_id'
    ];

    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //  Accessors & Mutators

    protected function province(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return strtoupper($value);
            },
        );
    }

    // Utility

    public function sameAddress($otherAddress)
    {
        return $this->full_name == $otherAddress->full_name &&
            $this->address == $otherAddress->address &&
            $this->city == $otherAddress->city &&
            $this->province == $otherAddress->province &&
            $this->postal_code == $otherAddress->postal_code &&
            $this->country_region == $otherAddress->country_region;
    }

    public function toString()
    {
        return $this->full_name.' | '.$this->address.', '.$this->city.' ('.$this->province.'), '.$this->postal_code.' '.$this->country_region; 
    }
}
