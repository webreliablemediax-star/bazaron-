<?php

namespace App\Http\Controllers\Backend\Shipping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ShippingWeight;
use App\Models\ShippingZone;
use App\Models\ShippingCharge;
use App\Models\Gst;

class ShippingController extends Controller
{

    // =========================================================
    // WEIGHT MASTER
    // =========================================================

    public function weight()
    {
        $weights = ShippingWeight::orderBy('id','asc')->get();

        return view(
            'backend.pages.shipping.weight',
            compact('weights')
        );
    }

    public function weightStore(Request $request)
{
    ShippingWeight::create([

        'title' => $request->title,

        'min_weight' => $request->min_weight,

        'max_weight' => $request->max_weight,

        'unit' => $request->unit,

    ]);

    return back()->with('success', 'Weight Added');
}


    // EDIT WEIGHT
    public function weightEdit($id)
    {
        $weight = ShippingWeight::findOrFail($id);

        $weights = ShippingWeight::latest()->get();

        return view(
            'backend.pages.shipping.weight',
            compact('weight', 'weights')
        );
    }


    // UPDATE WEIGHT
   public function weightUpdate(Request $request, $id)
{
    $weight = ShippingWeight::findOrFail($id);

    $weight->update([

        'title' => $request->title,

        'min_weight' => $request->min_weight,

        'max_weight' => $request->max_weight,

        'unit' => $request->unit,

    ]);

    return redirect()
        ->route('admin.shipping.weight')
        ->with('success', 'Weight Updated');
}


    // DELETE WEIGHT
    public function weightDelete($id)
    {
        ShippingWeight::findOrFail($id)->delete();

        return back()->with('success', 'Weight Deleted');
    }



    // =========================================================
    // ZONE MASTER
    // =========================================================

    public function zone()
    {
        $zones = ShippingZone::orderBy('id','asc')->get();

        return view(
            'backend.pages.shipping.zone',
            compact('zones')
        );
    }

    public function zoneStore(Request $request)
    {
        ShippingZone::create([

            'zone_name' => $request->zone_name,
            'definition' => $request->definition,

        ]);

        return back()->with('success', 'Zone Added');
    }


    // EDIT ZONE
    public function zoneEdit($id)
    {
        $zone = ShippingZone::findOrFail($id);

        $zones = ShippingZone::latest()->get();

        return view(
            'backend.pages.shipping.zone',
            compact('zone', 'zones')
        );
    }


    // UPDATE ZONE
    public function zoneUpdate(Request $request, $id)
    {
        $zone = ShippingZone::findOrFail($id);

        $zone->update([

            'zone_name' => $request->zone_name,
            'definition' => $request->definition,

        ]);

        return redirect()
            ->route('admin.shipping.zone')
            ->with('success', 'Zone Updated');
    }


