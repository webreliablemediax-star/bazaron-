<!-- MOBILE FILTER TOP BAR -->
<div class="mobile-filter-bar d-flex d-lg-none">

    <button class="mobile-filter-btn w-100" id="openMobileFilter">
        <i class="fa fa-sliders-h me-2"></i>
        Filters
    </button>

</div>

<!-- MOBILE FILTER OVERLAY -->
<div class="mobile-filter-overlay" id="mobileFilterOverlay"></div>


<div class="gshop-sidebar bg-white rounded-2 overflow-hidden mobile-filter-drawer" id="mobileFilterDrawer">

    <!-- MOBILE HEADER -->
    <div class="mobile-filter-header d-flex d-lg-none">
        <h5 class="mb-0">Filters</h5>

        <button type="button" class="mobile-filter-close" id="closeMobileFilter">
            <i class="fa fa-times"></i>
        </button>
    </div>

    <!-- Filter by Occasion -->
    <div class="sidebar-widget occasion-filter-widget bg-white py-5 px-4 border-top">

        <div class="widget-title d-flex">
            <h6 class="mb-0 flex-shrink-0">{{ localize('Filter by Occasion') }}</h6>
            <span class="hr-line w-100 position-relative d-block align-self-end ms-1"></span>
        </div>

        <form method="GET" action="{{ route('products.index') }}">

            <input type="hidden" name="search" value="{{ request()->search }}">
            <input type="hidden" name="min_price" value="{{ request()->min_price }}">
            <input type="hidden" name="max_price" value="{{ request()->max_price }}">
            <input type="hidden" name="category_id" value="{{ request()->category_id }}">
            <input type="hidden" name="tag_id" value="{{ request()->tag_id }}">
            <input type="hidden" name="age" value="{{ request()->age }}">
            <input type="hidden" name="size" value="{{ request()->size }}">

            <div class="mt-4">

                @foreach ($occasions as $occasion)
                    <div class="form-check mb-2">

                        <input class="form-check-input" type="checkbox" name="occasion[]" value="{{ $occasion->id }}"
                            id="occasion_{{ $occasion->id }}"
                            {{ in_array($occasion->id, (array) request()->occasion) ? 'checked' : '' }}>

                        <label class="form-check-label" for="occasion_{{ $occasion->id }}">
                            {{ $occasion->name }}
                        </label>

                    </div>
                @endforeach

            </div>

            <button type="submit" class="btn btn-primary btn-sm mt-3 w-100">
                {{ localize('Apply') }}
            </button>

        </form>

    </div>
    <!-- Filter by Occasion -->


    <!-- Filter by Categories -->
    <div class="sidebar-widget category-widget bg-white py-5 px-4 border-top">

        <div class="widget-title d-flex">
            <h6 class="mb-0 flex-shrink-0">Categories</h6>
            <span class="hr-line w-100 position-relative d-block align-self-end ms-1"></span>
        </div>

        <div class="mt-4">

            @php
                $categoryLinks = $sidebarCategories
                    ?? $subcategories
                    ?? $activeSubCategories
                    ?? collect();
            @endphp

            @if ($categoryLinks->count())

                @foreach ($categoryLinks as $subCat)
                    <div class="mb-2">

                        <a href="{{ route('category.landing', [
                            'slug' => $subCat->slug,
                            'category_code' => $subCat->category_code,
                        ]) }}"
                            class="fw-medium text-dark">

                            {{ $subCat->collectLocalization('name') }}

                        </a>

                    </div>  
                @endforeach

            @endif
            
        </div>

    </div>
    <!-- Filter by Categories -->


    <!-- Filter by Price -->
    <div class="sidebar-widget price-filter-widget bg-white py-5 px-4 border-top">

        <div class="widget-title d-flex">
            <h6 class="mb-0 flex-shrink-0">{{ localize('Filter by Price') }}</h6>
            <span class="hr-line w-100 position-relative d-block align-self-end ms-1"></span>
        </div>

        <div class="at-pricing-range mt-4">

            <form class="range-slider-form" method="GET" action="{{ route('products.index') }}">

                <div class="price-filter-range"></div>

                <div class="d-flex align-items-center mt-3">

                    <input type="number" min="0" value="{{ request()->min_price ?? $min_value }}"
                        class="min_price price-range-field price-input price-input-min" name="min_price"
                        data-value="{{ $min_value }}" data-min-range="0">

                    <span class="d-inline-block ms-2 me-2 fw-bold">-</span>

                    <input type="number" max="{{ $max_range }}" value="{{ request()->max_price ?? $max_value }}"
                        class="max_price price-range-field price-input price-input-max" name="max_price"
                        data-value="{{ $max_value }}" data-max-range="{{ $max_range }}">

                </div>

                <button type="submit" class="btn btn-primary btn-sm mt-3 w-100">
                    {{ localize('Filter') }}
                </button>

            </form>

        </div>

    </div>
    <!-- Filter by Price -->


    <!-- Filter by Color -->
    <div class="sidebar-widget color-filter-widget bg-white py-5 px-4 border-top">

        <div class="widget-title d-flex">
            <h6 class="mb-0 flex-shrink-0">{{ localize('Filter by Color') }}</h6>
            <span class="hr-line w-100 position-relative d-block align-self-end ms-1"></span>
        </div>

        <form method="GET" action="{{ route('products.index') }}">

            <input type="hidden" name="search" value="{{ request()->search }}">
            <input type="hidden" name="min_price" value="{{ request()->min_price }}">
            <input type="hidden" name="max_price" value="{{ request()->max_price }}">
            <input type="hidden" name="category_id" value="{{ request()->category_id }}">
            <input type="hidden" name="tag_id" value="{{ request()->tag_id }}">

            <div class="mt-4">

                @foreach ($colorValues as $color)
                    <div class="form-check mb-2">

                        <input class="form-check-input" type="checkbox" name="color[]" value="{{ $color }}"
                            id="color_{{ $color }}"
                            {{ in_array($color, (array) request()->color) ? 'checked' : '' }}>

                        <label class="form-check-label" for="color_{{ $color }}">
                            {{ $color }}
                        </label>

                    </div>
                @endforeach

            </div>

            <button type="submit" class="btn btn-primary btn-sm mt-3 w-100">
                {{ localize('Apply') }}
            </button>

        </form>

    </div>
    <!-- Filter by Color -->


    <!-- Filter by Size -->
    @if (!request()->is('category/fashion*'))

        <div class="sidebar-widget size-filter-widget bg-white py-5 px-4 border-top">

            <div class="widget-title d-flex">
                <h6 class="mb-0 flex-shrink-0">{{ localize('Filter by Size') }}</h6>
                <span class="hr-line w-100 position-relative d-block align-self-end ms-1"></span>
            </div>

            <form method="GET" action="{{ route('products.index') }}">

                <input type="hidden" name="search" value="{{ request()->search }}">
                <input type="hidden" name="min_price" value="{{ request()->min_price }}">
                <input type="hidden" name="max_price" value="{{ request()->max_price }}">
                <input type="hidden" name="category_id" value="{{ request()->category_id }}">
                <input type="hidden" name="tag_id" value="{{ request()->tag_id }}">

                <div class="mt-4">

                    @foreach ($sizeValues as $size)
                        <div class="form-check mb-2">

                            <input class="form-check-input" type="checkbox" name="size[]"
                                value="{{ $size }}" id="size_{{ $size }}"
                                {{ in_array($size, (array) request()->size) ? 'checked' : '' }}>

                            <label class="form-check-label" for="size_{{ $size }}">
                                {{ $size }}
                            </label>

                        </div>
                    @endforeach

                </div>

                <button type="submit" class="btn btn-primary btn-sm mt-3 w-100">
                    {{ localize('Apply') }}
                </button>

            </form>

        </div>

    @endif
    <!-- Filter by Size -->

