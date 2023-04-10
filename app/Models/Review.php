<?php

namespace App\Models;

use App\Models\Scopes\ApprovedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'description',
        'approved',
        'product_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'approved' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new ApprovedScope);
    }

    // Relationships
    
    public function product()
    {
        return $this->belongsTo(Product::class)->withoutGlobalScopes();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
