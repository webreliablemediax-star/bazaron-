<?php
namespace App\Http\Controllers\Frontend;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductVariationInfoResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use App\Models\ProductVariation;
use App\Models\Tag;
use App\Models\Variation;
use App\Models\VariationValue;
use App\Models\Category;
use App\Models\VendorProfile;
use Illuminate\Http\Request;
use App\Models\MegaMenuColumn;
class ProductController extends Controller
{
# =========================
# PRODUCT LISTING (FINAL STABLE)
# =========================
public function index(Request $request)
{
    $stripCategories = collect(); // 🔥 FIX
     $breadcrumbCategories = collect(); // ✅ safe

$currentNavbarCategory = null;
$searchKey = null;
$per_page = 9;
$sort_by = $request->sort_by ?? 'new';
$maxRange = Product::max('max_price');
$min_value = 0;
$max_value = formatPrice($maxRange, false, false, false, false);
$max_range = $max_value;
$products = Product::isPublished();
/* ================= Color Filter ================= */

if ($request->filled('color')) {

    $productIds = ProductVariation::whereIn('sku', $request->color)
                    ->pluck('product_id');

    $products = $products->whereIn('id', $productIds);

}
/* ================= Price Filter ================= */

if ($request->min_price != '' && $request->max_price != '') {

    $productIds = ProductVariation::whereBetween(
        'price',
        [
            $request->min_price,
            $request->max_price
        ]
    )->pluck('product_id');

    $products = $products->whereIn('id', $productIds);
}
/* ================= Filters ================= */
$colorVariation = Variation::where('name', 'Color')->first();
$colorValues = $colorVariation
? VariationValue::where('variation_id', $colorVariation->id)->pluck('name')
: collect();
$sizeVariation = Variation::where('name', 'Size')->first();
$sizeValues = $sizeVariation
? VariationValue::where('variation_id', $sizeVariation->id)->pluck('name')
: collect();
$occasionVariation = Variation::where('name', 'Occassion')->first();
$occasions = $occasionVariation
? VariationValue::where('variation_id', $occasionVariation->id)->get()
: collect();
/* ================= Search ================= */
if ($request->search) {
$searchKey = $request->search;
// Step 1: find category IDs
$categoryIds = Category::whereHas('category_localizations', function ($q) use ($searchKey) {
$q->where('name', 'like', '%' . $searchKey . '%');
})
->pluck('id')
->toArray();
// Step 2: get ALL related categories (children)
foreach ($categoryIds as $catId) {
$childIds = Category::where('parent_id', $catId)->pluck('id')->toArray();
$categoryIds = array_merge($categoryIds, $childIds);
foreach ($childIds as $childId) {
$subChildIds = Category::where('parent_id', $childId)->pluck('id')->toArray();
$categoryIds = array_merge($categoryIds, $subChildIds);
}
}
$categoryIds = array_unique($categoryIds);
// Step 3: get product IDs from pivot
$productIds = ProductCategory::whereIn('category_id', $categoryIds)
->pluck('product_id')
->toArray();
// 🔥 FINAL QUERY RESET (IMPORTANT)
$products = Product::isPublished()->where(function ($query) use ($searchKey, $productIds) {
// product name
$query->whereHas('product_localizations', function ($q) use ($searchKey) {
$q->where('name', 'like', '%' . $searchKey . '%');
});
// category products
if (!empty($productIds)) {
$query->orWhereIn('id', $productIds);
}
});
}
/* ================= Sort ================= */
$products = $sort_by === 'new'
? $products->latest()
: $products->orderBy('total_sale_count', 'DESC');
/* ================= Brand Filter ================= */

if ($request->brand_id) {

    $products = $products->where(
        'brand_id',
        $request->brand_id
    );

}
/* ================= CATEGORY STRIP (DB PERFECT FIX) ================= */
$clickedCategory = null;
$activeCategory = null;
$activeSubCategories = collect();
$sidebarCategories = collect();
if ($request->category_id) {
$clickedCategory = Category::with('childrenCategories')->find($request->category_id);
if ($clickedCategory) {
    // 🔥 BREADCRUMB CHAIN BUILD
$breadcrumbCategories = collect();

$current = $clickedCategory;

while ($current) {
    $breadcrumbCategories->prepend($current);
    $current = Category::find($current->parent_id);
}
/**
* 🔥 FINAL LOGIC (BASED ON YOUR DB):
* parent_id = 0 → Navbar Category (Electronics, Mobiles)
* Otherwise → climb up until parent_id = 0
*/
$navbarCategory = $clickedCategory;
while ($navbarCategory && $navbarCategory->parent_id != 0) {
$navbarCategory = Category::find($navbarCategory->parent_id);
}
// Load ONLY real navbar children (NO mixing)
// 🔥 STRICT: Only direct subcategories of navbar category (no auto refill behaviour)
$currentNavbarCategory = Category::find(optional($navbarCategory)->id);
if ($currentNavbarCategory) {
$currentNavbarCategory->setRelation(
'childrenCategories',
Category::where('parent_id', $currentNavbarCategory->id)
->where('is_active', 1)
->orderBy('sorting_order_level', 'asc')
->limit(11) // single row UI control
->get()
);
}
// Fallback safety
if (!$currentNavbarCategory) {
$currentNavbarCategory = $clickedCategory;
}
$activeCategory = $clickedCategory;

$stripCategories = Category::where('parent_id', $currentNavbarCategory->id)
    ->where('is_active', 1)
    ->orderBy('sorting_order_level','asc')
    ->get();
// Active subcategories (limit for single row UI)
// 🔥 ALWAYS use NAVBAR CATEGORY for strip (LEVEL 1 FIX)
// $activeSubCategories = Category::where('parent_id', $currentNavbarCategory->id)
//     ->where('is_active', 1)
//     ->orderBy('sorting_order_level','asc')
//     ->get();

// 🔥 UNIVERSAL CHECK (works for level 1, 2, 3... ALL)
$childCategories = Category::where('parent_id', $clickedCategory->id)
    ->where('is_active', 1)
    ->get();

if ($childCategories->count() > 0) {

    // 👉 CHECK: kya ye FINAL LEVEL hai?
$childCategories = Category::where('parent_id', $clickedCategory->id)
    ->where('is_active', 1)
    ->get();

if ($childCategories->count() > 0) {

    // 👉 ALWAYS show next level categories
    $activeSubCategories = $childCategories;
    $sidebarCategories = $childCategories;

    // 👉 hide products
    $products = Product::whereRaw('1 = 0');

} else {

    // 👉 FINAL LEVEL → show products
    $activeSubCategories = collect();

    $categoryIds = [$clickedCategory->id];

    $productIds = ProductCategory::whereIn('category_id', $categoryIds)
        ->pluck('product_id');

    $products = $products->whereIn('id', $productIds);
}

} else {

    // 👉 no child → direct products
    $categoryIds = [$clickedCategory->id];

    $productIds = ProductCategory::whereIn('category_id', $categoryIds)
        ->pluck('product_id');

    $products = $products->whereIn('id', $productIds);
}
}
}

/* ================= Sidebar Categories ================= */
$parentCategories = Category::with('childrenRecursive')
->where('parent_id', 0)
->where('is_active', 1)
->get();
/* ================= Mega Menu ================= */
$megaMenuColumns = MegaMenuColumn::with([
'categories',
'variation.variation_values',
'brand'
])->where('is_active', 1)
->orderBy('order')
->get();
/* ================= Final ================= */
$products = $products->paginate(50);
$tags = Tag::all();
return getView('pages.products.index', compact(
'products',
'searchKey',
'per_page',
'sort_by',
'min_value',
'max_value',
'tags',
'sizeValues',
'stripCategories',
'occasions',
'parentCategories',
'colorValues',
'currentNavbarCategory',
'activeCategory',
'activeSubCategories',
'sidebarCategories',
'max_range',
'breadcrumbCategories',
'megaMenuColumns'
));
}
public function categoryLanding($slug, $category_code)
{
$category = Category::withoutGlobalScopes()
            ->where('category_code', $category_code)
            ->where('is_active', 1)
            ->firstOrFail();
// Sirf main category allow karni hai
// if ($category->level != 0) {
// return redirect()->route('products.index', [
// 'category_id' => $category->id
// ]);
// }
$subcategories = Category::withoutGlobalScopes()
->where('parent_id', $category->id)
->where('is_active', 1)
->orderBy('sorting_order_level', 'asc')
->get();
$sidebarCategories = $subcategories;
// 🔥 SIDEBAR DATA (Products Page jaisa)
$parentCategories = Category::with('childrenRecursive')
->where('parent_id', 0)
->where('is_active', 1)
->get();
// 🔥 Sidebar Filters (same as index)
$colorVariation = Variation::where('name', 'Color')->first();
$colorValues = $colorVariation
? VariationValue::where('variation_id', $colorVariation->id)->pluck('name')
: collect();
$sizeVariation = Variation::where('name', 'Size')->first();
$sizeValues = $sizeVariation
? VariationValue::where('variation_id', $sizeVariation->id)->pluck('name')
: collect();
$occasionVariation = Variation::where('name', 'Occassion')->first();
$occasions = $occasionVariation
? VariationValue::where('variation_id', $occasionVariation->id)->get()
: collect();
$maxRange = Product::max('max_price');
$min_value = 0;
$max_value = formatPrice($maxRange, false, false, false, false);
$max_range = $max_value; // 🔥 ye missing tha
$tags = Tag::all();
$currentNavbarCategory = $category;
$currentNavbarCategory->setRelation(
'childrenCategories',
Category::withoutGlobalScopes()
->where('parent_id', $category->id)
->where('is_active', 1)
->orderBy('sorting_order_level', 'asc')
->get()
);
$activeSubCategories = Category::withoutGlobalScopes()
->where('parent_id', $category->id)
->where('is_active', 1)
->orderBy('sorting_order_level', 'asc')
->take(10)
->get();
$megaMenuColumns = MegaMenuColumn::with([
'categories',
'variation.variation_values',
'brand'
])->where('is_active', 1)
->orderBy('order')
->get();
// Preview products (optional)
$categoryIds = $subcategories->pluck('id')->toArray();

if (empty($categoryIds)) {
    // Last level category hai
    $categoryIds = [$category->id];
}

$productIds = ProductCategory::whereIn('category_id', $categoryIds)
    ->pluck('product_id');

$products = Product::whereIn('id', $productIds)
    ->where('is_published', 1)
    ->take(8)
    ->get();
// $productIds = ProductCategory::whereIn('category_id', $subcategories->pluck('id'))
// ->pluck('product_id');
// $products = Product::whereIn('id', $productIds)
// ->where('is_published', 1)
// ->take(8)
// ->get();
return getView('pages.category.landing', compact(
'category',
'subcategories',
'products',
'parentCategories',
'colorValues',
'sizeValues',
'occasions',
'min_value',
'max_value',
'max_range', 
'tags',
'currentNavbarCategory',
'activeSubCategories',
'sidebarCategories',
'megaMenuColumns'
));
}
# =========================
# PRODUCT SHOW (UNCHANGED + SAFE)
# =========================
public function show($slug)
{
$product = Product::with([
    'vendor.vendorProfile',
    'reviews' => function ($q) {
        $q->where('is_approved', 1)->latest();
    }
])->where('slug', $slug)->firstOrFail();
$reviews = $product->reviews;
$totalReviews = $reviews->count();
$averageRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;
$starCounts = [
5 => $reviews->where('rating', 5)->count(),
4 => $reviews->where('rating', 4)->count(),
3 => $reviews->where('rating', 3)->count(),
2 => $reviews->where('rating', 2)->count(),
1 => $reviews->where('rating', 1)->count(),
];
$productCategories = $product->categories()->pluck('category_id');
$category = $product->categories->first();

// 👉 top parent (navbar category)
while ($category && $category->parent_id != 0) {
    $category = $category->parent;
}

$currentNavbarCategory = $category;

// 👉 SAME STRIP as product listing page
$stripCategories = collect();

if ($currentNavbarCategory) {
    $stripCategories = Category::where('parent_id', $currentNavbarCategory->id)
        ->where('is_active', 1)
        ->orderBy('sorting_order_level', 'asc')
        ->get();
}
// $stripCategories = Category::where('parent_id', $currentNavbarCategory->id)
//     ->where('is_active', 1)
//     ->orderBy('sorting_order_level','asc')
//     ->get();
$relatedProducts = Product::whereIn(
'id',
ProductCategory::whereIn('category_id', $productCategories)
->where('product_id', '!=', $product->id)
->pluck('product_id')
)->where('is_published', 1)->take(12)->get();
$subCategories = collect();
if ($product->categories()->exists()) {
$mainCategory = $product->categories()->with('childrenCategories')->first();
if ($mainCategory) {
if ($mainCategory->parent_id) {
$parentCategory = Category::with('childrenCategories')
->find($mainCategory->parent_id);
$subCategories = $parentCategory
? $parentCategory->childrenCategories
->where('is_active', 1)
->sortBy('sorting_order_level')
->take(10)
: collect();
} else {
$subCategories = $mainCategory->childrenCategories
->where('is_active', 1)
->sortBy('sorting_order_level')
->take(10);
}
}
}
return getView('pages.products.show', compact(
'product',
'relatedProducts',
'totalReviews',
'averageRating',
'starCounts',
'stripCategories'
));
}

public function checkDelivery(Request $request)
{
    $product = Product::findOrFail($request->product_id);

   $vendor = VendorProfile::find($product->vendor_id);

    // Bazaron Shipping
    if (!$vendor || $vendor->has_own_logistics == 0) {

        return response()->json([
            'success' => true,
            'message' => 'Delivery Available'
        ]);
    }

    $exists = $vendor->pincodes()
        ->where('pincode', $request->pincode)
        ->exists();

    if ($exists) {

        return response()->json([
            'success' => true,
            'message' => 'Delivery Available'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Delivery not available on this pincode'
    ]);
}
}
