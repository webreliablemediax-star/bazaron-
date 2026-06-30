<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPageWhyChoosePoint extends Model
{
    use HasFactory;

    protected $table = 'seller_page_why_choose_points';

    protected $fillable = [
        'title',
        'display_order'
    ];
}