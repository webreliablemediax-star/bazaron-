<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // HERO SLIDER
        $sliders = Slider::where('is_active', 1)->get();

        // TOP CATEGORIES (overlay + section dono ke liye)
        $top_category_ids = getSetting('top_category_ids') != null
            ? json_decode(getSetting('top_category_ids'), true)
            : [];

        $topCategories = Category::whereIn('id', $top_category_ids)
            ->where('is_active', 1)
            ->get();

        return view(
            'frontend.default.pages.home',
            compact('sliders', 'topCategories')
        );
    }
}
