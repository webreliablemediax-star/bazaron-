<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MegaMenuColumn;
use App\Models\Category;
use App\Models\Variation;
use App\Models\Brand;

class MegaMenuColumnController extends Controller
{
    // 🔹 List all Mega Menu Columns
    public function index()
    {
        $columns = MegaMenuColumn::with('categories', 'variation')
            ->orderBy('order')
            ->get();

        return view('backend.pages.mega_menu_columns.index', compact('columns'));
    }

    // 🔹 Show form to create new column
    public function create()
    {
        // Category Tree (Parent + Sub + SubChild)
        $categories = Category::where(function ($q) {
                $q->whereNull('parent_id')
                  ->orWhere('parent_id', 0);
            })
            ->where('is_active', 1)
            ->with(['childrenCategories.childrenCategories'])
            ->orderBy('sorting_order_level', 'desc')
            ->get();

        $variations = Variation::where('is_active', 1)->get();
        $brands     = Brand::where('is_active', 1)->get();

        return view('backend.pages.mega_menu_columns.create', compact('categories', 'variations', 'brands'));
    }

    // 🔹 Store new column
    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'type'            => 'required|in:variation,brand,category',
            'variation_id'    => 'nullable|exists:variations,id',
            'order'           => 'nullable|integer',
            'is_active'       => 'required|boolean',
            'category_ids'    => 'nullable|array',
            'category_ids.*'  => 'exists:categories,id',
        ]);

        // Base data
        $data = $request->only('title', 'type', 'variation_id', 'order', 'is_active');

        // 🆕 VARIATION VALUE FILTER (IMPORTANT)
        if ($request->type === 'variation') {
            $data['variation_value_ids'] = $request->filled('variation_value_ids')
                ? json_encode($request->variation_value_ids)
                : null;
        } else {
            $data['variation_value_ids'] = null;
        }

        // 🆕 MULTIPLE BRAND SUPPORT (SAFE + BACKWARD COMPATIBLE)
        if ($request->type === 'brand') {
            $data['brand_ids'] = $request->filled('brand_ids')
                ? json_encode($request->brand_ids)
                : null;

            // fallback old column (so old frontend never breaks)
            $data['brand_id'] = $request->filled('brand_ids')
                ? $request->brand_ids[0]
                : null;
        } else {
            $data['brand_ids'] = null;
            $data['brand_id']  = null;
        }

        // SINGLE create (fixed)
        $column = MegaMenuColumn::create($data);

        // Sync categories (pivot table)
        if ($request->has('category_ids')) {
            $column->categories()->sync($request->category_ids);
        }

        return redirect()
            ->route('admin.mega_menu_columns.index')
            ->with('success', 'Mega Menu Column created successfully.');
    }

    // 🔹 Show form to edit existing column
    public function edit($id)
    {
        $column = MegaMenuColumn::with('categories')->findOrFail($id);

        $categories = Category::where(function ($q) {
                $q->whereNull('parent_id')
                  ->orWhere('parent_id', 0);
            })
            ->where('is_active', 1)
            ->with(['childrenCategories.childrenCategories'])
            ->orderBy('sorting_order_level', 'desc')
            ->get();

        $variations = Variation::where('is_active', 1)->get();
        $brands     = Brand::where('is_active', 1)->get();

        return view('backend.pages.mega_menu_columns.edit', compact('column', 'categories', 'variations', 'brands'));
    }

    // 🔹 Update existing column
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'type'            => 'required|in:variation,brand,category',
            'variation_id'    => 'nullable|exists:variations,id',
            'order'           => 'nullable|integer',
            'is_active'       => 'required|boolean',
            'category_ids'    => 'nullable|array',
            'category_ids.*'  => 'exists:categories,id',
        ]);

        $column = MegaMenuColumn::findOrFail($id);

        // Base update data
        $data = $request->only('title', 'type', 'variation_id', 'order', 'is_active');

        // 🆕 VARIATION VALUE FILTER (NEW FEATURE)
        if ($request->type === 'variation') {
            $data['variation_value_ids'] = $request->filled('variation_value_ids')
                ? json_encode($request->variation_value_ids)
                : null;
        } else {
            $data['variation_value_ids'] = null;
        }

        // 🆕 MULTIPLE BRAND SUPPORT (SAFE)
        if ($request->type === 'brand') {
            $data['brand_ids'] = $request->filled('brand_ids')
                ? json_encode($request->brand_ids)
                : null;

            // backward fallback
            $data['brand_id'] = $request->filled('brand_ids')
                ? $request->brand_ids[0]
                : null;
        } else {
            $data['brand_ids'] = null;
            $data['brand_id']  = null;
        }

        // SINGLE update (fixed)
        $column->update($data);

        // Sync categories
        if ($request->has('category_ids')) {
            $column->categories()->sync($request->category_ids);
        } else {
            $column->categories()->sync([]);
        }

        return redirect()
            ->route('admin.mega_menu_columns.index')
            ->with('success', 'Mega Menu Column updated successfully.');
    }

    // 🔹 Delete a column
    public function destroy($id)
    {
        $column = MegaMenuColumn::findOrFail($id);
        $column->categories()->detach();
        $column->delete();

        return redirect()
            ->route('admin.mega_menu_columns.index')
            ->with('success', 'Mega Menu Column deleted successfully.');
    }
}