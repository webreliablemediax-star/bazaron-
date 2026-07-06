<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorHoliday extends Model
{
    protected $fillable = [
        'vendor_id',
        'holiday_name',
        'holiday_date',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}