    // DELETE ZONE
    public function zoneDelete($id)
    {
        ShippingZone::findOrFail($id)->delete();

        return back()->with('success', 'Zone Deleted');
    }



// =========================================================
// SHIPPING CHARGES
// =========================================================

public function charge()
{
    $zones = ShippingZone::orderBy('id', 'asc')->get();
    $weights = ShippingWeight::orderBy('id', 'asc')->get();

    // Sort ShippingCharge by related zone_name
    $charges = ShippingCharge::with(['zone', 'weight'])
        ->join('shipping_zones', 'shipping_charges.zone_id', '=', 'shipping_zones.id')
        ->orderBy('shipping_zones.zone_name', 'asc') // <-- use zone_name column
        ->select('shipping_charges.*')
        ->get();

    return view(
        'backend.pages.shipping.charge',
        compact('zones', 'weights', 'charges')
    );
}




// =========================================================
// STORE CHARGE
// =========================================================

public function chargeStore(Request $request)
{

    $gst = Gst::first();

    $gstPercent = (float) ($gst->tax ?? 0);

    $charge = (float) $request->charge;

    $codCharge = (float) $request->cod_charge;


    // =====================================================
    // ADMIN VALUES
    // =====================================================

    $adminCharge = (float) $request->admin_charge;

    $adminCodCharge = (float) $request->admin_cod_charge;



    // =====================================================
    // SHIPPING GST
    // =====================================================

    $shippingGst = ($charge * $gstPercent) / 100;

    $totalShipping = $charge + $shippingGst;



    // =====================================================
    // COD GST
    // =====================================================

    $codGst = ($codCharge * $gstPercent) / 100;

    $totalCod = $codCharge + $codGst;



    // =====================================================
    // ADMIN SHIPPING GST
    // =====================================================

    $adminShippingGst = ($adminCharge * $gstPercent) / 100;

    $adminTotalShipping = $adminCharge + $adminShippingGst;



    // =====================================================
    // ADMIN COD GST
    // =====================================================

    $adminCodGst = ($adminCodCharge * $gstPercent) / 100;

    $adminTotalCod = $adminCodCharge + $adminCodGst;

   $adminMarginShipping = $totalShipping - $adminTotalShipping;

$adminMarginCod =  $totalCod - $adminTotalCod;


    ShippingCharge::create([

        'zone_id' => $request->zone_id,

        'weight_id' => $request->weight_id,



        // =================================================
        // SHIPPING
        // =================================================

        'charge' => $charge,

        'shipping_gst' => $shippingGst,

        'total_charge' => $totalShipping,



        // =================================================
        // COD
        // =================================================

        'cod_charge' => $codCharge,

        'cod_gst' => $codGst,

        'total_charge_with_cod' => $totalCod,



        // =================================================
        // ADMIN SHIPPING
        // =================================================

        'admin_charge' => $adminCharge,

        'admin_shipping_gst' => $adminShippingGst,

        'admin_total_charge' => $adminTotalShipping,



        // =================================================
        // ADMIN COD
        // =================================================

        'admin_cod_charge' => $adminCodCharge,

        'admin_cod_gst' => $adminCodGst,

        'admin_total_charge_with_cod' => $adminTotalCod,

        'admin_margin_shipping' => $adminMarginShipping,

        'admin_margin_cod' => $adminMarginCod

    ]);


    return back()->with(
        'success',
        'Shipping Charge Added Successfully'
    );
}




// =========================================================
// EDIT CHARGE
// =========================================================

public function chargeEdit($id)
{
    $charge = ShippingCharge::findOrFail($id);

    $zones = ShippingZone::orderBy('id', 'asc')->get();

    $weights = ShippingWeight::orderBy('id', 'asc')->get();

    $charges = ShippingCharge::with(
        'zone',
        'weight'
    )->latest()->get();

    return view(
        'backend.pages.shipping.charge',
        compact(
            'charge',
            'zones',
            'weights',
            'charges'
        )
    );
}




// =========================================================
// UPDATE CHARGE
// =========================================================

public function chargeUpdate(Request $request, $id)
{

    $chargeData = ShippingCharge::findOrFail($id);

    $gst = Gst::first();

    $gstPercent = (float) ($gst->tax ?? 0);

    $charge = (float) $request->charge;

    $codCharge = (float) $request->cod_charge;



    // =====================================================
    // ADMIN VALUES
    // =====================================================

    $adminCharge = (float) $request->admin_charge;

    $adminCodCharge = (float) $request->admin_cod_charge;



    // =====================================================
    // SHIPPING GST
    // =====================================================

    $shippingGst = ($charge * $gstPercent) / 100;

    $totalShipping = $charge + $shippingGst;



    // =====================================================
    // COD GST
    // =====================================================

    $codGst = ($codCharge * $gstPercent) / 100;

    $totalCod = $codCharge + $codGst;



    // =====================================================
    // ADMIN SHIPPING GST
    // =====================================================

    $adminShippingGst = ($adminCharge * $gstPercent) / 100;

    $adminTotalShipping = $adminCharge + $adminShippingGst;



    // =====================================================
    // ADMIN COD GST
    // =====================================================

    $adminCodGst = ($adminCodCharge * $gstPercent) / 100;

    $adminTotalCod = $adminCodCharge + $adminCodGst;

   $adminMarginShipping =   $totalShipping - $adminTotalShipping;

    $adminMarginCod =$totalCod - $adminTotalCod;

    $chargeData->update([

        'zone_id' => $request->zone_id,

        'weight_id' => $request->weight_id,



        // =================================================
        // SHIPPING
        // =================================================

        'charge' => $charge,

        'shipping_gst' => $shippingGst,

        'total_charge' => $totalShipping,



        // =================================================
        // COD
        // =================================================

        'cod_charge' => $codCharge,

        'cod_gst' => $codGst,

        'total_charge_with_cod' => $totalCod,



        // =================================================
        // ADMIN SHIPPING
        // =================================================

        'admin_charge' => $adminCharge,

        'admin_shipping_gst' => $adminShippingGst,

        'admin_total_charge' => $adminTotalShipping,



        // =================================================
        // ADMIN COD
        // =================================================

        'admin_cod_charge' => $adminCodCharge,

        'admin_cod_gst' => $adminCodGst,

        'admin_total_charge_with_cod' => $adminTotalCod,

        'admin_margin_shipping' => $adminMarginShipping,

        'admin_margin_cod' => $adminMarginCod

    ]);


    return redirect()
        ->route('admin.shipping.charge')
        ->with(
            'success',
            'Shipping Charge Updated Successfully'
        );
}




// =========================================================
// DELETE CHARGE
// =========================================================

public function chargeDelete($id)
{
    ShippingCharge::findOrFail($id)->delete();

    return back()->with(
        'success',
        'Shipping Charge Deleted Successfully'
    );
}
}