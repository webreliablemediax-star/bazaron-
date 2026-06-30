<?php

namespace App\Models;
use App\Models\User;
use App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class PurchaseQuantityRequest extends Model
{
    protected $fillable = [
        'seller_id',
        'product_id',
        'old_quantity',
        'requested_quantity',
        'status',
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