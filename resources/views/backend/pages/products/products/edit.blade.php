@extends('backend.layouts.master')
@section('title')
    {{ localize('Update Product') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection
@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto flex-grow-1">
                                    <div class="tt-page-title">
                                        <h2 class="h5 mb-0">{{ localize('Update Product') }} <sup
                                                class="badge bg-soft-warning px-2">{{ $lang_key }}</sup></h2>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <select id="language" class="w-100 form-control text-capitalize country-flag-select"
                                        data-toggle="select2" onchange="localizeData(this.value)">
                                        @foreach (\App\Models\Language::all() as $key => $language)
                                            <option value="{{ $language->code }}"
                                                {{ $lang_key == $language->code ? 'selected' : '' }}
                                                data-flag="{{ staticAsset('backend/assets/img/flags/' . $language->flag . '.png') }}">
                                                {{ $language->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4 g-4">
                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                        enctype="multipart/form-data" class="pb-650" id="product-form">
                        @csrf
                        <!-- @method('PUT') -->
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <input type="hidden" name="lang_key" value="{{ $lang_key }}">
                        <!--basic information start-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Basic Information') }}</h5>
                                
                                <div class="mb-4">
                                    <label class="form-label">
                                        Product Code
                                    </label>

                                    <input type="text" class="form-control" value="{{ $product->product_code }}"
                                        readonly>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="name" class="form-label">{{ localize('Product Name') }}</label>
                                    <input class="form-control" type="text" id="name"
                                        placeholder="{{ localize('Type your product name') }}" name="name"
                                        value="{{ $product->collectLocalization('name', $lang_key) }}" required>
                                    <span class="fs-sm text-muted">
                                        {{ localize('Product name is required and recommended to be unique.') }}
                                    </span>
                                </div>

                                <div class="mb-4">
                                    <label for="short_description"
                                        class="form-label">{{ localize('Short Description') }}</label>
                                    <textarea class="form-control" id="short_description" 
                                    placeholder="{{ localize('Type your product short description') }}" rows="5" name="short_description">{{ $product->collectLocalization('short_description', $lang_key) }}</textarea>
                                </div>
                            <div class="mb-4">
                                <label class="form-label">Delivery Days</label>

    <input
        type="number"
        class="form-control"
        value="{{ $product->delivery_days ?? 1 }}"
        readonly
    >

    <input
        type="hidden"
        name="delivery_days"
        value="{{ $product->delivery_days ?? 1 }}"
    >

    <input
        type="hidden"
        name="standard_delivery_hours"
        value="{{ $product->standard_delivery_hours }}"
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
                                    <textarea id="description" class="editor" name="description">{{ $product->collectLocalization('description', $lang_key) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <!--basic information end-->
                        @if (env('DEFAULT_LANGUAGE') == $lang_key)
                            <!--product category start-->
                            <div class="card mb-4" id="section-3">
                                <div class="card-body">
                                    <h5 class="mb-4">{{ localize('Product Categories') }}</h5>
                                    <div class="mb-4">
                                        @php
                                            $selectedCategoryId = optional($product->categories->first())->id;
                                        @endphp
                                        <select id="category_id" class="select2 form-control" name="category_id" required>
                                            @foreach ($categories as $category)
                                               
                                                @foreach ($category->childrenCategories as $childCategory)
                                                    @include(
                                                        'backend.pages.products.products.subCategory',
                                                        [
                                                            'subCategory' => $childCategory,
                                                            'selectedCategoryId' => $selectedCategoryId,
                                                        ]
                                                    )
                                                @endforeach
                                            @endforeach
                                        </select>
                                        {{-- ✅ YE ADD KARNA HAI --}}
                                        {{-- 🔥 CATEGORY BREADCRUMB PREVIEW --}}
                                        <div class="mt-2">
                                            <small class="text-muted" id="categoryBreadcrumb">
                                                @if ($product->categories->isNotEmpty())
                                                    @php
                                                        $category = $product->category;
                                                        $breadcrumb = [];
                                                        while ($category) {
                                                            array_unshift($breadcrumb, $category->name);
                                                            $category = $category->parent;
                                                        }
                                                    @endphp
                                                    {{ implode(' > ', $breadcrumb) }}
                                                @else
                                                    No category selected
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--product category end-->
                            
                           
                          
                           
                            
                            {{-- minimum and maximum price selling Price --}}
                            <div class="card mb-4" id="section-min-max-price">
                                <div class="card-body">
                                    <h5 class="mb-4">{{ localize('Price Range') }}</h5>
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="min_selling_price"
                                                    class="form-label">{{ localize('Minimum Selling Price') }}<span
                                                        class="text-danger">*</span></label>
                                                <input type="number" min="0" step="1"
                                                    id="min_selling_price" name="min_selling_price"
                                                    placeholder="{{ localize('Minimum selling price') }}"
                                                    class="form-control" value="{{ $product->min_selling_price ?? '' }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="max_selling_price"
                                                    class="form-label
                                                    ">{{ localize('Maximum Selling Price') }}<span
                                                        class="text-danger">*</span></label>
                                                <input type="number" min="0" step="1"
                                                    id="max_selling_price" name="max_selling_price"
                                                    placeholder="{{ localize('Maximum selling price') }}"
                                                    class="form-control" value="{{ $product->max_selling_price ?? '' }}"
                                                    required>
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

                                                $tax_value = '';

                                                $tax_type = 'percent';

                                                foreach ($product->taxes as $productTax) {
                                                    if ($productTax->tax_id == $tax->id) {
                                                        $tax_value = $productTax->tax_value;

                                                        $tax_type = $productTax->tax_type;
                                                    }
                                                }

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
                                                            <option value="{{ trim($value) }}"
                                                                {{ $tax_value == trim($value) ? 'selected' : '' }}>

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

                                                        <option value="percent"
                                                            {{ $tax_type == 'percent' ? 'selected' : '' }}>

                                                            {{ localize('Percent') }} %

                                                        </option>



                                                    </select>

                                                </div>

                                            </div>
                                        @endforeach

                                    </div>

                                </div>

                            </div>
                            <!--product tax end-->
                            <!--product price sku and stock start-->
                            <div class="card mb-4" id="section-5">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-4">{{ localize('Price, Sku & Stock') }}
                                        </h5>
                                        <div class="form-check form-switch">
                                            <label class="form-check-label fw-medium text-primary"
                                                for="is_variant">{{ localize('Has Variations?') }}</label>
                                            <input type="checkbox" class="form-check-input" id="is_variant"
                                                onchange="isVariantProduct(this)" name="is_variant"
                                                @if ($product->has_variation) checked @endif>
                                        </div>
                                    </div>
                                    <!-- without variation start-->
                                    <div class="noVariation"
                                        @if ($product->has_variation) style="display:none;" @endif>
                                        <div class="row g-3">
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label for="code" class="form-label">
                                                        {{ localize('HSN Code') }} <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" id="code" name="code"
                                                        value="{{ old('code', optional($product->variations->first())->code) }}"
                                                        placeholder="Enter HSN Code" class="form-control" maxlength="8"
                                                        pattern="\d{4,8}" inputmode="numeric">
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            $first_variation = $product->variations->first();
                                            $price = !$product->has_variation ? $first_variation->price : 0;
                                            $stock_qty = !$product->has_variation
                                                ? ($first_variation->product_variation_stock
                                                    ? $first_variation->product_variation_stock->stock_qty
                                                    : 0)
                                                : 1;
                                            $sku = !$product->has_variation ? $first_variation->sku : null;
                                            $code = !$product->has_variation ? $first_variation->code : null;
                                        @endphp
                                        <div class="row g-3">
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label for="price"
                                                        class="form-label">{{ localize('Price') }}</label>
                                                    <input type="number" min="0" step="0.0001" id="price"
                                                        name="price" placeholder="{{ localize('Product price') }}"
                                                        class="form-control" value="{{ $price }}"
                                                        {{ !$product->has_variation ? 'required' : '' }}>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label for="stock" class="form-label">{{ localize('Stock') }}
                                                        <small
                                                            class="text-warning">({{ localize("Default Location's Stock") }})</small>
                                                    </label>
                                                    <input type="number" id="stock"
                                                        placeholder="{{ localize('Stock qty') }}" name="stock"
                                                        class="form-control"
                                                        value="{{ old('stock', $product->stock_qty) }}"
                                                        {{ !$product->has_variation ? 'required' : '' }}>
                                                    {{-- <input type="number" id="stock"
                                                        placeholder="{{ localize('Stock qty') }}" name="stock"
                                                        class="form-control" value="{{ $stock_qty }}"
                                                        {{ !$product->has_variation ? 'required' : '' }}> --}}
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label for="sku"
                                                        class="form-label">{{ localize('SKU') }}</label>
                                                    <input type="text" id="sku"
                                                        placeholder="{{ localize('Product sku') }}" name="sku"
                                                        class="form-control" value="{{ $sku }}"
                                                        {{ !$product->has_variation ? 'required' : '' }}>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- without variation start end-->
                                    <!--for variation row start-->
                                    <div class="hasVariation"
                                        @if (!$product->has_variation) style="display:none;" @endif>
                                        <div class="hasVariation"
                                            @if (!$product->has_variation) style="display:none;" @endif>

                                            {{-- Variations Table --}}
                                            @if (count($variations) > 0)
                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        {{ localize('HSN Code') }} <span class="text-danger">*</span>
                                                    </label>

                                                    <input type="text" name="variation_hsn"
                                                        value="{{ old('variation_hsn', $variations->first()->code ?? '') }}"
                                                        class="form-control" maxlength="8" pattern="\d{1,8}"
                                                        inputmode="numeric" required readonly>
                                                </div>
                                                <div id="variation_combination"
                                                    class="border bg-light-subtle rounded p-2 mb-4">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ localize('Variation') }}</th>
                                                                <th>{{ localize('Price') }}</th>
                                                                <th>{{ localize('Stock') }}</th>
                                                                <th>{{ localize('SKU') }}</th>
                                                                <th>{{ localize('Image') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($variations as $key => $variation)
                                                                @php
                                                                    $name = collect(
                                                                        explode('/', $variation->variation_key),
                                                                    )
                                                                        ->filter()
                                                                        ->map(function ($part) {
                                                                            $ids = explode(':', $part);
                                                                            $value = \App\Models\VariationValue::find(
                                                                                $ids[1],
                                                                            );
                                                                            return $value
                                                                                ? $value->collectLocalization('name')
                                                                                : '';
                                                                        })
                                                                        ->implode('-');

                                                                @endphp

                                                                <tr>
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $name }}" disabled>
                                                                        <input type="hidden"
                                                                            name="variations[{{ $key }}][variation_key]"
                                                                            value="{{ $variation->variation_key }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" class="form-control"
                                                                            name="variations[{{ $key }}][price]"
                                                                            value="{{ old('variations.' . $key . '.price', $variation->price) }}">
                                                                    </td>

                                                                    <td>
                                                                        <input type="number" class="form-control"
                                                                            name="variations[{{ $key }}][stock]"
                                                                            value="{{ old('variations.' . $key . '.stock', $variation->stock_qty) }}">
                                                                    </td>
                                                                    <td><input type="text" class="form-control"
                                                                            name="variations[{{ $key }}][sku]"
                                                                            value="{{ $variation->sku }}"></td>

                                                                    <td>

                                                                        <div class="tt-product-thumb show-selected-files">

                                                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                                                data-bs-toggle="offcanvas"
                                                                                data-bs-target="#offcanvasBottom"
                                                                                onclick="showMediaManager(this)"
                                                                                data-selection="single">

                                                                                <input type="hidden"
                                                                                    name="variation_gallery[{{ $variation->variation_key }}]"
                                                                                    value="{{ old('variation_gallery')[$variation->variation_key] ?? $variation->image }}">

                                                                                <div class="no-avatar rounded-circle">
                                                                                    <span>
                                                                                        <i data-feather="plus"></i>
                                                                                    </span>
                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    </td>
                                                                    <!-- <td><input type="text" class="form-control" name="variations[{{ $key }}][code]" value="{{ $variation->code }}"></td> -->
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif

                                            {{-- Size & Color Dropdowns --}}
                                            @php
                                                $selectedOptions = [];

                                                // 🔥 variation_key se data nikaal
                                                foreach ($variations as $variation) {
                                                    $parts = explode('/', $variation->variation_key);

                                                    foreach ($parts as $part) {
                                                        if (!$part) {
                                                            continue;
                                                        }

                                                        [$variationId, $valueId] = explode(':', $part);

                                                        $selectedOptions[$variationId][] = $valueId;
                                                    }
                                                }

                                                // duplicates remove
                                                foreach ($selectedOptions as $k => $vals) {
                                                    $selectedOptions[$k] = array_unique($vals);
                                                }

                                                // ALL variations fetch (Packs, Age, Size etc.)
                                                $allVariations = \App\Models\Variation::whereIn(
                                                    'id',
                                                    array_keys($selectedOptions),
                                                )->get();
                                            @endphp
                                            <div class="row g-3 mb-4">
                                                @foreach ($allVariations as $variation)
                                                    @php
                                                        $values = \App\Models\VariationValue::where(
                                                            'variation_id',
                                                            $variation->id,
                                                        )->get();
                                                        $selectedIds = $selectedOptions[$variation->id] ?? [];
                                                    @endphp

                                                    <div class="col-lg-6">
                                                        <label class="form-label">{{ $variation->name }}</label>

                                                        <select class="select2 form-control" multiple
                                                            name="option_{{ $variation->id }}_choices[]"
                                                            onchange="generateVariationCombinations()">

                                                            @foreach ($values as $val)
                                                                <option value="{{ $val->id }}"
                                                                    {{ in_array($val->id, $selectedIds) ? 'selected' : '' }}>
                                                                    {{ $val->collectLocalization('name') }}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                @endforeach
                                            </div>




                                            {{-- Extra Variation Selector --}}
                                            <!-- <div class="chosen_variation_options mb-4">
                                                                                                                                                                                                            <div class="row g-3">
                                                                                                                                                                                                                <div class="col-lg-6">
                                                                                                                                                                                                                    <select class="form-select select2" id="variation_select" onchange="getVariationValues(this)" name="extra_variations[]">
                                                                                                                                                                                                                        <option value="">{{ localize('Select Variation') }}</option>
                                                                                                                                                                                                                    </select>
                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                <div class="col-lg-6">
                                                                                                                                                                                                                    <div class="variationvalues">
                                                                                                                                                                                                                        <input type="text" class="form-control" placeholder="{{ localize('Select variation values') }}" />
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                </div>
                                                                                                                                                                                                            </div>
                                                                                                                                                                                                        </div> -->

                                            {{-- Add Another Button --}}
                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <div id="variation-limit-alert"
                                                        class="alert alert-danger d-none mt-2">
                                                        You can only choose 2 variations combination.
                                                    </div>
                                                    <button class="btn btn-link px-0 fw-medium fs-base" type="button"
                                                        onclick="addAnotherVariation()">
                                                        <i data-feather="plus" class="me-1"></i>
                                                        {{ localize('Add Another Variation') }}
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="chosen_variation_options"></div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-4">
                                                </div>
                                            </div>
                                            {{-- SIZE GUIDE DISABLED --}}
                                            {{--
<div class="mt-4">
   <label class="form-label">{{ localize('Product Size Guide') }}</label>
   <div class="tt-image-drop rounded">
      <span class="fw-semibold">{{ localize('Choose Size Guide Image') }}</span>

      <div class="tt-product-thumb show-selected-files mt-3">
         <div class="avatar avatar-xl cursor-pointer choose-media"
            data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
            onclick="showMediaManager(this)" data-selection="single">

            <input type="hidden" name="size_guide"
               value="{{ $product->size_guide }}">

            <div class="no-avatar rounded-circle">
               <span><i data-feather="plus"></i></span>
            </div>
         </div>
      </div>

   </div>
</div>
--}}
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
                                                    <option value="">{{ localize('Select Brand') }}</option>
                                                    @if (auth()->user()->user_type == 'vendor')
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand['id'] }}"
                                                                {{ $brand['id'] == $product->brand_id ? 'selected' : '' }}>

                                                                {{ $brand['name'] }}

                                                            </option>
                                                        @endforeach
                                                    @else
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}"
                                                                {{ $brand->id == $product->brand_id ? 'selected' : '' }}>

                                                                {{ $brand->collectLocalization('name') }}

                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                                @if (auth()->user()->user_type != 'admin')
                                                    <div class="form-check mt-3">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="noBrandCheckbox"
                                                            {{ optional($product->brand)->name == 'Generic' ? 'checked' : '' }}>

                                                        <label class="form-check-label" for="noBrandCheckbox">
                                                            This product doesn't have a brand
                                                        </label>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--product brand and unit end-->
                            <!-- ================= BRAND SPECS (EDIT) ================= -->
                            <div class="card mb-4" id="section-brand-specs">
                                <div class="card-body">
                                    <h5 class="mb-4">{{ localize('Product Specifications') }}</h5>
                                    @php
                                        $brandSpecs = $product->brand_specs ?? [];
                                    @endphp
                                    <div id="brandSpecsRepeater">
                                        @if (!empty($brandSpecs))
                                            @foreach ($brandSpecs as $spec)
                                                <div class="row mb-3 brand-spec-row align-items-end">
                                                    <div class="col-lg-5">
                                                        <label class="form-label">Title</label>
                                                        <input type="text" name="brand_spec_title[]"
                                                            class="form-control" value="{{ $spec['title'] ?? '' }}"
                                                            placeholder="e.g. Fabric">
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <label class="form-label">Value</label>
                                                        <input type="text" name="brand_spec_value[]"
                                                            class="form-control" value="{{ $spec['value'] ?? '' }}"
                                                            placeholder="e.g. Cotton">
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button" class="btn btn-danger removeBrandSpecRow">
                                                            <i class="las la-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Default Empty Row -->
                                            <div class="row mb-3 brand-spec-row align-items-end">
                                                <div class="col-lg-5">
                                                    <label class="form-label">Title</label>
                                                    <input type="text" name="brand_spec_title[]" class="form-control"
                                                        placeholder="e.g. Fabric">
                                                </div>
                                                <div class="col-lg-5">
                                                    <label class="form-label">Value</label>
                                                    <input type="text" name="brand_spec_value[]" class="form-control"
                                                        placeholder="e.g. Cotton">
                                                </div>
                                                <div class="col-lg-2">
                                                    <button type="button" class="btn btn-danger removeBrandSpecRow">
                                                        <i class="las la-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-soft-primary" id="addMoreBrandSpecs">
                                        <i class="las la-plus"></i> Add More Specs
                                    </button>
                                </div>
                            </div>
                            <!-- ================= END BRAND SPECS ================= -->
                            <!-- 🔥 ICON SLIDER (TRUST BADGES) -->
                            <!-- ================= ICON SLIDER (FEATURES - SAME AS CREATE) ================= -->
                            <div class="row" id="section-icon-slider">
                                <div class="col-lg-12">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="mb-4">Icon Slider (Features)</h5>
                                            @php
                                                $iconSliders = $product->icon_slider ?? [];
                                            @endphp
                                            <div id="iconRepeater">
                                                @if (!empty($iconSliders))
                                                    @foreach ($iconSliders as $item)
                                                        <div class="row align-items-end icon-row mb-3">
                                                            <div class="col-lg-4">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" name="icon_titles[]"
                                                                    class="form-control"
                                                                    value="{{ $item['title'] ?? '' }}"
                                                                    placeholder="Free Delivery">
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <label class="form-label">Select Icon</label>
                                                                <select class="form-control icon-select"
                                                                    name="icon_classes[]">
                                                                    <option value="las la-truck"
                                                                        {{ ($item['icon'] ?? '') == 'las la-truck' ? 'selected' : '' }}>
                                                                        🚚 Free Delivery</option>
                                                                    <option value="las la-shipping-fast"
                                                                        {{ ($item['icon'] ?? '') == 'las la-shipping-fast' ? 'selected' : '' }}>
                                                                        ⚡ Fast Delivery</option>
                                                                    <option value="las la-box"
                                                                        {{ ($item['icon'] ?? '') == 'las la-box' ? 'selected' : '' }}>
                                                                        📦 Safe Packaging</option>
                                                                    <option value="las la-globe"
                                                                        {{ ($item['icon'] ?? '') == 'las la-globe' ? 'selected' : '' }}>
                                                                        🌍 Worldwide Shipping</option>
                                                                    <option value="las la-warehouse"
                                                                        {{ ($item['icon'] ?? '') == 'las la-warehouse' ? 'selected' : '' }}>
                                                                        🏬 Warehouse Dispatch</option>
                                                                    <option value="las la-lock"
                                                                        {{ ($item['icon'] ?? '') == 'las la-lock' ? 'selected' : '' }}>
                                                                        🔒 Secure Payment</option>
                                                                    <option value="las la-shield-alt"
                                                                        {{ ($item['icon'] ?? '') == 'las la-shield-alt' ? 'selected' : '' }}>
                                                                        🛡️ 1 Year Warranty</option>
                                                                    <option value="las la-user-shield"
                                                                        {{ ($item['icon'] ?? '') == 'las la-user-shield' ? 'selected' : '' }}>
                                                                        👤 Safe Checkout</option>
                                                                    <option value="las la-certificate"
                                                                        {{ ($item['icon'] ?? '') == 'las la-certificate' ? 'selected' : '' }}>
                                                                        🏅 Certified Product</option>
                                                                    <option value="las la-check-circle"
                                                                        {{ ($item['icon'] ?? '') == 'las la-check-circle' ? 'selected' : '' }}>
                                                                        ✔️ 100% Original</option>
                                                                    <option value="las la-sync"
                                                                        {{ ($item['icon'] ?? '') == 'las la-sync' ? 'selected' : '' }}>
                                                                        🔄 7 Days Replacement</option>
                                                                    <option value="las la-undo"
                                                                        {{ ($item['icon'] ?? '') == 'las la-undo' ? 'selected' : '' }}>
                                                                        ↩️ Easy Returns</option>
                                                                    <option value="las la-headset"
                                                                        {{ ($item['icon'] ?? '') == 'las la-headset' ? 'selected' : '' }}>
                                                                        🎧 24/7 Support</option>
                                                                    <option value="las la-comments"
                                                                        {{ ($item['icon'] ?? '') == 'las la-comments' ? 'selected' : '' }}>
                                                                        💬 Live Support</option>
                                                                    <option value="las la-phone"
                                                                        {{ ($item['icon'] ?? '') == 'las la-phone' ? 'selected' : '' }}>
                                                                        📞 Customer Care</option>
                                                                    <option value="las la-star"
                                                                        {{ ($item['icon'] ?? '') == 'las la-star' ? 'selected' : '' }}>
                                                                        ⭐ Highly Rated</option>
                                                                    <option value="las la-medal"
                                                                        {{ ($item['icon'] ?? '') == 'las la-medal' ? 'selected' : '' }}>
                                                                        🏆 Top Brand</option>
                                                                    <option value="las la-thumbs-up"
                                                                        {{ ($item['icon'] ?? '') == 'las la-thumbs-up' ? 'selected' : '' }}>
                                                                        👍 Trusted Quality</option>
                                                                    <option value="las la-heart"
                                                                        {{ ($item['icon'] ?? '') == 'las la-heart' ? 'selected' : '' }}>
                                                                        ❤️ Customer Favourite</option>
                                                                    <option value="las la-award"
                                                                        {{ ($item['icon'] ?? '') == 'las la-award' ? 'selected' : '' }}>
                                                                        🥇 Premium Quality</option>
                                                                    <option value="las la-tags"
                                                                        {{ ($item['icon'] ?? '') == 'las la-tags' ? 'selected' : '' }}>
                                                                        🏷️ Best Price</option>
                                                                    <option value="las la-gift"
                                                                        {{ ($item['icon'] ?? '') == 'las la-gift' ? 'selected' : '' }}>
                                                                        🎁 Special Offers</option>
                                                                    <option value="las la-percentage"
                                                                        {{ ($item['icon'] ?? '') == 'las la-percentage' ? 'selected' : '' }}>
                                                                        💸 Big Discounts</option>
                                                                    <option value="las la-coins"
                                                                        {{ ($item['icon'] ?? '') == 'las la-coins' ? 'selected' : '' }}>
                                                                        💰 Cash on Delivery</option>
                                                                    <option value="las la-credit-card"
                                                                        {{ ($item['icon'] ?? '') == 'las la-credit-card' ? 'selected' : '' }}>
                                                                        💳 Multiple Payments</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <label class="form-label">Preview</label>
                                                                <div class="border rounded p-3 text-center bg-light shadow-sm"
                                                                    style="font-size: 30px;">
                                                                    <i
                                                                        class="{{ $item['icon'] ?? 'las la-truck' }} preview-icon"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                <button type="button" class="btn btn-danger removeRow">
                                                                    <i class="las la-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <!-- Default Row -->
                                                    <div class="row align-items-end icon-row mb-3">
                                                        <div class="col-lg-4">
                                                            <label class="form-label">Title</label>
                                                            <input type="text" name="icon_titles[]"
                                                                class="form-control" placeholder="Free Delivery">
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label class="form-label">Select Icon</label>
                                                            <select class="form-control icon-select"
                                                                name="icon_classes[]">
                                                                <option value="las la-truck"
                                                                    {{ ($item['icon'] ?? '') == 'las la-truck' ? 'selected' : '' }}>
                                                                    🚚 Free Delivery</option>
                                                                <option value="las la-shipping-fast"
                                                                    {{ ($item['icon'] ?? '') == 'las la-shipping-fast' ? 'selected' : '' }}>
                                                                    ⚡ Fast Delivery</option>
                                                                <option value="las la-box"
                                                                    {{ ($item['icon'] ?? '') == 'las la-box' ? 'selected' : '' }}>
                                                                    📦 Safe Packaging</option>
                                                                <option value="las la-globe"
                                                                    {{ ($item['icon'] ?? '') == 'las la-globe' ? 'selected' : '' }}>
                                                                    🌍 Worldwide Shipping</option>
                                                                <option value="las la-warehouse"
                                                                    {{ ($item['icon'] ?? '') == 'las la-warehouse' ? 'selected' : '' }}>
                                                                    🏬 Warehouse Dispatch</option>
                                                                <option value="las la-lock"
                                                                    {{ ($item['icon'] ?? '') == 'las la-lock' ? 'selected' : '' }}>
                                                                    🔒 Secure Payment</option>
                                                                <option value="las la-shield-alt"
                                                                    {{ ($item['icon'] ?? '') == 'las la-shield-alt' ? 'selected' : '' }}>
                                                                    🛡️ 1 Year Warranty</option>
                                                                <option value="las la-user-shield"
                                                                    {{ ($item['icon'] ?? '') == 'las la-user-shield' ? 'selected' : '' }}>
                                                                    👤 Safe Checkout</option>
                                                                <option value="las la-certificate"
                                                                    {{ ($item['icon'] ?? '') == 'las la-certificate' ? 'selected' : '' }}>
                                                                    🏅 Certified Product</option>
                                                                <option value="las la-check-circle"
                                                                    {{ ($item['icon'] ?? '') == 'las la-check-circle' ? 'selected' : '' }}>
                                                                    ✔️ 100% Original</option>
                                                                <option value="las la-sync"
                                                                    {{ ($item['icon'] ?? '') == 'las la-sync' ? 'selected' : '' }}>
                                                                    🔄 7 Days Replacement</option>
                                                                <option value="las la-undo"
                                                                    {{ ($item['icon'] ?? '') == 'las la-undo' ? 'selected' : '' }}>
                                                                    ↩️ Easy Returns</option>
                                                                <option value="las la-headset"
                                                                    {{ ($item['icon'] ?? '') == 'las la-headset' ? 'selected' : '' }}>
                                                                    🎧 24/7 Support</option>
                                                                <option value="las la-comments"
                                                                    {{ ($item['icon'] ?? '') == 'las la-comments' ? 'selected' : '' }}>
                                                                    💬 Live Support</option>
                                                                <option value="las la-phone"
                                                                    {{ ($item['icon'] ?? '') == 'las la-phone' ? 'selected' : '' }}>
                                                                    📞 Customer Care</option>
                                                                <option value="las la-star"
                                                                    {{ ($item['icon'] ?? '') == 'las la-star' ? 'selected' : '' }}>
                                                                    ⭐ Highly Rated</option>
                                                                <option value="las la-medal"
                                                                    {{ ($item['icon'] ?? '') == 'las la-medal' ? 'selected' : '' }}>
                                                                    🏆 Top Brand</option>
                                                                <option value="las la-thumbs-up"
                                                                    {{ ($item['icon'] ?? '') == 'las la-thumbs-up' ? 'selected' : '' }}>
                                                                    👍 Trusted Quality</option>
                                                                <option value="las la-heart"
                                                                    {{ ($item['icon'] ?? '') == 'las la-heart' ? 'selected' : '' }}>
                                                                    ❤️ Customer Favourite</option>
                                                                <option value="las la-award"
                                                                    {{ ($item['icon'] ?? '') == 'las la-award' ? 'selected' : '' }}>
                                                                    🥇 Premium Quality</option>
                                                                <option value="las la-tags"
                                                                    {{ ($item['icon'] ?? '') == 'las la-tags' ? 'selected' : '' }}>
                                                                    🏷️ Best Price</option>
                                                                <option value="las la-gift"
                                                                    {{ ($item['icon'] ?? '') == 'las la-gift' ? 'selected' : '' }}>
                                                                    🎁 Special Offers</option>
                                                                <option value="las la-percentage"
                                                                    {{ ($item['icon'] ?? '') == 'las la-percentage' ? 'selected' : '' }}>
                                                                    💸 Big Discounts</option>
                                                                <option value="las la-coins"
                                                                    {{ ($item['icon'] ?? '') == 'las la-coins' ? 'selected' : '' }}>
                                                                    💰 Cash on Delivery</option>
                                                                <option value="las la-credit-card"
                                                                    {{ ($item['icon'] ?? '') == 'las la-credit-card' ? 'selected' : '' }}>
                                                                    💳 Multiple Payments</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <label class="form-label">Preview</label>
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
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-soft-primary" id="addMoreIcon">
                                                <i class="las la-plus"></i> Add More
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- 🔥 ICON SLIDER end (TRUST BADGES) -->
                            <!-- ================= ABOUT THIS ITEM (EDIT) ================= -->
                            <div class="card mb-4" id="section-about-items">
                                <div class="card-body">
                                    <h5 class="mb-4">About This Item </h5>
                                    <div id="aboutItemRepeater">
                                        @if (!empty($product->about_items))
                                            @foreach ($product->about_items as $item)
                                                <div class="row mb-3 about-item-row align-items-end">
                                                    <div class="col-lg-10">
                                                        <label class="form-label">Bullet Point</label>
                                                        <input type="text" name="about_items[]" class="form-control"
                                                            value="{{ $item }}"
                                                            placeholder="e.g. Soft breathable cotton fabric">
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button" class="btn btn-danger removeAboutRow">
                                                            <i class="las la-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Default Empty Row -->
                                            <div class="row mb-3 about-item-row align-items-end">
                                                <div class="col-lg-10">
                                                    <label class="form-label">Bullet Point</label>
                                                    <input type="text" name="about_items[]" class="form-control"
                                                        placeholder="e.g. Skin friendly & lightweight">
                                                </div>
                                                <div class="col-lg-2">
                                                    <button type="button" class="btn btn-danger removeAboutRow">
                                                        <i class="las la-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-soft-primary" id="addMoreAboutItem">
                                        <i class="las la-plus"></i> Add Bullet Point
                                    </button>
                                </div>
                            </div>
                            <!-- ================= END ABOUT THIS ITEM ================= -->
                            <!--product image and gallery start-->
                             
                            <div class="card mb-4" id="section-2">
                                <div class="card-body">
                                    @if(auth()->user()->user_type == 'admin')
                                    <!-- ⭐ PRODUCT VIDEO (YouTube + MP4) - EDIT PAGE -->
                                    <div class="mb-4">
                                        <label class="form-label">
                                            {{ localize('Product Video (YouTube URL or MP4 Upload)') }}
                                        </label>
                                        <div class="card border">
                                            <div class="card-body">
                                                {{-- Current YouTube Video Preview --}}
                                                @if (!empty($product->video_url) && $product->video_type == 'youtube')
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Current YouTube Video</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $product->video_url }}" disabled>
                                                    </div>
                                                @endif
                                                {{-- Current Uploaded MP4 Preview --}}
                                                @if (!empty($product->video_url) && $product->video_type == 'upload')
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Current Uploaded
                                                            Video</label><br>
                                                        <video controls width="250" class="rounded border">
                                                            <source
                                                                src="{{ asset($product->video_url) }}?v={{ time() }}"
                                                                type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    </div>
                                                @endif
                                                <!-- YouTube URL -->
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">YouTube Video URL</label>
                                                    @php
                                                        $isVendor = auth()->user()->user_type != 'admin';
                                                    @endphp

                                                    <input type="text" name="video_url" class="form-control"
                                                        style="{{ $isVendor ? 'background:#f1f3f5; color:#6c757d; cursor:not-allowed;' : '' }}"
                                                        value="{{ $product->video_type == 'youtube' ? $product->video_url : '' }}"
                                                        placeholder="{{ $isVendor ? 'Admin will add video URL' : 'https://www.youtube.com/watch?v=xxxx' }}"
                                                        {{ $isVendor ? 'readonly' : '' }}>
                                                    <small class="text-muted">
                                                        Paste YouTube link (Recommended for fast loading & thumbnail)
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ⭐ END PRODUCT VIDEO -->
                                    @endif
                                   <h5 class="mb-4">{{ localize('Images') }}</h5>
                                        <div class="mb-4">
                                            <label class="form-label">{{ localize('Thumbnail') }}</label>
                                            <div class="tt-image-drop rounded">
                                                <span
                                                    class="fw-semibold">{{ localize('Choose Product Thumbnail') }}</span>
                                                <!-- choose media -->
                                                <div class="tt-product-thumb show-selected-files mt-3">
                                                    <div class="avatar avatar-xl cursor-pointer choose-media"
                                                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                        onclick="showMediaManager(this)" data-selection="single">
                                                        <input type="hidden" name="image"
                                                            value="{{ $product->thumbnail_image }}">
                                                        <div class="no-avatar rounded-circle">
                                                            <span><i data-feather="plus"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- choose media -->
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">{{ localize('Gallery') }}</label>
                                            <div class="tt-image-drop rounded">
                                                <span class="fw-semibold">{{ localize('Choose Gallery Images') }}</span>
                                                <!-- choose media -->
                                                <div class="tt-product-thumb show-selected-files mt-3">
                                                    <div class="avatar avatar-xl cursor-pointer choose-media"
                                                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                        onclick="showMediaManager(this)" data-selection="multiple">
                                                        <input type="hidden" name="images"
                                                            value="{{ $product->gallery_images }}">
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
                           
                          
                            <!--product image and gallery end-->
                            <!-- ================= bazaron PRODUCT ATTRIBUTES (EDIT - bazaron FIXED) ================= -->
                            <div class="card mb-4" id="section-bazaron-attributes">
                                <div class="card-body">
                                    <h5 class="mb-4">Bazaron Product Details</h5>

                                    <div class="row g-3">

                                        <div class="col-lg-6">
                                            <label class="form-label">Model Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="model_number" class="form-control"
                                                value="{{ $product->model_number }}" placeholder="e.g. IP14-MAG-SMKP"
                                                required>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label">Model Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="model_name" class="form-control"
                                                value="{{ $product->model_name }}"
                                                placeholder="e.g. iPhone 14 Transparent Case" required>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label">Manufacturer <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="manufacturer_name" class="form-control"
                                                value="{{ $product->manufacturer_name }}" placeholder="e.g. Brand Name"
                                                required>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label">Generic Keyword</label>
                                            <input type="text" name="generic_keyword" class="form-control"
                                                value="{{ $product->generic_keyword }}"
                                                placeholder="e.g. iphone 14 magsafe case">
                                        </div>

                                        <div class="col-lg-12">
                                            <label class="form-label">Special Features</label>
                                            <input type="text" name="special_features" class="form-control"
                                                value="{{ $product->special_features }}"
                                                placeholder="e.g. Shockproof, Slim Fit">
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label">Style <span class="text-danger">*</span></label>
                                            <input type="text" name="style" class="form-control"
                                                value="{{ $product->style }}" required>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label">Item Condition <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="item_condition" class="form-control"
                                                value="{{ $product->item_condition }}">
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label">Outer Material</label>
                                            <input type="text" name="outer_material" class="form-control"
                                                value="{{ $product->material }}">
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label">Compatible Devices</label>
                                            <input type="text" name="compatible_devices" class="form-control"
                                                value="{{ $product->compatible_devices }}">
                                        </div>

                                        <div class="col-lg-4">
                                            <label class="form-label">Unit Count</label>
                                            <input type="number" name="unit_count" class="form-control"
                                                value="{{ $product->unit_count }}">
                                        </div>

                                        <div class="col-lg-4">
                                            <label class="form-label">Item Type Name</label>
                                            <input type="text" name="item_type_name" class="form-control"
                                                value="{{ $product->item_type_name }}">
                                        </div>

                                        <div class="col-lg-4">
                                            <label class="form-label">Number of Items</label>
                                            <input type="number" name="number_of_items" class="form-control"
                                                value="{{ $product->number_of_items }}">
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label">Water Resistance Level</label>
                                            <input type="text" name="water_resistance_level" class="form-control"
                                                value="{{ $product->water_resistance_level }}">
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label">Target Gender <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="target_gender" class="form-control"
                                                value="{{ $product->target_gender }}" required>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label">Age Range Description <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="age_range_description" class="form-control"
                                                value="{{ $product->age_range_description }}" required>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label">Subject Character</label>
                                            <input type="text" name="subject_character" class="form-control"
                                                value="{{ $product->subject_character }}">
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- ================= END bazaron ATTRIBUTES ================= -->
                            <!-- ================= SAFETY & COMPLIANCE (EDIT - SAME PATTERN) ================= -->
                            <div class="card mb-4" id="section-safety-compliance">
                                <div class="card-body">
                                    <h5 class="mb-4">Safety & Compliance</h5>
                                    <div class="row g-3">
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
                                            <label class="form-label">Manufacturer</label>
                                            <input type="text" name="manufacturer" class="form-control"
                                                value="{{ $product->manufacturer }}" placeholder="e.g. ABC Pvt Ltd">
                                        </div>
                                        <!-- Importer -->
                                        <div class="col-lg-6">
                                            <label class="form-label">Importer Name</label>
                                            <input type="text" name="importer_name" class="form-control"
                                                value="{{ $product->importer_name }}" placeholder="e.g. XYZ Imports">
                                        </div>
                                        <!-- Packer -->
                                        <div class="col-lg-6">
                                            <label class="form-label">Packer Details <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="packer_details" class="form-control"
                                                value="{{ $product->packer_details }}"
                                                placeholder="e.g. Packed by ABC Industries">
                                        </div>
                                        <!-- Safety Info -->
                                        <div class="col-lg-12">
                                            <label class="form-label">Safety Information</label>
                                            <textarea name="safety_information" class="form-control" rows="3" placeholder="Safety instructions">{{ $product->safety_information }}</textarea>
                                        </div>
                                        <!-- Compliance -->
                                        <div class="col-lg-12">
                                            <label class="form-label">Compliance / Certifications</label>
                                            <input type="text" name="compliance_certification" class="form-control"
                                                value="{{ $product->compliance_certification }}"
                                                placeholder="e.g. ISO, BIS, CE">
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
                                            <label class="form-label">Item Length <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="item_length" class="form-control"
                                                value="{{ $product->item_length ?? '' }}" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">
                                                Item Length Unit <span class="text-danger">*</span>
                                            </label>

                                            <select name="item_length_unit" class="form-control" required>

                                                <option value="picometre"
                                                    {{ ($product->item_length_unit ?? '') == 'picometre' ? 'selected' : '' }}>
                                                    Picometre
                                                </option>

                                                <option value="angstrom"
                                                    {{ ($product->item_length_unit ?? '') == 'angstrom' ? 'selected' : '' }}>
                                                    Angstrom
                                                </option>

                                                <option value="nanometre"
                                                    {{ ($product->item_length_unit ?? '') == 'nanometre' ? 'selected' : '' }}>
                                                    Nanometre
                                                </option>

                                                <option value="micron"
                                                    {{ ($product->item_length_unit ?? '') == 'micron' ? 'selected' : '' }}>
                                                    Micron
                                                </option>

                                                <option value="mm"
                                                    {{ ($product->item_length_unit ?? '') == 'mm' ? 'selected' : '' }}>
                                                    Millimetres
                                                </option>

                                                <option value="cm"
                                                    {{ ($product->item_length_unit ?? '') == 'cm' ? 'selected' : '' }}>
                                                    Centimetres
                                                </option>

                                                <option value="dm"
                                                    {{ ($product->item_length_unit ?? '') == 'dm' ? 'selected' : '' }}>
                                                    Decimetres
                                                </option>

                                                <option value="m"
                                                    {{ ($product->item_length_unit ?? '') == 'm' ? 'selected' : '' }}>
                                                    Metres
                                                </option>

                                                <option value="km"
                                                    {{ ($product->item_length_unit ?? '') == 'km' ? 'selected' : '' }}>
                                                    Kilometres
                                                </option>

                                                <option value="mil"
                                                    {{ ($product->item_length_unit ?? '') == 'mil' ? 'selected' : '' }}>
                                                    Mils
                                                </option>

                                                <option value="hundredths-inch"
                                                    {{ ($product->item_length_unit ?? '') == 'hundredths-inch' ? 'selected' : '' }}>
                                                    Hundredths-Inches
                                                </option>

                                                <option value="inch"
                                                    {{ ($product->item_length_unit ?? '') == 'inch' ? 'selected' : '' }}>
                                                    Inches
                                                </option>

                                                <option value="ft"
                                                    {{ ($product->item_length_unit ?? '') == 'ft' ? 'selected' : '' }}>
                                                    Feet
                                                </option>

                                                <option value="mile"
                                                    {{ ($product->item_length_unit ?? '') == 'mile' ? 'selected' : '' }}>
                                                    Miles
                                                </option>

                                                <option value="yard"
                                                    {{ ($product->item_length_unit ?? '') == 'yard' ? 'selected' : '' }}>
                                                    Yards
                                                </option>

                                            </select>
                                        </div>
                                        <!-- ITEM WIDTH -->
                                        <div class="col-lg-6">
                                            <label class="form-label">Item Width <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="item_width" class="form-control"
                                                value="{{ $product->item_width ?? '' }}" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Item Width Unit <span
                                                    class="text-danger">*</span></label>
                                            <select name="item_width_unit" class="form-control" required>
                                                <option value="picometre">Picometre</option>
                                                <option value="angstrom">Angstrom</option>
                                                <option value="nanometre">Nanometre</option>
                                                <option value="micron">Micron</option>
                                                <option value="mm">Millimetres</option>
                                                <option value="cm">Centimetres</option>
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
                                            <label class="form-label">Item Height <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="item_height" class="form-control"
                                                value="{{ $product->item_height ?? '' }}" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Item Height Unit <span
                                                    class="text-danger">*</span></label>
                                            <select name="item_height_unit" class="form-control" required>
                                                <option value="picometre">Picometre</option>
                                                <option value="angstrom">Angstrom</option>
                                                <option value="nanometre">Nanometre</option>
                                                <option value="micron">Micron</option>
                                                <option value="mm">Millimetres</option>
                                                <option value="cm">Centimetres</option>
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
                                            <input type="number" step="0.01" name="package_weight"
                                                class="form-control" value="{{ $product->package_weight ?? '' }}"
                                                required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">
                                                Package Weight Unit <span class="text-danger">*</span>
                                            </label>

                                            <select name="package_weight_unit" class="form-control" required>

                                                <option value="mg"
                                                    {{ ($product->package_weight_unit ?? '') == 'mg' ? 'selected' : '' }}>
                                                    Milligrams
                                                </option>

                                                <option value="grams"
                                                    {{ ($product->package_weight_unit ?? '') == 'grams' ? 'selected' : '' }}>
                                                    Grams
                                                </option>

                                                <option value="kg"
                                                    {{ ($product->package_weight_unit ?? '') == 'kg' ? 'selected' : '' }}>
                                                    Kilograms
                                                </option>

                                                <option value="tons"
                                                    {{ ($product->package_weight_unit ?? '') == 'tons' ? 'selected' : '' }}>
                                                    Tons
                                                </option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ================= ITEM & PACKAGE DIMENSIONS END(bazaron STYLE) ================= -->
                            <!-- ================= ADDITIONAL INFORMATION (EDIT) ================= -->
                            <div class="card mb-4" id="section-additional-info">
                                <div class="card-body">
                                    <h5 class="mb-4">{{ localize('Additional Information ') }}</h5>
                                    <div id="additionalInfoRepeater">
                                        @if (!empty($product->additional_info))
                                            @foreach ($product->additional_info as $info)
                                                <div class="row mb-3 additional-info-row align-items-end">
                                                    <div class="col-lg-5">
                                                        <label class="form-label">Title</label>
                                                        <input type="text" name="info_title[]" class="form-control"
                                                            value="{{ $info['title'] ?? '' }}"
                                                            placeholder="e.g. Material">
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <label class="form-label">Value</label>
                                                        <input type="text" name="info_value[]" class="form-control"
                                                            value="{{ $info['value'] ?? '' }}"
                                                            placeholder="e.g. 100% Cotton">
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button" class="btn btn-danger removeInfoRow">
                                                            <i class="las la-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Default Empty Row -->
                                            <div class="row mb-3 additional-info-row align-items-end">
                                                <div class="col-lg-5">
                                                    <label class="form-label">Title</label>
                                                    <input type="text" name="info_title[]" class="form-control"
                                                        placeholder="e.g. Material">
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
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-soft-primary" id="addMoreInfo">
                                        <i class="las la-plus"></i> Add More Info
                                    </button>
                                </div>
                            </div>
                            <!-- ================= END ADDITIONAL INFORMATION ================= -->
                            <!-- ================= PRODUCT INFO (EDIT) ================= -->
                            <div class="card mb-4" id="section-product-info">
                                <div class="card-body">
                                    <h5 class="mb-4">Product Information</h5>
                                    <div id="productInfoRepeater">
                                        @if (!empty($product->product_info))
                                            @foreach ($product->product_info as $info)
                                                <div class="row mb-3 product-info-row align-items-end">
                                                    <div class="col-lg-5">
                                                        <label class="form-label">Title</label>
                                                        <input type="text" name="pinfo_title[]" class="form-control"
                                                            value="{{ $info['title'] ?? '' }}"
                                                            placeholder="e.g. Material">
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <label class="form-label">Value</label>
                                                        <input type="text" name="pinfo_value[]" class="form-control"
                                                            value="{{ $info['value'] ?? '' }}"
                                                            placeholder="e.g. 100% Cotton">
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button"
                                                            class="btn btn-danger removeProductInfoRow">
                                                            <i class="las la-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row mb-3 product-info-row align-items-end">
                                                <div class="col-lg-5">
                                                    <input type="text" name="pinfo_title[]" class="form-control"
                                                        placeholder="Title">
                                                </div>
                                                <div class="col-lg-5">
                                                    <input type="text" name="pinfo_value[]" class="form-control"
                                                        placeholder="Value">
                                                </div>
                                                <div class="col-lg-2">
                                                    <button type="button" class="btn btn-danger removeProductInfoRow">
                                                        <i class="las la-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-soft-primary" id="addMoreProductInfo">
                                        <i class="las la-plus"></i> Add Product Info
                                    </button>
                                </div>
                            </div>
                            <!-- ================= END PRODUCT INFO ================= -->
                            <!--product discount start-->
                            <div class="card mb-4" id="section-6">
                                <div class="card-body">
                                    <h5 class="mb-4">{{ localize('Product Discount') }}</h5>
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            @php
                                                $start_date = $product->discount_start_date
                                                    ? date('m/d/Y', $product->discount_start_date)
                                                    : null;
                                                $end_date = $product->discount_end_date
                                                    ? date('m/d/Y', $product->discount_end_date)
                                                    : null;
                                            @endphp
                                            <div class="mb-3">
                                                <label class="form-label">{{ localize('Date Range') }}</label>
                                                <div class="input-group">
                                                    <input class="form-control date-range-picker date-range"
                                                        type="text"
                                                        placeholder="{{ localize('Start date - End date') }}"
                                                        name="date_range"
                                                        @if ($start_date != null && $end_date != null) data-startdate="'{{ $start_date }}'"
      data-enddate="'{{ $end_date }}'" @endif>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="discount_value"
                                                    class="form-label">{{ localize('Discount Amount') }}</label>
                                                <input class="form-control" type="number"
                                                    placeholder="{{ localize('Type discount amount') }}"
                                                    id="discount_value" step="0.001" name="discount_value"
                                                    value="{{ $product->discount_value ?? 0 }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="discount_type"
                                                    class="form-label">{{ localize('Percent or Fixed') }}</label>
                                                <select class="select2 form-control" id="discount_type"
                                                    name="discount_type">
                                                    <option value="percent"
                                                        {{ $product->discount_type == 'percent' ? 'selected' : '' }}>
                                                        {{ localize('Percent %') }}</option>
                                                    <option value="flat"
                                                        {{ $product->discount_type == 'flat' ? 'selected' : '' }}>
                                                        {{ localize('Fixed') }}</option>
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
                                                    min="1" class="form-control"
                                                    value="{{ $product->min_purchase_qty }}">
                                            </div>
                                        </div>
                                         <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label for="max_purchase_qty"
                                                    class="form-label">{{ localize('Maximum Purchase Qty') }}</label>
                                                <input type="number" id="max_purchase_qty" name="max_purchase_qty"
                                                    min="1" class="form-control"
                                                    value="{{ $product->max_purchase_qty }}"
                                                    max="{{ auth()->user()->user_type == 'vendor' ? max(10, $product->admin_max_purchase_qty) : 999999 }}">
                                            </div>
                                        </div>
                                          @if(auth()->user()->user_type == 'vendor')
