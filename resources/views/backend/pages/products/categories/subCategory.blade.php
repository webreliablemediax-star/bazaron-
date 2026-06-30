@php
    $itemPrefix = '';
    for ($i = 0; $i < ($subCategory->level ?? 0); $i++) {
        $itemPrefix .= '▸';
    }
@endphp

<option value="{{ $subCategory->id }}"
    {{ isset($category) && $subCategory->id == $category->parent_id ? 'selected' : '' }}>
    {{ $itemPrefix . ' ' . $subCategory->collectLocalization('name') }}
</option>

{{-- 🔥 VERY IMPORTANT: use childrenCategories (NOT categories) --}}
@if ($subCategory->childrenCategories && $subCategory->childrenCategories->count())
    @foreach ($subCategory->childrenCategories->sortByDesc('sorting_order_level') as $childCategory)
        @include('backend.pages.products.categories.subCategory', [
            'subCategory' => $childCategory,
            'category' => $category ?? null
        ])
    @endforeach
@endif