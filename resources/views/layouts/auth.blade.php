<!DOCTYPE html>

@php
    $locale = str_replace('_', '-', app()->getLocale()) ?? 'en';
    $localLang = \App\Models\Language::where('code', $locale)->first();
    if ($localLang == null) {
        $localLang = \App\Models\Language::where('code', 'en')->first();
    }
@endphp

@if ($localLang->is_rtl == 1)
    <html dir="rtl" lang="{{ $locale }}" data-bs-theme="light">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
@endif


<head>
    <!--required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--favicon icon-->
    <!--<link rel="icon" href="{{ staticAsset('frontend/default/assets/img/favicon.png') }}" type="image/png"-->
    <!--    sizes="16x16">-->
    
    
    
    <link rel="icon" href="https://goodiesonline.in/public/uploads/media/n6kGq1ZOa08HIU4yFz8i0EOlh8UoE2ZAKlhgqCZ5.png?v=2" type="image/png" sizes="32x32">

    <!--title-->
    <title>
        @yield('title')
    </title>

    <!--build:css-->
    @include('frontend.default.inc.css')
    <!-- endbuild -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <!-- recaptcha -->
    @if (getSetting('enable_recaptcha') == 1)
        {!! RecaptchaV3::initJs() !!}
    @endif
    <!-- recaptcha -->

    <style>
        /* Hide orange category navbar */
.navnew{
display:none !important;
}
/* FIX ACCOUNT TEXT WRAP */
.account-text{
line-height:1.1;
text-align:right;
white-space:nowrap;
}

.account-text span{
display:block;
font-size:12px;
}

.account-text strong{
display:block;
font-size:13px;
}



    </style>
     
        
    


</head>

<body>
            

    <div class="container">
        @yield('content')
    </div>

    <!--preloader start-->
    <div id="preloader">
        <div id="status"></div>
    </div>
    <!--preloader end-->

    <!--main content wrapper start-->
    <div class="main-wrapper">

        @yield('contents')

        <!-- FOOTER -->



    </div>


    <!-- scripts -->
    @yield('scripts')

    <!--build:js-->
    @include('frontend.default.inc.scripts')
    <!--endbuild-->
    @include('frontend.default.inc.scripts')

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>

</html>


