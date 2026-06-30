<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPageFeature extends Model
{
    use HasFactory;

    protected $table = 'seller_page_features';

    protected $fillable = [
        'title',
        'description',
        'icon',
        'display_order'
    ];

}