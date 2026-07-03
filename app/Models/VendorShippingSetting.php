<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorShippingSetting extends Model
{
    use HasFactory;

    protected $table = 'vendor_shipping_settings';

    protected $fillable = [

        'user_id',

        'address_name',
        'full_address',
        'timezone',

        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',

        'standard_saturday',
        'standard_sunday',
        'express_saturday',
        'express_sunday',

        'cutoff_time',

        'handling_days',
        'delivery_type',   
        'holiday_year',
'bazaron_only',
'self_ship_only',
        'order_capacity',
        'bazaron_enabled',
'pickup_address',
'cod_enabled',
'free_shipping_threshold',
'default_package_weight',
'package_length',
'package_width',
'package_height',
 'local_regions',
        'local_transit_time',
        'local_shipping_fee_order',
        'local_shipping_fee_item',

        'regional_regions',
        'regional_transit_time',
        'regional_shipping_fee_order',
        'regional_shipping_fee_item',

        'national_regions',
        'national_transit_time',
        'national_shipping_fee_order',
        'national_shipping_fee_item',
        // Self Shipping

'template_name',
'is_default_template',

'same_day_enabled',
'one_day_enabled',
'two_day_enabled',

'expedited_regions',
'expedited_transit_time',
'expedited_shipping_fee_order',
'expedited_shipping_fee_item',

'standard_zone1_regions',
'standard_zone1_transit_time',
'standard_zone1_fee_order',
'standard_zone1_fee_item',

'standard_zone2_regions',
'standard_zone2_transit_time',
'standard_zone2_fee_order',
'standard_zone2_fee_item',

'standard_zone3_regions',
'standard_zone3_transit_time',
'standard_zone3_fee_order',
'standard_zone3_fee_item',


'standard_zone4_regions',
'standard_zone4_transit_time',
'standard_zone4_fee_order',
'standard_zone4_fee_item',


'standard_zone5_regions',
'standard_zone5_transit_time',
'standard_zone5_fee_order',
'standard_zone5_fee_item'
    ];
}