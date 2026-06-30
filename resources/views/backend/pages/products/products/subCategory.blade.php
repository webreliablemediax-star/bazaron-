{{-- @php
    $itemPrefix = null;
    for ($i = 0; $i < $subCategory->level; $i++) {
        $itemPrefix .= '▸';
    }
@endphp

<option value="{{ $subCategory->id }}" data-level="{{ $subCategory->level }}" data-parent="{{ $subCategory->parent_id }}"
    {{ isset($selectedCategoryId) && $selectedCategoryId == $subCategory->id ? 'selected' : '' }}>

    {{ $itemPrefix . ' ' . $subCategory->collectLocalization('name') }}
</option>

@if ($subCategory->childrenCategories->count())
    @foreach ($subCategory->childrenCategories as $childCategory)
        @include('backend.pages.products.products.subCategory', ['subCategory' => $childCategory])
    @endforeach
@endif --}}


@php
    $itemPrefix = null;

    for ($i = 0; $i < $subCategory->level; $i++) {
        $itemPrefix .= '▸';
    }

    // 🔥 check child exist or not
    $hasChild = $subCategory->childrenCategories->count() > 0;
@endphp


{{-- sirf wahi category show hogi jiska koi child nahi hai --}}
@if (!$hasChild)
    <option value="{{ $subCategory->id }}" data-level="{{ $subCategory->level }}"
        data-parent="{{ $subCategory->parent_id }}"
        {{ isset($selectedCategoryId) && $selectedCategoryId == $subCategory->id ? 'selected' : '' }}>

        @php
            $breadcrumb = [];

            $parent = $subCategory;

            while ($parent) {
                array_unshift($breadcrumb, $parent->collectLocalization('name'));
                $parent = $parent->parentCategory ?? null;
            }
        @endphp

        {{ implode(' > ', $breadcrumb) }}

    </option>
@endif
{{-- @if (!$hasChild)
    <option value="{{ $subCategory->id }}" data-level="{{ $subCategory->level }}"
        data-parent="{{ $subCategory->parent_id }}"
        {{ isset($selectedCategoryId) && $selectedCategoryId == $subCategory->id ? 'selected' : '' }}>

        {{ $itemPrefix . ' ' . $subCategory->collectLocalization('name') }}

    </option>
@endif --}}


{{-- recursion same rahega --}}
@if ($subCategory->childrenCategories->count())

    @foreach ($subCategory->childrenCategories as $childCategory)
        @include('backend.pages.products.products.subCategory', [
            'subCategory' => $childCategory,
        ])
    @endforeach

@endif
