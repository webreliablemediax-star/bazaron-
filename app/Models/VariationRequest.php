<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariationRequest extends Model
{
    protected $fillable = [
        'seller_id',
        'product_id',
        'variation_name',
        'variation_values',
        'status',
    ];
     protected $casts = [
        'variation_values' => 'array',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

   public function product()
{
    return $this->belongsTo(Product::class);
}
}