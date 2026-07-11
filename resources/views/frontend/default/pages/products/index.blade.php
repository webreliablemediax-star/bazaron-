    @extends('frontend.default.layouts.master')

    @section('title')
        {{ localize('Products') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
    @endsection

    @section('breadcrumb-contents')
        <div class="breadcrumb-content">
            <h2 class="mb-2 text-center">
                {{ $currentNavbarCategory->name ?? localize('Products') }}
            </h2>

            <nav>
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item fw-bold" aria-current="page">
                        {{ optional($currentNavbarCategory)->collectLocalization('name') ?? localize('Products') }}
                    </li>
                </ol>
            </nav>
        </div>
    @endsection

    @section('contents')
        @include('frontend.default.inc.breadcrumb')

        {{-- WHITE SUBCATEGORY STRIP (FINAL FIXED) --}}
        @if (isset($stripCategories) && $stripCategories->isNotEmpty())
            <div class="subcategory-strip bg-white border-bottom py-3">
                <ul class="subcat-list d-flex align-items-center gap-4 mb-0 justify-content-center">

                    @foreach ($stripCategories->filter(function ($cat) {
            $name = strtolower($cat->collectLocalization('name'));
            return !str_contains($name, 'shop by');
        }) as $subCat)
                        <li class="subcat-item position-relative">

                            {{-- Sub Category Link --}}
                            <a href="{{ route('category.landing', [
                                'slug' => $subCat->slug,
                                'category_code' => $subCat->category_code,
                            ]) }}"
                                class="fw-medium text-dark subcat-link">
                                {{ $subCat->collectLocalization('name') }}
                            </a>

                            {{-- FLYOUT --}}
                            @if ($subCat->childrenCategories->count())
                                <div class="nav-fullWidthSubnavFlyout">
                                    <div class="mega-inner mega-menu-8-grid">

                                        {{-- ===================================================== --}}
                                        {{-- PREPARE DATA --}}
                                        {{-- ===================================================== --}}

                                        @php
                                            /*
                                             * Current sub category ke liye configured
                                             * mega menu columns nikal rahe hain.
                                             */
                                            $flyoutColumns = collect($megaMenuColumns ?? [])->filter(function (
                                                $column,
                                            ) use ($subCat) {
                                                if (!isset($column->categories) || $column->categories->isEmpty()) {
                                                    return false;
                                                }

                                                return $column->categories->pluck('id')->contains($subCat->id);
                                            });

                                            /*
                                             * BRAND columns alag
                                             */
                                            $brandColumns = $flyoutColumns->where('type', 'brand');

                                            /*
                                             * VARIATION columns alag
                                             */
                                            $variationColumns = $flyoutColumns->where('type', 'variation');

                                            /*
                                             * Child Categories ko maximum
                                             * 6 columns me distribute karenge.
                                             */
                                            $childCategories = $subCat->childrenCategories;

                                            $subcategoryColumns = collect([
                                                collect(),
                                                collect(),
                                                collect(),
                                                collect(),
                                                collect(),
                                                collect(),
                                            ]);

                                            /*
                                             * Round-robin distribution
                                             */
                                            foreach ($childCategories as $index => $child) {
                                                $columnIndex = $index % 6;

                                                $subcategoryColumns[$columnIndex]->push($child);
                                            }

                                        @endphp



                                        {{-- ===================================================== --}}
                                        {{-- FIRST 6 FIXED SUBCATEGORY COLUMNS --}}
                                        {{-- ===================================================== --}}

                                        @for ($i = 0; $i < 6; $i++)
                                            <div class="mega-subcategory-column">

                                                @foreach ($subcategoryColumns[$i] as $child)
                                                    <div class="mega-category-group">

                                                        {{-- CHILD CATEGORY --}}

                                                        <h6 class="fw-bold mb-2">

                                                            <a href="{{ route('category.landing', [
                                                                'slug' => $child->slug,
                                                                'category_code' => $child->category_code,
                                                            ]) }}"
                                                                class="text-dark text-decoration-none">

                                                                {{ $child->collectLocalization('name') }}

                                                            </a>

                                                        </h6>



                                                        {{-- SUB CHILD CATEGORIES --}}

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

                                            </div>
                                        @endfor



                                        {{-- ===================================================== --}}
                                        {{-- COLUMN 7 : FIXED BRAND COLUMN --}}
                                        {{-- ===================================================== --}}

                                        <div class="mega-special-column mega-brand-column">

                                            @foreach ($brandColumns as $column)
                                                <div class="mega-category-group">

                                                    <h6 class="fw-bold mb-2">

                                                        {{ $column->title }}

                                                    </h6>


                                                    @php

                                                        $brandIds = [];

                                                        if (!empty($column->brand_ids)) {
                                                            $decoded = json_decode($column->brand_ids, true);

                                                            $brandIds = is_array($decoded) ? $decoded : [];
                                                        } elseif (!empty($column->brand_id)) {
                                                            $brandIds = [$column->brand_id];
                                                        }

                                                        $brands = \App\Models\Brand::whereIn('id', $brandIds)
                                                            ->where('is_active', 1)
                                                            ->get();

                                                    @endphp


                                                    @foreach ($brands as $brand)
                                                        <a href="{{ route('products.index', [
                                                            'brand_id' => $brand->id,
                                                        ]) }}"
                                                            class="d-block mb-1 small text-dark">

                                                            {{ $brand->name }}

                                                        </a>
                                                    @endforeach


                                                </div>
                                            @endforeach

                                        </div>



                                        {{-- ===================================================== --}}
                                        {{-- COLUMN 8 : FIXED VARIATION COLUMN --}}
                                        {{-- ===================================================== --}}

                                        <div class="mega-special-column mega-variation-column">

                                            @foreach ($variationColumns as $column)
                                                <div class="mega-category-group">

                                                    <h6 class="fw-bold mb-2">

                                                        {{ $column->title }}

                                                    </h6>


                                                    @if ($column->variation)
                                                        @php

                                                            $valueQuery = \App\Models\VariationValue::where(
                                                                'variation_id',
                                                                $column->variation_id,
                                                            )->where('is_active', 1);

                                                            if (!empty($column->variation_value_ids)) {
                                                                $selectedValueIds = json_decode(
                                                                    $column->variation_value_ids,
                                                                    true,
                                                                );

                                                                if (
                                                                    is_array($selectedValueIds) &&
                                                                    count($selectedValueIds)
                                                                ) {
                                                                    $valueQuery->whereIn('id', $selectedValueIds);
                                                                }
                                                            }

                                                            $filteredValues = $valueQuery->get();

                                                        @endphp


                                                        @foreach ($filteredValues as $val)
                                                            <a href="{{ route('products.index', [
                                                                'variation_value' => $val->id,
                                                            ]) }}"
                                                                class="d-block mb-1 small text-dark">

                                                                {{ $val->name }}

                                                            </a>
                                                        @endforeach
                                                    @endif


                                                </div>
                                            @endforeach

                                        </div>


                                    </div>
                                </div>
                            @endif

                        </li>
                    @endforeach

                </ul>
            </div>
        @endif




        <form class="filter-form" action="{{ Request::fullUrl() }}" method="GET">
            @if (request()->has('category_id'))
                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
            @endif

            <!--shop grid section start-->
            <section class="gshop-gshop-grid ptb-120">
                <div class="container">
                    <div class="row g-4">
                        <!-- Sidebar -->
                        <div class="col-xl-3">
                            <div class="d-none d-xl-block">
                                @include('frontend.default.pages.products.inc.productSidebar')
                            </div>
                            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasProductFilter"
                                aria-labelledby="offcanvasProductFilterLabel">
                                <div class="offcanvas-body">
                                    <div class="text-end">
                                        <button type="button" class="btn-close text-reset text-end"
                                            data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                    </div>
                                    <nav class="mobile-menu-wrapper scrollbar">
                                        @include('frontend.default.pages.products.inc.productSidebar')
                                    </nav>
                                </div>
                            </div>
                        </div>

                        <!-- Products Grid -->
                        <div class="col-xl-9">

                            @php
                                $cat = $currentNavbarCategory ?? null;

                                function getBanner($cat, $field)
                                {
                                    while ($cat) {
                                        if (!empty($cat->$field)) {
                                            return $cat->$field;
                                        }
                                        $cat = $cat->parentCategory;
                                    }
                                    return null;
                                }
                            @endphp

                            {{-- 🔥 TOP BANNER OUTSIDE GRID --}}
                            @php $banner1 = getBanner($cat, 'banner_image_1'); @endphp

                            @if ($banner1)
                                <div class="mb-4">
                                    <img src="{{ uploadedAsset($banner1) }}" class="w-100 rounded">
                                </div>
                            @endif

                            <div class="shop-grid">
                                @if (isset($activeSubCategories) && $activeSubCategories->isNotEmpty())
                                    <div class="row gx-4  my-5">
                                        @foreach ($activeSubCategories as $sub)
                                            <div class="col-xl-2-4 col-lg-3 col-md-4 col-6">

                                                <a href="{{ route('category.landing', [
                                                    'slug' => $sub->slug,
                                                    'category_code' => $sub->category_code,
                                                ]) }}"
                                                    class="category-card-lg text-decoration-none">

                                                    <h5 class="category-title-lg">
                                                        {{ $sub->collectLocalization('name') }}
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
                                    </div>
                                @endif

                                <!-- Products -->
                                <div class="row g-2"style="margin-top:2px" ;>


                                    @php $count = 0; @endphp

                                    @if (isset($products) && $products->count() > 0)
                                        @forelse($products as $product)
                                            <div class="col-xl-2-4 col-lg-3 col-md-4 col-sm-6 col-6">
                                                @include(
                                                    'frontend.default.pages.partials.products.vertical-product-card',
                                                    [
                                                        'product' => $product,
                                                        'bgClass' => 'bg-white',
                                                    ]
                                                )
                                            </div>

                                            @php $count++; @endphp

                                            {{-- 🔥 AFTER 10 PRODUCTS → Banner 2 --}}
                                            @if ($count == 10)
                                                @php $banner2 = getBanner($cat, 'banner_image_2'); @endphp
                                                @if ($banner2)
                                                    <div class="col-12 mb-4">
                                                        <img src="{{ uploadedAsset($banner2) }}" class="w-100 rounded"
                                                            style="max-height:260px;object-fit:cover;">
                                                    </div>
                                                @endif
                                            @endif

                                            {{-- 🔥 AFTER 20 PRODUCTS → Banner 3 --}}
                                            @if ($count == 20)
                                                @php $banner3 = getBanner($cat, 'banner_image_3'); @endphp
                                                @if ($banner3)
                                                    <div class="col-12 mb-4">
                                                        <img src="{{ uploadedAsset($banner3) }}" class="w-100 rounded"
                                                            style="max-height:260px;object-fit:cover;">
                                                    </div>
                                                @endif
                                            @endif

                                            {{-- 🔥 AFTER 30 PRODUCTS → Banner 4 --}}
                                            @if ($count == 30)
                                                @php $banner4 = getBanner($cat, 'banner_image_4'); @endphp
                                                @if ($banner4)
                                                    <div class="col-12 mb-4">
                                                        <img src="{{ uploadedAsset($banner4) }}" class="w-100 rounded"
                                                            style="max-height:260px;object-fit:cover;">
                                                    </div>
                                                @endif
                                            @endif

                                            {{-- 🔥 AFTER 40 PRODUCTS → Banner 5 --}}
                                            @if ($count == 40)
                                                @php $banner5 = getBanner($cat, 'banner_image_5'); @endphp
                                                @if ($banner5)
                                                    <div class="col-12 mb-4">
                                                        <img src="{{ uploadedAsset($banner5) }}" class="w-100 rounded"
                                                            style="max-height:260px;object-fit:cover;">
                                                    </div>
                                                @endif
                                            @endif
                                        @empty
                                            <div class="col-12 text-center py-5">
                                                <img src="{{ staticAsset('frontend/default/assets/img/empty-cart.svg') }}"
                                                    alt="No Products" class="img-fluid mb-3">
                                                <p class="fw-bold">{{ localize('No products found.') }}</p>
                                            </div>
                                        @endforelse
                                    @endif

                                </div>
                                <!-- Products end -->


                                <!-- Banner 2.. -->
                                {{--
                                @php
                                    $promoBanner2 = getSetting('product_page_banner_2');
                                @endphp

                                @if (!empty($promoBanner2))
                                    <div>...</div>
                                @endif
                                --}}


                                <!-- Banner 2 end.. -->

                                <!-- Pagination -->
                                <ul class="d-flex align-items-center gap-3 mt-7">
                                    {{ $products->appends(request()->input())->links() }}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- meta description --}}
                @if (!empty($activeCategory) && !empty($activeCategory->description))
                    <div class="container-fluid my-5">
                        <div class="category-seo-content p-0 bg-white rounded shadow-sm">
                            {!! $activeCategory->description !!}
                        </div>
                    </div>
                @endif

            </section>
            <!--shop grid section end-->
        </form>
    @endsection

    @section('scripts')
        <script>
            "use strict";

            // Pagination input & sorting
            $('.product-listing-pagination').on('focusout', function() {
                $('.filter-form').submit();
            });
            $('.sort_by').on('change', function() {
                $('.filter-form').submit();
            });

            // bazaron-style parent toggle
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
