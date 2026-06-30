<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tsd;

class TsdController extends Controller
{
    public function index()
    {
        $tsds = Tsd::latest()->get();

        return view('backend.pages.tsd.index', compact('tsds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Tsd::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'TSD Added Successfully');
    }

    public function edit($id)
    {
        $tsd = Tsd::findOrFail($id);

        $tsds = Tsd::latest()->get();

        return view('backend.pages.tsd.index', compact('tsd', 'tsds'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $tsd = Tsd::findOrFail($id);

        $tsd->update([
            'name' => $request->name
        ]);

        return redirect()->route('admin.tsd.index')
            ->with('success', 'TSD Updated Successfully');
    }

    public function delete($id)
    {
        $tsd = Tsd::findOrFail($id);

        $tsd->delete();

        return redirect()->back()->with('success', 'TSD Deleted Successfully');
    }
}