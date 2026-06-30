<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPageStep extends Model
{
    use HasFactory;

    protected $table = 'seller_page_steps';

    protected $fillable = [
        'step_number',
        'title',
        'description',
        'image'
    ];
}