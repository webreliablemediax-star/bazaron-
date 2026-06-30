<?php

namespace App\Http\Controllers\Backend\Products;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryLocalization;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    # construct
    public function __construct()
    {
        $this->middleware(['permission:categories'])->only('index');
        $this->middleware(['permission:add_categories'])->only(['create', 'store']);
        $this->middleware(['permission:edit_categories'])->only(['edit', 'update']);
        $this->middleware(['permission:top_categories'])->only(['updateTop']);
        $this->middleware(['permission:delete_categories'])->only(['delete']);
    }

    # category list
    public function index(Request $request)
    {
        $searchKey = null;
        $categories = Category::orderBy('sorting_order_level', 'desc');
        if ($request->search != null) {
            $categories = $categories->where('name', 'like', '%' . $request->search . '%');
            $searchKey = $request->search;
        }

        $categories = $categories->paginate(paginationNumber());
        return view('backend.pages.products.categories.index', compact('categories', 'searchKey'));
    }

    # return view of create form
    public function create()
{
    // 🔥 Load FULL category tree (Level 0 → Level 3 is enough)
    $categories = Category::where(function ($q) {
            $q->where('parent_id', 0)
              ->orWhereNull('parent_id');
        })
        ->where('is_active', 1)
        ->with([
            'childrenCategories.childrenCategories.childrenCategories'
        ])
        ->orderBy('sorting_order_level', 'desc')
        ->get()
        ->map(function ($cat) {

        $cat->name = $cat->collectLocalization('name');

        if ($cat->childrenCategories) {
            $cat->childrenCategories->map(function ($child) {

                $child->name = $child->collectLocalization('name');

                if ($child->childrenCategories) {
                    $child->childrenCategories->map(function ($sub) {

                        $sub->name = $sub->collectLocalization('name');

                        return $sub;
                    });
                }

                return $child;
            });
        }

        return $cat;
    });

  
     
    $brands = Brand::isActive()->get();
    

    return view('backend.pages.products.categories.create', compact('categories', 'brands'));
}

    # add new data
    public function store(Request $request)
    {
        $exists = Category::whereRaw('LOWER(name) = ?', [strtolower(trim($request->name))])
        ->where('parent_id', $request->parent_id)
        ->exists();

        if ($exists) {
            flash('This category already exists under the selected parent.')->error();
            return back()->withInput();
        }   
        $category = new Category;
        $category->name = $request->name;
        do {
                $categoryCode = mt_rand(1000000000,9999999999);
             } while (Category::where('category_code', $categoryCode)->exists());
        $category->category_code = $categoryCode;
        $category->sorting_order_level = 0;
        $category->thumbnail_image = $request->image;
        $category->meta_image = $request->meta_image;

        if ($request->sorting_order_level != null) {
            $category->sorting_order_level = $request->sorting_order_level;
        }

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;
            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1;
        } else {
            $category->parent_id = $request->parent_id;
            $category->level = 0;
        }

        if ($request->slug != null) {
            $category->slug = Str::slug($request->slug);
        } else {
        $category->slug = Str::slug($request->name);
        }

        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->commission_percentage = $request->commission_percentage ?? 0;



        // Landing Page Banners
$category->banner_image_1 = $request->banner_image_1;
$category->banner_link_1  = $request->banner_link_1;

$category->banner_image_2 = $request->banner_image_2;
$category->banner_link_2  = $request->banner_link_2;

$category->banner_image_3 = $request->banner_image_3;
$category->banner_link_3  = $request->banner_link_3;

$category->banner_image_4 = $request->banner_image_4;
$category->banner_link_4  = $request->banner_link_4;

$category->banner_image_5 = $request->banner_image_5;
$category->banner_link_5 = $request->banner_link_5;

$category->banner_image_6 = $request->banner_image_6;
$category->banner_link_6  = $request->banner_link_6;

$category->banner_image_7 = $request->banner_image_7;
$category->banner_link_7 = $request->banner_link_7;

$category->banner_image_8 = $request->banner_image_8;
$category->banner_link_8  = $request->banner_link_8;

$category->banner_image_9 = $request->banner_image_9;
$category->banner_link_9  = $request->banner_link_9;

$category->banner_image_10 = $request->banner_image_10;
$category->banner_link_10  = $request->banner_link_10;

