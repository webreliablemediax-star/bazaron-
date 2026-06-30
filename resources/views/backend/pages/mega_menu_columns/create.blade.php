@extends('backend.layouts.master')

@section('title')
    {{ localize('Mega Menu Columns') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <div class="card">
        <div class="card-body">
            <form
                action="{{ isset($column) ? route('admin.mega_menu_columns.update', $column->id) : route('admin.mega_menu_columns.store') }}"
                method="POST">

                @csrf
                @if(isset($column))
                    @method('PUT')
                @endif

                {{-- Categories --}}
                <div class="form-group">
                    <label>{{ localize('Select Categories') }}</label>
                    <select name="category_ids[]" class="form-control" multiple required>
    @foreach($categories as $cat)

        {{-- 🔶 Parent Category (Orange Navbar) --}}
        <option value="{{ $cat->id }}"
            @if(isset($column) && $column->categories->contains($cat->id)) selected @endif>
            {{ $cat->collectLocalization('name') }}
        </option>

        {{-- 🔹 Level 1 Subcategories (White Strip) --}}
        @if($cat->childrenCategories && $cat->childrenCategories->count())
            @foreach($cat->childrenCategories as $child)
                <option value="{{ $child->id }}"
                    @if(isset($column) && $column->categories->contains($child->id)) selected @endif>
                    &nbsp;&nbsp;▸ {{ $child->collectLocalization('name') }}
                </option>

                {{-- 🔹 Level 2 Subcategories (Deep Menu like Audio → Headphones) --}}
                @if($child->childrenCategories && $child->childrenCategories->count())
                    @foreach($child->childrenCategories as $subChild)
                        <option value="{{ $subChild->id }}"
                            @if(isset($column) && $column->categories->contains($subChild->id)) selected @endif>
                            &nbsp;&nbsp;&nbsp;&nbsp;▸ {{ $subChild->collectLocalization('name') }}
                        </option>
                    @endforeach
                @endif

            @endforeach
        @endif

    @endforeach
</select>
                </div>


                {{-- Title --}}
                <div class="form-group">
                    <label>{{ localize('Title') }}</label>
                    <input type="text" name="title" class="form-control" value="{{ $column->title ?? '' }}" required>
                </div>

                {{-- Column Type --}}
                <div class="form-group">
                    <label>{{ localize('Column Type') }}</label>
                    <select name="type" class="form-control" id="column-type" required>
                        <option value="variation" @if(isset($column) && $column->type == 'variation') selected @endif>
                            Variation</option>
                        <option value="brand" @if(isset($column) && $column->type == 'brand') selected @endif>Brand</option>
                        <!-- <option value="category" @if(isset($column) && $column->type == 'category') selected @endif>
                                Category/Subcategory</option> -->
                    </select>
                </div>

                {{-- Variation --}}
                <div class="form-group type-dependent" id="variation-select" style="display:none;">
                    <label>{{ localize('Select Variation') }}</label>
                    <select name="variation_id" class="form-control">
                        <option value="">{{ localize('Select') }}</option>
                        @foreach($variations as $variation)
                            <option value="{{ $variation->id }}" @if(isset($column) && $column->variation_id == $variation->id)
                            selected @endif>{{ $variation->name }}</option>
                        @endforeach
                    </select>
                </div>


                {{-- 🔥 Variation Values (Checkbox Filter) --}}
<div class="form-group type-dependent" id="variation-values-box" style="display:none;">
    <label>{{ localize('Select Variation Values (Optional Filter)') }}</label>

    @php
        $selectedValues = [];
        if (isset($column) && !empty($column->variation_value_ids)) {
            $decoded = json_decode($column->variation_value_ids, true);
            $selectedValues = is_array($decoded) ? $decoded : [];
        }
    @endphp

    <div id="variation-values-container"
         style="max-height:220px; overflow-y:auto; border:1px solid #e5e7eb; padding:12px; border-radius:8px; background:#fafafa;">
        <small class="text-muted">Select variation first to load values</small>
    </div>
</div>

                {{-- Brand (MULTI SELECT - CLEAN UI) --}}
<div class="form-group type-dependent" id="brand-select" style="display:none;">
    <label>{{ localize('Select Brand') }}</label>

    <select name="brand_ids[]" 
            class="form-control select2" 
            multiple 
            data-placeholder="Select Multiple Brands">
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}">
                {{ $brand->name }}
            </option>
        @endforeach
    </select>
</div>

                {{-- Order --}}
                <div class="form-group">
                    <label>{{ localize('Order') }}</label>
                    <input type="number" name="order" class="form-control" value="{{ $column->order ?? 0 }}">
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label>{{ localize('Status') }}</label>
                    <select name="is_active" class="form-control">
                        <option value="1" @if(isset($column) && $column->is_active) selected @endif>Active</option>
                        <option value="0" @if(isset($column) && !$column->is_active) selected @endif>Inactive</option>
                    </select>
                </div>

                <button type="submit"
                    class="btn btn-primary">{{ isset($column) ? localize('Update') : localize('Create') }}</button>
            </form>
        </div>
    </div>

    {{-- JS for show/hide based on type --}}
    <script>
    // 🔥 MASTER TOGGLE (SAFE - existing logic + variation values support)
    function toggleTypeFields() {
        const type = document.getElementById('column-type').value;

        const variation = document.getElementById('variation-select');
        const brand = document.getElementById('brand-select');
        const valuesBox = document.getElementById('variation-values-box'); // NEW

        if (variation) {
            variation.style.display = type === 'variation' ? 'block' : 'none';
        }

        if (brand) {
            brand.style.display = type === 'brand' ? 'block' : 'none';
        }

        // 🔥 IMPORTANT: show checkbox box only for variation
        if (valuesBox) {
            valuesBox.style.display = type === 'variation' ? 'block' : 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {

        // Initial toggle (VERY IMPORTANT)
        toggleTypeFields();

        // 🔥 Select2 (Brand multi-select) — untouched safe feature
        if (typeof $ !== 'undefined' && $('.select2').length) {
            $('.select2').select2({
                width: '100%',
                placeholder: "Select Multiple Brands",
                closeOnSelect: false,
                allowClear: true
            });
        }

        const variationSelect = document.querySelector('select[name="variation_id"]');
        const valuesBox = document.getElementById('variation-values-box');
        const valuesContainer = document.getElementById('variation-values-container');
        const columnType = document.getElementById('column-type');

        function loadVariationValues(variationId) {
            if (!valuesContainer) return;

            if (!variationId) {
                valuesBox.style.display = 'none';
                valuesContainer.innerHTML = '<small class="text-muted">Select variation first to load values</small>';
                return;
            }

            // Ensure box visible when variation selected
            valuesBox.style.display = 'block';
            valuesContainer.innerHTML = '<small class="text-muted">Loading values...</small>';

            fetch(`/admin/get-variation-values/${variationId}`)
                .then(res => res.json())
                .then(data => {
                    if (!data || data.length === 0) {
                        valuesContainer.innerHTML = '<small class="text-muted">No values found</small>';
                        return;
                    }

                    let html = '';
                    data.forEach(val => {
                        html += `
                            <div class="form-check mb-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="variation_value_ids[]"
                                       value="${val.id}"
                                       id="val_${val.id}">
                                <label class="form-check-label" for="val_${val.id}">
                                    ${val.name}
                                </label>
                            </div>
                        `;
                    });

                    valuesContainer.innerHTML = html;
                })
                .catch(() => {
                    valuesContainer.innerHTML = '<small class="text-danger">Failed to load values</small>';
                });
        }

        // 🔁 Load on dropdown change
        if (variationSelect) {
            variationSelect.addEventListener('change', function () {
                if (columnType.value === 'variation') {
                    loadVariationValues(this.value);
                }
            });

            // 🔥 AUTO LOAD on EDIT page (super important)
            if (variationSelect.value && columnType.value === 'variation') {
                loadVariationValues(variationSelect.value);
            }
        }

        // Type change listener (keep original behavior)
        document.getElementById('column-type').addEventListener('change', toggleTypeFields);
    });
</script>
@endsection