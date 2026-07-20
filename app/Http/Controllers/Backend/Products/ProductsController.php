<?php

namespace App\Http\Controllers\Backend\Products;

use App\Models\VariationGallery;
use App\Http\Controllers\Controller;
use App\Models\VendorShippingSetting;
use App\Models\Language;
use App\Models\VendorHoliday;
use App\Models\Brand;
use App\Models\VendorBrandRequest;
use App\Models\Unit;
use App\Models\Tax;
use App\Models\Category;
use App\Models\Location;
use App\Models\Variation;
use App\Models\VariationValue;
use App\Models\PurchaseQuantityRequest;
use App\Models\Product;
use App\Models\ProductLocalization;
use App\Models\ProductVariation;
use App\Models\ProductVariationStock;
use App\Models\ProductVariationCombination;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;


use App\Models\ShippingWeight;
use App\Models\ShippingCharge;
use App\Models\Gst;
use App\Models\PaymentGateway;
use App\Models\Tsd;

use Illuminate\Support\Facades\DB;
class ProductsController extends Controller
{
    # construct
    public function __construct()
    {
        $this->middleware(['permission:products'])->only('index');
        $this->middleware(['permission:add_products'])->only(['create', 'store']);
        $this->middleware(['permission:edit_products'])->only(['edit', 'update']);
        $this->middleware(['permission:publish_products'])->only(['updatePublishedStatus']);
    }
    # product list
    public function index(Request $request)
    {
        $searchKey = null;
        $brand_id = null;
        $is_published = null;
        $products = Product::shop()->with('vendorProfile.shippingSetting')->latest();
       if ($request->search != null) {

    $products = $products->where(function ($q) use ($request) {

        $q->where('name', 'like', '%' . $request->search . '%')
          ->orWhere('product_code', 'like', '%' . $request->search . '%');

    });

    $searchKey = $request->search;
}
        if ($request->brand_id != null) {
            $products = $products->where('brand_id', $request->brand_id);
            $brand_id = $request->brand_id;
        }
        if ($request->is_published != null) {
            $products = $products->where('is_published', $request->is_published);
            $is_published = $request->is_published;
        }
        $brands = Brand::latest()->get();
        $products = $products->paginate(paginationNumber());
        return view('backend.pages.products.products.index', compact('products', 'brands', 'searchKey', 'brand_id', 'is_published'));
    }
    # return view of create form
    public function create()
    {
        // if (Auth::user()->user_type === 'vendor') {
        //     $vendorProfile = Auth::user()->vendorProfile;
        //     $rootCategoryId = $vendorProfile->product_categories;
        //     $categories = Category::where('id', $rootCategoryId)
        //         ->with('childrenRecursive')
        //         ->get();
        // } else {
        //     $categories = Category::where('parent_id', 0)
        //         ->orderBy('sorting_order_level', 'desc')
        //         ->with('childrenRecursive')
        //         ->get();
        // }
         $categories = Category::where('parent_id', 0)
                    ->orderBy('sorting_order_level', 'desc')
                    ->with('childrenRecursive')
                    ->get();
        // brands
        if (Auth::user()->user_type === 'vendor') {

    $brands = VendorBrandRequest::where('user_id', Auth::id())
        ->where('status', 'approved')
        ->get()
        ->map(function ($item) {

            return [
                'id'   => $item->id,
                'name' => $item->brand_name
            ];

        });

} else {

    $brands = Brand::isActive()->get();

}
        $units = Unit::isActive()->get();
        // ❌ REMOVE THIS (गलत है)
        // $variations = ProductVariation::where('product_id', $product->id)->get();
        // ✅ CREATE PAGE me variations empty hone chahiye
        $variations = Variation::isActive()->get();
        $taxes = Tax::isActive()->get();
        $tags = Tag::all();
        // ✅ IMPORTANT
        $product = null;
        $shipping = VendorShippingSetting::where(
    'user_id',
    auth()->id()
)->first();
        do {
    
            $nextProductCode ='BZIN' . str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        }
        while (
            Product::where(
                'product_code',
                $nextProductCode
            )->exists()
        );
                return view('backend.pages.products.products.create', compact(
            'categories',
            'brands',
            'units',
            'variations',
            'taxes',
            'tags',
            'product',
            'nextProductCode',
            'shipping'
        ));
    }
    # get variation values to add new product
    public function getVariationValues(Request $request)
    {
        $variation_id = $request->variation_id;
        $variation_values = VariationValue::isActive()->where('variation_id', $variation_id)->get();
        return view('backend.pages.products.products.new_variation_values', compact('variation_values', 'variation_id'));
    }
    public function getVariationsByCategory($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([]);
        }
        // 🔥 ROOT CATEGORY NIKALO (LEVEL 0 TAK JAO)
        // while ($category && $category->parent_id != 0) {
        //     $category = Category::find($category->parent_id);
        // }
        $variations = Variation::whereHas('categories', function ($q) use ($id) {
            $q->where('category_id', $id);
        })->get();
        if (!$category) {
            return response()->json([]);
        }
        // 🔥 PIVOT TABLE SE VARIATION FETCH
        $variations = Variation::whereHas('categories', function ($q) use ($category) {
            $q->where('category_id', $category->id);
        })->get();
        return response()->json($variations);
    }
    # new chosen variation
    public function getNewVariation(Request $request)
    {
        $categoryId = $request->category_id;
        // safety (just in case)
        if (!$categoryId) {
            return response()->json(['count' => 0]);
        }
        $category = Category::find($categoryId);
        if (!$category) {
            return response()->json(['count' => 0]);
        }
        // 🔥 ROOT CATEGORY FIND
        // while ($category && $category->parent_id != 0) {
        //     $category = Category::find($category->parent_id);
        // }
        $variations = Variation::whereHas('categories', function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        });
        // 🔥 FILTERED VARIATIONS
        $variations = Variation::whereHas('categories', function ($q) use ($category) {
            $q->where('category_id', $category->id);
        });
        // 🔥 remove already selected variations
        // if ($request->has('chosen_variations')) {
        //     $variations = $variations->whereNotIn('id', $request->chosen_variations);
        // }
        $variations = $variations->get();
        if ($variations->count() > 0) {
            return response()->json([
                'count' => $variations->count(),
                'view' => view('backend.pages.products.products.new_variation', compact('variations'))->render(),
            ]);
        }
        return response()->json(['count' => 0]);
    }
    # generate variation combinations
    public function generateVariationCombinations(Request $request)
{
    $variations_and_values = [];
    $chosen_variations = [];

    foreach ($request->all() as $key => $value) {

        if (Str::startsWith($key, 'option_')) {

            $parts = explode('_', $key);

            if (count($parts) >= 2) {

                $chosen_variations[] = $parts[1];

            }
        }
    }

    sort($chosen_variations, SORT_NUMERIC);

    foreach ($chosen_variations as $option) {

        $option_name = 'option_'.$option.'_choices';

        if ($request->has($option_name)) {

            $values = $request[$option_name];

            sort($values);

            $variations_and_values[$option] = $values;
        }
    }

    $combinations = [[]];

    foreach ($variations_and_values as $variation => $variation_values) {

        $temp = [];

        foreach ($combinations as $combination) {

            foreach ($variation_values as $value) {

                $temp[] =
                $combination + [
                    $variation => $value
                ];

            }

        }

        $combinations = $temp;
    }


    // 🔥 EXISTING DATA LOAD FOR EDIT
    $existingVariations = collect();

    if ($request->filled('product_id')) {

        $existingVariations =
        ProductVariation::with([
            'product_variation_stock'
        ])
        ->where(
            'product_id',
            $request->product_id
        )
        ->get()
        ->keyBy('variation_key');
    }

    return view(
        'backend.pages.products.products.new_variation_combinations',
        compact(
            'combinations',
            'existingVariations'
        )
    )->render();
}
    # add new data
    public function store(Request $request)
    {

        $user = auth()->user();
        if ($request->has('variations') && !empty($request->variations)) {
            $request->validate([
                'variation_hsn' => 'required|digits_between:1,8'
            ]);
        } else {
            $request->validate([
                         'code' => 'required|digits_between:1,8',
               'model_number'          => 'required',
'model_name'            => 'required',
'manufacturer_name'     => 'required',
'style'                 => 'required',
'target_gender'         => 'required',
'age_range_description' => 'required',
'country_of_origin'     => 'required',
'item_condition'        => 'nullable',
'packer_details'        => 'required',

                
            ]);
        }
        // 🔹 Product Create
        $product = new Product;
        do {

            $productCode ='BZIN' . str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        } 
        while (
            Product::where(
                'product_code',
                $productCode
            )->exists()
        );
        $product->product_code = $productCode;
        // $product->shop_id = 1; // DB pattern same as old products
        // $product->vendor_id = null; // kyuki DB me sab NULL hi hai
        // $product->added_by = 'admin';
        if ($user->user_type === 'vendor') {
            $shop = \App\Models\Shop::where('user_id', $user->id)->first();
            $vendorProfile = $user->vendorProfile;
            $product->shop_id = $shop ? $shop->id : 1;
            $product->vendor_id = $vendorProfile ? $vendorProfile->id : null;
            $product->added_by = 'seller';
        } else {
            $product->shop_id = 1;
            $product->vendor_id = null;
            $product->added_by = 'admin';
        }
        $product->name = $request->name;
        $product->slug = Str::slug($request->name, '-') . '-' . strtolower(Str::random(5));
        $product->brand_id = $request->brand_id;
        $product->unit_id = $request->unit_id;
        $product->category_id = $request->category_id;
        $product->sell_target = $request->sell_target;
        $product->thumbnail_image = $request->image;
        // 🔒 GALLERY LIMIT (MAX 9 - bazaron STYLE)
        $galleryImages = $request->images;
        if (!empty($galleryImages)) {
            $imagesArray = explode(',', $galleryImages); // because DB pattern = string IDs
            $imagesArray = array_slice($imagesArray, 0, 9); // limit 9
            $product->gallery_images = implode(',', $imagesArray);
        } else {
            $product->gallery_images = null;
        }
        // ⭐ NEW UNIVERSAL VIDEO SYSTEM (YouTube + MP4) - SAFE & TARGETED
        // If YouTube URL provided
        // YouTube
        // 🔒 Only admin can set video
        if (auth()->user()->user_type === 'admin' && !empty($request->video_url)) {
            $product->video_url = $request->video_url;
            $product->video_type = 'youtube';
        } else {
            $product->video_url = null;
            $product->video_type = 'none';
        }
        $product->size_guide = $request->size_guide;
        $product->description = $request->description;
        $product->short_description = $request->short_description;
        $product->min_selling_price = $request->min_selling_price;
        $product->max_selling_price = $request->max_selling_price;
     $shippingSetting = VendorShippingSetting::where(
    'user_id',
    auth()->id()
)->first();

$holidayCount = VendorHoliday::where(
    'vendor_id',
    auth()->id()
)
->whereDate('holiday_date', now()->toDateString())
->count();

$weeklyOffCount = strcasecmp(
    (string) ($shippingSetting->weekly_off ?? ''),
    now($shippingSetting->timezone ?? config('app.timezone'))->format('l')
) === 0 ? 1 : 0;

$product->delivery_days =
    ($shippingSetting->handling_days ?? 1) + $holidayCount + $weeklyOffCount;
        // 🔥 bazaron BASIC IDENTIFIERS (NEW - SAME PATTERN)
        $product->external_product_id = $request->external_product_id;
        $product->product_id_type = $request->product_id_type;
        // 🔥 SAFETY & COMPLIANCE (NEW - bazaron STYLE)
        $product->country_of_origin = $request->country_of_origin;
        $product->manufacturer = $request->manufacturer;
        $product->importer_name = $request->importer_name;
        $product->packer_details = $request->packer_details;
        $product->safety_information = $request->safety_information;
        $product->compliance_certification = $request->compliance_certification;
        // ⭐⭐⭐ ADDITIONAL INFORMATION SAVE (FINAL FIX - REPEATER SAFE) ⭐⭐⭐
        $additionalInfoData = [];
        $infoTitles = $request->input('info_title', []);
        $infoValues = $request->input('info_value', []);
        if (!empty($infoTitles) && is_array($infoTitles)) {
            foreach ($infoTitles as $index => $title) {
                if (!empty($title)) {
                    $additionalInfoData[] = [
                        'title' => $title,
                        'value' => $infoValues[$index] ?? '',
                    ];
                }
            }
        }
        $product->additional_info = $additionalInfoData;
        // ⭐⭐⭐ YE NAYA CODE ADD KARO YAHI ⭐⭐⭐
        $iconSliderData = [];
        if ($request->has('icon_titles') && $request->has('icon_classes')) {
            $titles = $request->icon_titles;
            $icons  = $request->icon_classes;
            foreach ($titles as $index => $title) {
                if (!empty($title)) {
                    $iconSliderData[] = [
                        'title' => $title,
                        'icon'  => $icons[$index] ?? 'las la-truck',
                    ];
                }
            }
        }
        // ⭐⭐⭐ PRODUCT INFO SAVE (LEFT TABLE) ⭐⭐⭐
        $productInfoData = [];
        if ($request->has('pinfo_title') && $request->has('pinfo_value')) {
            $titles = $request->pinfo_title;
            $values = $request->pinfo_value;
            foreach ($titles as $index => $title) {
                if (!empty($title)) {
                    $productInfoData[] = [
                        'title' => $title,
                        'value' => $values[$index] ?? '',
                    ];
                }
            }
        }
        $product->product_info = !empty($productInfoData) ? $productInfoData : null;
        // ⭐⭐⭐ ABOUT THIS ITEM SAVE (BULLETS) ⭐⭐⭐
        $aboutItemsData = [];
        if ($request->has('about_items')) {
            foreach ($request->about_items as $item) {
                if (!empty($item)) {
                    $aboutItemsData[] = $item;
                }
            }
        }
        $product->about_items = !empty($aboutItemsData) ? $aboutItemsData : null;
        // ⭐⭐⭐ BRAND SPECS SAVE (NEW) ⭐⭐⭐
        $brandSpecsData = [];
        if ($request->has('brand_spec_title') && $request->has('brand_spec_value')) {
            $titles = $request->brand_spec_title;
            $values = $request->brand_spec_value;
            foreach ($titles as $index => $title) {
                if (!empty($title)) {
                    $brandSpecsData[] = [
                        'title' => $title,
                        'value' => $values[$index] ?? '',
                    ];
                }
            }
        }
        $product->brand_specs = !empty($brandSpecsData) ? $brandSpecsData : [];
        // ⭐⭐⭐ BAZARON bazaron ATTRIBUTES SAVE (CREATE) ⭐⭐⭐
        $product->model_number = $request->model_number;
        $product->model_name = $request->model_name;
        $product->manufacturer_name = $request->manufacturer_name;
        $product->generic_keyword = $request->generic_keyword;
        $product->special_features = $request->special_features;
        $product->style = $request->style;
         $product->item_condition = $request->item_condition;
        $product->outer_material = $request->outer_material;
        $product->compatible_devices = $request->compatible_devices;
        $product->unit_count = $request->unit_count;
        $product->item_type_name = $request->item_type_name;
        $product->number_of_items = $request->number_of_items;
        $product->water_resistance_level = $request->water_resistance_level;
        $product->target_gender = $request->target_gender;
        $product->age_range_description = $request->age_range_description;
        $product->subject_character = $request->subject_character;
        // ⭐⭐⭐ DIMENSIONS SAVE (bazaron STYLE) ⭐⭐⭐
        $product->item_length = $request->item_length;
        $product->item_length_unit = $request->item_length_unit;
        $product->item_width = $request->item_width;
        $product->item_width_unit = $request->item_width_unit;
        $product->item_height = $request->item_height;
        $product->item_height_unit = $request->item_height_unit;
        $product->package_weight = $request->package_weight;
        $product->package_weight_unit = $request->package_weight_unit;
        //         // 🔹 Product Video Upload
        // if ($request->hasFile('product_video')) {
        //     $video = $request->file('product_video');
        //     $videoName = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
        //     $video->move(public_path('uploads/products/videos'), $videoName);
        //     $product->product_video = 'uploads/products/videos/' . $videoName;
        // }
        // 🔹 Price
        if ($request->has('variations') && !empty($request->variations)) {
            $product->min_price = priceToUsd(min(array_column($request->variations, 'price')));
            $product->max_price = priceToUsd(max(array_column($request->variations, 'price')));
        } else {
            $price = $request->price ?? ($product->min_price ?? 0);
            $product->min_price = priceToUsd($price);
            $product->max_price = priceToUsd($price);
        }
        # discount (SAFE FIX - prevents NULL DB error)
        $product->discount_value = $request->filled('discount_value')
            ? $request->discount_value
            : ($product->discount_value ?? 0);
        $product->discount_type = $request->filled('discount_type')
            ? $request->discount_type
            : ($product->discount_type ?? 'flat');
        // 🔹 Stock Qty
        $product->stock_qty = ($request->has('variations') && !empty($request->variations))
            ? max(array_column($request->variations, 'stock'))
            : $request->stock;
        // ✅ Approval Logic
        if ($user->user_type === 'vendor') {
            $product->status = 'pending';   // Admin approval ke liye
            $product->is_published = 0;     // frontend se hide
        } else {
            $product->status = 'approved';  // Admin khud upload kare to auto approved
            $product->is_published = 1;
        }
        $product->has_variation = ($request->has('variations')) ? 1 : 0;
$product->standard_delivery_hours =
    $request->standard_delivery_hours ?? 24;

$product->express_delivery_hours =
    $request->express_delivery_hours ?? 12;
        $product->min_purchase_qty = $request->min_purchase_qty;
        $product->max_purchase_qty = $request->max_purchase_qty;
          $product->admin_max_purchase_qty = $request->max_purchase_qty;
        // 🔹 SEO
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_img = $request->meta_image;
        # icon slider save (NEW - for your custom feature)
        if ($request->has('icon_titles')) {
            $icons = [];
            foreach ($request->icon_titles as $key => $title) {
                if (!empty($title)) {
                    $icons[] = [
                        'title' => $title,
                        'icon' => $request->icon_classes[$key] ?? 'las la-star',
                    ];
                }
            }
            $product->icon_slider = $icons; // model cast will auto JSON encode
        }
        $product->save();
        
        // 🔹 Product Localization
        $ProductLocalization = ProductLocalization::firstOrNew([
            'lang_key' => env('DEFAULT_LANGUAGE'),
            'product_id' => $product->id
        ]);
        $ProductLocalization->name = $request->name;
        $ProductLocalization->description = $request->description;
        $ProductLocalization->save();
        // 🔹 Tags
        $product->tags()->sync($request->tag_ids);
        // 🔹 Categories
        $product->categories()->sync([$request->category_id]);
        // 🔹 Taxes
        $tax_data = [];
        $tax_ids = [];
        if ($request->has('taxes')) {
            foreach ($request->taxes as $key => $tax) {
                array_push($tax_data, [
                    'tax_value' => $tax,
                    'tax_type' => $request->tax_types[$key]
                ]);
            }
            $tax_ids = $request->tax_ids;
        }
        $taxes = array_combine($tax_ids, $tax_data);
        $product->product_taxes()->sync($taxes);
        // 🔹 Default Location
        $location = Location::where('is_default', 1)->first();
        // 🔹 Variations + Stock
        if ($request->has('variations') && !empty($request->variations)) {
            foreach ($request->variations as $variation) {
                $product_variation = new ProductVariation;
                $product_variation->product_id = $product->id;
                $product_variation->variation_key = $variation['variation_key'];
                $product_variation->price = priceToUsd($variation['price']);
                $product_variation->sku = $variation['sku'];
                $product_variation->code = $request->variation_hsn;
                // 🔥 YE LINE YAHI ADD KAR


$product_variation->image =
$request->input(
'variation_gallery.' .
$variation['variation_key']
);

                // $product_variation->is_active = isset($variation['is_active']) ? 1 : 0;
                $product_variation->save();
                $product_variation_stock = new ProductVariationStock;
                $product_variation_stock->product_variation_id = $product_variation->id;
                $product_variation_stock->location_id = $location->id;
                $product_variation_stock->stock_qty = $variation['stock'];
                $product_variation_stock->save();
                foreach (array_filter(explode("/", $variation['variation_key'])) as $combination) {
                    $product_variation_combination = new ProductVariationCombination;
                    $product_variation_combination->product_id = $product->id;
                    $product_variation_combination->product_variation_id = $product_variation->id;
                    $product_variation_combination->variation_id = explode(":", $combination)[0];
                    $product_variation_combination->variation_value_id = explode(":", $combination)[1];
                    // 🔥🔥🔥 MAIN FIX YE HAI
                    // $product_variation_combination->is_active = isset($variation['is_active']) ? 1 : 0;
                    $product_variation_combination->save();
                }
                if ($request->has('variation_gallery')) {
                    $combinationKey = $variation['variation_key'];
                    if (isset($request->variation_gallery[$combinationKey])) {
                        $galleryImages = explode(',', $request->variation_gallery[$combinationKey]);
                        foreach ($galleryImages as $img) {

if (!empty($img)) {

VariationGallery::create([

'product_id'=>$product->id,

'variation_combination_id'=>$product_variation->id,

'image'=>$img

]);

}

}
                    }
                }
            }
            // 🔥 NEW: VARIATION WISE GALLERY SAVE (NO PATTERN BREAK)
        } else {
            $variation = new ProductVariation;
            $variation->product_id = $product->id;
            $variation->sku = $request->sku;
            $variation->code = $request->code;
            $variation->price = priceToUsd($request->price);
            $variation->save();
            $product_variation_stock = new ProductVariationStock;
            $product_variation_stock->product_variation_id = $variation->id;
            $product_variation_stock->location_id = $location->id;
            $product_variation_stock->stock_qty = $request->stock;
            $product_variation_stock->save();
        }
        flash(localize('Product submitted for admin approval'))->success();
        return redirect()->route('admin.products.index');
    }
    # return view of edit form
    public function edit(Request $request, $id)
    {
        $location = Location::where('is_default', 1)->first();
        $request->session()->put('stock_location_id', $location->id);
        $lang_key = $request->lang_key;
        $language = Language::where('is_active', 1)->where('code', $lang_key)->first();
        if (!$language) {
            flash(localize('Language you are trying to translate is not available or not active'))->error();
            return redirect()->route('admin.products.index');
        }
        $product = Product::findOrFail($id);
        // 🔥 YE CODE YAHI ADD KAR
        $selectedVariations = [];
        $combinations = ProductVariationCombination::where('product_id', $product->id)->get();
        foreach ($combinations as $combination) {
            $selectedVariations[$combination->variation_id][] = $combination->variation_value_id;
        }
        $categories = Category::where('parent_id', 0)
            ->orderBy('sorting_order_level', 'desc')
            ->with('childrenRecursive')
            ->get();
        if (Auth::user()->user_type === 'vendor') {
            // 🧑‍💼 Vendor ke liye
            $brands = VendorBrandRequest::where('status', 'approved')
    ->where('user_id', Auth::id())
    ->get()
    ->map(function ($item) {

        return [
            'id' => $item->id,
            'name' => $item->brand_name
        ];

    });
                
        } else {
            // 👑 Admin ke liye (same old)
            if (Auth::user()->user_type === 'vendor') {
                // 👉 sirf Generic brand
                $brands = Brand::where('name', 'Generic')->get();
            } else {
                // 👉 admin ke liye sab brands
                $brands = Brand::isActive()->get();
            }
        }
        $units = Unit::isActive()->get();
        $variations = ProductVariation::with([
    'product_variation_stock' => function ($q) use ($location) {
        $q->where('location_id', $location->id);
    }
])
->where('product_id', $product->id)
->get();

foreach ($variations as $variation) {
    $variation->stock_qty =
        optional($variation->product_variation_stock)->stock_qty ?? 0;

        // 🔥 FORCE IMAGE FOR EDIT RENDER
    $variation->image =
        ProductVariation::where(
            'id',
            $variation->id
        )
        ->value(
            'image'
        );
}
        // $variations = \App\Models\ProductVariation::with([
        //     'product_variation_stock',
        //     'product_variation_stock_without_location'
        // ])->where('product_id', $product->id)->get();
        $taxes = Tax::isActive()->get();
        $tags = Tag::all();
        
        return view('backend.pages.products.products.edit', compact('product', 'categories', 'brands', 'units', 'variations', 'taxes', 'lang_key', 'tags', 'selectedVariations'));
    }
    // UPDATE PART...................................................................................................................................................//
    # update product
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $isVariant = $request->has('variations') && count($request->variations) > 0;
        if ($isVariant) {
            $request->validate([
                'variations' => 'required|array',
                'variations.*.price' => 'required',
                'variations.*.stock' => 'required',
                'variations.*.sku' => 'required',
                'variations.*.code' => 'nullable|digits_between:1,8'
            ]);
        } else {
            $request->validate([
                'price' => 'required',
                'stock' => 'required',
                'sku' => 'required',
                'code' => 'required|digits_between:1,8',
               'model_number'          => 'required',
'model_name'            => 'required',
'manufacturer_name'     => 'required',
'style'                 => 'required',
'target_gender'         => 'required',
'age_range_description' => 'required',
'country_of_origin'     => 'required',
'item_condition'        => 'nullable',
'packer_details'        => 'required',
            ]);
        }
        if ($isVariant) {
            $request->merge([
                'price' => null,
                'stock' => null,
                'sku'   => null,
                'code'  => null
            ]);
        }
        //     if ($request->hasFile('video_file')) {
        //     $file = $request->file('video_file');
        //     $error = $file->getError();
        //     if ($error !== UPLOAD_ERR_OK) {
        //         dd("Upload failed with error code: $error");a
        //     }
        //     dd($file);
        // } else {
        //     dd("No file uploaded");
        // }
        if ($request->has('is_variant') && (!$request->has('variations') || empty($request->variations))) {
            flash(localize('Invalid product variations, please check again'))->error();
            return redirect()->back();
        }
         $product = Product::with('variation_combinations')->findOrFail($id);
        // The admin publish toggle updates is_published only, so this is the
        // reliable indicator that the seller product has already been approved.
        $wasPublished = (int) $product->is_published === 1;
        $isVendor = $user->user_type === 'vendor' || $user->hasRole('vendor');
        $allowedQty = max(
    10,
    $product->admin_max_purchase_qty
);
        $oldProduct = clone $product;
        if ($request->lang_key == env("DEFAULT_LANGUAGE")) {
            $product->name = $request->name;
            // $product->slug = (!is_null($request->slug)) ? Str::slug($request->slug, '-') : Str::slug($request->name, '-') . '-' . strtolower(Str::random(5));
            $product->description = $request->description;
            $product->sell_target = $request->sell_target;
            $brand_id = $request->brand_id;
            // agar string ya empty aaya (jaise "generic")
            if (!$brand_id || !is_numeric($brand_id)) {
                $generic = \App\Models\Brand::where('name', 'Generic')->first();
                $brand_id = $generic ? $generic->id : 44; // fallback
            }
                $product->brand_id = $brand_id;
                $product->unit_id = $request->unit_id;
                $product->short_description = $request->short_description;
                $product->min_selling_price = $request->min_selling_price;
                $product->max_selling_price = $request->max_selling_price;
           $shippingSetting = VendorShippingSetting::where(
    'user_id',
    auth()->id()
)->first();

$holidayCount = VendorHoliday::where(
    'vendor_id',
    auth()->id()
)
->whereDate('holiday_date', now()->toDateString())
->count();

$weeklyOffCount = strcasecmp(
    (string) ($shippingSetting->weekly_off ?? ''),
    now($shippingSetting->timezone ?? config('app.timezone'))->format('l')
) === 0 ? 1 : 0;

$product->delivery_days =
    ($shippingSetting->handling_days ?? 1) + $holidayCount + $weeklyOffCount;
            // 🔥 NEW BASIC IDENTIFIERS UPDATE (SAME PATTERN - NO NEW LOGIC)
            $product->external_product_id = $request->external_product_id;
            $product->product_id_type = $request->product_id_type;
            // 🔥 SAFETY & COMPLIANCE UPDATE (NEW)
            $product->country_of_origin = $request->country_of_origin;
            $product->manufacturer = $request->manufacturer;
            $product->importer_name = $request->importer_name;
            $product->packer_details = $request->packer_details;
            $product->safety_information = $request->safety_information;
            $product->compliance_certification = $request->compliance_certification;
            //             // 🔹 Update Product Video
            // if ($request->hasFile('product_video')) {
            //     $video = $request->file('product_video'); // ✅ SAME NAME
            //     $videoName = time().'_'.$product->id.'.'.$video->getClientOriginalExtension();
            //     $video->move(public_path('uploads/products/videos'), $videoName);
            //     if (!empty($product->product_video) && file_exists(public_path($product->product_video))) {
            //         unlink(public_path($product->product_video));
            //     }
            //     $product->product_video = 'uploads/products/videos/' . $videoName;
            // }
            $product->thumbnail_image = $request->image;
            // 🔒 GALLERY LIMIT (MAX 9 - SAFE WITH OLD PRODUCTS)
            $galleryImages = $request->images;
            if (!empty($galleryImages)) {
                $imagesArray = explode(',', $galleryImages);
                $imagesArray = array_slice($imagesArray, 0, 9);
                $product->gallery_images = implode(',', $imagesArray);
            }
            // ⭐ NEW: YouTube Video Update (SAFE - NO PATTERN BREAK)
            // ⭐ UNIVERSAL VIDEO UPDATE (SAFE - NO DATA LOSS)
            // If new YouTube URL provided
            // YouTube
            // 🔒 Only admin can update video
            if (auth()->user()->user_type === 'admin' && $request->filled('video_url')) {
                if ($product->video_type === 'upload' && !empty($product->video_url)) {
                    $oldPath = public_path($product->video_url);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
                $product->video_url = $request->video_url;
                $product->video_type = 'youtube';
            }
            // else → DO NOTHING (old video preserved perfectly)
            $product->size_guide = $request->size_guide;
            # min-max price
            if ($isVariant) {
                $product->min_price = priceToUsd(min(array_column($request->variations, 'price')));
                $product->max_price = priceToUsd(max(array_column($request->variations, 'price')));
            } else {
                $product->min_price = priceToUsd($request->price);
                $product->max_price = priceToUsd($request->price);
            }
            // ⭐⭐⭐ UPDATE ADDITIONAL INFORMATION (FINAL FIX) ⭐⭐⭐
            $additionalInfoData = [];
            $infoTitles = $request->input('info_title', []);
            $infoValues = $request->input('info_value', []);
            if (!empty($infoTitles) && is_array($infoTitles)) {
                foreach ($infoTitles as $index => $title) {
                    if (!empty($title)) {
                        $additionalInfoData[] = [
                            'title' => $title,
                            'value' => $infoValues[$index] ?? '',
                        ];
                    }
                }
            }
            $product->additional_info = $additionalInfoData;
            // ⭐⭐⭐ PRODUCT INFO UPDATE (LEFT TABLE) ⭐⭐⭐
            $productInfoData = [];
            if ($request->has('pinfo_title') && $request->has('pinfo_value')) {
                $titles = $request->pinfo_title;
                $values = $request->pinfo_value;
                foreach ($titles as $index => $title) {
                    if (!empty($title)) {
                        $productInfoData[] = [
                            'title' => $title,
                            'value' => $values[$index] ?? '',
                        ];
                    }
                }
            }
            $product->product_info = !empty($productInfoData)
                ? $productInfoData
                : null;
            // ⭐⭐⭐ ABOUT THIS ITEM UPDATE (BULLETS) ⭐⭐⭐
            $aboutItemsData = [];
            if ($request->has('about_items')) {
                foreach ($request->about_items as $item) {
                    if (!empty($item)) {
                        $aboutItemsData[] = $item;
                    }
                }
            }
            $product->about_items = !empty($aboutItemsData)
                ? $aboutItemsData
                : null;
            // ⭐⭐⭐ UPDATE BRAND SPECS (NEW) ⭐⭐⭐
            $brandSpecsData = [];
            if ($request->has('brand_spec_title') && $request->has('brand_spec_value')) {
                $titles = $request->brand_spec_title;
                $values = $request->brand_spec_value;
                foreach ($titles as $index => $title) {
                    if (!empty($title)) {
                        $brandSpecsData[] = [
                            'title' => $title,
                            'value' => $values[$index] ?? '',
                        ];
                    }
                }
            }
            $product->brand_specs = !empty($brandSpecsData) ? $brandSpecsData : [];
            // ⭐⭐⭐ BAZARON bazaron STYLE ATTRIBUTES (STATIC 15 FIELDS - FINAL FIX) ⭐⭐⭐
            $product->model_number = $request->model_number;
            $product->model_name = $request->model_name;
            $product->manufacturer_name = $request->manufacturer_name;
            $product->generic_keyword = $request->generic_keyword;
            $product->special_features = $request->special_features;
            $product->style = $request->style;
             $product->item_condition = $request->item_condition;
            $product->outer_material = $request->outer_material;
            $product->compatible_devices = $request->compatible_devices;
            $product->unit_count = $request->unit_count;
            $product->item_type_name = $request->item_type_name;
            $product->number_of_items = $request->number_of_items;
            $product->water_resistance_level = $request->water_resistance_level;
            $product->target_gender = $request->target_gender;
            $product->age_range_description = $request->age_range_description;
            $product->subject_character = $request->subject_character;
            // ⭐⭐⭐ DIMENSIONS UPDATE ⭐⭐⭐
            $product->item_length = $request->item_length;
            $product->item_length_unit = $request->item_length_unit;
            $product->item_width = $request->item_width;
            $product->item_width_unit = $request->item_width_unit;
            $product->item_height = $request->item_height;
            $product->item_height_unit = $request->item_height_unit;
            $product->package_weight = $request->package_weight;
            $product->package_weight_unit = $request->package_weight_unit;
            # discount (SAFE FIX - prevents NULL DB error)
            $product->discount_value = $request->filled('discount_value')
                ? $request->discount_value
                : ($product->discount_value ?? 0);
            $product->discount_type = $request->filled('discount_type')
                ? $request->discount_type
                : ($product->discount_type ?? 'flat');
            if ($request->date_range != null) {
                if (Str::contains($request->date_range, 'to')) {
                    $date_var = explode(" to ", $request->date_range);
                } else {
                    $date_var = [date("d-m-Y"), date("d-m-Y")];
                }
                $product->discount_start_date = strtotime($date_var[0]);
                $product->discount_end_date = strtotime($date_var[1]);
            } // approval system logic yaha add karein
            # stock qty based on all variations / no variation 
            $product->stock_qty = $isVariant
                ? max(array_column($request->variations, 'stock'))
                : ($request->stock ?? $product->stock_qty ?? 0);
            $product->has_variation = $isVariant ? 1 : 0;
            # shipping info
           $product->standard_delivery_hours =
    $request->standard_delivery_hours
    ?? $product->standard_delivery_hours
    ?? 24;

$product->express_delivery_hours =
    $request->express_delivery_hours
    ?? $product->express_delivery_hours
    ?? 12;
            if (auth()->user()->user_type == 'vendor') {

    $request->validate([
        'max_purchase_qty' => 'nullable|integer|max:' . $allowedQty
    ], [
        'max_purchase_qty.max' =>
        'Maximum purchase quantity cannot exceed ' . $allowedQty
    ]);

}
            $product->min_purchase_qty = $request->min_purchase_qty;
            $product->max_purchase_qty = $request->max_purchase_qty;
               if (auth()->user()->user_type == 'admin') {

    $product->admin_max_purchase_qty =
        $request->max_purchase_qty;

}
            $product->meta_title = $request->meta_title;
            $product->meta_description = $request->meta_description;
            $product->meta_img = $request->meta_image;
            $product->category_id = $request->category_id;
            // ⭐⭐⭐ ICON SLIDER UPDATE ⭐⭐⭐
            if ($request->has('icon_titles')) {
                $icons = [];
                foreach ($request->icon_titles as $key => $title) {
                    if (!empty($title)) {
                        $icons[] = [
                            'title' => $title,
                            'icon' => $request->icon_classes[$key] ?? 'las la-star',
                        ];
                    }
                }
                $product->icon_slider = $icons;
            }
            // Preserve the published state of an already approved seller product on edit.
            if ($isVendor && $wasPublished) {
                $product->status = 'approved';
                $product->is_published = 1;
            } elseif ($isVendor) {
                $product->status = 'pending';
                $product->is_published = 0;
            } else {
                $product->status = 'approved';
                $product->is_published = 1;
            }
            $product->save();
            # tags
            $product->tags()->sync($request->tag_ids);
            # category
            $product->categories()->sync([$request->category_id]);
            $product->category_id = $request->category_id;
            # taxes
            $tax_data = array();
            $tax_ids = array();
            if ($request->has('taxes')) {
                foreach ($request->taxes as $key => $tax) {
                    array_push($tax_data, [
                        'tax_value' => $tax,
                        'tax_type' => $request->tax_types[$key]
                    ]);
                }
                $tax_ids = $request->tax_ids;
            }
            $taxes = array_combine($tax_ids, $tax_data);
            $product->product_taxes()->sync($taxes);
            $location = Location::where('is_default', 1)->first();
            if ($isVariant) {
                $new_requested_variations = collect($request->variations);
                $new_requested_variations_key = $new_requested_variations->pluck('variation_key')->toArray();
                $old_variations_keys = $product->variations->pluck('variation_key')->toArray();
                $old_matched_variations = $new_requested_variations->whereIn('variation_key', $old_variations_keys);
                $new_variations = $new_requested_variations->whereNotIn('variation_key', $old_variations_keys);
                # delete old variations that isn't requested
                $product->variations->whereNotIn('variation_key', $new_requested_variations_key)->each(function ($variation) use ($location) {
                    foreach ($variation->combinations as $comb) {
                        $comb->delete();
                    }
                    $variation->product_variation_stock_without_location()->where('location_id', $location->id)->delete();
                    $variation->delete();
                });
                # update old matched variations
                foreach ($old_matched_variations as $variation) {
                    $p_variation = ProductVariation::where('product_id', $product->id)->where('variation_key', $variation['variation_key'])->first();
                    $p_variation->price = priceToUsd($variation['price']);
                    $p_variation->sku = $variation['sku'];
                    
                    $p_variation->code = $request->variation_hsn;

                    // 🔥 IMAGE UPDATE
$p_variation->image =
$request->variation_gallery[
$variation['variation_key']
]
?? $p_variation->image;


                    $p_variation->save();
                    # update stock of this variation
                    $productVariationStock = ProductVariationStock::where('product_variation_id', $p_variation->id)
                        ->where('location_id', $location->id)
                        ->first();
                    if (is_null($productVariationStock)) {
                        $productVariationStock = new ProductVariationStock;
                        $productVariationStock->product_variation_id = $p_variation->id;
                    }
                    $productVariationStock->stock_qty = $variation['stock'];
                    $productVariationStock->location_id = $location->id;
                    $productVariationStock->save();
                }
                # store new requested variations
                foreach ($new_variations as $variation) {
                    $product_variation = new ProductVariation;
                    $product_variation->product_id = $product->id;
                    $product_variation->variation_key = $variation['variation_key'];
                    $product_variation->price = priceToUsd($variation['price']);
                    $product_variation->sku = $variation['sku'];
                    $product_variation->code = $request->variation_hsn;

                   // 🔥 IMAGE SAVE
$product_variation->image =
$request->variation_gallery[
$variation['variation_key']
]
?? null;
                    // $product_variation->is_active = isset($variation['is_active']) ? 1 : 0;
                    $product_variation->save();
                    $product_variation_stock = new ProductVariationStock;
                    $product_variation_stock->product_variation_id = $product_variation->id;
                    $product_variation_stock->stock_qty = $variation['stock'];
                    $product_variation_stock->location_id = $location->id; // 👈 ADD THIS
                    $product_variation_stock->save();
                    foreach (array_filter(explode("/", $variation['variation_key'])) as $combination) {
                        $product_variation_combination = new ProductVariationCombination;
                        $product_variation_combination->product_id = $product->id;
                        $product_variation_combination->product_variation_id = $product_variation->id;
                        $product_variation_combination->variation_id = explode(":", $combination)[0];
                        $product_variation_combination->variation_value_id = explode(":", $combination)[1];
                        //  $product_variation_combination->is_active = isset($variation['is_active']) ? 1 : 0;
                        $product_variation_combination->save();
                    }
                }
            } else {
                # check if old product is variant then delete all old variation & combinations
                if ($oldProduct->is_variant) {
                    foreach ($product->variations as $variation) {
                        foreach ($variation->combinations as $comb) {
                            $comb->delete();
                        }
                        $variation->delete();
                    }
                }
                $variation = $product->variations->first();
                $variation->product_id = $product->id;
                $variation->variation_key = null;
                $variation->sku = $request->sku;
                $variation->code = $request->code;
                $variation->price = priceToUsd($request->price);
                $variation->save();
                if ($variation->product_variation_stock) {
                    $productVariationStock = $variation->product_variation_stock_without_location()->where('location_id', $location->id)->first();
                    if (is_null($productVariationStock)) {
                        $productVariationStock = new ProductVariationStock;
                    }
                    $productVariationStock->product_variation_id = $variation->id;
                    $productVariationStock->stock_qty = $request->stock;
                    $productVariationStock->location_id = $location->id;
                    $productVariationStock->save();
                } else {
                    $product_variation_stock = new ProductVariationStock;
                    $product_variation_stock->product_variation_id = $variation->id;
                    $product_variation_stock->stock_qty = $request->stock;
                    $product_variation_stock->save();
                }
            }
        }
        # Product Localization
        $ProductLocalization = ProductLocalization::firstOrNew(['lang_key' => $request->lang_key, 'product_id' => $product->id]);
        $ProductLocalization->name = $request->name;
        $ProductLocalization->description = $request->description;
        $ProductLocalization->short_description = $request->short_description;
        $ProductLocalization->save();
        
        flash(localize('Product has been updated successfully'))->success();
        return back();
    }
    # update status
    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->is_featured = $request->status;
        if ($product->save()) {
            return 1;
        }
        return 0;
    }
    # update published
    public function updatePublishedStatus(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->is_published = $request->status;
        if ($product->save()) {
            return 1;
        }
        return 0;
    }



   public function duplicate($id)
{
    $product = Product::with([
        'categories',
        'variations',
        'variation_combinations',
        'variationGalleries',
        'product_localizations',
        'tags',
        'taxes'
    ])->findOrFail($id);

    DB::beginTransaction();

    try {

        // PRODUCT
        $newProduct = $product->replicate();
do {

    $nextProductCode =
    'BZIN' . str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

} while (
    Product::where(
        'product_code',
        $nextProductCode
    )->exists()
);
$newProduct->product_code = $nextProductCode;
        $name =
            $product->collectLocalization(
                'name'
            );

        $newProduct->slug =
            \Str::slug(
                $name . '-copy-' . time()
            );

        $newProduct->is_published = 0;

        $newProduct->created_at =
            now();

        $newProduct->updated_at =
            now();

        $newProduct->save();


        // KEEP PRODUCT DATA
        $newProduct->stock_qty =
            $product->stock_qty;

        $newProduct->min_price =
            $product->min_price;

        $newProduct->max_price =
            $product->max_price;

        $newProduct->has_variation =
            $product->has_variation;

        $newProduct->save();



        // LOCALIZATION
        foreach (
            $product
            ->product_localizations
            as $localization
        ) {

            $newLoc =
                $localization
                ->replicate();

            $newLoc->product_id =
                $newProduct->id;

            $newLoc->name .=
                ' (Copy)';

            $newLoc->save();
        }



        // CATEGORY
        if (
            $product
            ->categories
            ->count()
        ) {

            $newProduct
                ->categories()
                ->sync(
                    $product
                        ->categories
                        ->pluck('id')
                        ->toArray()
                );
        }



        // TAG
        if (
            $product
            ->tags
            ->count()
        ) {

            $newProduct
                ->tags()
                ->sync(
                    $product
                        ->tags
                        ->pluck('id')
                        ->toArray()
                );
        }



        // TAX
        foreach (
            $product
            ->taxes
            as $tax
        ) {

            $newTax =
                $tax
                ->replicate();

            $newTax->product_id =
                $newProduct->id;

            $newTax->save();
        }



        // GALLERY
        foreach (
            $product
            ->variationGalleries
            as $gallery
        ) {

            $newGallery =
                $gallery
                ->replicate();

            $newGallery->product_id =
                $newProduct->id;

            $newGallery->save();
        }



        // VARIATIONS + STOCK
        $variationMap = [];

        foreach (
            $product
            ->variations
            as $variation
        ) {

            $newVariation =
                $variation
                ->replicate();

            $newVariation->product_id =
                $newProduct->id;

            $newVariation->save();



            $stocks =
                ProductVariationStock
                    ::where(
                        'product_variation_id',
                        $variation->id
                    )
                    ->get();


            foreach (
                $stocks
                as $oldStock
            ) {

                $newStock =
                    $oldStock
                    ->replicate();

                $newStock
                    ->product_variation_id =
                    $newVariation->id;

                $newStock->save();
            }


            $variationMap[
                $variation->id
            ] =
            $newVariation->id;
        }



        // COMBINATIONS
        foreach (
            $product
            ->variation_combinations
            as $combination
        ) {

            $newCombination =
                $combination
                ->replicate();

            $newCombination->product_id =
                $newProduct->id;

            if (
                isset(
                    $variationMap[
                        $combination
                        ->product_variation_id
                    ]
                )
            ) {

                $newCombination
                    ->product_variation_id =
                    $variationMap[
                        $combination
                        ->product_variation_id
                    ];
            }

            $newCombination->save();
        }



        DB::commit();

        return redirect()
            ->route(
                'admin.products.edit',
                [
                    'id' =>
                    $newProduct->id,

                    'lang_key' =>
                    env(
                        'DEFAULT_LANGUAGE'
                    )
                ]
            )
            ->with(
                'success',
                'Product duplicated successfully'
            );

    } catch (
        \Exception $e
    ) {

        DB::rollback();

        return back()
            ->with(
                'error',
                $e->getMessage()
            );
    }
}



