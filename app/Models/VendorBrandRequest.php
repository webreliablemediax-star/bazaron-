<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorBrandRequest extends Model
{
    use HasFactory;

    protected $table = 'vendor_brand_requests';

    protected $fillable = [
        'user_id',
        'brand_name',
        'logo',
        'product_image',
        'packaging_image',
        'hand_image',
        'description',
        'status',
        'approved_by',
        'rejection_reason'
    ];
}
