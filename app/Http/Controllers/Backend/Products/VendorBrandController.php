<?php

namespace App\Http\Controllers\Backend\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorBrandRequest;
use Illuminate\Support\Facades\Auth;

class VendorBrandController extends Controller
{
    /**
     * Display vendor brand requests
     */
    public function index()
    {
        $brands = VendorBrandRequest::where('user_id', Auth::id())
                    ->latest()
                    ->paginate(10);

        return view('backend.pages.products.brands.index', compact('brands'));
    }

    /**
     * Show create form (optional)
     */
    public function create()
    {
        return view('backend.pages.products.brands.create');
    }

    /**
     * Store new brand request (Vendor)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable',
            'product_image' => 'required',
            'packaging_image' => 'nullable',
            'hand_image' => 'nullable',
        ]);

        $brand = new VendorBrandRequest();
        $brand->user_id = Auth::id();
        $brand->brand_name = $request->name;
        $brand->logo = $request->logo;
        $brand->product_image = $request->product_image;
        $brand->packaging_image = $request->packaging_image;
        $brand->hand_image = $request->hand_image;
        $brand->status = 'pending';

        $brand->save();

        flash('Brand request submitted successfully')->success();
        return back();
    }

    /**
     * Show single request (optional)
     */
    public function show($id)
    {
        $brand = VendorBrandRequest::where('user_id', Auth::id())
                    ->findOrFail($id);

        return view('backend.pages.products.brands.show', compact('brand'));
    }

    /**
     * Delete request (optional)
     */
    public function destroy($id)
    {
        $brand = VendorBrandRequest::where('user_id', Auth::id())
                    ->findOrFail($id);

        $brand->delete();

        flash('Brand request deleted successfully')->success();
        return back();
    }
}
