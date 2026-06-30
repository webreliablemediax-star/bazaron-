<?php

namespace App\Http\Controllers\Vendor;
use App\Models\VendorShippingSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorProfile;
use App\Models\VendorProfileUpdateRequest;

class VendorDashboardController extends Controller
{
    public function index()
{
    return redirect('/admin');
}

    // 👉 Page open
    public function invoiceConfig()
    {
        $vendor = VendorProfile::where('user_id', auth()->id())->first();

        return view('vendor.invoice_config', compact('vendor'));
    }
    public function profileSettings()
{
    $vendor = VendorProfile::where('user_id', auth()->id())->first();

    return view('vendor.profile_settings', compact('vendor'));
}
public function profileSettingsUpdate(Request $request)
{
    $vendor = VendorProfile::where('user_id', auth()->id())->first();

    $changes = [];

    foreach ($request->except('_token') as $field => $newValue) {

        $oldValue = $vendor->$field;

        if ((string)$oldValue != (string)$newValue) {

            $changes[$field] = [

                'old' => $oldValue,

                'new' => $newValue

            ];
        }
    }

    if (empty($changes)) {

        return back()->with(
            'error',
            'No changes detected.'
        );
    }

    VendorProfileUpdateRequest::create([

        'vendor_id' => auth()->id(),

        'section' => 'profile',

        'request_data' => $changes,

        'status' => 'pending'

    ]);

    return back()->with(
        'success',
        'Profile update request sent successfully. Waiting for admin approval.'
    );
}

public function shipmentSettings()
{
    $shipping = VendorShippingSetting::firstOrCreate(
        ['user_id' => auth()->id()]
    );

    return view('vendor.shipment_settings', compact('shipping'));
}
public function shipmentSettingsUpdate(Request $request)
{
    $shipping = VendorShippingSetting::firstOrCreate(
        ['user_id' => auth()->id()]
    );

    $data = $request->except('_token');

    // Operating Days
    $data['monday'] = $request->has('monday') ? 1 : 0;
    $data['tuesday'] = $request->has('tuesday') ? 1 : 0;
    $data['wednesday'] = $request->has('wednesday') ? 1 : 0;
    $data['thursday'] = $request->has('thursday') ? 1 : 0;
    $data['friday'] = $request->has('friday') ? 1 : 0;
    $data['saturday'] = $request->has('saturday') ? 1 : 0;
    $data['sunday'] = $request->has('sunday') ? 1 : 0;

    // Ship Through
    $data['bazaron_only'] = $request->has('bazaron_only') ? 1 : 0;
    $data['self_ship_only'] = $request->has('self_ship_only') ? 1 : 0;

    $data['bazaron_enabled'] = $request->has('bazaron_enabled') ? 1 : 0;
    $data['cod_enabled'] = $request->has('cod_enabled') ? 1 : 0;
    $data['is_default_template'] = $request->has('is_default_template') ? 1 : 0;

    $data['same_day_enabled'] = $request->has('same_day_enabled') ? 1 : 0;
    $data['one_day_enabled'] = $request->has('one_day_enabled') ? 1 : 0;
    $data['two_day_enabled'] = $request->has('two_day_enabled') ? 1 : 0;
    // Self Shipping
    $data['is_default_template'] = $request->has('is_default_template') ? 1 : 0;

    $data['same_day_enabled'] = $request->has('same_day_enabled') ? 1 : 0;
    $data['one_day_enabled'] = $request->has('one_day_enabled') ? 1 : 0;
    $data['two_day_enabled'] = $request->has('two_day_enabled') ? 1 : 0;

    $shipping->update($data);
    return back()->with('success', 'Shipment settings updated successfully');
}


    // 👉 Save data
    public function invoiceConfigSave(Request $request)
    {
        $vendor = VendorProfile::where('user_id', auth()->id())->first();

        $vendor->invoice_prefix = $request->invoice_prefix;

        // 🔥 IMPORTANT LOGIC
        // agar tu chahta hai same number se start ho
        $vendor->invoice_last_number = $request->invoice_serial - 1;

        $vendor->save();

        return back()->with('success', 'Invoice updated successfully');
    }
}