</div>


<style>
    .mobile-filter-bar {
        position: sticky;
        top: 0;
        z-index: 99;
        background: #fff;
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .mobile-filter-btn {
        height: 46px;
        border: none;
        background: #2874f0;
        color: #fff;
        font-weight: 600;
        border-radius: 8px;
    }

    .mobile-filter-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: .3s;
    }

    .mobile-filter-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .mobile-filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        border-bottom: 1px solid #eee;
        position: sticky;
        top: 0;
        background: #fff;
        z-index: 2;
    }

    .mobile-filter-close {
        border: none;
        background: none;
        font-size: 22px;
    }

    @media(max-width:991px) {

        .mobile-filter-drawer {
            position: fixed;
            top: 0;
            left: -100%;
            width: 85%;
            max-width: 360px;
            height: 100vh;
            overflow-y: auto;
            z-index: 9999;
            transition: .35s ease;
            border-radius: 0 !important;
        }

        .mobile-filter-drawer.active {
            left: 0;
        }

        .sidebar-widget {
            padding: 20px 16px !important;
        }

        .widget-title h6 {
            font-size: 15px;
            font-weight: 700;
        }

        .form-check {
            padding: 8px 0;
        }

    }

    @media(min-width:992px) {

        .mobile-filter-bar,
        .mobile-filter-header,
        .mobile-filter-overlay {
            display: none !important;
        }

    }
</style>


<script>
    document.addEventListener("DOMContentLoaded", function() {

        let slider = document.querySelector(".price-filter-range");

        if (slider) {

            let minPrice = document.querySelector(".min_price");
            let maxPrice = document.querySelector(".max_price");

            let min = parseInt(minPrice.dataset.minRange);
            let max = parseInt(maxPrice.dataset.maxRange);

            noUiSlider.create(slider, {
                start: [minPrice.value || min, maxPrice.value || max],
                connect: true,
                range: {
                    'min': min,
                    'max': max
                }
            });

            slider.noUiSlider.on('update', function(values) {
                minPrice.value = Math.round(values[0]);
                maxPrice.value = Math.round(values[1]);
            });

        }


        const openBtn = document.getElementById("openMobileFilter");
        const closeBtn = document.getElementById("closeMobileFilter");
        const drawer = document.getElementById("mobileFilterDrawer");
        const overlay = document.getElementById("mobileFilterOverlay");

        if (openBtn) {

            openBtn.addEventListener("click", function() {

                drawer.classList.add("active");
                overlay.classList.add("active");
                document.body.style.overflow = "hidden";

            });

        }

        if (closeBtn) {

            closeBtn.addEventListener("click", function() {

                drawer.classList.remove("active");
                overlay.classList.remove("active");
                document.body.style.overflow = "";

            });

        }

        if (overlay) {

            overlay.addEventListener("click", function() {

                drawer.classList.remove("active");
                overlay.classList.remove("active");
                document.body.style.overflow = "";

            });

        }

    });
</script>
