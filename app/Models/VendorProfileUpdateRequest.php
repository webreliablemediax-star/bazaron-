<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProfileUpdateRequest extends Model
{
    use HasFactory;

    protected $fillable = [

        'vendor_id',
        'section',
        'request_data',
        'status',
        'approved_by',
        'approved_at'

    ];

    protected $casts = [

        'request_data' => 'array'

    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}