$category->description = $request->description;

        $category->save();
        $category->brands()->sync($request->brand_ids);

        $categoryLocalization = CategoryLocalization::firstOrNew(['lang_key' => env('DEFAULT_LANGUAGE'), 'category_id' => $category->id]);
        $categoryLocalization->name = $category->name;
        $categoryLocalization->meta_title = $category->meta_title;
        $categoryLocalization->meta_description = $category->meta_description;
        $categoryLocalization->thumbnail_image = $request->image;
        $categoryLocalization->meta_image = $request->meta_image;

        $category->save();
        $categoryLocalization->save();

        flash(localize('Category has been inserted successfully'))->success();
        return redirect()->route('admin.categories.index');
    }

    # return view of edit form
    public function edit(Request $request, $id)
    {
        $lang_key = $request->lang_key;
        $language = Language::where('is_active', 1)->where('code', $lang_key)->first();
        if (!$language) {
            flash(localize('Language you are trying to translate is not available or not active'))->error();
            return redirect()->route('admin.categories.index');
        }

        $category = Category::findOrFail($id);
        $categories = Category::where(function ($q) {
        $q->where('parent_id', 0)
          ->orWhereNull('parent_id');
    })
    ->where('id', '!=', $category->id)
    ->where('is_active', 1)
    ->with([
        'childrenCategories.childrenCategories.childrenCategories'
    ])
    ->whereNotIn('id', $this->childrenIds($category->id, true))
    ->orderBy('sorting_order_level', 'desc')
    ->get()
     ->map(function ($cat) {

        $cat->name = $cat->collectLocalization('name');

        if ($cat->childrenCategories) {
            $cat->childrenCategories->map(function ($child) {

                $child->name = $child->collectLocalization('name');

                if ($child->childrenCategories) {
                    $child->childrenCategories->map(function ($sub) {

                        $sub->name = $sub->collectLocalization('name');

                        return $sub;
                    });
                }

                return $child;
            });
        }

        return $cat;
    });

        $brands = Brand::isActive()->get();
        return view('backend.pages.products.categories.edit', compact('category', 'categories', 'brands', 'lang_key'));
    }

    # update category
    public function update(Request $request)
    {
        $category = Category::findOrFail($request->id);
        
        $exists = Category::whereRaw('LOWER(name) = ?', [strtolower(trim($request->name))])
        ->where('parent_id', $request->parent_id)
        ->where('id', '!=', $request->id)
        ->exists();

        if ($exists) {
            flash('This category already exists under the selected parent.')->error();
            return back()->withInput();
        }

        if ($request->lang_key == env("DEFAULT_LANGUAGE")) {
            $category->name = $request->name;
            $category->thumbnail_image = $request->image;
            $category->meta_image = $request->meta_image;

            // Slug only update if manually changed
$category->slug = $request->slug
    ? Str::slug($request->slug)
    : Str::slug($request->name);
            if ($request->sorting_order_level != null) {
                $category->sorting_order_level = $request->sorting_order_level;
            }

            $oldLevel = $category->level;

            if ($request->parent_id != "0") {
                $category->parent_id = $request->parent_id;
                $parent = Category::find((int) $request->parent_id);
                $category->level = $parent->level + 1;
            } else {
                $category->parent_id = 0;
                $category->level = 0;
            }

            if ($category->level > $oldLevel) {
                $this->downLevelOneStep($category->id);
            } elseif ($category->level < $oldLevel) {
                $this->upLevelOneStep($category->id);
            }
            if ($request->lang_key == env("DEFAULT_LANGUAGE")) {
    $category->commission_percentage = $request->commission_percentage ?? 0;
   
}

            $category->meta_title = $request->meta_title;
            $category->meta_description = $request->meta_description;



            // Landing Page Banners
$category->banner_image_1 = $request->banner_image_1;
$category->banner_link_1  = $request->banner_link_1;

$category->banner_image_2 = $request->banner_image_2;
$category->banner_link_2  = $request->banner_link_2;

$category->banner_image_3 = $request->banner_image_3;
$category->banner_link_3  = $request->banner_link_3;

$category->banner_image_4 = $request->banner_image_4;
$category->banner_link_4  = $request->banner_link_4;

$category->banner_image_5 = $request->banner_image_5;
$category->banner_link_5 = $request->banner_link_5;

$category->banner_image_6 = $request->banner_image_6;
$category->banner_link_6  = $request->banner_link_6;

$category->banner_image_7 = $request->banner_image_7;
$category->banner_link_7 = $request->banner_link_7;

$category->banner_image_8 = $request->banner_image_8;
$category->banner_link_8  = $request->banner_link_8;

$category->banner_image_9 = $request->banner_image_9;
$category->banner_link_9  = $request->banner_link_9;

$category->banner_image_10 = $request->banner_image_10;
$category->banner_link_10  = $request->banner_link_10;

$category->description = $request->description;

            $category->save();
            $category->brands()->sync($request->brand_ids);
        }


        $categoryLocalization = CategoryLocalization::firstOrNew(['lang_key' => $request->lang_key, 'category_id' => $category->id]);
        $categoryLocalization->name = $request->name;
        $categoryLocalization->meta_title = $request->meta_title;
        $categoryLocalization->meta_description = $request->meta_description;
        $categoryLocalization->thumbnail_image = $request->image;
        $categoryLocalization->meta_image = $request->meta_image;

        $category->save();
        $categoryLocalization->save();
        flash(localize('Category has been updated successfully'))->success();
        return back();
    }

    # update status
    public function updateFeatured(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->is_featured = $request->status;
        if ($category->save()) {
            return 1;
        }
        return 0;
    }

    # update Top
    public function updateTop(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->is_top = $request->status;
        if ($category->save()) {
            return 1;
        }
        return 0;
    }

    # delete category
    public function delete($id)
    {
        $category = Category::where('id', $id)->first();
        if (!is_null($category)) {
            $this->moveChildrenToParent($category->id);

            try {
                ProductCategory::where('category_id', $category->id)->delete();
            } catch (\Throwable $th) {
            }

             $category->forceDelete();
        }
        flash(localize('Category has been deleted successfully'))->success();
        return back();
    }

    # get immediate children collection of a category
    public function getImmediateChildren($id, $includeDeleted = false, $toArray = false)
    {
        $children = Category::where('parent_id', $id)->orderBy('sorting_order_level', 'desc')->get();
        $children = $toArray && !is_null($children) ? $children->toArray() : array();
        return $children;
    }

    # get immediate children ids of a categories
    public function getImmediateChildrenIds($id, $includeDeleted = false)
    {
        $children = $this->getImmediateChildren($id, $includeDeleted, true);
        return !empty($children) ? array_column($children, 'id') : array();
    }

    # get immediate children count
    public function getImmediateChildrenCount($id, $includeDeleted = false)
    {
        return Category::where('parent_id', $id)->count();
    }

    # all sub-children of a category
    public function subChildren($id, $includeDeleted = false, $dataArray = array())
    {
        $children = $this->getImmediateChildren($id, $includeDeleted, true);

        if (!empty($children)) {
            foreach ($children as $child) {
                $dataArray[] = $child;
                $dataArray   = $this->subChildren($child['id'], $includeDeleted, $dataArray);
            }
        }
        return $dataArray;
    }

    # all sub-children ids of a category 
    public function childrenIds($id, $includeDeleted = false)
    {
        $children = $this->subChildren($id, $includeDeleted = false);

        return !empty($children) ? array_column($children, 'id') : array();
    }

    # update category level
    public function upLevelOneStep($id)
    {
        if ($this->getImmediateChildrenIds($id, true) > 0) {
            foreach ($this->getImmediateChildrenIds($id, true) as $value) {
                $category = Category::find($value);
                $category->level -= 1;
                $category->save();
                return $this->upLevelOneStep($value);
            }
        }
    }

    # update category level 
    public function downLevelOneStep($id)
    {
        if ($this->getImmediateChildrenIds($id, true) > 0) {
            foreach ($this->getImmediateChildrenIds($id, true) as $value) {
                $category = Category::find($value);
                $category->level += 1;
                $category->save();
                return $this->downLevelOneStep($value);
            }
        }
    }

    # update parent id of child / children
    public function moveChildrenToParent($id)
    {
        $childrenIds = $this->getImmediateChildrenIds($id, true);
        $category = Category::where('id', $id)->first();
        $this->upLevelOneStep($id);
        Category::whereIn('id', $childrenIds)->update(['parent_id' => $category->parent_id]);
    }
      public function updateStatus(Request $request)
{
    $category = Category::findOrFail($request->id);

    $category->is_active = $request->status;

    $category->save();

    return response()->json(1);
}
}
