<?php

namespace App\Http\Controllers\Backend\Shipping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gst;

class GstController extends Controller
{
    // Show All Data
    public function index()
    {
        $gsts = Gst::latest()->get();

        return view(
            'backend.pages.shipping.gst',
            compact('gsts')
        );
    }

    // Store Data
    public function store(Request $request)
    {
        $request->validate([
            'tax' => 'required'
        ]);

        Gst::create([
            'tax' => $request->tax
        ]);

        return redirect()->back()
            ->with('success', 'GST Added Successfully');
    }

    // Edit Data On Same Page
    public function edit($id)
    {
        $gst = Gst::findOrFail($id);

        $gsts = Gst::latest()->get();

        return view(
            'backend.pages.shipping.gst',
            compact('gst', 'gsts')
        );
    }

    // Update Data
    public function update(Request $request, $id)
    {
        $request->validate([
            'tax' => 'required|numeric|min:0|max:100'
        ]);

        $gst = Gst::findOrFail($id);

        $gst->update([
            'tax' => $request->tax
        ]);

        return redirect()->route('admin.gst.index')
            ->with('success', 'GST Updated Successfully');
    }

    // Delete Data
    public function destroy($id)
    {
        $gst = Gst::findOrFail($id);

        $gst->delete();

        return redirect()->back()
            ->with('success', 'GST Deleted Successfully');
    }
}