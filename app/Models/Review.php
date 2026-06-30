<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $guarded = [];

    // ⭐ Casts (important for ratings & flags)
    protected $casts = [
        'rating' => 'integer',
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
        'helpful_count' => 'integer',
    ];

    // ⭐ Relation → Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // ⭐ Relation → User (review author)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
