<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $label="$this->full_name";
        if($this->full_name && $this->company) $label.="($this->company)";
        elseif(!$this->full_name) $label.="$this->company";

        $label.="\n";
        $label.="$this->address";
        if($this->address2) $label.="($this->address2)";
        $label.="";

        $label.="\n";
        $label.="$this->city ($this->province), $this->postal_code";

        $label.="\n";
        $label.="$this->country_region";

        return nl2br(e($label));
    }
}
