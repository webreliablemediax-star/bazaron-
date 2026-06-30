<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingWeight extends Model
{
    protected $fillable = [

        'title',

        'min_weight',

        'max_weight',
        'unit',

    ];
}
