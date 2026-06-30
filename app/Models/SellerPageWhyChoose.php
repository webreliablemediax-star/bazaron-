<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPageWhyChoose extends Model
{
    use HasFactory;

    protected $table = 'seller_page_why_choose';

    protected $fillable = [
        'section_title',
        'section_description'
    ];
}