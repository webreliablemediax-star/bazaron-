@extends('backend.layouts.master')
@section('title')
    {{ localize('Add Product') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection
@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Add Product') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4 g-4">
                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                        class="pb-650" id="product-form">
                        @csrf
                        <!--basic information start-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Basic Information') }}</h5>
                                
                                <div class="mb-4">
                                    <label class="form-label">
                                        Product Code
                                    </label>

                                    <input type="text" class="form-control" value="{{ $nextProductCode }}" readonly>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="name" class="form-label">{{ localize('Product Name') }}</label>
                                    <input class="form-control" type="text" id="name"
                                        placeholder="{{ localize('Type your product name') }}" name="name" required>
                                    <span class="fs-sm text-muted">
                                        {{ localize('Product name is required and recommended to be unique.') }}
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <label for="short_description"
                                        class="form-label">{{ localize('Short Description') }}</label>
                                    <textarea class="form-control" id="short_description"
                                        placeholder="{{ localize('Type your product short description') }}" rows="5" name="short_description"></textarea>
                                </div>
                            <div class="mb-4">
    <label class="form-label">Delivery Days</label>

    <input
        type="number"
        class="form-control"
        value="{{ $product->delivery_days ?? ($shipping->handling_days ?? 1) }}"
        readonly
    >

    <small class="text-muted">
        Delivery duration is managed by Admin settings.
    </small>
</div>
                                 <div class="row">
                                    <div class="col-lg-6 mb-4">
                                        <label class="form-label">External Product ID (UPC/EAN)</label>
                                        <input type="text" name="external_product_id" class="form-control"
                                            placeholder="e.g. 8901234567890">
                                        <small class="text-muted">
                                            <!-- Optional: bazaron style unique product identifier -->
                                        </small>
                                    </div>
                                    <!-- Product ID Type -->
                                    <div class="col-lg-6 mb-4">
                                        <label class="form-label">Product ID Type</label>
                                        <select class="form-control" name="product_id_type">
                                            <option value="">Select Type</option>
                                            <option value="UPC">UPC</option>
                                            <option value="EAN">EAN</option>
                                            <option value="ISBN">ISBN</option>
                                            <option value="GTIN">GTIN</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="description" class="form-label">{{ localize('Description') }}</label>
                                    <textarea id="description" class="editor" name="description"></textarea>
                                </div>
                            </div>
                        </div>
                        <!--basic information end-->
                        <!--product category start-->
                        <div class="card mb-4" id="section-3">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Product Categories') }}</h5>
                                <div class="mb-4">
                                    <select id="category_id" class="select2 form-control"
                                        data-placeholder="{{ localize('Select category') }}" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                           
                                            @foreach ($category->childrenCategories as $childCategory)
                                                @include('backend.pages.products.products.subCategory', [
                                                    'subCategory' => $childCategory,
                                                ])
                                            @endforeach
                                        @endforeach
                                    </select>
                                    {{-- 🔥 CATEGORY BREADCRUMB PREVIEW --}}
                                    <div class="mt-2">
                                        <small class="text-muted" id="categoryBreadcrumb">
                                            No category selected
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--product category end-->
                        {{-- min and max selling price --}}
                        <div class="card mb-4" id="section-price-range">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Price Range') }}</h5>
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="min_selling_price"
                                                class="form-label ">{{ localize('Min Selling Price') }} <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="number" min="0" step="1" id="min_selling_price"
                                                name="min_selling_price"
                                                placeholder="{{ localize('Minimum Selling Price') }}" class="form-control"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="max_selling_price"
                                                class="form-label ">{{ localize('Max Selling Price') }} <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="number" min="0" step="1" id="max_selling_price"
                                                placeholder="{{ localize('Maximum Selling Price') }}"
                                                name="max_selling_price" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                         <!--product tax start-->
                        <div class="card mb-4" id="section-8">

                            <div class="card-body">

                                <h5 class="mb-4">

                                    {{ localize('Product Taxes') }}

                                </h5>

                                <div class="row g-3">

                                    @foreach ($taxes as $tax)
                                        @php
                                            $values = explode(',', $tax->tax_value);
                                        @endphp

                                        <!-- TAX VALUE -->
                                        <div class="col-lg-6">

                                            <div class="mb-0">

                                                <label class="form-label">

                                                    {{ $tax->name }}

                                                </label>

                                                <input type="hidden" value="{{ $tax->id }}" name="tax_ids[]">

                                                <select name="taxes[]" class="select2 form-control" required>

                                                    <option value="">
                                                        Select Tax
                                                    </option>

                                                    @foreach ($values as $value)
                                                        <option value="{{ trim($value) }}">

                                                            {{ trim($value) }}%

                                                        </option>
                                                    @endforeach

                                                </select>

                                            </div>

                                        </div>

                                        <!-- TAX TYPE -->
                                        <div class="col-lg-6">

                                            <div class="mb-0">

                                                <label class="form-label">

                                                    {{ localize('Percent or Fixed') }}

                                                </label>

                                                <select class="select2 form-control" name="tax_types[]">

                                                    <option value="percent">

                                                        {{ localize('Percent') }} %

                                                    </option>

                                                    {{-- <option value="flat">

                                                        {{ localize('Fixed') }}

                                                    </option> --}}

                                                </select>

                                            </div>

                                        </div>
                                    @endforeach

                                </div>

                            </div>

                        </div>
                        <!--product tax end-->
                        <!-- ================= PRICE,SKU, & STOCK================= -->
                        <div class="card mb-4" id="section-5">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-4">{{ localize('Price, Sku & Stock') }}</h5>

                                    <div class="form-check form-switch">
                                        <label class="form-check-label fw-medium text-primary"
                                            for="is_variant">{{ localize('Has Variations?') }}</label>
                                        <input type="checkbox" class="form-check-input" id="is_variant"
                                            onchange="isVariantProduct(this)" name="is_variant">
                                    </div>
                                </div>
                                <!-- without variation start-->
                                <div class="noVariation">
                                    <!-- 🔥 HSN ROW (TOP) -->
                                    <div class="row g-3">
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="code" class="form-label">
                                                    {{ localize('HSN Code') }} <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" id="code" name="code"
                                                    value="{{ old('code') }}" placeholder="Enter HSN Code"
                                                    class="form-control" maxlength="8" pattern="\d{4,8}"
                                                    title="HSN must be 4 to 8 digits" inputmode="numeric" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="price" class="form-label">{{ localize('Price') }}</label>
                                                <input type="number" min="0" step="0.0001" id="price"
                                                    name="price" placeholder="{{ localize('Product price') }}"
                                                    class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="stock" class="form-label">{{ localize('Stock') }} <small
                                                        class="text-warning">({{ localize('Default Location') }})</small></label>
                                                <input type="number" id="stock"
                                                    placeholder="{{ localize('Stock qty') }}" name="stock"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="sku" class="form-label">{{ localize('SKU') }}</label>
                                                <input type="text" id="sku"
                                                    placeholder="{{ localize('Product sku') }}" name="sku"
                                                    class="form-control" required>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- without variation start end-->
                                <!--for variation row start-->
                                <div class="hasVariation" style="display: none">
                                    <div class="row mb-3">
                                        <div class="col-lg-3">
                                            <label class="form-label">
                                                {{ localize('HSN Code') }} <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="variation_hsn" placeholder="Enter HSN Code"
                                                class="form-control" maxlength="8" pattern="\d{1,8}"
                                                inputmode="numeric" required disabled>
                                        </div>
                                    </div>
                                    @php
                                        $sizes = \App\Models\VariationValue::isActive()
                                            ->where('variation_id', 1)
                                            ->get();
                                        $colors = \App\Models\VariationValue::isActive()
                                            ->where('variation_id', 2)
                                            ->get();
                                    @endphp
                                    <!-- <div class="row g-3"> -->
                                    <!-- size -->
                                    <!-- @if (count($sizes) > 0)
    <div class="col-lg-6">
                                                                                                      <div class="mb-0">
                                                                                                         <label for="product-thumb"
                                                                                                            class="form-label">{{ localize('Sizes') }}</label>
                                                                                                         <input type="hidden" name="chosen_variations[]" value="1">
                                                                                                         <select class="select2 form-control" multiple="multiple"
                                                                                                            data-placeholder="{{ localize('Select Sizes') }}"
                                                                                                            onchange="generateVariationCombinations()"
                                                                                                            name="option_1_choices[]">
                                                                                                            @foreach ($sizes as $size)
    <option value="{{ $size->id }}">
                                                                                                               {{ $size->collectLocalization('name') }}
                                                                                                            </option>
    @endforeach
                                                                                                         </select>
                                                                                                      </div>
                                                                                                   </div>
    @endif -->
                                    <!-- size end -->
                                    <!-- colors -->
                                    <!-- @if (count($colors) > 0)
    <div class="col-lg-6">
                                                                                                      <div class="mb-0">
                                                                                                         <label for="product-thumb"
                                                                                                            class="form-label">{{ localize('Colors') }}</label> -->
                                    <!-- <input type="hidden" name="chosen_variations[]" value="2"> -->
                                    <!-- <select class="select2 form-control" multiple="multiple"
                                                                                                   data-placeholder="{{ localize('Select colors') }}"
                                                                                                   onchange="generateVariationCombinations()"
                                                                                                   name="option_2_choices[]">
                                                                                                   @foreach ($colors as $color)
    <option value="{{ $color->id }}">
                                                                                                      {{ $color->collectLocalization('name') }}
                                                                                                   </option>
    @endforeach
                                                                                                   </select>
                                                                                                   </div>
                                                                                                   </div>
    @endif -->
                                    <!-- colors end -->
                                    <!-- </div> -->
                                    @if (count($variations) > 0)
                                        <div class="row g-3 mt-1">
                                            <div class="col-lg-6">
                                                <div class="mb-0">
                                                    <label class="form-label">{{ localize('Select Variations') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-0">
                                                    <label class="form-label">{{ localize('Select Values') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="chosen_variation_options">
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <div class="mb-0">
                                                        <select id="variation_select" class="form-select select2"
                                                            onchange="getVariationValues(this)"
                                                            name="chosen_variations[]">
                                                            <option value="">{{ localize('Select a Variation') }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-0">
                                                        <div class="variationvalues">
                                                            <input type="text" class="form-control"
                                                                placeholder="{{ localize('Select variation values') }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-4">
                                                    <div id="variation-limit-alert"
                                                        class="alert alert-danger d-none mt-2">
                                                        You can only choose 2 variations combination. Ex: size,color
                                                    </div>
                                                    <button class="btn btn-link px-0 fw-medium fs-base" type="button"
                                                        onclick="addAnotherVariation()">
                                                        <i data-feather="plus" class="me-1"></i>
                                                        {{ localize('Add Another Variation') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="variation_combination" id="variation_combination">
                                        {{-- combinations will be added here via ajax response --}}
                                    </div>
                                    
                                </div>
                            </div>
                            <!--for variation row end-->
                        </div>
                        <!--product price sku and stock end-->
                        <!--product brand and unit start-->
                        <div class="row" id="section-4">
                            <div class="col-lg-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="mb-4">{{ localize('Product Brand') }}</h5>
                                        <div class="tt-select-brand">
                                            <select class="select2 form-control" id="selectBrand" name="brand_id">
                                                @if (auth()->user()->user_type == 'admin')
                                                    <!-- 👑 ADMIN -->
                                                    <option value="">{{ localize('Select Brand') }}</option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}">
                                                            {{ $brand->collectLocalization('name') }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <!-- 🧑‍💼 VENDOR -->
                                                    <option value="">{{ localize('Select Brand') }}</option>
                                                    <!-- Generic -->
                                                    {{-- <option value="44">Generic</option> --}}
                                                    <!-- Approved Brands -->
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand['id'] }}">
                                                            {{ $brand['name'] }}
                                                        </option>
                                                    @endforeach
                                                    <!-- Add New -->
                                                    <!-- <option value="add_new">+ Add New Brand</option> -->
                                                @endif
                                            </select>
                                        </div>
                                        @if (auth()->user()->user_type != 'admin')
                                            <div class="form-check mt-3">
                                                <input class="form-check-input" type="checkbox" id="noBrandCheckbox"  name="no_brand" value="1">
                                                <label class="form-check-label" for="noBrandCheckbox">
                                                    This product doesn't have a brand
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!--product brand and unit end-->
                        <!-- ================= BRAND SPECS (ADMIN) ================= -->
                        <div class="card mb-4" id="section-brand-specs">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Product Specifications') }}</h5>
                                <div id="brandSpecsRepeater">
                                    <!-- Single Row -->
                                    <div class="row mb-3 brand-spec-row align-items-end">
                                        <div class="col-lg-5">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="brand_spec_title[]" class="form-control"
                                                placeholder="e.g. Fabric, Pattern, Occasion">
                                        </div>
                                        <div class="col-lg-5">
                                            <label class="form-label">Value</label>
                                            <input type="text" name="brand_spec_value[]" class="form-control"
                                                placeholder="e.g. Cotton, Solid, Casual">
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button" class="btn btn-danger removeBrandSpecRow">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add More Button -->
                                <button type="button" class="btn btn-soft-primary" id="addMoreBrandSpecs">
                                    <i class="las la-plus"></i> Add More Specs
                                </button>
                            </div>
                        </div>
                        <!-- ================= END BRAND SPECS ================= -->
                        <!-- icons slider -->
                        <div class="row" id="section-icon-slider">
                            <div class="col-lg-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="mb-4">{{ localize('Icon Slider ') }}</h5>
                                        <div id="iconRepeater">
                                            <!-- Single Row -->
                                            <div class="row align-items-end icon-row mb-3">
                                                <div class="col-lg-4">
                                                    <label class="form-label">{{ localize('Title') }}</label>
                                                    <input type="text" name="icon_titles[]" class="form-control"
                                                        placeholder="Free Delivery">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="form-label">{{ localize('Select Icon') }}</label>
                                                    <select class="form-control icon-select" name="icon_classes[]">
                                                        <!-- Delivery & Shipping -->
                                                        <option value="las la-truck">🚚 Free Delivery</option>
                                                        <option value="las la-shipping-fast">⚡ Fast Delivery</option>
                                                        <option value="las la-box">📦 Safe Packaging</option>
                                                        <option value="las la-globe">🌍 Worldwide Shipping</option>
                                                        <option value="las la-warehouse">🏬 Warehouse Dispatch</option>
                                                        <!-- Trust & Security (Bazaron Style) -->
                                                        <option value="las la-lock">🔒 Secure Payment</option>
                                                        <option value="las la-shield-alt">🛡️ 1 Year Warranty</option>
                                                        <option value="las la-user-shield">👤 Safe Checkout</option>
                                                        <option value="las la-certificate">🏅 Certified Product</option>
                                                        <option value="las la-check-circle">✔️ 100% Original</option>
                                                        <!-- Returns & Support -->
                                                        <option value="las la-sync">🔄 7 Days Replacement</option>
                                                        <option value="las la-undo">↩️ Easy Returns</option>
                                                        <option value="las la-headset">🎧 24/7 Support</option>
                                                        <option value="las la-comments">💬 Live Support</option>
                                                        <option value="las la-phone">📞 Customer Care</option>
                                                        <!-- Quality & Branding -->
                                                        <option value="las la-star">⭐ Highly Rated</option>
                                                        <option value="las la-medal">🏆 Top Brand</option>
                                                        <option value="las la-thumbs-up">👍 Trusted Quality</option>
                                                        <option value="las la-heart">❤️ Customer Favourite</option>
                                                        <option value="las la-award">🥇 Premium Quality</option>
                                                        <!-- Offers & Pricing -->
                                                        <option value="las la-tags">🏷️ Best Price</option>
                                                        <option value="las la-gift">🎁 Special Offers</option>
                                                        <option value="las la-percentage">💸 Big Discounts</option>
                                                        <option value="las la-coins">💰 Cash on Delivery</option>
                                                        <option value="las la-credit-card">💳 Multiple Payments</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label class="form-label">{{ localize('Preview') }}</label>
                                                    <div class="border rounded p-3 text-center bg-light shadow-sm"
                                                        style="font-size: 30px;">
                                                        <i class="las la-truck preview-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1">
                                                    <button type="button" class="btn btn-danger removeRow">
                                                        <i class="las la-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Add More Button -->
                                        <button type="button" class="btn btn-soft-primary" id="addMoreIcon">
                                            <i class="las la-plus"></i> {{ localize('Add More') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- icons slider end -->
                        <!-- ================= ABOUT THIS ITEM (BULLETS) ================= -->
                        <div class="card mb-4" id="section-about-items">
                            <div class="card-body">
                                <h5 class="mb-4">About This Item </h5>
                                <div id="aboutRepeater">
                                    <div class="row mb-3 about-row align-items-end">
                                        <div class="col-lg-10">
                                            <label class="form-label">Bullet Point</label>
                                            <input type="text" name="about_items[]" class="form-control"
                                                placeholder="e.g. Premium Cotton Fabric – Soft & breathable">
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button" class="btn btn-danger removeAboutRow">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-soft-primary" id="addMoreAbout">
                                    <i class="las la-plus"></i> Add Bullet
                                </button>
                            </div>
                        </div>
                        <!-- ================= ABOUT THIS ITEM END (BULLETS) ================= -->
                        <!--product image and gallery start-->
                        <div class="card mb-4" id="section-2">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Images') }}</h5>
                                <!-- Thumbnail -->
                                <div class="mb-4">
                                    <label class="form-label">{{ localize('Thumbnail') }} (1000 x 1000 px or 2000 x 2000 px with white background)</label>
                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">{{ localize('Choose Product Thumbnail') }}</span>
                                        <!-- choose media -->
                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                onclick="showMediaManager(this)" data-selection="single">
                                                <input type="hidden" name="image">
                                                <div class="no-avatar rounded-circle">
                                                    <span><i data-feather="plus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- choose media -->
                                    </div>
                                </div>
                                <!-- Gallery Images (ONLY Media Manager - DO NOT MIX VIDEO HERE) -->
                                <div class="mb-4">
                                    <label class="form-label">{{ localize('Gallery') }} (Size 1000 x 1000 px or 2000 x 2000 px)</label>
                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">{{ localize('Choose Gallery Images') }}</span>
                                        <!-- choose media -->
                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                onclick="showMediaManager(this)" data-selection="multiple">
                                                <input type="hidden" name="images">
                                                <div class="no-avatar rounded-circle">
                                                    <span><i data-feather="plus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- choose media -->
                                    </div>
                                </div>
                                <!-- ⭐ PRODUCT VIDEO (bazaron Style – Separate from Gallery) -->
                                     @if (auth()->user()->user_type == 'admin')                                
                                       <div class="mb-4">
                                    <label class="form-label">
                                        {{ localize('Product Video (YouTube URL)') }}
                                    </label>
                                    <div class="card border">
                                        <div class="card-body">
                                            <!-- YouTube URL -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">YouTube Video URL</label>
                                                @php
                                                    $isVendor = auth()->user()->user_type != 'admin';
                                                @endphp

                                                <input type="text" name="video_url" class="form-control"
                                                    style="{{ $isVendor ? 'background:#f1f3f5; color:#6c757d; cursor:not-allowed;' : '' }}"
                                                    {{ $isVendor ? 'readonly' : '' }}>
                                                <small class="text-muted">
                                                    Recommended: YouTube link (fast loading + auto thumbnail like Bazaron)
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <!--product image and gallery end-->
                       

                        <!-- ================= bazaron PRODUCT ATTRIBUTES (bazaron FULL FIXED) ================= -->
                        <div class="card mb-4" id="section-bazaron-attributes">
                            <div class="card-body">
                                <h5 class="mb-4">Attributes</h5>
                                <div class="row g-3">
                                    <!-- Model Number -->
                                    <div class="col-lg-6">
                                        <label class="form-label">
                                            Model Number <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="model_number" class="form-control"
                                            placeholder="e.g. IP14-MAG-SMKP">
                                    </div>
                                    <!-- Model Name -->
                                    <div class="col-lg-6">
                                        <label class="form-label">
                                            Model Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="model_name" class="form-control"
                                            placeholder="e.g. iPhone 14 Transparent Case">
                                    </div>
                                    <!-- Manufacturer -->
                                    <div class="col-lg-6">
                                        <label class="form-label">
                                            Manufacturer <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="manufacturer_name" class="form-control"
                                            placeholder="e.g. Generic / Brand Name">
                                    </div>
                                    <!-- Generic Keyword -->
                                    <div class="col-lg-6">
                                        <label class="form-label">Generic Keyword</label>
                                        <input type="text" name="generic_keyword" class="form-control"
                                            placeholder="e.g. iphone 14 back cover magsafe case">
                                    </div>
                                    <!-- Special Features -->
                                    <div class="col-lg-12">
                                        <label class="form-label">Special Features</label>
                                        <input type="text" name="special_features" class="form-control"
                                            placeholder="e.g. Shockproof, Slim Fit, Anti-Yellowing">
                                    </div>
                                    <!-- Style -->
                                    <div class="col-lg-6">
                                        <label class="form-label">
                                            Style <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="style" class="form-control"
                                            placeholder="e.g. Minimalist, Transparent">
                                    </div>
                                    <!-- Theme -->
                                      <div class="col-lg-6">
                                        <label class="form-label">
                                            Item Condition <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="item_condition" class="form-control"
                                            placeholder="e.g. New, Old">



                                    </div>
                                    <!-- Material -->
                                    <div class="col-lg-6">
                                        <label class="form-label">Outer Material</label>
                                        <input type="text" name="outer_material" class="form-control"
                                            placeholder="e.g. Polycarbonate + TPU">
                                    </div>
                                    <!-- Compatible Devices -->
                                    <div class="col-lg-6">
                                        <label class="form-label">Compatible Devices</label>
                                        <input type="text" name="compatible_devices" class="form-control"
                                            placeholder="e.g. iPhone 14 (6.1 inch)">
                                    </div>
                                    <!-- Unit Count -->
                                    <div class="col-lg-4">
                                        <label class="form-label">Unit Count</label>
                                        <input type="number" name="unit_count" class="form-control"
                                            placeholder="e.g. 1">
                                    </div>
                                    <!-- Item Type Name -->
                                    <div class="col-lg-4">
                                        <label class="form-label">Item Type Name</label>
                                        <input type="text" name="item_type_name" class="form-control"
                                            placeholder="e.g. Mobile Cover">
                                    </div>
                                    <!-- Number of Items -->
                                    <div class="col-lg-4">
                                        <label class="form-label">Number of Items</label>
                                        <input type="number" name="number_of_items" class="form-control"
                                            placeholder="e.g. 1">
                                    </div>
                                    <!-- Water Resistance Level -->
                                    <div class="col-lg-6">
                                        <label class="form-label">Water Resistance Level</label>
                                        <input type="text" name="water_resistance_level" class="form-control"
                                            placeholder="e.g. Not Water Resistant">
                                    </div>
                                    <!-- Target Gender -->
                                    <div class="col-lg-6">
                                        <label class="form-label">
                                            Target Gender <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="target_gender" class="form-control"
                                            placeholder="e.g. Unisex">
                                    </div>
                                    <!-- Age Range Description -->
                                    <div class="col-lg-6">
                                        <label class="form-label">
                                            Age Range Description <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="age_range_description" class="form-control"
                                            placeholder="e.g. Adult">
                                    </div>
                                    <!-- Subject Character -->
                                    <div class="col-lg-6">
                                        <label class="form-label">Subject Character</label>
                                        <input type="text" name="subject_character" class="form-control"
                                            placeholder="e.g. Batman (optional)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ================= END bazaron ATTRIBUTES ================= -->
                        <!-- ================= END bazaron ATTRIBUTES ================= -->
                        <!-- ================= SAFETY & COMPLIANCE ================= -->
                        <div class="card mb-4" id="section-safety-compliance">
                            <div class="card-body">
                                <h5 class="mb-4">Safety & Compliance</h5>
                                <div class="row g-3">
                                    <!-- Country of Origin -->
                                     <!-- Country of Origin -->
                                    <div class="col-lg-6">
                                        <label class="form-label">
                                            Country of Origin <span class="text-danger">*</span>
                                        </label>

                                        @php
                                            $countries = [
                                                'Afghanistan',
                                                'Albania',
                                                'Algeria',
                                                'Andorra',
                                                'Angola',
                                                'Argentina',
                                                'Armenia',
                                                'Australia',
                                                'Austria',
                                                'Azerbaijan',
                                                'Bahamas',
                                                'Bahrain',
                                                'Bangladesh',
                                                'Barbados',
                                                'Belarus',
                                                'Belgium',
                                                'Belize',
                                                'Benin',
                                                'Bhutan',
                                                'Bolivia',
                                                'Bosnia and Herzegovina',
                                                'Botswana',
                                                'Brazil',
                                                'Brunei',
                                                'Bulgaria',
                                                'Burkina Faso',
                                                'Burundi',
                                                'Cambodia',
                                                'Cameroon',
                                                'Canada',
                                                'Chad',
                                                'Chile',
                                                'China',
                                                'Colombia',
                                                'Costa Rica',
                                                'Croatia',
                                                'Cuba',
                                                'Cyprus',
                                                'Czech Republic',
                                                'Denmark',
                                                'Dominican Republic',
                                                'Ecuador',
                                                'Egypt',
                                                'El Salvador',
                                                'Estonia',
                                                'Ethiopia',
                                                'Finland',
                                                'France',
                                                'Georgia',
                                                'Germany',
                                                'Ghana',
                                                'Greece',
                                                'Guatemala',
                                                'Haiti',
                                                'Honduras',
                                                'Hong Kong',
                                                'Hungary',
                                                'Iceland',
                                                'India',
                                                'Indonesia',
                                                'Iran',
                                                'Iraq',
                                                'Ireland',
                                                'Israel',
                                                'Italy',
                                                'Jamaica',
                                                'Japan',
                                                'Jordan',
                                                'Kazakhstan',
                                                'Kenya',
                                                'Kuwait',
                                                'Kyrgyzstan',
                                                'Laos',
                                                'Latvia',
                                                'Lebanon',
                                                'Libya',
                                                'Lithuania',
                                                'Luxembourg',
                                                'Malaysia',
                                                'Maldives',
                                                'Malta',
                                                'Mexico',
                                                'Mongolia',
                                                'Morocco',
                                                'Myanmar',
                                                'Nepal',
                                                'Netherlands',
                                                'New Zealand',
                                                'Nigeria',
                                                'North Korea',
                                                'Norway',
                                                'Oman',
                                                'Pakistan',
                                                'Palestine',
                                                'Panama',
                                                'Paraguay',
                                                'Peru',
                                                'Philippines',
                                                'Poland',
                                                'Portugal',
                                                'Qatar',
                                                'Romania',
                                                'Russia',
                                                'Saudi Arabia',
                                                'Singapore',
                                                'Slovakia',
                                                'Slovenia',
                                                'South Africa',
                                                'South Korea',
                                                'Spain',
                                                'Sri Lanka',
                                                'Sudan',
                                                'Sweden',
                                                'Switzerland',
                                                'Syria',
                                                'Taiwan',
                                                'Tajikistan',
                                                'Tanzania',
                                                'Thailand',
                                                'Tunisia',
                                                'Turkey',
                                                'Turkmenistan',
                                                'Uganda',
                                                'Ukraine',
                                                'United Arab Emirates',
                                                'United Kingdom',
                                                'United States',
                                                'Uruguay',
                                                'Uzbekistan',
                                                'Venezuela',
                                                'Vietnam',
                                                'Yemen',
                                                'Zambia',
                                                'Zimbabwe',
                                            ];
                                        @endphp

                                        <select name="country_of_origin" class="form-control select2" required>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country }}"
                                                    {{ $country == 'India' ? 'selected' : '' }}>
                                                    {{ $country }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Manufacturer -->
                                    <div class="col-lg-6">
                                        <label class="form-label">
                                            Manufacturer <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="manufacturer" class="form-control"
                                            placeholder="e.g. ABC Pvt Ltd">
                                    </div>
                                    <!-- Importer (Optional) -->
                                    <div class="col-lg-6">
                                        <label class="form-label">Importer Name</label>
                                        <input type="text" name="importer_name" class="form-control"
                                            placeholder="e.g. XYZ Imports">
                                    </div>
                                    <!-- Packer -->
                                    <div class="col-lg-6">
                                        <label class="form-label">
                                            Packer Details <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="packer_details" class="form-control"
                                            placeholder="e.g. Packed by ABC Industries">
                                    </div>
                                    <!-- Safety Information -->
                                    <div class="col-lg-12">
                                        <label class="form-label">Safety Information</label>
                                        <textarea name="safety_information" class="form-control" rows="3"
                                            placeholder="e.g. Keep away from fire, Not suitable for children under 3 years"></textarea>
                                    </div>
                                    <!-- Compliance Certificate -->
                                    <div class="col-lg-12">
                                        <label class="form-label">Compliance / Certifications</label>
                                        <input type="text" name="compliance_certification" class="form-control"
                                            placeholder="e.g. ISO, BIS, CE Certified">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ================= END SAFETY & COMPLIANCE ================= -->
                        <!-- ================= ITEM & PACKAGE DIMENSIONS (bazaron STYLE) ================= -->
                        <div class="card mb-4" id="section-dimensions">
                            <div class="card-body">
                                <h5 class="mb-4">Item & Package Dimensions</h5>
                                <div class="row g-3">
                                    <!-- ITEM LENGTH -->
                                    <div class="col-lg-6">
                                        <label class="form-label">Item Length <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="item_length" class="form-control"
                                            value="{{ old('item_length') }}" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label">Item Length Unit <span
                                                class="text-danger">*</span></label>
                                        <select name="item_length_unit" class="form-control" required>
                                            <option value="cm">Centimetres</option>
                                            <option value="picometre">Picometre</option>
                                            <option value="angstrom">Angstrom</option>
                                            <option value="nanometre">Nanometre</option>
                                            <option value="micron">Micron</option>
                                            <option value="mm">Millimetres</option>
                                            
                                            <option value="dm">Decimetres</option>
                                            <option value="m">Metres</option>
                                            <option value="km">Kilometres</option>
                                            <option value="mil">Mils</option>
                                            <option value="hundredths-inch">Hundredths-Inches</option>
                                            <option value="inch">Inches</option>
                                            <option value="ft">Feet</option>
                                            <option value="mile">Miles</option>
                                            <option value="yard">Yards</option>
                                        </select>
                                    </div>
                                    <!-- ITEM WIDTH -->
                                    <div class="col-lg-6">
                                        <label class="form-label">Item Width <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="item_width" class="form-control"
                                            value="{{ old('item_width') }}" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label">Item Width Unit <span
                                                class="text-danger">*</span></label>
                                        <select name="item_width_unit" class="form-control" required>
                                            <option value="cm">Centimetres</option>
                                            <option value="picometre">Picometre</option>
                                            <option value="angstrom">Angstrom</option>
                                            <option value="nanometre">Nanometre</option>
                                            <option value="micron">Micron</option>
                                            <option value="mm">Millimetres</option>
                                            
                                            <option value="dm">Decimetres</option>
                                            <option value="m">Metres</option>
                                            <option value="km">Kilometres</option>
                                            <option value="mil">Mils</option>
                                            <option value="hundredths-inch">Hundredths-Inches</option>
                                            <option value="inch">Inches</option>
                                            <option value="ft">Feet</option>
                                            <option value="mile">Miles</option>
                                            <option value="yard">Yards</option>
                                        </select>
                                    </div>
                                    <!-- ITEM HEIGHT -->
                                    <div class="col-lg-6">
                                        <label class="form-label">Item Height <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="item_height" class="form-control"
                                            value="{{ old('item_height') }}" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label">Item Height Unit <span
                                                class="text-danger">*</span></label>
                                        <select name="item_height_unit" class="form-control" required>
                                            <option value="cm">Centimetres</option>
                                            <option value="picometre">Picometre</option>
                                            <option value="angstrom">Angstrom</option>
                                            <option value="nanometre">Nanometre</option>
                                            <option value="micron">Micron</option>
                                            <option value="mm">Millimetres</option>
                                            <option value="dm">Decimetres</option>
                                            <option value="m">Metres</option>
                                            <option value="km">Kilometres</option>
                                            <option value="mil">Mils</option>
                                            <option value="hundredths-inch">Hundredths-Inches</option>
                                            <option value="inch">Inches</option>
                                            <option value="ft">Feet</option>
                                            <option value="mile">Miles</option>
                                            <option value="yard">Yards</option>
                                        </select>
                                    </div>
                                    <!-- PACKAGE WEIGHT -->
                                    <div class="col-lg-6">
                                        <label class="form-label">Package Weight <span
                                                class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="package_weight" class="form-control"
                                            value="{{ old('package_weight') }}" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label">Package Weight Unit <span
                                                class="text-danger">*</span></label>
                                        <select name="package_weight_unit" class="form-control" required>
                                            <option value="grams">Grams</option>
                                            <option value="kg">Kilograms</option>
                                            <option value="kg">MilLigrams</option>
                                            <option value="kg">Tons</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ================= ITEM & PACKAGE DIMENSIONS end(bazaron STYLE) ================= -->
                        <!-- ================= ADDITIONAL INFORMATION (ADMIN) ================= -->
                        <div class="card mb-4" id="section-additional-info">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Additional Information ') }}</h5>
                                <div id="additionalInfoRepeater">
                                    <!-- Single Row -->
                                    <div class="row mb-3 additional-info-row align-items-end">
                                        <div class="col-lg-5">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="info_title[]" class="form-control"
                                                placeholder="e.g. Material, Fit Type, Age Group">
                                        </div>
                                        <div class="col-lg-5">
                                            <label class="form-label">Value</label>
                                            <input type="text" name="info_value[]" class="form-control"
                                                placeholder="e.g. 100% Cotton">
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button" class="btn btn-danger removeInfoRow">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add More Button -->
                                <button type="button" class="btn btn-soft-primary" id="addMoreInfo">
                                    <i class="las la-plus"></i> Add More Info
                                </button>
                            </div>
                        </div>
                        <!-- ================= END ADDITIONAL INFORMATION ================= -->
                        <!-- ================= PRODUCT INFO (LEFT TABLE) ================= -->
                        <div class="card mb-4" id="section-product-info">
                            <div class="card-body">
                                <h5 class="mb-4">Product Information</h5>
                                <div id="productInfoRepeater">
                                    <!-- Single Row -->
                                    <div class="row mb-3 product-info-row align-items-end">
                                        <div class="col-lg-5">
                                            <label class="form-label">Label</label>
                                            <input type="text" name="pinfo_title[]" class="form-control"
                                                placeholder="e.g. Material, Fit Type">
                                        </div>
                                        <div class="col-lg-5">
                                            <label class="form-label">Value</label>
                                            <input type="text" name="pinfo_value[]" class="form-control"
                                                placeholder="e.g. 100% Cotton">
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button" class="btn btn-danger removePinfoRow">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-soft-primary" id="addMorePinfo">
                                    <i class="las la-plus"></i> Add Product Info
                                </button>
                            </div>
                        </div>
                        <!-- ================= PRODUCT INFO END (LEFT TABLE) ================= -->
                        <!--product discount start-->
                        <div class="card mb-4" id="section-6">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Product Discount') }}</h5>
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ localize('Date Range') }}</label>
                                            <div class="input-group">
                                                <input class="form-control date-range-picker date-range" type="text"
                                                    placeholder="{{ localize('Start date - End date') }}"
                                                    name="date_range">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="discount_value"
                                                class="form-label">{{ localize('Discount Amount') }}</label>
                                            <input class="form-control" type="number"
                                                placeholder="{{ localize('Type discount amount') }}" id="discount_value"
                                                value="0" step="0.001" name="discount_value">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="discount_type"
                                                class="form-label">{{ localize('Percent or Fixed') }}</label>
                                            <select class="select2 form-control" id="discount_type" name="discount_type">
                                                <option value="percent">{{ localize('Percent %') }}</option>
                                                <option value="flat">{{ localize('Fixed') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--product discount end-->
                        <!--shipping configuration start-->
                        <div class="card mb-4" id="section-7">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Shipping Configuration') }}</h5>
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div class="mb-0">
                                            <label for="min_purchase_qty"
                                                class="form-label">{{ localize('Minimum Purchase Qty') }}</label>
                                            <input type="number" id="min_purchase_qty" name="min_purchase_qty"
                                                min="1" value="1" class="form-control">
                                        </div>
                                    </div>
                                     <div class="col-lg-6">
                                        <div class="mb-0">
                                            <label for="max_purchase_qty"
                                                class="form-label">{{ localize('Maximum Purchase Qty') }}</label>
                                            <input type="number" id="max_purchase_qty" name="max_purchase_qty"
                                                min="1" value="10" class="form-control"
                                                max="{{ auth()->user()->user_type == 'vendor' ? 10 : 999999 }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 d-none">
                                        <div class="mb-0">
                                            <label for="standard_delivery_hours"
                                                class="form-label">{{ localize('Standard Delivery Time') }}</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" class="form-control"
                                                    name="standard_delivery_hours" value="72" min="0" required
                                                    id="standard_delivery_hours">
                                                <div class="input-group-append"><span
                                                        class="input-group-text">hr(s)</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 d-none">
                                        <div class="mb-0">
                                            <label for="express_delivery_hours"
                                                class="form-label">{{ localize('Express Delivery Time') }}</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" class="form-control"
                                                    name="express_delivery_hours" value="24" min="0" required
                                                    id="express_delivery_hours">
                                                <div class="input-group-append"><span
                                                        class="input-group-text">hr(s)</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--shipping configuration end-->
                        
                        <!--product sell target & status start-->
                        <div class="row g-3" id="section-9">
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="mb-4">{{ localize('Sell Target') }}</h5>
                                        <div class="tt-select-brand">
                                            <input type="number" min="0" name="sell_target" class="form-control"
                                                placeholder="{{ localize('Type your sell target') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="mb-4">{{ localize('Product Status') }}</h5>
                                        <div class="tt-select-brand">
                                            <select class="select2 form-control" id="is_published" name="is_published">
                                                <option value="1">{{ localize('Published') }}</option>
                                                <option value="0">{{ localize('Unpublished') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--product sell target & status end-->
                        <!--seo meta description start-->
                        <div class="card mb-4" id="section-10">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('SEO Meta Configuration') }}</h5>
                                <div class="mb-4">
                                    <label for="meta_title" class="form-label">{{ localize('Meta Title') }}</label>
                                    <input type="text" name="meta_title" id="meta_title"
                                        placeholder="{{ localize('Type meta title') }}" class="form-control">
                                    <span class="fs-sm text-muted">
                                        {{ localize('Set a meta tag title. Recommended to be simple and unique.') }}
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <label for="meta_description"
                                        class="form-label">{{ localize('Meta Description') }}</label>
                                    <textarea class="form-control" name="meta_description" id="meta_description" rows="4"
                                        placeholder="{{ localize('Type your meta description') }}"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">{{ localize('Meta Image') }}</label>
                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">{{ localize('Choose Meta Image') }}</span>
                                        <!-- choose media -->
                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                onclick="showMediaManager(this)" data-selection="single">
                                                <input type="hidden" name="meta_image">
                                                <div class="no-avatar rounded-circle">
                                                    <span><i data-feather="plus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- choose media -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--seo meta description end-->
                        <!-- submit button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-primary" type="submit">
                                        <i data-feather="save" class="me-1"></i> {{ localize('Save Product') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- submit button end -->
                    </form>
                </div>
                <!--right sidebar-->
                <div class="col-xl-3 order-1 order-md-1 order-lg-1 order-xl-2">
                    <div class="card tt-sticky-sidebar">
                        <div class="card-body">
                            <h5 class="mb-4">{{ localize('Product Information') }}</h5>
                            <div class="tt-vertical-step">
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="#section-1" class="active">{{ localize('Basic Information') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-3">{{ localize('Category') }}</a>
                                    </li>
                                      <li>
                                        <a href="#section-8">{{ localize('Product Taxes') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-5">{{ localize('Price, SKU, Stock & Variations') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-4">{{ localize('Product Brand & Unit') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-brand-specs">Brand Specs</a>
                                    </li>
                                    <li>
                                        <a href="#section-icon-slider">Icon Slider</a>
                                    </li>
                                    <li>
                                        <a href="#section-about">About This Item</a>
                                    </li>
                                    <li>
                                        <a href="#section-2">{{ localize('Product Images') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-tags">{{ localize('Product tags') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-bazaron-attributes">Bazaron Attributes</a>
                                    </li>
                                    <li>
                                        <a href="#section-safety-compliance">Safety & Compliance</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#section-dimensions">
                                            Item Dimensions
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#section-additional-info">Additional Information</a>
                                    </li>
                                    <li>
                                        <a href="#section-product-info">Product Information</a>
                                    </li>
                                    <li>
                                        <a href="#section-6">{{ localize('Product Discount') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-7">{{ localize('Minimum Purchase') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-9">{{ localize('Sell Target and Status') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-10">{{ localize('SEO Meta Options') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    @include('backend.inc.product-scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // ================= ICON REPEATER =================
            const iconRepeater = document.getElementById('iconRepeater');
            const addIconBtn = document.getElementById('addMoreIcon');

            if (addIconBtn && iconRepeater) {
                addIconBtn.addEventListener('click', function() {
                    const firstRow = iconRepeater.querySelector('.icon-row');
                    const newRow = firstRow.cloneNode(true);

                    newRow.querySelectorAll('input').forEach(input => input.value = '');
                    newRow.querySelector('.icon-select').selectedIndex = 0;
                    newRow.querySelector('.preview-icon').className = 'las la-truck preview-icon';

                    iconRepeater.appendChild(newRow);
                });
            }

            document.addEventListener('click', function(e) {
                if (e.target.closest('.removeRow')) {
                    const rows = document.querySelectorAll('.icon-row');
                    if (rows.length > 1) {
                        e.target.closest('.icon-row').remove();
                    }
                }
            });


            // ================= ADDITIONAL INFO =================
            const infoRepeater = document.getElementById('additionalInfoRepeater');
            const addInfoBtn = document.getElementById('addMoreInfo');

            if (addInfoBtn && infoRepeater) {
                addInfoBtn.addEventListener('click', function() {
                    const firstRow = infoRepeater.querySelector('.additional-info-row');
                    const newRow = firstRow.cloneNode(true);

                    newRow.querySelectorAll('input').forEach(input => input.value = '');
                    infoRepeater.appendChild(newRow);
                });
            }

            document.addEventListener('click', function(e) {
                if (e.target.closest('.removeInfoRow')) {
                    const rows = document.querySelectorAll('.additional-info-row');
                    if (rows.length > 1) {
                        e.target.closest('.additional-info-row').remove();
                    }
                }
            });


            // ================= PRODUCT INFO =================
            const pinfoRepeater = document.getElementById('productInfoRepeater');
            const addPinfoBtn = document.getElementById('addMorePinfo');

            if (addPinfoBtn && pinfoRepeater) {
                addPinfoBtn.addEventListener('click', function() {
                    const firstRow = pinfoRepeater.querySelector('.product-info-row');
                    const newRow = firstRow.cloneNode(true);

                    newRow.querySelectorAll('input').forEach(input => input.value = '');
                    pinfoRepeater.appendChild(newRow);
                });
            }

            document.addEventListener('click', function(e) {
                if (e.target.closest('.removePinfoRow')) {
                    const rows = document.querySelectorAll('.product-info-row');
                    if (rows.length > 1) {
                        e.target.closest('.product-info-row').remove();
                    }
                }
            });


            // ================= ABOUT ITEMS =================
            const aboutRepeater = document.getElementById('aboutRepeater');
            const addAboutBtn = document.getElementById('addMoreAbout');

            if (addAboutBtn && aboutRepeater) {
                addAboutBtn.addEventListener('click', function() {
                    const firstRow = aboutRepeater.querySelector('.about-row');
                    const newRow = firstRow.cloneNode(true);

                    newRow.querySelectorAll('input').forEach(input => input.value = '');
                    aboutRepeater.appendChild(newRow);
                });
            }

            document.addEventListener('click', function(e) {
                if (e.target.closest('.removeAboutRow')) {
                    const rows = document.querySelectorAll('.about-row');
                    if (rows.length > 1) {
                        e.target.closest('.about-row').remove();
                    }
                }
            });


            // ================= BRAND SPECS (MAIN BUG FIX) =================
            const brandSpecsRepeater = document.getElementById('brandSpecsRepeater');
            const addBrandSpecsBtn = document.getElementById('addMoreBrandSpecs');

            if (addBrandSpecsBtn && brandSpecsRepeater) {
                addBrandSpecsBtn.addEventListener('click', function() {
                    const firstRow = brandSpecsRepeater.querySelector('.brand-spec-row');
                    const newRow = firstRow.cloneNode(true);

                    newRow.querySelectorAll('input').forEach(input => input.value = '');
                    brandSpecsRepeater.appendChild(newRow);
                });
            }


            // ===== CATEGORY BREADCRUMB (CREATE PAGE FIX) =====
            const categorySelect = document.querySelector('[name="category_id"]');
            const breadcrumbEl = document.getElementById('categoryBreadcrumb');

            const categories = @json($categories);

            function findCategory(id, list, path = []) {
                for (let cat of list) {
                    if (cat.id == id) {
                        return [...path, cat.name];
                    }
                    if (cat.children_categories && cat.children_categories.length) {
                        let result = findCategory(id, cat.children_categories, [...path, cat.name]);
                        if (result) return result;
                    }
                }
                return null;
            }

            $(categorySelect).on('change', function() {

                let selected = $(this).val();

                if (!selected) {
                    breadcrumbEl.innerText = 'No category selected';
                    return;
                }

                let lastId = selected;

                let path = findCategory(lastId, categories);

                breadcrumbEl.innerText = path ? path.join(' > ') : 'No category selected';
            });







            // 🔒 Gallery Max Limit = 9 (Fixed - Works with Media Manager)
            const galleryInput = document.querySelector('input[name="images"]');

            if (galleryInput) {
                const observer = new MutationObserver(() => {

                    if (!galleryInput.value) return;

                    let ids = galleryInput.value.split(',').filter(id => id.trim() !== '');

                    if (ids.length > 9) {
                        ids = ids.slice(0, 9);
                        galleryInput.value = ids.join(',');

                        alert('Maximum 9 gallery images allowed.');
                    }
                });

                observer.observe(galleryInput, {
                    attributes: true,
                    attributeFilter: ['value']
                });
            }
            //endddd     ...............................................




            document.addEventListener('click', function(e) {
                if (e.target.closest('.removebazaronAttrRow')) {
                    const rows = document.querySelectorAll('.bazaron-attr-row');
                    if (rows.length > 1) {
                        e.target.closest('.bazaron-attr-row').remove();
                    }
                }
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('.removeBrandSpecRow')) {
                    const rows = document.querySelectorAll('.brand-spec-row');
                    if (rows.length > 1) {
                        e.target.closest('.brand-spec-row').remove();
                    }
                }
            });

        });

        // ===== HSN CODE LIMIT =====
        const hsnInputs = document.querySelectorAll("#code, input[name='variation_hsn']");

        hsnInputs.forEach(input => {
            input.addEventListener("input", function() {
                this.value = this.value.replace(/\D/g, '');

                if (this.value.length > 8) {
                    this.value = this.value.slice(0, 8);
                }
            });
        });

        //Category wise variation.........................................................
        $('#category_id').on('change', function() {
            let selected = $(this).val();

            if (!selected) {
                $('#variation_select').html('<option value="">Select Variation</option>');
                return;
            }

            let lastSelectedId = selected;

            $.get('/admin/category/' + lastSelectedId + '/variations', function(data) {
                let html = '<option value="">Select Variation</option>';

                if (data.length === 0) {
                    html = '<option value="">No variation found</option>';
                }

                data.forEach(function(item) {
                    html += `<option value="${item.id}">${item.name}</option>`;
                });

                $('#variation_select').html(html).trigger('change');
            });
        });


        //category click disable onlevel 0,1,2


        $('#category_id').on('select2:selecting', function(e) {

            let level = $(e.params.args.data.element).data('level');

            if (level < 2) {
                alert('Please select last level category');
                return false; // ✅ safe
            }

        });





        function addAnotherVariation() {

            let totalVariations = document.querySelectorAll('.chosen_variation_options .row').length;

            if (totalVariations >= 2) {

                // ❌ alert('You can only choose 2 variations');

                // ✅ show red alert
                let alertBox = document.getElementById('variation-limit-alert');
                alertBox.classList.remove('d-none');

                // auto hide after 3 sec (optional sexy touch 😎)
                setTimeout(() => {
                    alertBox.classList.add('d-none');
                }, 3000);

                return;
            }

            $.ajax({
                url: '/admin/products/get-new-variation',
                type: 'GET',
                data: {
                    category_id: $('#category_id').val()
                },
                dataType: 'json',

                success: function(data) {
                    if (data.count > 0) {
                        $('.chosen_variation_options').append(data.view);
                        $('.select2').select2();
                    } else {
                        alert('No more variations available');
                    }
                },

                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert('Error loading variation');
                }
            });
        }




        // ===== FINAL VARIATION FIX =====
        document.getElementById('product-form').addEventListener('submit', function(e) {

            let isVariant = document.getElementById('is_variant').checked;
            let colorSelect = document.querySelector('[name="option_2_choices[]"]');

            if (isVariant && colorSelect && $(colorSelect).val() && $(colorSelect).val().length > 0) {

                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'chosen_variations[]';
                input.value = 2;

                this.appendChild(input);
            }

            if (isVariant && (!colorSelect || !$(colorSelect).val() || $(colorSelect).val().length === 0)) {
                document.getElementById('is_variant').checked = false;
            }

        });
        // ✅ TOGGLE FUNCTION
        function isVariantProduct(el) {

            let variationBox = document.querySelector('.hasVariation');
            let simpleBox = document.querySelector('.noVariation');

            let vhsn = document.querySelector('input[name="variation_hsn"]');
            let normalHsn = document.getElementById('code');

            let price = document.getElementById('price');
            let stock = document.getElementById('stock');
            let sku = document.getElementById('sku');

            if (el.checked) {

                // ✅ show variation
                variationBox.style.display = 'block';
                simpleBox.style.display = 'none';

                // 🔥 enable variation HSN
                if (vhsn) {
                    vhsn.disabled = false;
                    vhsn.removeAttribute('disabled');
                }

                // 🔥 disable normal HSN
                if (normalHsn) {
                    normalHsn.disabled = true;
                }

                // 🔥 IMPORTANT FIX (REMOVE REQUIRED)
                price.removeAttribute('required');
                stock.removeAttribute('required');
                sku.removeAttribute('required');

            } else {

                // ✅ show simple
                variationBox.style.display = 'none';
                simpleBox.style.display = 'block';

                // 🔥 disable variation HSN
                if (vhsn) {
                    vhsn.disabled = true;
                }

                // 🔥 enable normal HSN
                if (normalHsn) {
                    normalHsn.disabled = false;
                }

                // 🔥 ADD REQUIRED BACK
                price.setAttribute('required', true);
                stock.setAttribute('required', true);
                sku.setAttribute('required', true);
            }
        }


        // ✅ PAGE LOAD INIT
        let toggle = document.getElementById('is_variant');

        if (toggle) {
            isVariantProduct(toggle);

            toggle.addEventListener('change', function() {
                isVariantProduct(this);
            });
        }

        
       // ✅ BRAND CHECKBOX

       $('#noBrandCheckbox').change(function () {

    if ($(this).is(':checked')) {

        if ($("#selectBrand option[value='44']").length == 0) {
            $('#selectBrand').prepend('<option value="44">Generic</option>');
        }

        $('#selectBrand')
            .val('44')
            .css('pointer-events', 'none')
            .trigger('change');

    } else {

        $('#selectBrand')
            .css('pointer-events', 'auto');

        // option remove mat karna agar Generic already brands list me hai
        $('#selectBrand')
            .val('')
            .trigger('change');
    }

});
    </script>
@endsection
