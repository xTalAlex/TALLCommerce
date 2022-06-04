<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const PATH = "categories";

    protected $fillable = [
        'name',
        'description',
        'parent_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero')
            ->singleFile();
    }

    public function scopeMain($query){
        $query->whereDoesntHave('parent');
    }

    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public function parent(){
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function children(){
        return $this->hasMany(Category::class,'parent_id');
    }

    public function getHeroAttribute()
    {
        return $this->getFirstMediaUrl('hero');
    }

    public function setHeroAttribute($value)
    {
        if($value)
        $this->addMediaFromDisk( str_replace("storage/","",$value) , config('platform.attachment.disk') )
            ->toMediaCollection('hero');
    }

}
