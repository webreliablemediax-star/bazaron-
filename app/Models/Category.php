<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    // 🔥 standard relation names eager load
    // protected $with = ['category_localizations', 'parent'];

    /* =======================
       RELATIONSHIPS
    ======================= */

    // Parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id')
                    ->whereNull('deleted_at');
    }

    // Active child categories only
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->where('is_active', 1)
            ->whereNull('deleted_at');
    }

    // Recursive children (safe)
    public function childrenRecursive(){
    return $this->hasMany(Category::class, 'parent_id')
        ->where('is_active', 1)
        ->whereNull('deleted_at')
        ->with('childrenRecursive');
}

    /* =======================
       BACKWARD COMPATIBILITY
    ======================= */

    // In case old code still uses these names
    public function parentCategory()
    {
        return $this->parent();
    }

    public function childrenCategories()
    {
        return $this->children();
    }

    /* =======================
       LOCALIZATION
    ======================= */

    public function category_localizations()
    {
        return $this->hasMany(CategoryLocalization::class);
    }

    public function collectLocalization($entity = '', $lang_key = '')
    {
        $lang_key = $lang_key ?: app()->getLocale();

        $localization = $this->category_localizations
            ->where('lang_key', $lang_key)
            ->first();

        return $localization && $localization->$entity
            ? $localization->$entity
            : $this->$entity;
    }

    /* =======================
       PRODUCTS & BRANDS
    ======================= */

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function brands()
    {
        return $this->belongsToMany(
            Brand::class,
            'category_brands',
            'category_id',
            'brand_id'
        )->where('is_active', 1);
    }

    /* =======================
       MEGA MENU
    ======================= */

    public function megaMenuColumns()
    {
        return $this->belongsToMany(
            \App\Models\MegaMenuColumn::class,
            'mega_menu_column_category',
            'category_id',
            'mega_menu_column_id'
        )->where('is_active', 1);
    }
    public function variations()
{
    return $this->belongsToMany(Variation::class, 'category_variations');
}


public function getBreadcrumbAttribute()
{
    $breadcrumb = [];
    $category = $this;

    while ($category) {
        array_unshift($breadcrumb, $category->name);
        $category = $category->parent;
    }

    return implode(' > ', $breadcrumb);
}

}
