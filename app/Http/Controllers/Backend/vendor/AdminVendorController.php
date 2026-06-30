<?php

namespace App\Http\Controllers\Backend\vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\VendorProfile;
use App\Models\PurchaseQuantityRequest;
use Illuminate\Http\Request;
use App\Models\VendorProfileUpdateRequest;
class AdminVendorController extends Controller
{
    // Show list of vendors with status pending
    public function index()
    {
        $vendors = VendorProfile::whereHas('user', function($q) {
            $q->where('status', 'pending'); // sirf pending wale hi dikhao
        })->with('user')->get();

        return view('auth.index', compact('vendors'));
    }

    // Approve vendor
    public function approve($id)
    {
        $vendor = VendorProfile::findOrFail($id);
        $user   = $vendor->user;

        // Step 1: Status update
        $user->update(['status' => 'approved']);

        // Step 2: Vendor role assign karo (agar pehle se nahi hai)
        if (!$user->hasRole('vendor')) {
            $user->assignRole('vendor'); // Spatie permission ka method
        }

        return back()->with('success', 'Seller approved and role assigned successfully.');
    }
    public function profileRequests()
{
    $requests = VendorProfileUpdateRequest::with('vendor')
        ->latest()
        ->get();

    return view(
        'backend.pages.vendor.profile_requests',
        compact('requests')
    );
}
public function approveProfileRequest($id)
{
    $profileRequest = VendorProfileUpdateRequest::findOrFail($id);

    $vendor = VendorProfile::where(
        'user_id',
        $profileRequest->vendor_id
    )->first();

    if (!$vendor) {

        return back()->with(
            'error',
            'Vendor not found.'
        );
    }
if (empty($profileRequest->request_data)) {

    return back()->with(
        'error',
        'No request data found.'
    );

}
    $updateData = [];

    foreach ($profileRequest->request_data as $field => $value) {

        $updateData[$field] = $value['new'];

    }

    $vendor->update($updateData);

    $profileRequest->update([

        'status' => 'approved',

        'approved_by' => auth()->id(),

        'approved_at' => now()

    ]);

    return redirect()
        ->route('admin.vendor.profile.requests')
        ->with(
            'success',
            'Profile request approved successfully.'
        );
}
public function rejectProfileRequest($id)
{
    $profileRequest = VendorProfileUpdateRequest::findOrFail($id);

    $profileRequest->update([

        'status' => 'rejected',

        'approved_by' => auth()->id(),

        'approved_at' => now()

    ]);

    return back()->with(
        'success',
        'Profile request rejected successfully.'
    );
}
public function showProfileRequest($id)
{
    $requestData = VendorProfileUpdateRequest::with('vendor')
        ->findOrFail($id);

    return view(
        'backend.pages.vendor.profile_request_show',
        compact('requestData')
    );
}
public function purchaseQuantityRequests()
{
    $requests = PurchaseQuantityRequest::with([
        'seller',
        'product'
    ])
    ->latest()
    ->get();

    return view(
        'backend.pages.vendor.purchase_quantity_requests',
        compact('requests')
    );
}
public function showPurchaseQuantityRequest($id)
{
    $requestData = PurchaseQuantityRequest::with([
        'seller',
        'product'
    ])->findOrFail($id);

    return view(
        'backend.pages.vendor.purchase_quantity_request_show',
        compact('requestData')
    );
}
public function approvePurchaseQuantityRequest($id)
{
    $requestData = PurchaseQuantityRequest::findOrFail($id);

    $product = Product::findOrFail($requestData->product_id);

    $product->admin_max_purchase_qty =
        $requestData->requested_quantity;

    $product->save();

    $requestData->update([
        'status' => 'approved',
    ]);

    return redirect()
        ->route('admin.purchase.quantity.requests')
        ->with(
            'success',
            'Purchase quantity request approved successfully.'
        );
}
public function rejectPurchaseQuantityRequest($id)
{
    $requestData = PurchaseQuantityRequest::findOrFail($id);

    $requestData->update([
        'status' => 'rejected',
    ]);

    return redirect()
        ->route('admin.purchase.quantity.requests')
        ->with(
            'success',
            'Purchase quantity request rejected successfully.'
        );
}
public function deliverySettings()
{
    return view(
        'backend.pages.vendor.delivery_settings'
    );
}


public function deliverySettingsUpdate(Request $request)
{
    DB::table('system_settings')->updateOrInsert(
        [
            'entity' => 'free_delivery_text'
        ],
        [
            'value'      => $request->free_delivery_text,
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );

    Cache::forget('settings'); // 🔥 IMPORTANT

    return back()->with(
        'success',
        'Delivery settings updated successfully.'
    );
}


    // Reject vendor
    public function reject($id)
    {
        $vendor = VendorProfile::findOrFail($id);
        $vendor->user->update(['status' => 'rejected']);

        return back()->with('success', 'Vendor rejected successfully.');
    }
    public function loginStatus(Request $request)
{
    $vendor = User::findOrFail($request->id);

    $vendor->is_active = $request->status;
    $vendor->save();

    return response()->json([
        'success' => true,
        'message' => 'Vendor login status updated successfully.'
    ]);
}
}