<div class="col-lg-6 d-flex flex-column justify-content-end">
    <label class="form-label d-block">&nbsp;</label>

    <button type="button"
    class="btn btn-warning w-auto align-self-start px-4"
    data-bs-toggle="modal"
    data-bs-target="#purchaseQtyRequestModal">

    Request Purchase Quantity Increase
</button>

    <small class="d-block text-muted mt-2">
        Approved Limit: {{ $product->admin_max_purchase_qty ?? 10 }}
    </small>
</div>
@endif
                                        <div class="col-lg-6 d-none">
                                            <div class="mb-0">
                                                <label for="express_delivery_hours"
                                                    class="form-label">{{ localize('Express Delivery Time') }}</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" class="form-control"
                                                        name="express_delivery_hours" value="24" min="0"
                                                        required id="express_delivery_hours"
                                                        value="{{ $product->express_delivery_hours }}">
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
                                                <input type="number" min="0" name="sell_target"
                                                    class="form-control"
                                                    placeholder="{{ localize('Type your sell target') }}"
                                                    value="{{ $product->sell_target }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="mb-4">{{ localize('Product Status') }}</h5>
                                            <div class="tt-select-brand">
                                                <select class="select2 form-control" id="is_published"
                                                    name="is_published">
                                                    <option value="1"
                                                        {{ $product->is_published == 1 ? 'selected' : '' }}>
                                                        {{ localize('Published') }}</option>
                                                    <option value="0"
                                                        {{ $product->is_published == 0 ? 'selected' : '' }}>
                                                        {{ localize('Unpublished') }}</option>
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
                                            placeholder="{{ localize('Type meta title') }}" class="form-control"
                                            value="{{ $product->meta_title }}">
                                        <span class="fs-sm text-muted">
                                            {{ localize('Set a meta tag title. Recommended to be simple and unique.') }}
                                        </span>
                                    </div>
                                    <div class="mb-4">
                                        <label for="meta_description"
                                            class="form-label">{{ localize('Meta Description') }}</label>
                                        <textarea class="form-control" name="meta_description" id="meta_description" rows="4"
                                            placeholder="{{ localize('Type your meta description') }}">{{ $product->meta_description }}</textarea>
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
                                                    <input type="hidden" name="meta_image"
                                                        value="{{ $product->meta_img }}">
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
                        @endif
                        <!-- submit button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-primary" type="submit">
                                        <i data-feather="save" class="me-1"></i> {{ localize('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- submit button end -->
                    </form>
                </div>
                <!--right sidebar-->
                <div class="col-xl-3 order-1 order-md-1 order-lg-1 order-xl-2">
                    <div class="card tt-sticky-sidebar d-none d-xl-block">
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
                                        <a href="#section-about">About Section</a>
                                    </li>
                                    <li>
                                        <a href="#section-2">{{ localize('Product Images') }}</a>
                                    </li>
                                    @if (env('DEFAULT_LANGUAGE') == $lang_key)
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
                                    @endif
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
            // 🔥 YE ADD KAR (IMPORTANT)
            window.preSelectedVariations = @json($selectedVariations);
        });


        // ===== ICON REPEATER (SAME AS CREATE) =====
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


        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-icon')) {
                e.target.closest('.icon-row').remove();
            }
        });

        // ===== Additional Info Repeater =====
        const infoRepeater = document.getElementById('additionalInfoRepeater');
        const addInfoBtn = document.getElementById('addMoreInfo');

        if (addInfoBtn) {
            addInfoBtn.addEventListener('click', function() {
                const firstRow = document.querySelector('.additional-info-row');
                const newRow = firstRow.cloneNode(true);

                // Reset input values
                newRow.querySelectorAll('input').forEach(input => input.value = '');

                infoRepeater.appendChild(newRow);
            });
        }

        // Remove Row
        document.addEventListener('click', function(e) {
            if (e.target.closest('.removeInfoRow')) {
                const rows = document.querySelectorAll('.additional-info-row');
                if (rows.length > 1) {
                    e.target.closest('.additional-info-row').remove();
                }
            }
        });


        // ===== PRODUCT INFO REPEATER =====
        const pInfoRepeater = document.getElementById('productInfoRepeater');
        const addPInfoBtn = document.getElementById('addMoreProductInfo');

        if (addPInfoBtn) {
            addPInfoBtn.addEventListener('click', function() {
                const firstRow = document.querySelector('.product-info-row');
                const newRow = firstRow.cloneNode(true);

                newRow.querySelectorAll('input').forEach(input => input.value = '');
                pInfoRepeater.appendChild(newRow);
            });
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('.removeProductInfoRow')) {
                const rows = document.querySelectorAll('.product-info-row');
                if (rows.length > 1) {
                    e.target.closest('.product-info-row').remove();
                }
            }
        });




        // ===== ABOUT THIS ITEM REPEATER =====
        const aboutRepeater = document.getElementById('aboutItemRepeater');
        const addAboutBtn = document.getElementById('addMoreAboutItem');

        if (addAboutBtn) {
            addAboutBtn.addEventListener('click', function() {
                const firstRow = document.querySelector('.about-item-row');
                const newRow = firstRow.cloneNode(true);

                // Reset input value
                newRow.querySelectorAll('input').forEach(input => input.value = '');

                aboutRepeater.appendChild(newRow);
            });
        }

        // Remove About Row
        document.addEventListener('click', function(e) {
            if (e.target.closest('.removeAboutRow')) {
                const rows = document.querySelectorAll('.about-item-row');
                if (rows.length > 1) {
                    e.target.closest('.about-item-row').remove();
                }
            }
        });

        // ================= BRAND SPECS (MOST IMPORTANT) =================
        const brandSpecsRepeater = document.getElementById('brandSpecsRepeater');
        const addBrandSpecsBtn = document.getElementById('addMoreBrandSpecs');

        if (addBrandSpecsBtn && brandSpecsRepeater) {
            addBrandSpecsBtn.addEventListener('click', function() {
                const firstRow = document.querySelector('.brand-spec-row');
                const newRow = firstRow.cloneNode(true);

                // Clear old values
                newRow.querySelectorAll('input').forEach(input => input.value = '');

                brandSpecsRepeater.appendChild(newRow);
            });
        }


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





        // Remove Brand Spec Row
        document.addEventListener('click', function(e) {
            if (e.target.closest('.removeBrandSpecRow')) {
                const rows = document.querySelectorAll('.brand-spec-row');
                if (rows.length > 1) {
                    e.target.closest('.brand-spec-row').remove();
                }
            }
        });




        $(document).ready(function() {

            const breadcrumbEl = document.getElementById('categoryBreadcrumb');
            const categories = @json($categories);

            function findCategory(id, list) {
                for (let cat of list) {
                    if (cat.id == id) return cat;

                    if (cat.children_categories && cat.children_categories.length) {
                        let found = findCategory(id, cat.children_categories);
                        if (found) return found;
                    }
                }
                return null;
            }

            function buildBreadcrumb(cat) {
                let path = [];

                while (cat) {
                    path.unshift(cat.name);
                    cat = findCategory(cat.parent_id, categories);
                }

                return path.join(' > ');
            }

            $('#category_id').on('change select2:select', function() {

                let selectedId = $(this).val();

                if (!selectedId) {
                    breadcrumbEl.innerText = 'No category selected';
                    return;
                }

                let category = findCategory(selectedId, categories);

                if (category) {
                    breadcrumbEl.innerText = buildBreadcrumb(category);
                }
            });

        });

        document.addEventListener("DOMContentLoaded", function() {
            const hsnInput = document.getElementById("code");

            if (hsnInput) {
                hsnInput.addEventListener("input", function() {

                    // only numbers allow
                    this.value = this.value.replace(/\D/g, '');

                    // max 8 digits
                    if (this.value.length > 8) {
                        this.value = this.value.slice(0, 8);
                    }
                });
            }
        });


        document.addEventListener("DOMContentLoaded", function() {

            const checkbox = document.getElementById('is_variant');

            // Function to show/hide variation section
            if (checkbox && checkbox.checked) {
                isVariantProduct(checkbox); // makes .hasVariation visible
            }

            // Initialize Select2 for visible selects only
            function initSelect2() {
                $('.select2').each(function() {
                    if ($(this).data('select2')) {
                        $(this).select2('destroy'); // destroy existing Select2 instances
                    }
                    $(this).select2({
                        width: '100%',
                        dropdownParent: $(this).closest('.hasVariation').length ? $(this).closest(
                            '.hasVariation') : $(document.body)
                    });
                });
            }

            // CSRF setup for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Generate combinations only if variant is checked
            if (checkbox && checkbox.checked) {
                setTimeout(() => {
                    $('select[name="option_1_choices[]"]').trigger('change');
                    $('select[name="option_2_choices[]"]').trigger('change');
                    generateVariationCombinations();
                }, 300); // slight delay to ensure DOM is updated
            }

            $(document).ready(function() {

                // 🔥 AUTO TRIGGER (edit page ke liye)
                setTimeout(() => {
                    $('[name="extra_variations[]"]').trigger('change');
                }, 500);


                // AJAX function to generate variation combinations
                window.generateVariationCombinations = function() {

                    let formData = $('#product-form').serializeArray();

                    // 🔥 REMOVE OLD VARIATIONS
                    formData = formData.filter(item => !item.name.startsWith('variations'));

                    $.ajax({
                        url: "{{ route('product.generateVariationCombinations') }}",
                        type: 'POST',
                        data: $.param(formData),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            console.log("UPDATED TABLE 👉", data);
                            $('#variation_combination').html(data);
                        }
                    });
                };


                // 🔥 EVENT LISTENER (corrected)
                $(document).on('change', '[name="extra_variations[]"]', function() {
                    console.log("🔥 variation changed");
                    generateVariationCombinations();
                });

            }); // ✅ YE closing bahut important hai


        });

        $('#category_id').on('select2:selecting', function(e) {

            let level = $(e.params.args.data.element).data('level');

            if (level < 2) {
                alert('Please select last level category');
                return false;
            }

        });


        $(document).ready(function() {

            let selectedCategory = $('#category_id').val();

            if (selectedCategory) {

                $.get('/admin/category/' + selectedCategory + '/variations', function(data) {

                    let html = '<option value="">Select Variation</option>';

                    if (data.length === 0) {
                        html = '<option value="">No variation found</option>';
                    }

                    data.forEach(function(item) {
                        html += `<option value="${item.id}">${item.name}</option>`;
                    });

                    $('#variation_select').html(html).trigger('change');

                });

            }

        });


        $('#category_id').on('change', function() {
            let selected = $(this).val();

            if (!selected) {
                $('#variation_select').html('<option>Select Variation</option>');
                return;
            }

            $.get('/admin/category/' + selected + '/variations', function(data) {

                let html = '<option value="">Select Variation</option>';

                data.forEach(function(item) {
                    html += `<option value="${item.id}">${item.name}</option>`;
                });

                $('#variation_select').html(html).trigger('change');

            });
        });
        $(document).on('change', '[name^="option_"]', function() {
            console.log("🔥 main variation changed");
            generateVariationCombinations();
        });

        function addAnotherVariation() {

            let container = document.querySelector('.chosen_variation_options');

            // ✅ safe check
            if (!container) {
                alert('Variation container missing');
                return;
            }

            let totalVariations = container.querySelectorAll('.row').length;

            if (totalVariations >= 2) {

                let alertBox = document.getElementById('variation-limit-alert');
                alertBox.classList.remove('d-none');

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
                success: function(data) {

                    // ✅ append inside correct container
                    container.insertAdjacentHTML('beforeend', data.view);

                    $('.select2').select2();
                },
                error: function() {
                    alert('Error loading variation');
                }
            });
        }
        let variationAlertTimer;

        function showVariationLimitAlert() {
            let alertBox = document.getElementById('variation-limit-alert');

            if (!alertBox) return;

            alertBox.classList.remove('d-none');

            clearTimeout(variationAlertTimer);

            variationAlertTimer = setTimeout(() => {
                alertBox.classList.add('d-none');
            }, 3000);
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                if (typeof generateVariationCombinations === "function") {
                    generateVariationCombinations();
                }
            }, 500);
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            function updateBreadcrumb() {
                let select = document.getElementById("category_id");
                let selectedOption = select.options[select.selectedIndex];

                if (!selectedOption.value) {
                    document.getElementById("categoryBreadcrumb").innerText = "No category selected";
                    return;
                }

                let breadcrumb = [];
                let current = selectedOption;

                while (current) {
                    breadcrumb.unshift(current.text.trim());

                    let parentId = current.getAttribute("data-parent");
                    current = Array.from(select.options).find(opt => opt.value == parentId);
                }

                document.getElementById("categoryBreadcrumb").innerText = breadcrumb.join(" > ");
            }

            // 🔥 ON LOAD (IMPORTANT FOR EDIT PAGE)
            updateBreadcrumb();

            // 🔥 ON CHANGE (CREATE PAGE BEHAVIOR)
            document.getElementById("category_id").addEventListener("change", updateBreadcrumb);

        });



        // 🔥 BRAND CHECKBOX
        $(document).ready(function() {

            if ($('#noBrandCheckbox').is(':checked')) {
                $('#selectBrand').prop('disabled', true);
            }

            $('#noBrandCheckbox').on('change', function() {

                if ($(this).is(':checked')) {

                    $('#selectBrand')
                        .val('50') // Generic brand id
                        .prop('disabled', true)
                        .trigger('change');

                } else {

                    $('#selectBrand')
                        .prop('disabled', false)
                        .val('')
                        .trigger('change');
                }

            });
            
        }};

      $('.select2').select2({
            width: '100%'
        });
    </script>
    
@if(auth()->user()->user_type == 'vendor')
<div class="modal fade" id="purchaseQtyRequestModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('vendor.purchase-quantity.request') }}" method="POST">
            @csrf

            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="old_quantity"
                value="{{ $product->admin_max_purchase_qty ?? 10 }}">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Request Purchase Quantity Increase
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">
                            Current Approved Limit
                        </label>

                        <input type="number"
                            class="form-control"
                            value="{{ $product->admin_max_purchase_qty ?? 10 }}"
                            readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Requested Quantity
                        </label>

                        <input type="number"
                            name="requested_quantity"
                            class="form-control"
                            min="{{ ($product->admin_max_purchase_qty ?? 10) + 1 }}"
                            required
                            placeholder="e.g. 50 or 100">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        Send Request
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endif
@endsection
