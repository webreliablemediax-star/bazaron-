<?php

namespace App\Http\Controllers\Vendor;
use App\Models\VendorShippingSetting;
use App\Http\Controllers\Controller;
use App\Models\VendorBrandRequest;
use App\Models\VendorAdditionalDocument;
use App\Models\VariationRequest;
use App\Models\PurchaseQuantityRequest;
use App\Models\VendorHoliday;
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

    $additionalDocuments = VendorAdditionalDocument::where(
        'vendor_id',
        auth()->id()
    )->latest()->get();

    return view(
        'vendor.profile_settings',
        compact('vendor', 'additionalDocuments')
    );
}
public function profileSettingsUpdate(Request $request)
{
    $vendor = VendorProfile::where('user_id', auth()->id())->first();

    $changes = [];
    $documentsUploaded = false;

    // Existing profile fields ka approval flow
    foreach ($request->except([
        '_token',
        'kyc_docs',
        'digital_signature',
        'additional_documents'
    ]) as $field => $newValue) {

        $oldValue = $vendor->$field;

        if ((string) $oldValue != (string) $newValue) {

            $changes[$field] = [
                'old' => $oldValue,
                'new' => $newValue
            ];
        }
    }


    // ADDITIONAL DOCUMENTS DIRECT VENDOR PROFILE ME SAVE
   if ($request->hasFile('additional_documents')) {

    foreach ($request->file('additional_documents') as $file) {

        if ($file->isValid()) {

            $path = $file->store(
                'uploads/media',
                'public'
            );

            VendorAdditionalDocument::create([
                'vendor_id' => auth()->id(),
                'file_path' => $path,
            ]);
        }
    }

    $documentsUploaded = true;
}


    // Kuch bhi change/upload nahi hua
    if (empty($changes) && !$documentsUploaded) {

        return back()->with(
            'error',
            'No changes detected.'
        );
    }


    // Sirf Additional Documents upload hue
    if (empty($changes) && $documentsUploaded) {

        return back()->with(
            'success',
            'Additional documents uploaded successfully.'
        );
    }


    // Baaki profile fields change hui hain
    if (!empty($changes)) {

        VendorProfileUpdateRequest::create([

            'vendor_id' => auth()->id(),

            'section' => 'profile',

            'request_data' => $changes,

            'status' => 'pending'

        ]);
    }


    // Documents bhi upload hue + profile fields bhi change hui
    if ($documentsUploaded) {

        return back()->with(
            'success',
            'Additional documents uploaded successfully and profile update request sent for admin approval.'
        );
    }


    // Sirf normal profile fields change hui
    return back()->with(
        'success',
        'Profile update request sent successfully. Waiting for admin approval.'
    );
}

public function shipmentSettings()
{
    $adminTemplate = VendorShippingSetting::where(
        'is_default_template',
        1
    )->first();

    $shipping = VendorShippingSetting::firstOrCreate(
        ['user_id' => auth()->id()],
        [
            'delivery_type' => $adminTemplate->delivery_type ?? 'one_day',
            'handling_days' => $adminTemplate->handling_days ?? 1,
        ]
    );

    $holidays = VendorHoliday::where(
        'vendor_id',
        auth()->id()
    )
    ->orderBy('holiday_date')
    ->get();

    return view(
        'vendor.shipment_settings',
        compact('shipping', 'holidays')
    );
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

// Delivery Type => Handling Days Mapping
$data['delivery_type'] = $request->delivery_type ?? 'one_day';

switch ($data['delivery_type']) {

    case 'same_day':
        $data['handling_days'] = 0;
        break;

    case 'two_day':
        $data['handling_days'] = 2;
        $data['delivery_days'] = $data['handling_days'];
        break;

    default:
        $data['delivery_type'] = 'one_day';
        $data['handling_days'] = 1;
        break;
}
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

public function requestApprovals()
{
    $brandRequests = VendorBrandRequest::where(
        'user_id',
        auth()->id()
    )->latest()->get();

    $variationRequests = VariationRequest::with('product')
        ->where('seller_id', auth()->id())
        ->latest()
        ->get();

    $purchaseRequests = PurchaseQuantityRequest::with('product')
        ->where('seller_id', auth()->id())
        ->latest()
        ->get();

    return view(
        'backend.vendors.request-approvals',
        compact(
            'brandRequests',
            'variationRequests',
            'purchaseRequests'
        )
    );
}
//HOLIDAY
public function holidays()
{
    $holidays = VendorHoliday::where(
        'vendor_id',
        auth()->id()
    )
    ->orderBy('holiday_date')
    ->get();

    return view(
        'vendor.holidays',
        compact('holidays')
    );
}
public function storeHoliday(Request $request)
{
    $request->validate([
        'holiday_name' => 'required',
        'holiday_date' => 'required|date',
    ]);

    $exists = VendorHoliday::where('vendor_id', auth()->id())
        ->where('holiday_date', $request->holiday_date)
        ->exists();

    if ($exists) {

        return back()->with(
            'error',
            'A holiday already exists on this date.'
        );
    }

    VendorHoliday::create([
        'vendor_id'    => auth()->id(),
        'holiday_name' => $request->holiday_name,
        'holiday_date' => $request->holiday_date,
    ]);

    return back()->with(
        'success',
        'Holiday added successfully'
    );
}
public function deleteHoliday($id)
{
    VendorHoliday::where('id', $id)
        ->where('vendor_id', auth()->id())
        ->delete();

    return back()->with(
        'success',
        'Holiday deleted successfully'
    );
}
}