public function sellerPayout($id)
{
    // PRODUCT
    $product = Product::select(
            'id',
            'name',
            'thumbnail_image',
            'category_id',
            'min_price',
            'package_weight',
            'package_weight_unit'
        )
        ->with([
            'category:id,commission_percentage'
        ])
        ->findOrFail($id);

    // PRODUCT PRICE
    $productPrice =
        $product->min_price ?? 0;

    // COMMISSION %
    $commissionPercent =
        $product->category->commission_percentage ?? 0;

    // GST
    $gst = Gst::select(
            'id',
            'tax'
        )->first();

    // PAYMENT GATEWAY
    $paymentGateway = PaymentGateway::select(
            'id',
            'name'
        )->first();

    // TDS
    $tds = Tsd::select(
            'id',
            'name'
        )->first();



    /*
    |--------------------------------------------------------------------------
    | PRODUCT WEIGHT
    |--------------------------------------------------------------------------
    */

    $productWeight =
    (float) ($product->package_weight ?? 0);

    $productUnit =
    strtolower(trim($product->package_weight_unit));


    /*
    |--------------------------------------------------------------------------
    | UNIT NORMALIZATION
    |--------------------------------------------------------------------------
    */

    // KG
    if (
        $productUnit == 'kilograms' ||
        $productUnit == 'kilogram' ||
        $productUnit == 'kg'
    ) {

        $productUnit = 'kg';
    }


    // GRAMS
    if (
        $productUnit == 'grams' ||
        $productUnit == 'gram' ||
        $productUnit == 'g'
    ) {

        $productUnit = 'gram';
    }


    /*
    |--------------------------------------------------------------------------
    | SMART CONVERSION
    |--------------------------------------------------------------------------
    |
    | Only convert KG to gram if less than 1 KG
    |
    | 0.5 KG = 500 gram
    | 0.75 KG = 750 gram
    |
    | 1 KG and above uses KG slab directly
    |
    */

    if (
        $productUnit == 'kg' &&
        $productWeight <= 1.00
    ) {

        $productWeight =
            $productWeight * 1000;

        $productUnit = 'gram';
    }


    /*
    |--------------------------------------------------------------------------
    | WEIGHT SLAB
    |--------------------------------------------------------------------------
    */

    $weight = ShippingWeight::select(
            'id',
            'title',
            'min_weight',
            'max_weight',
            'unit'
        )
        ->where('unit', $productUnit)
        ->where('min_weight', '<', $productWeight)
        ->where('max_weight', '>=', $productWeight)
        ->first();



    /*
    |--------------------------------------------------------------------------
    | SAFE CHECK
    |--------------------------------------------------------------------------
    */

    if (!$weight) {

        return back()->with(
            'error',
            'No shipping slab found for this product weight'
        );
    }



    /*
    |--------------------------------------------------------------------------
    | SHIPPING CHARGES
    |--------------------------------------------------------------------------
    */

    $shippings = ShippingCharge::select(
            'id',
            'zone_id',
            'weight_id',
            'charge',
            'shipping_gst',
            'total_charge',
            'cod_charge',
            'cod_gst',
            'total_charge_with_cod'
        )
        ->with([
            'zone:id,zone_name'
        ])
        ->where('weight_id', $weight->id)
        ->orderBy('zone_id', 'asc')
    ->get();



    /*
    |--------------------------------------------------------------------------
    | SAFE CHECK
    |--------------------------------------------------------------------------
    */

    if ($shippings->isEmpty()) {

        return back()->with(
            'error',
            'No shipping charges found for this weight slab'
        );
    }



    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'backend.pages.products.products.payout',
        compact(
            'product',
            'productPrice',
            'commissionPercent',
            'gst',
            'paymentGateway',
            'tds',
            'weight',
            'shippings'
        )
    );
}

