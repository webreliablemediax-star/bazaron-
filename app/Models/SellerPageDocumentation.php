<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPageDocumentation extends Model
{
    use HasFactory;

    protected $table = 'seller_page_documentations';

    protected $fillable = [
        'section_title',
        'section_description'
    ];
}