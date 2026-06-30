@extends('backend.layouts.master')

@section('title')
    {{ localize('Update Category') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
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
                                        <h2 class="h5 mb-0">{{ localize('Update Category') }} <sup
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
                    <form action="{{ route('admin.categories.update') }}" method="POST" class="pb-650">
                        @csrf
                        <input type="hidden" name="id" value="{{ $category->id }}">
                        <input type="hidden" name="lang_key" value="{{ $lang_key }}">
                        <!--basic information start-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Basic Information') }}</h5>

                                <div class="mb-4">
                                    <label for="name" class="form-label">{{ localize('Category Name') }}</label>
                                    <input class="form-control" type="text" id="name"
                                        placeholder="{{ localize('Type your category name') }}" name="name" required
                                        value="{{ $category->collectLocalization('name') }}">
                                </div>

                                @if (env('DEFAULT_LANGUAGE') == $lang_key)
<div class="mb-4">
    <label for="slug" class="form-label">Slug (URL)</label>
    <input class="form-control"
           type="text"
           id="slug"
           name="slug"
           placeholder="example: mobiles"
           value="{{ $category->slug }}">
    <small class="text-muted">
        This will be used in category URL. Example: /category/mobiles
    </small>
</div>
@endif


                                @if (env('DEFAULT_LANGUAGE') == $lang_key)
                                    <div class="mb-4">
                                        <label for="parent_id" class="form-label">{{ localize('Base Category') }}</label>
                                         @php
                                            function renderEditCategoryOptions(
                                                $category,
                                                $currentParentId,
                                                $currentCategoryId,
                                                $prefix = '',
                                            ) {
                                                if ($category->id != $currentCategoryId) {
                                                    $selected = $category->id == $currentParentId ? 'selected' : '';

                                                    echo '<option value="' .
                                                        $category->id .
                                                        '" ' .
                                                        $selected .
                                                        '>' .
                                                        $prefix .
                                                        $category->collectLocalization('name') .
                                                        '</option>';

                                                    foreach (
                                                        $category
                                                            ->childrenCategories()
                                                            ->orderBy('sorting_order_level', 'desc')
                                                            ->get()
                                                        as $child
                                                    ) {
                                                        renderEditCategoryOptions(
                                                            $child,
                                                            $currentParentId,
                                                            $currentCategoryId,
                                                            $prefix . $category->collectLocalization('name') . ' > ',
                                                        );
                                                    }
                                                }
                                            }
                                        @endphp

                                        <select class="form-control select2 w-100" name="parent_id" data-toggle="select2">

                                            <option value="0" {{ $category->parent_id == 0 ? 'selected' : '' }}>
                                                -
                                            </option>

                                            @foreach ($categories as $acategory)
                                                @php
                                                    renderEditCategoryOptions(
                                                        $acategory,
                                                        $category->parent_id,
                                                        $category->id,
                                                    );
                                                @endphp
                                            @endforeach

                                        </select>
                                        </select>

<div class="mt-2">
    <small class="text-muted" id="categoryBreadcrumb">
        No category selected
    </small>
</div>
                                    </div>

                                    <div class="mb-4">

                                        @php
                                            $categoryBrands = $category->brands()->pluck('brand_id');
                                        @endphp

                                        <label class="form-label">{{ localize('Brands') }}</label>
                                        <select class="form-control select2" name="brand_ids[]" class="w-100"
                                            data-toggle="select2" data-placeholder="{{ localize('Select brands') }}"
                                            multiple>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    {{ $categoryBrands->contains($brand->id) ? 'selected' : '' }}>
                                                    {{ $brand->collectLocalization('name') }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label for="sorting_order_level"
                                            class="form-label">{{ localize('Sorting Priority Number') }}</label>
                                        <input class="form-control" type="number" id="sorting_order_level"
                                            placeholder="{{ localize('Type sorting priority number') }}"
                                            name="sorting_order_level" value="{{ $category->sorting_order_level }}">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!--basic information end-->
                        <div class="mb-4">
    <label for="commission_percentage" class="form-label">{{ localize('Commission (%)') }}</label>
    <input type="number" step="0.01" min="0" max="100" class="form-control" id="commission_percentage" name="commission_percentage" value="{{ old('commission_percentage', $category->commission_percentage) }}">
</div>


                        <!--product image and gallery start-->
                        <div class="card mb-4" id="section-2">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Images') }}</h5>
                                <div class="mb-4">
                                    <label class="form-label">{{ localize('Thumbnail') }}</label>
                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">{{ localize('Choose Category Thumbnail') }}</span>
                                        <!-- choose media -->
                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                onclick="showMediaManager(this)" data-selection="single">
                                                <input type="hidden" name="image"
                                                    value="{{ $category->collectLocalization('thumbnail_image', $lang_key) }}">
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



                        {{-- CATEGORY LANDING PAGE BANNERS --}}
@if(env('DEFAULT_LANGUAGE') == $lang_key)
<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-4">Landing Page Banners</h5>

        @for($i = 1; $i <= 10; $i++)
            <div class="mb-4 border rounded p-3">
                <label class="form-label">Banner {{ $i }} Image</label>

                <div class="tt-product-thumb show-selected-files mt-2">
                    <div class="avatar avatar-xl cursor-pointer choose-media"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasBottom"
                        onclick="showMediaManager(this)"
                        data-selection="single">

                        <input type="hidden"
                               name="banner_image_{{ $i }}"
                               value="{{ $category->{'banner_image_'.$i} }}">

                        <div class="no-avatar rounded-circle">
                            <span><i data-feather="plus"></i></span>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Banner {{ $i }} Link</label>
                    <input type="text"
                           name="banner_link_{{ $i }}"
                           class="form-control"
                           placeholder="https://example.com"
                           value="{{ $category->{'banner_link_'.$i} }}">
                </div>
            </div>
        @endfor

    </div>
</div>
@endif

                        <!--seo meta description start-->
                        <div class="card mb-4" id="section-10">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('SEO Meta Configuration') }}</h5>

                                <div class="mb-4">
                                    <label for="meta_title" class="form-label">{{ localize('Meta Title') }}</label>
                                    <input type="text" name="meta_title" id="meta_title"
                                        placeholder="{{ localize('Type meta title') }}" class="form-control"
                                        value="{{ $category->collectLocalization('meta_title', $lang_key) }}">
                                    <span class="fs-sm text-muted">
                                        {{ localize('Set a meta tag title. Recommended to be simple and unique.') }}
                                    </span>
                                </div>

                                <div class="mb-4">
                                    <label for="meta_description"
                                        class="form-label">{{ localize('Meta Description') }}</label>
                                    <textarea class="form-control" name="meta_description" id="meta_description" rows="4"
                                        placeholder="{{ localize('Type your meta description') }}">{{ $category->collectLocalization('meta_description', $lang_key) }}</textarea>
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
                                                    value="{{ $category->collectLocalization('meta_image', $lang_key) }}">
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

                        <!-- Category SEO Description -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-4">Category Description (Footer SEO Content)</h5>

        <textarea name="description"
                  class="form-control summernote"
                  rows="8">
            {{ old('description', $category->description) }}
        </textarea>
    </div>
</div>

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
                            <h5 class="mb-4">{{ localize('Category Information') }}</h5>
                            <div class="tt-vertical-step">
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="#section-1" class="active">{{ localize('Basic Information') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-2">{{ localize('Category Image') }}</a>
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
    <script>
        "use strict";

        // runs when the document is ready --> for media files
        $(document).ready(function() {
            getChosenFilesCount();
            showSelectedFilePreviewOnLoad();
        });

        // 🔥 Auto slug generator
        document.getElementById('name').addEventListener('keyup', function() {
            let slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');

            document.getElementById('slug').value = slug;
        });

$(document).ready(function () {
    if ($('.summernote').length > 0) {
        $('.summernote').summernote({
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });
    }
});


const categories = @json($categories);

function findCategoryPath(id, list, path = []) {
    for (let cat of list) {

        // match id
        if (cat.id == id) {
            return [...path, cat.name];
        }

        // 🔥 handle both cases
        let children = cat.childrenCategories || cat.children_categories;

        if (children && children.length > 0) {
            let result = findCategoryPath(id, children, [...path, cat.name]);
            if (result) return result;
        }
    }
    return null;
}

$(document).ready(function () {

    function updateBreadcrumb() {
        let selectedId = $('[name="parent_id"]').val();

        if (!selectedId || selectedId == 0) {
            $('#categoryBreadcrumb').text('No category selected');
            return;
        }

        let path = findCategoryPath(selectedId, categories);

        if (path) {
            $('#categoryBreadcrumb').text(path.join(' > '));
        } else {
            $('#categoryBreadcrumb').text('No category selected');
        }
    }

    // 🔥 IMPORTANT (select2 fix)
    $(document).on('change', '[name="parent_id"]', function () {
        updateBreadcrumb();
    });
     updateBreadcrumb(); 

});
</script>
@endsection
   