public function adminPayout($id)
{
    /*
    |--------------------------------------------------------------------------
    | PRODUCT
    |--------------------------------------------------------------------------
    */

    $product = Product::select(
            'id',
            'category_id',
            'name',
            'thumbnail_image',
            'min_price',
            'package_weight',
            'package_weight_unit'
        )
        ->with([
            'category:id,commission_percentage'
        ])
        ->findOrFail($id);



    /*
    |--------------------------------------------------------------------------
    | PRODUCT PRICE
    |--------------------------------------------------------------------------
    */

    $productPrice =
        $product->min_price ?? 0;



    /*
    |--------------------------------------------------------------------------
    | COMMISSION %
    |--------------------------------------------------------------------------
    */

    $commissionPercent =
        $product->category->commission_percentage ?? 0;



    /*
    |--------------------------------------------------------------------------
    | GST
    |--------------------------------------------------------------------------
    */

    $gst = Gst::select(
            'id',
            'tax'
        )->first();



    /*
    |--------------------------------------------------------------------------
    | PAYMENT GATEWAY
    |--------------------------------------------------------------------------
    */

    $paymentGateway = PaymentGateway::select(
            'id',
            'name'
        )->first();



    /*
    |--------------------------------------------------------------------------
    | TDS
    |--------------------------------------------------------------------------
    */

    $tds = Tsd::select(
            'id',
            'name'
        )->first();



    /*
    |--------------------------------------------------------------------------
    | PRODUCT WEIGHT
    |--------------------------------------------------------------------------
    */

    $productWeight =
    (float) ($product->package_weight ?? 0);

$productUnit =
    strtolower(trim($product->package_weight_unit));


        /*
        |--------------------------------------------------------------------------
        | UNIT NORMALIZATION
        |--------------------------------------------------------------------------
        */

        // KG
        if (
            $productUnit == 'kilograms' ||
            $productUnit == 'kilogram' ||
            $productUnit == 'kg'
        ) {

            $productUnit = 'kg';
        }


        // GRAMS
        if (
            $productUnit == 'grams' ||
            $productUnit == 'gram' ||
            $productUnit == 'g'
        ) {

            $productUnit = 'gram';
        }


/*
|--------------------------------------------------------------------------
| SMART CONVERSION
|--------------------------------------------------------------------------
|
| Only convert KG to gram if less than 1 KG
|
| 0.5 KG = 500 gram
| 0.75 KG = 750 gram
|
| 1 KG and above uses KG slab directly
|
*/

if (
    $productUnit == 'kg' &&
    $productWeight < 1
) {

    $productWeight =
        $productWeight * 1000;

    $productUnit = 'gram';
}


    /*
    |--------------------------------------------------------------------------
    | WEIGHT SLAB
    |--------------------------------------------------------------------------
    */

    $weight = ShippingWeight::select(
            'id',
            'title',
            'min_weight',
            'max_weight',
            'unit'
        )
        ->where('unit', $productUnit)
        ->where('min_weight', '<', $productWeight)
        ->where('max_weight', '>=', $productWeight)
        ->first();



    /*
    |--------------------------------------------------------------------------
    | SAFE CHECK
    |--------------------------------------------------------------------------
    */

    if (!$weight) {

        return back()->with(
            'error',
            'No shipping slab found for this product weight'
        );
    }



    /*
    |--------------------------------------------------------------------------
    | SHIPPING CHARGES
    |--------------------------------------------------------------------------
    */

    $shippings = ShippingCharge::select(
            'id',
            'zone_id',
            'weight_id',

            // CUSTOMER SIDE
            'charge',
            'shipping_gst',
            'total_charge',

            'cod_charge',
            'cod_gst',
            'total_charge_with_cod',

            // ADMIN SIDE
            'admin_charge',
            'admin_shipping_gst',
            'admin_total_charge',

            'admin_cod_charge',
            'admin_cod_gst',
            'admin_total_charge_with_cod',

            // MARGIN
            'admin_margin_shipping',
            'admin_margin_cod'
        )
        ->with([
            'zone:id,zone_name'
        ])
        ->where('weight_id', $weight->id)
        ->orderBy('zone_id', 'asc')
        ->get();



    /*
    |--------------------------------------------------------------------------
    | SAFE CHECK
    |--------------------------------------------------------------------------
    */

    if ($shippings->isEmpty()) {

        return back()->with(
            'error',
            'No shipping charges found for this weight slab'
        );
    }



    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'backend.pages.products.products.adminpayout',
        compact(
            'product',
            'productPrice',
            'commissionPercent',
            'gst',
            'paymentGateway',
            'tds',
            'weight',
            'shippings'
        )
    );
}
public function purchaseQuantityRequest(Request $request)
{
    $request->validate([
        'product_id'         => 'required|exists:products,id',
        'requested_quantity' => 'required|integer|min:11',
    ]);

    $product = Product::findOrFail($request->product_id);

    // Same product ki pending request dobara na bane
    $alreadyPending = PurchaseQuantityRequest::where(
        'product_id',
        $product->id
    )
    ->where('seller_id', auth()->id())
    ->where('status', 'pending')
    ->exists();

    if ($alreadyPending) {

        flash('A request is already pending for this product.')
            ->warning();

        return back();
    }

    PurchaseQuantityRequest::create([
        'seller_id'          => auth()->id(),
        'product_id'         => $product->id,
        'old_quantity'       => $product->admin_max_purchase_qty ?? 10,
        'requested_quantity' => $request->requested_quantity,
        'status'             => 'pending',
    ]);

    flash('Purchase quantity request submitted successfully.')
        ->success();

    return back();
}
    # delete product
    public function delete($id)
    {
        // product find karo
        $product = Product::findOrFail($id);
        // product ko delete karo
        $product->delete();
        // redirect back with success message
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}

