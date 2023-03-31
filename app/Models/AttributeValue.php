<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value',
    ];

    protected $appends = [
        'label',
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            if($model->products)
                optional(
                    $model->products->filter(function ($item) {
                        return $item->shouldBeSearchable();
                    })
                )->searchable();
        });
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public static function getLabel($name,  $value){
        $label = $value;
        switch(strtolower($name)){
            case('pz'):
                    $label = $value.'pz';
                break;
            case('gr'):
            case('g'):
                if($value < 1000)
                    $label = $value.'g';
                else
                    $label = round($value/1000,2).'kg';
                break;
            case('kg'):
                $label = $value.'Kg';
                break;
            default:
                break;
        }
        return $label;
    }

    public function getLabelAttribute()
    {
        return $this->getLabel($this->attribute->name, $this->value);
    }
}
