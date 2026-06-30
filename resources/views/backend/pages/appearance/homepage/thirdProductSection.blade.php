@extends('backend.layouts.master')

@section('title')
    {{ localize('Website Homepage Configuration') }}
    {{ getSetting('title_separator') }}
    {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">

            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
<h2 class="h5 mb-lg-0">
    {{ getSetting('third_section_title') ?? localize('') }}
</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-4">
                <div class="col-xl-9 order-2 order-xl-1">

                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf

                        <div class="card mb-4">
                            <div class="card-body">
                                {{-- SECTION TITLE --}}
<div class="mb-3">
    <label class="form-label">{{ localize('Section Title') }}</label>
    <input type="hidden" name="types[]" value="third_section_title">
    <input type="text"
        name="third_section_title"
        class="form-control"
        placeholder="{{ localize('Third Product Section') }}"
        value="{{ getSetting('third_section_title') }}">
</div>

{{-- SECTION SUB TITLE --}}
<div class="mb-3">
    <label class="form-label">{{ localize('Section Sub Title') }}</label>
    <input type="hidden" name="types[]" value="third_section_subtitle">
    <input type="text"
        name="third_section_subtitle"
        class="form-control"
        value="{{ getSetting('third_section_subtitle') }}">
</div>


                                @php
                                    $third_products = getSetting('third_section_products')
                                        ? json_decode(getSetting('third_section_products'))
                                        : [];
                                @endphp

                                {{-- PRODUCTS --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        {{ localize('Select Products') }}
                                    </label>
                                    <input type="hidden" name="types[]" value="third_section_products">

                                    <select class="select2 form-control" multiple name="third_section_products[]">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ in_array($product->id, $third_products) ? 'selected' : '' }}>
                                                {{ $product->collectLocalization('name') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- BANNER IMAGE --}}
                                <div class="mb-3">
                                    <label class="form-label">{{ localize('Banner Image') }}</label>
                                    <input type="hidden" name="types[]" value="third_section_banner">

                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">{{ localize('Choose Banner Image') }}</span>
                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                onclick="showMediaManager(this)" data-selection="single"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom">

                                                <input type="hidden" name="third_section_banner"
                                                    value="{{ getSetting('third_section_banner') }}">

                                                <div class="no-avatar rounded-circle">
                                                    <span><i data-feather="plus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- BANNER LINK --}}
                                <div class="mb-3">
                                    <label class="form-label">{{ localize('Banner Link') }}</label>
                                    <input type="hidden" name="types[]" value="third_section_banner_link">

                                    <input type="url" class="form-control" name="third_section_banner_link"
                                        value="{{ getSetting('third_section_banner_link') }}"
                                        placeholder="{{ env('APP_URL') . '/example' }}">
                                </div>

                            </div>
                        </div>

                        <button class="btn btn-primary">
                            <i data-feather="save" class="me-1"></i>
                            {{ localize('Save') }}
                        </button>
                    </form>
                </div>

                <div class="col-xl-3 order-1 order-xl-2">
                    <div class="card tt-sticky-sidebar">
                        <div class="card-body">
                            <h5 class="mb-3">{{ localize('Homepage Configuration') }}</h5>
                            <ul class="list-unstyled">
                                @include('backend.pages.appearance.homepage.inc.rightSidebar')
                            </ul>
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
    $(document).ready(function () {
        getChosenFilesCount();
        showSelectedFilePreviewOnLoad();
    });
</script>
@endsection
