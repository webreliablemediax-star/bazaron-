<?php
namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorProfile;
use App\Models\VendorAddress;
class VendorAddressController extends Controller
{
// 🟢 PAGE LOAD
public function index()
{
$vendor = Auth::user()->vendorProfile ?? new VendorProfile();
// 🔥 new: address list
$addresses = VendorAddress::where('user_id', Auth::id())->get();
return view('vendor.manage_address', compact('vendor', 'addresses'));
}
// 🟢 UPDATE PRIMARY ADDRESS
public function update(Request $request)
{
$request->validate([
'gst_number' => [
'required',
'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[A-Z0-9]{1}Z[A-Z0-9]{1}$/'
],
]);
$vendor = Auth::user()->vendorProfile ?? new VendorProfile();
$vendor->user_id = Auth::id();
$vendor->gst_number = strtoupper($request->gst_number);
$vendor->warehouse_address = $request->warehouse_address;
$vendor->city = $request->city;
$vendor->state = $request->state;
$vendor->zip = $request->zip;
$vendor->save();
// 🔥 new: default address bhi update ho
VendorAddress::where('user_id', Auth::id())
->where('is_default', 1)
->update([
'gst_number' => strtoupper($request->gst_number),
'warehouse_address' => $request->warehouse_address,
'city' => $request->city,
'state' => $request->state,
'zip' => $request->zip,
]);
return back()->with('success', 'Address updated successfully');
}
// 🟢 ADD NEW ADDRESS
public function storeNewAddress(Request $request)
{
VendorAddress::create([
'user_id' => Auth::id(),
'gst_number' => strtoupper($request->new_gst_number),
'warehouse_address' => $request->new_warehouse_address,
'city' => $request->new_city,
'state' => $request->new_state,
'zip' => $request->new_zip,
'is_default' => 0
]);
return back()->with('success', 'New Address Added');
}
// 🟢 SET DEFAULT ADDRESS
public function setDefault($id)
{
// sabko 0
VendorAddress::where('user_id', Auth::id())
->update(['is_default' => 0]);
// selected ko 1
$address = VendorAddress::where('id', $id)
->where('user_id', Auth::id())
->first();
if ($address) {
$address->update(['is_default' => 1]);
// vendor profile update
$vendor = Auth::user()->vendorProfile;
if ($vendor) {
$vendor->update([
'gst_number' => $address->gst_number,
'warehouse_address' => $address->warehouse_address,
'city' => $address->city,
'state' => $address->state,
'zip' => $address->zip,
]);
}
}
return back()->with('success', 'Default address updated');
}
}