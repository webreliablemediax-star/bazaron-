<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Campaign;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    # set theme
    public function theme($name = "")
    {
        session(['theme' => $name]);
        return redirect()->route('home');
    }

    # homepage
    public function index()
    {
        $blogs = Blog::isActive()->latest()->take(3)->get();

        // sliders
        $sliders = getSetting('hero_sliders') != null
            ? json_decode(getSetting('hero_sliders'))
            : [];

        // ✅ TOP CATEGORIES (FIX)
        $top_category_ids = getSetting('top_category_ids') != null
            ? json_decode(getSetting('top_category_ids'), true)
            : [];

        $topCategories = Category::whereIn('id', $top_category_ids)->get();

        // banners
        $banner_section_one_banners = getSetting('banner_section_one_banners') != null
            ? json_decode(getSetting('banner_section_one_banners'))
            : [];

        // client feedback
        $client_feedback = getSetting('client_feedback') != null
            ? json_decode(getSetting('client_feedback'))
            : [];

        // featured products
        $featuredLeftIds = getSetting('featured_products_left') != null
            ? json_decode(getSetting('featured_products_left'))
            : [];

        $featuredProductsLeft = Product::whereIn('id', $featuredLeftIds)
            ->isPublished()
            ->get();

        $featuredRightIds = getSetting('featured_products_right') != null
            ? json_decode(getSetting('featured_products_right'))
            : [];

        $featuredProductsRight = Product::whereIn('id', $featuredRightIds)
            ->isPublished()
            ->get();

        // center banner
        $featuredBanner = getSetting('featured_center_banner');
        $featuredBannerLink = getSetting('featured_banner_link');

        return getView('pages.home', compact(
            'blogs',
            'sliders',
            'banner_section_one_banners',
            'client_feedback',
            'featuredProductsLeft',
            'featuredProductsRight',
            'featuredBanner',
            'featuredBannerLink',
            'topCategories'
        ));
    }

    # all brands
    public function allBrands()
    {
        return getView('pages.brands');
    }

    # all categories
    public function allCategories()
    {
        return getView('pages.categories');
    }

    # all coupons
    public function allCoupons()
    {
        return getView('pages.coupons.index');
    }

    # all offers
    public function allOffers()
    {
        return getView('pages.offers');
    }

    # all blogs
    public function allBlogs(Request $request)
    {
        $searchKey = null;
        $blogs = Blog::isActive()->latest();

        if ($request->search != null) {
            $blogs->where('title', 'like', '%' . $request->search . '%');
            $searchKey = $request->search;
        }

        if ($request->category_id != null) {
            $blogs->where('blog_category_id', $request->category_id);
        }

        $blogs = $blogs->paginate(paginationNumber(5));

        return getView('pages.blogs.index', compact('blogs', 'searchKey'));
    }

    # blog details
    public function showBlog($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        return getView('pages.blogs.blogDetails', compact('blog'));
    }

    # campaigns
    public function campaignIndex()
    {
        return getView('pages.campaigns.index');
    }

    public function showCampaign($slug)
    {
        $campaign = Campaign::where('slug', $slug)->first();
        return getView('pages.campaigns.show', compact('campaign'));
    }

    # about us
    public function aboutUs()
    {
        $features = getSetting('about_us_features') != null
            ? json_decode(getSetting('about_us_features'))
            : [];

        $why_choose_us = getSetting('about_us_why_choose_us') != null
            ? json_decode(getSetting('about_us_why_choose_us'))
            : [];

        return getView('pages.quickLinks.aboutUs', compact('features', 'why_choose_us'));
    }

    # contact us
    public function contactUs()
    {
        return getView('pages.quickLinks.contactUs');
    }

    # dynamic page
    public function showPage($slug)
    {
        $page = Page::where('slug', $slug)->first();
        return getView('pages.quickLinks.index', compact('page'));
    }
}
