<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPagePricing extends Model
{
    use HasFactory;

    protected $table = 'seller_page_pricing';

    protected $fillable = [
        'section_title',
        'feature_name',
        'feature_value',
        'display_order'
    ];
}