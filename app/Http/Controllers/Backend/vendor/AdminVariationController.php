<?php

namespace App\Http\Controllers\Backend\vendor;

use App\Http\Controllers\Controller;
use App\Models\VariationRequest;
use App\Models\Variation;
use App\Models\VariationValue;

class AdminVariationController extends Controller
{
    public function index()
    {
        $requests = VariationRequest::with([
            'seller',
            'product'
        ])->latest()->get();

        return view(
            'backend.pages.vendor.variation_requests',
            compact('requests')
        );
    }

    public function show($id)
    {
        $requestData = VariationRequest::with([
            'seller',
            'product'
        ])->findOrFail($id);

        return view(
            'backend.pages.vendor.variation_request_show',
            compact('requestData')
        );
    }

    public function approve($id)
    {
        $requestData = VariationRequest::findOrFail($id);

        $variation = Variation::create([
            'name' => $requestData->variation_name,
            'is_active' => 1,
        ]);

        $values = explode(
            ',',
            $requestData->variation_values
        );

        foreach ($values as $value) {

            VariationValue::create([
                'variation_id' => $variation->id,
                'name' => trim($value),
                'is_active' => 1,
            ]);
        }

        $requestData->update([
            'status' => 'approved'
        ]);

        return redirect()
            ->route('admin.variation.requests')
            ->with(
                'success',
                'Variation request approved successfully.'
            );
    }

    public function reject($id)
    {
        $requestData = VariationRequest::findOrFail($id);

        $requestData->update([
            'status' => 'rejected'
        ]);

        return back()->with(
            'success',
            'Variation request rejected successfully.'
        );
    }
}