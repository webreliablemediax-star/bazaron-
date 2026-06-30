@extends('frontend.default.layouts.master')

@section('title', $category->meta_title ?: $category->name)

@section('meta_description')
    {{ $category->meta_description ?: strip_tags($category->description) }}
@endsection

@section('meta_keywords')
    {{ $category->meta_keywords ?? $category->name }}
@endsection

@section('meta')
    <meta property="og:title" content="{{ $category->meta_title ?: $category->name }}" />

    <meta property="og:description" content="{{ $category->meta_description ?: strip_tags($category->description) }}" />

    <meta property="og:url" content="{{ url()->current() }}" />

    @if (!empty($category->thumbnail_image))
        <meta property="og:image" content="{{ uploadedAsset($category->thumbnail_image) }}" />
    @endif
@endsection

@section('contents')
    @include('frontend.default.inc.breadcrumb')


    {{-- WHITE SUBCATEGORY STRIP (FINAL FIXED) --}}
    @if (isset($activeSubCategories) && $activeSubCategories->count())
        <div class="subcategory-strip bg-white border-bottom py-3">
            <ul class="subcat-list d-flex align-items-center gap-4 mb-0 justify-content-center">

                @foreach ($activeSubCategories->filter(function ($cat) {
            $name = strtolower($cat->collectLocalization('name'));
            return !str_contains($name, 'shop by');
        }) as $subCat)
                    <li class="subcat-item position-relative">

                        <a href="{{ route('category.landing', [
                            'slug' => $subCat->slug,
                            'category_code' => $subCat->category_code,
                        ]) }}"
                            class="fw-medium text-dark subcat-link">
                            {{ $subCat->collectLocalization('name') }}
                        </a>

                        @if ($subCat->childrenCategories->count())
                            <div class="nav-fullWidthSubnavFlyout">
                                <div class="mega-inner d-flex flex-wrap">

                                    @foreach ($subCat->childrenCategories as $child)
                                        <div class="col-md-3">
                                            <h6 class="fw-bold mb-2">
                                                <a href="{{ route('category.landing', [
                                                    'slug' => $child->slug,
                                                    'category_code' => $child->category_code,
                                                ]) }}"
                                                class="text-dark text-decoration-none">
                                                    {{ $child->collectLocalization('name') }}
                                                </a>
                                            </h6>

                                            @foreach ($child->childrenCategories as $subChild)
                                                <a href="{{ route('category.landing', [
                                                    'slug' => $subChild->slug,
                                                    'category_code' => $subChild->category_code,
                                                ]) }}"
                                                    class="d-block mb-1 small text-dark">
                                                    {{ $subChild->collectLocalization('name') }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endforeach

                                    @php
                                        $flyoutColumns = collect($megaMenuColumns ?? [])->filter(function (
                                            $column,
                                        ) use ($subCat) {
                                            if (!isset($column->categories) || $column->categories->isEmpty()) {
                                                return false;
                                            }
                                            return $column->categories->pluck('id')->contains($subCat->id);
                                        });
                                    @endphp

                                      @foreach ($flyoutColumns as $column)

     <div class="col-md-3">

        <h6 class="fw-bold mb-2">
            {{ $column->title }}
        </h6>

        @if ($column->type === 'brand')

            @php
                $brandIds = json_decode($column->brand_ids, true) ?? [];

                $brands = \App\Models\Brand::whereIn('id', $brandIds)->get();
            @endphp

            @foreach ($brands as $brand)

                <a href="{{ route('products.index', ['brand_id' => $brand->id]) }}"
                    class="d-block mb-1 small text-dark">

                    {{ $brand->name }}

                </a>

            @endforeach

        @endif

    </div>

@endforeach

                                </div>
                            </div>
                        @endif

                    </li>
                @endforeach

            </ul>
        </div>
    @endif





    <div class="container-fluid ps-1 pe-0 py-5">
        <div class="row gx-5">

            {{-- LEFT SIDEBAR --}}
            <div class="col-xl-2">
                @include('frontend.default.pages.products.inc.productSidebar')
            </div>

            {{-- RIGHT CONTENT --}}
            {{-- RIGHT CONTENT --}}
            <div class="col-xl-10">

                {{-- 🔥 TOP BIG BANNER --}}
                @php
                    $banner1 = null;

                    if (!empty($category->banner_image_1)) {
                        $banner1 = $category->banner_image_1;
                    } elseif (!empty($category->parentCategory) && !empty($category->parentCategory->banner_image_1)) {
                        $banner1 = $category->parentCategory->banner_image_1;
                    }
                @endphp

                @if ($banner1)
                    <div class="mb-5">
                        @php
                            $link1 = '#';

                            if (!empty($category->banner_link_1)) {
                                $link1 = $category->banner_link_1;
                            } elseif (
                                !empty($category->parentCategory) &&
                                !empty($category->parentCategory->banner_link_1)
                            ) {
                                $link1 = $category->parentCategory->banner_link_1;
                            }
                        @endphp

                        <a href="{{ $link1 }}">
                            <img src="{{ uploadedAsset($banner1) }}"
                                class="img-fluid w-100 rounded-3 shadow-sm category-big-banner">
                        </a>
                    </div>
                @endif


                {{-- 🔥 BLOCK 1 : SUBCATEGORIES --}}
               @if ($subcategories->count())

                    <div class="row gx-4 gy-4 mb-5">
                        @foreach ($subcategories as $sub)
                            <div class="col-xl-2-4 col-lg-3 col-md-4 col-6">
                                <a href="{{ route('category.landing', [
                                    'slug' => $sub->slug,
                                    'category_code' => $sub->category_code,
                                ]) }}"
                                    class="category-card-lg text-decoration-none">

                                    <h5 class="category-title-lg">
                                        {{ $sub->name }}
                                    </h5>

                                    <div class="category-img-lg">
                                        <img
                                            src="{{ $sub->thumbnail_image
                                                ? uploadedAsset($sub->thumbnail_image)
                                                : asset('frontend/default/images/placeholder.png') }}">
                                    </div>

                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="row g-4 my-3">
                        @foreach ($products as $product)
                            <div class="col-xl-2-4 col-lg-3 col-md-4 col-sm-6 col-6">
                                @include('frontend.default.pages.partials.products.vertical-product-card', [
                                    'product' => $product,
                                    'bgClass' => 'bg-white',
                                ])
                            </div>
                        @endforeach
                    </div>

                @endif


                {{-- 🔥 MIDDLE BANNER --}}
                @php
                    $banner2 = null;

                    if (!empty($category->banner_image_2)) {
                        $banner2 = $category->banner_image_2;
                    } elseif (!empty($category->parentCategory) && !empty($category->parentCategory->banner_image_2)) {
                        $banner2 = $category->parentCategory->banner_image_2;
                    }
                @endphp

                @if ($banner2)
                    <img src="{{ uploadedAsset($banner2) }}"
                        class="img-fluid w-100 rounded-3 shadow-sm category-big-banner my-5">
                @endif


                {{-- 🔥 BLOCK 2 --}}
                <!-- <div class="row gx-4 gy-4 my-5">
                            @foreach ($subcategories as $sub)
    <div class="col-xl-2-4 col-lg-3 col-md-4 col-6">
                                    <a href="{{ route('products.index', ['category_id' => $sub->id]) }}"
                                    class="category-card-lg text-decoration-none">

                                        <h5 class="category-title-lg">
                                            {{ $sub->name }}
                                        </h5>

                                        <div class="category-img-lg">
                                            <img src="{{ $sub->thumbnail_image
                                                ? uploadedAsset($sub->thumbnail_image)
                                                : asset('frontend/default/images/placeholder.png') }}"
                                                alt="{{ $sub->name }}">
                                        </div>

                                    </a>
                                </div>
    @endforeach
                        </div> -->


                {{-- 🔥 LAST BANNER --}}
              @if ($category->banner_image_3 || $category->banner_image_4)
                    <div class="row g-4 my-5">

                        @if ($category->banner_image_3)
                            <div class="col-md-6">
                                <a href="{{ $category->banner_link_3 ?? '#' }}">
                                    <img src="{{ uploadedAsset($category->banner_image_3) }}"
                                        class="img-fluid w-100 rounded-3 shadow-sm category-half-banner">
                                </a>
                            </div>
                        @endif

                        @if ($category->banner_image_4)
                            <div class="col-md-6">
                                <a href="{{ $category->banner_link_4 ?? '#' }}">
                                    <img src="{{ uploadedAsset($category->banner_image_4) }}"
                                        class="img-fluid w-100 rounded-3 shadow-sm category-half-banner">
                                </a>
                            </div>
                        @endif

                    </div>
                @endif

                @if ($category->banner_image_5)
                    <div class="my-5">
                        <a href="{{ $category->banner_link_5 ?? '#' }}">
                            <img src="{{ uploadedAsset($category->banner_image_5) }}"
                                class="img-fluid w-100 rounded-3 shadow-sm category-big-banner">
                        </a>
                    </div>
                @endif

                {{-- 🔥 BANNER 6 + 7 --}}
                @if ($category->banner_image_6 || $category->banner_image_7)
                    <div class="row g-4 my-5">

                        @if ($category->banner_image_6)
                            <div class="col-md-6">
                                <a href="{{ $category->banner_link_6 ?? '#' }}">
                                    <img src="{{ uploadedAsset($category->banner_image_6) }}"
                                        class="img-fluid w-100 rounded-3 shadow-sm category-half-banner">
                                </a>
                            </div>
                        @endif

                        @if ($category->banner_image_7)
                            <div class="col-md-6">
                                <a href="{{ $category->banner_link_7 ?? '#' }}">
                                    <img src="{{ uploadedAsset($category->banner_image_7) }}"
                                        class="img-fluid w-100 rounded-3 shadow-sm category-half-banner">
                                </a>
                            </div>
                        @endif

                    </div>
                @endif


                {{-- 🔥 BANNER 8 --}}
                @if ($category->banner_image_8)
                    <div class="my-5">
                        <a href="{{ $category->banner_link_8 ?? '#' }}">
                            <img src="{{ uploadedAsset($category->banner_image_8) }}"
                                class="img-fluid w-100 rounded-3 shadow-sm category-big-banner">
                        </a>
                    </div>
                @endif


                {{-- 🔥 BANNER 9 + 10 --}}
                @if ($category->banner_image_9 || $category->banner_image_10)
                    <div class="row g-4 my-5">

                        @if ($category->banner_image_9)
                            <div class="col-md-6">
                                <a href="{{ $category->banner_link_9 ?? '#' }}">
                                    <img src="{{ uploadedAsset($category->banner_image_9) }}"
                                        class="img-fluid w-100 rounded-3 shadow-sm category-half-banner">
                                </a>
                            </div>
                        @endif

                        @if ($category->banner_image_10)
                            <div class="col-md-6">
                                <a href="{{ $category->banner_link_10 ?? '#' }}">
                                    <img src="{{ uploadedAsset($category->banner_image_10) }}"
                                        class="img-fluid w-100 rounded-3 shadow-sm category-half-banner">
                                </a>
                            </div>
                        @endif

                    </div>
                @endif
            </div>
        </div>

    </div>
    </div>
    </div>



    {{-- 🔥 CATEGORY SEO DESCRIPTION (Above Footer) --}}
    @if (!empty($category->description))
        <div class="container-fluid my-5">
            <div class="category-seo-content p-0 bg-white rounded shadow-sm">
                {!! $category->description !!}
            </div>
        </div>
    @endif

