<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorAdditionalDocument extends Model
{
    protected $fillable = [
        'vendor_id',
        'file_path',
        'status',
        'approved_by',
        'approved_at',
    ];
}