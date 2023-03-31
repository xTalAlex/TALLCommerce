<?php

namespace App\Models;

use Illuminate\Support\Str;
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
        'user_id',
        'label',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'label',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function province(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return strtoupper($value);
            },
        );
    }

    public function sameAddress($otherAddress)
    {
        return $this->full_name == $otherAddress->full_name &&
            $this->address == $otherAddress->address &&
            $this->city == $otherAddress->city &&
            $this->province == $otherAddress->province &&
            $this->postal_code == $otherAddress->postal_code &&
            $this->country_region == $otherAddress->country_region;
    }

    /**
     * 
     *      Print with {!!  !!}
     * 
     */
    public function getLabelAttribute()
    {
        $label=null;
        
        $label="$this->full_name";

        if(Str::length($label)) $label.="\n";

        $label.="$this->address";

        if(Str::length($label)) $label.="\n";
        $label.="$this->city";
        if($this->province)
            $label.=" (".$this->province.")";
        if($this->postal_code && ($this->city || $this->province))
            $label.=", ";
        $label.="$this->postal_code";

        if(Str::length($label)) $label.="\n";
        $label.="$this->country_region";

        return $label ? nl2br(e($label)) : null;
    }
}
