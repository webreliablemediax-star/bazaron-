<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gst_number',
        'warehouse_address',
        'city',
        'state',
        'zip',
        'is_default'
    ];

    // relation with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}