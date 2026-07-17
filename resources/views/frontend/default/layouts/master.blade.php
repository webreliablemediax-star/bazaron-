<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>

    <!--required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- favicon -->
    <link rel="icon" href="{{ uploadedAsset(getSetting('favicon')) }}" type="image/png" sizes="16x16">

    <!--title-->
    {{-- <title>{{ getSetting('system_title') }}</title> --}}
    <title>{{ trim($__env->yieldContent('title', getSetting('system_title'))) }}</title>
    <!--meta-->
    <meta name="robots" content="index, follow">
    {{-- <meta name="description" content="{{ getSetting('global_meta_description') }}"> --}}
    <meta name="description"
        content="{{ trim(preg_replace('/\s+/', ' ', $__env->yieldContent('meta_description', getSetting('global_meta_description')))) }}">
    {{-- <meta name="keywords" content="{{ getSetting('global_meta_keywords') }}"> --}}
    <meta name="keywords"
        content="{{ trim(preg_replace('/\s+/', ' ', $__env->yieldContent('meta_keywords', getSetting('global_meta_keywords')))) }}">

    @yield('meta')

    {{-- ⭐ HERO LCP PRELOAD (SAFE + NO DB MODEL CALL) --}}
    @if (isset($sliders) && count($sliders) > 0 && isset($sliders[0]->image))
        <link rel="preload" as="image" href="{{ uploadedAsset($sliders[0]->image) }}" fetchpriority="high">
    @endif

    <!-- head scripts -->
    @include('frontend.default.inc.head-scripts')

    {{-- SAFE localLang fallback (NO undefined error) --}}
    @php
        if (!isset($localLang) || $localLang == null) {
            try {
                $localLang = \App\Models\Language::where('code', app()->getLocale())->first();

                if (!$localLang) {
                    $localLang = \App\Models\Language::first();
                }
            } catch (\Exception $e) {
                // fallback dummy object to prevent crash
                $localLang = (object) [
                    'is_rtl' => 0,
                ];
            }
        }
    @endphp


    <!--build:css-->
    @include('frontend.default.inc.css', ['localLang' => $localLang])
    <!-- endbuild -->

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- PWA (disabled for performance testing) -->
    <!--
    <meta name="theme-color" content="#6eb356"/>
    <link rel="apple-touch-icon" href="{{ staticAsset('/pwa.png') }}"/>
    <link rel="manifest" href="{{ staticAsset('/manifest.json') }}"/>
    -->

    <!-- recaptcha -->
    @if (getSetting('enable_recaptcha') == 1)
        {!! RecaptchaV3::initJs() !!}
    @endif

</head>


<body>

    @php
        // for visitors to add to cart
        $tempValue = strtotime('now') . rand(10, 1000);
        $theTime = time() + 86400 * 365;
        if (!isset($_COOKIE['guest_user_id'])) {
            setcookie('guest_user_id', $tempValue, $theTime, '/'); // 86400 = 1 day
        }

    @endphp

    <!--preloader start-->
    <!--<div id="preloader">-->
    <!--    <img src="{{ staticAsset('frontend/default/assets/img/preloader.gif') }}" alt="preloader" class="img-fluid">-->
    <!--</div>-->
    <!--preloader end-->

    <!--main content wrapper start-->
    <div class="main-wrapper">
        <!--header section start-->
        @if (isset($exception))
            @if ($exception->getStatusCode() != 503)
                @include('frontend.default.inc.header')
            @endif
        @else
            @include('frontend.default.inc.header')
            @if (session('error'))
                <div id="topAlert"
                    style="
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #fff3cd;
            color: #856404;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 9999;
        ">
                    {{ session('error') }}
                </div>

                <script>
                    setTimeout(function() {
                        let alertBox = document.getElementById('topAlert');
                        if (alertBox) {
                            alertBox.style.transition = "opacity 0.5s";
                            alertBox.style.opacity = "0";
                            setTimeout(() => alertBox.remove(), 500);
                        }
                    }, 2000);
                </script>
            @endif
        @endif
        <!--header section end-->

        <!--breadcrumb section start-->
        @yield('breadcrumb')
        <!--breadcrumb section end-->

        <!--offcanvas menu start-->
        @include('frontend.default.inc.offcanvas')
        <!--offcanvas menu end-->

        @yield('contents')

        <!-- modals -->
        @include('frontend.default.pages.partials.products.quickViewModal')
        <!-- modals -->


        <!--footer section start-->
        @if (isset($exception))
            @if ($exception->getStatusCode() != 503)
                @include('frontend.default.inc.footer')
                @include('frontend.default.inc.bottomToolbar')
            @endif
        @else
            @include('frontend.default.inc.footer')
            @include('frontend.default.inc.bottomToolbar')
        @endif
        <!--footer section end-->

    </div>


    <!--scroll bottom to top button start-->
    <!--<button class="scroll-top-btn">-->
    <!--    <i class="fa-regular fa-hand-pointer"></i></button>-->
    <!--scroll bottom to top button end-->


    <!--build:js-->
    @include('frontend.default.inc.scripts')
    <script src="{{ asset('frontend/default/assets/js/app.js') }}" defer></script>

    <script>
        function handleCartItem(action, id) {

            fetch("{{ route('carts.update') }}", {

                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },

                    body: JSON.stringify({
                        action: action,
                        id: id
                    })

                })

                .then(res => res.json())

                .then(data => {

                    if (data.success) {

                        // cart page update
                        let cartListing = document.querySelector(".cart-listing");
                        if (cartListing) {
                            cartListing.innerHTML = data.carts;
                        }

                        // minicart update
                        let navCart = document.querySelector(".cart-navbar-wrapper");
                        if (navCart) {
                            navCart.innerHTML = data.navCarts;
                        }

                        // cart counter update  
                        document.querySelectorAll(".cart-counter").forEach(function(counter) {
                            counter.innerText = data.cartCount;
                            if (parseInt(data.cartCount) > 0) {
                                counter.classList.remove("d-none");
                            } else {
                                counter.classList.add("d-none");
                            }
                        });

                        // subtotal update
                        document.querySelectorAll(".sub-total-price").forEach(function (subtotal) {
                            subtotal.innerText = data.subTotal;
                        });

                        let couponDiscountRow = document.querySelector(".coupon-discount-wrapper");
                        let couponDiscountPrice = document.querySelector(".coupon-discount-price");

                        if (couponDiscountRow && couponDiscountPrice && data.couponCode) {
                            couponDiscountRow.classList.remove("d-none");
                            couponDiscountPrice.innerText = data.couponDiscount;
                        }

                    }

                })

                .catch(err => console.log(err));

        }
    </script>

    <!--endbuild-->



    <!--page's scripts-->
    @yield('scripts')
    <!--page's script-->

    <!-- for pwa-->
    <!-- <script src="{{ url('/') . '/public/sw.js' }}"></script>
        <script>
            if (!navigator.serviceWorker?.controller) {
                navigator.serviceWorker?.register("./public/sw.js").then(function(reg) {
                    // console.log("Service worker has been registered for scope: " + reg.scope);
                });
            }
        </script> -->
    <!--for pwa -->

</body>

</html>
