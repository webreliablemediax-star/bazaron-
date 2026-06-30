<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ staticAsset('backend/assets/img/favicon.png') }}">

    <!-- App / Theme CSS -->
    @include('backend.inc.styles')

    <!-- 🔥 PRODUCT IMAGE CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/product.css') }}">
</head>

<body>

    <main class="tt-main-wrapper bg-secondary-subtle h-100">
        @yield('contents')
    </main>

    <!-- 🔥 ALL CATEGORIES CLICK JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const allCat = document.querySelector('.all-categories');
            const megaMenu = document.querySelector('.categories-sidebar');

            if (allCat && megaMenu) {
                allCat.addEventListener('click', function (e) {
                    e.preventDefault();
                    megaMenu.classList.toggle('active');
                });
            }

        });
    </script>

    <!-- 🔥 JS DEPENDENCIES (ORDER MAT BIGADNA) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/elevatezoom/3.0.8/jquery.elevatezoom.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <!-- 🔥 PRODUCT GALLERY / ZOOM LOGIC -->
    <script src="{{ asset('frontend/assets/js/product-gallery.js') }}"></script>

</body>
</html>
