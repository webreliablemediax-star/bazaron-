<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPage extends Model
{
    use HasFactory;

    protected $table = 'seller_pages';

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_button_text',
        'hero_button_link',
        'hero_image',

        'cta_title',
        'cta_description',
        'cta_button_text',
        'cta_button_link',
        'cta_background',
        'features_description',
'steps_description',
'pricing_description',
'cta_image'
    ];
}