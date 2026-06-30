<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instraction;

class InstractionController extends Controller
{
    public function index()
    {
        $instractions = Instraction::latest()->get();

        return view('auth.instraction', compact('instractions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        Instraction::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Added Successfully');
    }

    public function edit($id)
    {
        $edit = Instraction::findOrFail($id);
        $instractions = Instraction::latest()->get();

        return view('auth.instraction', compact('edit', 'instractions'));
    }

    public function update(Request $request, $id)
    {
        $data = Instraction::findOrFail($id);

        $data->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('instraction.index')
                         ->with('success', 'Updated Successfully');
    }

    public function destroy($id)
    {
        Instraction::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Deleted Successfully');
    }
}