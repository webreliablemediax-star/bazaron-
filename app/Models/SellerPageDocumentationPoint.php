<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPageDocumentationPoint extends Model
{
    use HasFactory;

    protected $table = 'seller_page_documentation_points';

    protected $fillable = [
        'title',
        'icon',
        'display_order'
    ];
}