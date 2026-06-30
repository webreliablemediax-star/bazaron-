<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\MegaMenuColumn;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);

        View::composer('*', function ($view) {

            $currentNavbarCategory = null;

            // 🟢 Navbar Categories (Orange Bar)
            $navbarCategoryIds = json_decode(getSetting('navbar_categories'), true);
            $navbarCategoryIds = is_array($navbarCategoryIds) ? $navbarCategoryIds : [];

            $navbarCategories = Cache::remember(
                'navbar_categories_full',
                3600,
                function () use ($navbarCategoryIds) {

                    if (empty($navbarCategoryIds)) {
                        return collect([]);
                    }

                    return Category::with([
                        // Level 1
                        'childrenCategories' => function ($q) {
                            $q->where('is_active', 1)
                              ->whereNull('deleted_at')
                              ->orderBy('sorting_order_level');
                        },
                        // Level 2
                        'childrenCategories.childrenCategories' => function ($q) {
                            $q->where('is_active', 1)
                              ->whereNull('deleted_at')
                              ->orderBy('sorting_order_level');
                        }
                    ])
                    ->whereIn('id', $navbarCategoryIds)
                    ->where('is_active', 1)
                    ->orderBy('sorting_order_level')
                    ->get();
                }
            );

            // 🟡 Active Category detect (for white strip)
            if (request()->has('category_id')) {
                $clicked = Category::with('parent')->find(request()->category_id);

                if ($clicked) {
                    $currentNavbarCategory = $clicked->parent_id
                        ? Category::with('childrenCategories')->find($clicked->parent_id)
                        : $clicked;
                }
            }

            // 🔥 MOST IMPORTANT: Load Mega Menu Columns (Shop By Brand etc)
            // 🔥 Mega Menu Columns (Flyout Data - No Hardcoding)
$megaMenuColumns = MegaMenuColumn::with([
        'categories',   // pivot mapping (mega_menu_column_category)
        'variation',
        'brand' // 🔥 ADD THIS
    ])
    ->where('is_active', 1)
    ->orderBy('order')
    ->get();

            // 🚀 Share globally to ALL frontend views
            $view->with([
                'currentNavbarCategory' => $currentNavbarCategory,
                'navbarCategories'      => $navbarCategories,
                'megaMenuColumns'       => $megaMenuColumns, // ⭐ THIS WAS MISSING
            ]);
        });
    }
}