@endsection

<style>


    .subcat-card {
        transition: all 0.3s ease;
        border: 1px solid #f1f1f1;
    }

    /*.subcat-card:hover {*/
    /*    transform: translateY(-6px);*/
    /*    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);*/
    /*}*/

    .subcat-img {
        height: 120px;
        object-fit: contain;
    }

    .subcat-card {
        transition: 0.3s ease;
        border: 1px solid #eee;
    }

    .subcat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .subcat-img {
        height: 120px;
        object-fit: contain;
    }

    .category-title-lg {
        text-align: center;
        font-size: 14px;
        font-weight: 500;
        margin-top: 8px;
    }

    .col-xl-2-4 {
        flex: 0 0 25%;
        max-width: 25%;
    }

    @media (max-width: 992px) {
        .col-xl-2-4 {
            flex: 0 0 33.33%;
            max-width: 33.33%;
        }
    }

    @media (max-width: 768px) {
        .col-xl-2-4 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    .nav-fullWidthSubnavFlyout {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: #fff;
        width: 100vw;
        z-index: 9999;
        padding: 30px;
    }

    .category-big-banner {
        height: 420px;
        object-fit: cover;
        border-radius: 16px;
        transition: transform 0.4s ease;
    }

    .category-big-banner:hover {
        transform: scale(1.02);
    }

    .category-page-wrapper .container-fluid {
        padding-right: 0 !important;
    }

    .category-page-wrapper .row {
        margin-right: 0 !important;
    }

    .category-seo-content {
        font-size: 14px;
        line-height: 1.8;
        color: #333;
    }

    .category-seo-content h1,
    .category-seo-content h2 {
        font-size: 22px;
        font-weight: 600;
        margin-top: 20px;
    }

    .category-seo-content h3 {
        font-size: 18px;
        font-weight: 600;
        margin-top: 15px;
    }

    .category-seo-content p {
        margin-bottom: 12px;
    }

    .category-seo-content h1 {
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .category-seo-content h2 {
        font-size: 22px;
        font-weight: 600;
        margin-top: 35px;
        margin-bottom: 10px;
    }

    .category-seo-content h3 {
        font-size: 18px;
        font-weight: 600;
        margin-top: 20px;
        margin-bottom: 5px;
    }

    .category-seo-content p {
        font-size: 15px;
        line-height: 1.9;
        margin-bottom: 12px;
    }
</style>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.subcat-item').forEach(item => {
                item.addEventListener('mouseenter', () => {
                    const flyout = item.querySelector('.nav-fullWidthSubnavFlyout');
                    if (flyout) flyout.style.display = 'block';
                });
                item.addEventListener('mouseleave', () => {
                    const flyout = item.querySelector('.nav-fullWidthSubnavFlyout');
                    if (flyout) flyout.style.display = 'none';
                });
            });
        });
    </script>
@endsection
