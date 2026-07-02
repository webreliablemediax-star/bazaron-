<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VariationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VariationRequestController extends Controller
{
    public function store(Request $request)
    {
            
        $request->validate([
            'product_id'         => 'required',
            'variation_values'   => 'required|array|min:1',
            'variation_values.*' => 'required|string',
        ]);

     VariationRequest::create([
    'seller_id'        => Auth::id(),
    'product_id'       => $request->product_id,

    'variation_name'   => 'Custom Variation',

    'variation_values' => implode(',', $request->variation_values),

    'status'           => 'pending',
]);

        return back()->with(
            'success',
            'Variation request submitted successfully.'
        );
    }
}