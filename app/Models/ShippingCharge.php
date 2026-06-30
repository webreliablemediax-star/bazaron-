<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
    protected $fillable = [

    'zone_id',

    'weight_id',

    'charge',

    'shipping_gst',

    'total_charge',

    'cod_charge',

    'cod_gst',

    'total_charge_with_cod',
       'admin_charge',

        'admin_shipping_gst',

        'admin_total_charge',

        'admin_cod_charge',

        'admin_cod_gst',

        'admin_total_charge_with_cod',
        'admin_margin_shipping',
        'admin_margin_cod'

];



    public function zone()
    {
        return $this->belongsTo(ShippingZone::class);
    }


    public function weight()
    {
        return $this->belongsTo(ShippingWeight::class);
    }

 

}