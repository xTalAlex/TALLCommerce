<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phone',
        'full_name',
        'company',
        'address',
        'address2',
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

    /**
     * 
     *      Print with {!!  !!}
     * 
     */
    public function getLabelAttribute()
    {
        $label=null;
        
        $label="$this->full_name";
        if ($this->full_name && $this->company) {
            $label.="($this->company)";
        } elseif (!$this->full_name) {
            $label.="$this->company";
        }

        if(Str::length($label)) $label.="\n";

        $label.="$this->address";
        if ($this->address2) {
            $label.="($this->address2)";
        }
        $label.="";

        if(Str::length($label)) $label.="\n";
        $label.="$this->city";
        if($this->province)
            $label.=" ($this->province)";
        if($this->postal_code && ($this->city || $this->province))
            $label.=", ";
        $label.="$this->postal_code";

        if(Str::length($label)) $label.="\n";
        $label.="$this->country_region";

        return $label ? nl2br(e($label)) : null;
    }
}
