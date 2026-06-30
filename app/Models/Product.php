<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    // ⭐ IMPORTANT (for icon slider JSON auto convert to array)
    protected $casts = [
        'icon_slider' => 'array',
        'additional_info' => 'array',
        'about_items'    => 'array',   // NEW
        'product_info'   => 'array', 
        'brand_specs' => 'array',  // NEW
        
    ];

    // Auto load localizations
    protected $with = ['product_localizations'];

    // 🔹 Shop Scope (FIXED - missing function tha)
//     public function scopeShop($query)
// {
//     dd(auth()->user()->getRoleNames());
//     dd(
//         auth()->id(),
//         auth()->user()->user_type,
//         auth()->user()->hasRole('vendor')
//     );
// }
    public function scopeShop($query)
    {
        // Agar vendor hai to uska shop_id nikalo
        if (auth()->check() && auth()->user()->hasRole('vendor')) {
            $shop = \App\Models\Shop::where('user_id', auth()->id())->first();
            if ($shop) {
               
                return $query->where('shop_id', $shop->id);
            }
        }

        // Agar admin hai to sab dikhao
        return $query;
    }

    // 🔹 Published Scope
    public function scopeIsPublished($query)
    {
        return $query->where('is_published', 1);
    }

    // 🔹 Localization Relation
    public function product_localizations()
    {
        return $this->hasMany(ProductLocalization::class);
    }

    // 🔹 Collect Localization (safe)
    public function collectLocalization($entity = '', $lang_key = '')
    {
        $lang_key = $lang_key == '' ? app()->getLocale() : $lang_key;
        $product_localizations = $this->product_localizations
            ->where('lang_key', $lang_key)
            ->first();

        return $product_localizations != null && isset($product_localizations->$entity)
            ? $product_localizations->$entity
            : $this->$entity;
    }

    // 🔹 Categories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function product_categories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    // 🔹 Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // 🔹 Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // 🔹 Variations
    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function variation_combinations()
    {
        return $this->hasMany(ProductVariationCombination::class);
    }

    // 🔹 Taxes
    public function taxes()
    {
        return $this->hasMany(ProductTax::class);
    }

    public function product_taxes()
    {
        return $this->belongsToMany(Tax::class, 'product_taxes', 'product_id', 'tax_id');
    }

    // 🔹 Tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }
    public function variationGalleries()
{
    return $this->hasMany(VariationGallery::class);
}
    // ⭐ Reviews (bazaron rating system core)
public function reviews()
{
    return $this->hasMany(\App\Models\Review::class, 'product_id');
}
// 🔹 Vendor (FIX)
public function vendor()
{
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}
public function category()
{
    return $this->belongsTo(Category::class);
}
public function vendorProfile()
{
    return $this->belongsTo(\App\Models\VendorProfile::class, 'vendor_id');
}

}
