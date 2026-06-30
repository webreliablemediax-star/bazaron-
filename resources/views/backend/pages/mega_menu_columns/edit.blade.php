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
                        <option value="{{ $cat->id }}"
                            @if(isset($column) && $column->categories->contains($cat->id)) selected @endif>
                            {{ $cat->collectLocalization('name') }}
                        </option>

                        @foreach($cat->childrenCategories as $subCat)
                            <option value="{{ $subCat->id }}"
                                @if(isset($column) && $column->categories->contains($subCat->id)) selected @endif>
                                └ {{ $subCat->collectLocalization('name') }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            {{-- Title --}}
            <div class="form-group">
                <label>{{ localize('Title') }}</label>
                <input type="text" name="title" class="form-control"
                    value="{{ $column->title ?? '' }}" required>
            </div>

            {{-- Column Type --}}
            <div class="form-group">
                <label>{{ localize('Column Type') }}</label>
                <select name="type" class="form-control" id="column-type" required>
                    <option value="variation"
                        @if(isset($column) && $column->type == 'variation') selected @endif>
                        Variation
                    </option>
                    <option value="brand"
                        @if(isset($column) && $column->type == 'brand') selected @endif>
                        Brand
                    </option>
                </select>
            </div>

            {{-- Variation --}}
            <div class="form-group type-dependent" id="variation-select" style="display:none;">
                <label>{{ localize('Select Variation') }}</label>
                <select name="variation_id" class="form-control">
                    <option value="">{{ localize('Select') }}</option>
                    @foreach($variations as $variation)
                        <option value="{{ $variation->id }}"
                            @if(isset($column) && $column->variation_id == $variation->id) selected @endif>
                            {{ $variation->name }}
                        </option>
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

            {{-- Brand (MULTIPLE SAFE) --}}
            <div class="form-group type-dependent" id="brand-select" style="display:none;">
                <label>{{ localize('Select Brand') }}</label>

                @php
                    $selectedBrands = [];

                    if (isset($column) && !empty($column->brand_ids)) {
                        $decoded = json_decode($column->brand_ids, true);
                        $selectedBrands = is_array($decoded) ? $decoded : [];
                    } elseif (isset($column) && !empty($column->brand_id)) {
                        $selectedBrands = [$column->brand_id];
                    }
                @endphp

               <select name="brand_ids[]" class="form-control select2" multiple data-placeholder="Select Brands">
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}"
                            {{ in_array($brand->id, $selectedBrands) ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Order --}}
            <div class="form-group">
                <label>{{ localize('Order') }}</label>
                <input type="number" name="order" class="form-control"
                    value="{{ $column->order ?? 0 }}">
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label>{{ localize('Status') }}</label>
                <select name="is_active" class="form-control">
                    <option value="1"
                        @if(isset($column) && $column->is_active) selected @endif>Active</option>
                    <option value="0"
                        @if(isset($column) && !$column->is_active) selected @endif>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                {{ isset($column) ? localize('Update') : localize('Create') }}
            </button>
        </form>
    </div>
</div>

<script>
    // 🔁 Existing + Enhanced Toggle Logic (SAFE)
    function toggleTypeFields() {
        const type = document.getElementById('column-type').value;

        const variationEl = document.getElementById('variation-select');
        const brandEl = document.getElementById('brand-select');
        const variationValuesBox = document.getElementById('variation-values-box');

        // Show/Hide Variation dropdown
        if (variationEl) {
            variationEl.style.display = type === 'variation' ? 'block' : 'none';
        }

        // Show/Hide Brand multi select
        if (brandEl) {
            brandEl.style.display = type === 'brand' ? 'block' : 'none';
        }

        // 🔥 NEW: Show variation values box only when variation type selected
        if (variationValuesBox) {
            variationValuesBox.style.display = type === 'variation' ? 'block' : 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {

        // Keep original behavior (IMPORTANT - do not remove)
        toggleTypeFields();

        // 🔥 Select2 for multi brand (SAFE - already working feature)
        if (typeof $ !== 'undefined' && $('.select2').length) {
            $('.select2').select2({
                width: '100%',
                placeholder: "Select Multiple Brands",
                closeOnSelect: false,
                allowClear: true
            });
        }

        // 🔥 NEW: Variation Values Dynamic Loader
        const variationSelect = document.querySelector('select[name="variation_id"]');
        const valuesContainer = document.getElementById('variation-values-container');
        const columnType = document.getElementById('column-type');

        function loadVariationValues(variationId) {
            if (!valuesContainer) return;

            if (!variationId) {
                valuesContainer.innerHTML = '<small class="text-muted">Select variation first to load values</small>';
                return;
            }

            valuesContainer.innerHTML = '<small class="text-muted">Loading variation values...</small>';

            // ⚠️ IMPORTANT: URL same as admin panel prefix (/admin/)
            fetch(`/admin/get-variation-values/${variationId}`)
                .then(response => response.json())
                .then(data => {

                    if (!data || data.length === 0) {
                        valuesContainer.innerHTML = '<small class="text-muted">No variation values found</small>';
                        return;
                    }

                    let html = '';

                    data.forEach(function (val) {
                        html += `
                            <div class="form-check mb-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="variation_value_ids[]"
                                       value="${val.id}"
                                       id="variation_val_${val.id}">
                                <label class="form-check-label" for="variation_val_${val.id}">
                                    ${val.name}
                                </label>
                            </div>
                        `;
                    });

                    valuesContainer.innerHTML = html;
                })
                .catch(function () {
                    valuesContainer.innerHTML = '<small class="text-danger">Failed to load variation values</small>';
                });
        }

        // 🔁 On Variation Change → Load Values
        if (variationSelect) {
            variationSelect.addEventListener('change', function () {
                if (columnType.value === 'variation') {
                    loadVariationValues(this.value);
                }
            });

            // 🔥 AUTO LOAD on Edit Page (VERY IMPORTANT)
            if (variationSelect.value && columnType.value === 'variation') {
                loadVariationValues(variationSelect.value);
            }
        }
    });

    // Keep original listener (SAFE)
    document.getElementById('column-type').addEventListener('change', toggleTypeFields);
</script>
@endsection