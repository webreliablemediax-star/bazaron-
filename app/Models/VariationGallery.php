<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariationGallery extends Model
{
    protected $fillable = [
        'product_id',
        'variation_combination_id',
        'image'
        // 'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function combination()
    {
        return $this->belongsTo(ProductVariationCombination::class, 'variation_combination_id');
    }
}