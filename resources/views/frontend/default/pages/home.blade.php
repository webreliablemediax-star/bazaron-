@extends('frontend.default.layouts.master')

@section('title')
    {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <!--hero section start-->
    <div class="position-relative">

        {{-- Hero: Sirf Desktop pe --}}
        <div class="d-none d-md-block">
            @include('frontend.default.pages.partials.home.hero')
        </div>
        <!-- hero section end -->

        {{-- Desktop: Category hero ke upar overlap kare --}}
        <div class="d-none d-md-block position-absolute w-100 z-10" style="top:100%; transform: translateY(-260px);">
            @include('frontend.default.pages.partials.home.category')
        </div>

    </div>

    {{-- Mobile: Category normal flow mein --}}
    <div class="d-block d-md-none" style="position: relative; z-index: 1; margin-bottom: 20px; transform: none;">
        @include('frontend.default.pages.partials.home.category')
    </div>


    <!--hero section end-->



    <!--featured products start-->
    @include('frontend.default.pages.partials.home.featuredProducts')
    <!--featured products end-->

    <!--trending products start-->
    @include('frontend.default.pages.partials.home.trendingProducts')
    <!--trending products end-->

    <!--banner section start-->
    @include('frontend.default.pages.partials.home.banners')
    <!--banner section end-->

    <!--banner section start-->
    @include('frontend.default.pages.partials.home.bestDeals')
    <!--banner section end-->

    <!--banner 2 section start-->
    @include('frontend.default.pages.partials.home.bannersTwo')
    <!--banner 2 section end-->

    @include('frontend.default.pages.partials.home.bestSellingProducts')

    @include('frontend.default.pages.partials.home.secondProductsSection')

    @include('frontend.default.pages.partials.home.thirdProductsSection')

    @include('frontend.default.pages.partials.home.fourthProductsSection')


    <!--feedback section start-->
    @include('frontend.default.pages.partials.home.feedback')
    <!--feedback section end-->


    <!--products listing start-->
    @include('frontend.default.pages.partials.home.products')
    <!--products listing end-->

    @if (getSetting('enable_custom_product_section') == 1)
        <!-- start -->
        @include('frontend.default.pages.partials.home.customProductsSection')
        <!-- end -->
    @endif

    <!--blog section start-->
    @include('frontend.default.pages.partials.home.blogs', ['blogs' => $blogs])
    <!--blog section end-->
@endsection

@section('scripts')
    <script>
        "use strict";

        // runs when the document is ready 
        $(document).ready(function() {
            @if (\App\Models\Location::where('is_published', 1)->count() > 1)
                notifyMe('info', '{{ localize('Select your location if not selected') }}');
            @endif
        });
    </script>
@endsection
