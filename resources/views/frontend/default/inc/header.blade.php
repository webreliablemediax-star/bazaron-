<style>
    .offcanvas-left-menu.position-fixed img {
        width: 85px;
    }

    .gshop-navbar .logo img {
        width: 90px;
        display: block;
    }

    /* 🔥 FIX: hide all-category sidebar by default */
    .categories-sidebar {
        display: none;
        position: absolute;
        top: 100%;
        left: -60%;
        background: #fff;
        z-index: 999;
        width: 220px;
    }

    .nav-left:hover .categories-sidebar {
        display: block;
    }

    .mega-menu-dropdown {
        position: absolute;
        top: 70%;
        /* pehle 100% tha */
        left: 0;
        background: #fff;
        width: 800px;
        display: none;
        z-index: 9999;
        padding: 20px;
    }


    .nav-item:hover .mega-menu-dropdown {
        display: block;
    }

    /* ACCOUNT TEXT */

    .account-trigger {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #fff;
    }

    .account-text {
        line-height: 1.1;
        text-align: right;
    }

    .account-text span {
        font-size: 12px;
        color: #fff;
    }

    .account-text strong {
        font-size: 13px;
        color: #fff;
    }

    /* ===== ACCOUNT DROPDOWN SIMPLE ===== */

    .user-menu-wrapper {
        display: none;
        position: absolute;
        right: 0;
        top: 42px;
        background: #fff;
        width: 240px;
        padding: 10px 0;
        box-shadow: 0 6px 18px rgba(0, 0, 0, .15);
        border-radius: 6px;
        z-index: 9999;
    }

    .account-trigger {
        position: relative;
    }

    .account-trigger:hover .user-menu-wrapper {
        display: block;
    }

    .account-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .account-menu li {
        border-bottom: 1px solid #f2f2f2;
    }

    .account-menu li:last-child {
        border-bottom: none;
    }

    .account-menu a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        font-size: 14px;
        color: #111;
        text-decoration: none;
    }

    .account-menu a:hover {
        background: #f7f7f7;
    }

    .signin-btn {
        display: block;
        width: 80%;
        margin: 10px auto;
        text-align: center;
        background: black;
        padding: 8px;
        border-radius: 6px;
        font-weight: 600;
        color: #111;
        text-decoration: none;
    }

    .signin-btn:hover {
        color: white !important;
        background: black;
    }

    .signin-btn {
        background: #000;
        color: #fff !important;
    }

    .mega-menu-dropdown {
        pointer-events: none;
    }

    .nav-item:hover .mega-menu-dropdown {
        display: block;
        pointer-events: auto;
    }

    .lang-wrapper {
        position: relative;
    }

    .lang-btn {
        background: transparent;
        border: 1px solid #ccc;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
    }

    .lang-dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 40px;
        width: 230px;
        background: #fff;
        color: #000;
        border-radius: 8px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        padding: 15px;
        z-index: 99999;
    }

    .lang-dropdown label {
        display: block;
        margin-bottom: 6px;
        cursor: pointer;
    }


    .navnew.header-sticky {
        position: static !important;
    }

    .gheader {
        position: relative;
        z-index: 9999;
    }

    /* scroll ke baad sticky */
    .gheader.sticky-active {
        position: fixed;
        top: 0;
        width: 100%;
        background: #0b2c5f;

    }

    .delivery-location-box {
        display: flex;
        align-items: center;
        color: #fff;
        cursor: pointer;
        line-height: 1.2;
    }

    .delivery-location-box i {
        font-size: 20px;
    }

    .delivery-location-box small {
        font-size: 11px;
        color: #ddd;
    }

    .delivery-location-box strong {
        font-size: 14px;
        color: #fff;
        font-weight: 700;
    }

    .delivery-location-box {
        display: flex;
        align-items: center;
        color: #fff;
        cursor: pointer;
        line-height: 1.2;
        margin-left: 25px;
        /* right push */
        position: relative;
        left: 20px;
    }

    .location-text {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
    }

    .location-top {
        font-size: 12px;
        color: #d6d6d6;
        font-weight: 400;
    }

    .location-bottom {
        font-size: 15px;
        color: #fff;
        font-weight: 700;
        margin-top: 2px;
    }

    .amazon-location-modal {
        border-radius: 12px;
    }

    #locationModal .modal-dialog {
        max-width: 520px;
    }

    .amazon-address-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: .3s;
    }

    .amazon-address-card:hover {
        border-color: #0073e6;
        background: #f7fbff;
    }

    .amazon-address-card strong {
        display: block;
        margin-bottom: 5px;
    }

    .amazon-divider {
        text-align: center;
        margin: 15px 0;
        position: relative;
    }

    .amazon-divider span {
        background: #fff;
        padding: 0 10px;
        position: relative;
        z-index: 2;
    }

    .amazon-divider:before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 1px;
        background: #ddd;
    }
</style>

<header class="gheader z-2">
    <div class="gshop-navbar position-relative">
        <div class="container">
            <div class="row align-items-center">

                <!-- LOGO -->
                <div class="col-lg-2 col-md-3 col-6 d-flex align-items-center justify-content-between">

                    <a href="{{ route('home') }}" class="logo">
                        <img src="{{ uploadedAsset(getSetting('navbar_logo')) }}" alt="logo">
                    </a>
                    <a href="{{ route('vendor.register') }}" class="mobile-seller-top d-block d-md-none">
                        <i class="fa fa-store"></i> Become a Seller
                    </a>
                </div>


                <!-- SEARCH BAR -->
                <div class="col-lg-5 col-md-6 d-none d-md-block">
                    <form class="search-form d-flex" action="{{ route('products.index') }}">
                        <input type="text" name="search" placeholder="{{ localize('Search products') }}"
                            class="w-100">
                        <button type="submit" style="background-color:#e86301 !important">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- LOCATION -->
                @if (!auth()->check() || auth()->user()->user_type != 'vendor')
                    <div class="col-lg-2 d-none d-md-flex align-items-center">
                        <div class="delivery-location-box" id="deliveryBox" data-bs-toggle="modal"
                            data-bs-target="#locationModal">
                            <i class="fa fa-map-marker-alt me-2"></i>

                            <div class="location-text">
                                <span class="location-top">
                                    Deliver to {{ Auth::check() ? Auth::user()->name : 'Guest' }}
                                </span>

                                @if (session('user_pincode'))
                                    <span class="location-bottom">
                                        {{ session('user_district') }}
                                        {{ session('user_pincode') }}
                                    </span>
                                @else
                                    <span class="location-bottom">
                                        Select Location
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif


                <!-- RIGHT ICONS (3 ICONS) -->
                <div class="col-lg-3 col-md-3 col-6"style="margin-left:auto" ;>
                    <div
                        class="gshop-header-icons d-none d-md-inline-flex align-items-center d-flex justify-content-end">
                        <!-- <div class="gshop-header-search dropdown">
                                <button type="button" class="header-icon" data-bs-toggle="dropdown">
                                    <svg width="18" height="23" viewBox="0 0 22 23" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.68859 0.5C4.34645 0.5 0 4.84646 0 10.1886C0 15.5311 4.34645 19.8772 9.68859 19.8772C15.031 19.8772 19.3772 15.5311 19.3772 10.1886C19.3772 4.84646 15.031 0.5 9.68859 0.5ZM9.68859 18.0886C5.33261 18.0886 1.78866 14.5447 1.78866 10.1887C1.78866 5.83266 5.33261 2.28867 9.68859 2.28867C14.0446 2.28867 17.5885 5.83262 17.5885 10.1886C17.5885 14.5446 14.0446 18.0886 9.68859 18.0886Z"
                                            fill="#5D6374" />
                                        <path
                                            d="M21.7406 20.9824L16.6436 15.8853C16.2962 15.538 15.7338 15.538 15.3865 15.8853C15.0391 16.2323 15.0391 16.7954 15.3865 17.1424L20.4835 22.2395C20.6571 22.4131 20.8845 22.5 21.1121 22.5C21.3393 22.5 21.5669 22.4131 21.7406 22.2395C22.0879 21.8925 22.0879 21.3294 21.7406 20.9824Z"
                                            fill="#5D6374" />
                                    </svg>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end border-0">
                                    <form class="search-form d-flex align-items-center"
                                        action="{{ route('products.index') }}">
                                        <input type="text" placeholder="{{ localize('Search products') }}"
                                            class="w-100" name="search"
                                            @isset($searchKey) value="{{ $searchKey }}" @endisset>
                                        <button type="submit" class="submit-icon-btn-secondary"><i
                                                class="fa-solid fa-magnifying-glass"></i></button>
                                    </form>
                                </div>
                            </div> -->
                        @if (!auth()->check() || auth()->user()->user_type != 'vendor')
                            <a href="{{ route('vendor.register') }}" class="become-seller-btn">
                                <i class="fa-solid fa-store"></i>
                                <span>Become a<br>Seller</span>
                            </a>
                        @endif
                        <div class="gshop-header-user position-relative">
                            <div class="account-trigger d-flex align-items-center">

                                <div class="account-text me-1">
                                    @auth
                                        <span>Hello, {{ Auth::user()->name }}</span>
                                    @else
                                        <span>Hello, Sign in</span>
                                    @endauth
                                    <strong>Account & Lists</strong>
                                </div>




                                <button type="button" class="header-icon border-0 bg-transparent p-0">
                                    <svg width="18" height="25" viewBox="0 0 22 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.092 11.9546C12.6656 11.9546 14.0281 11.3902 15.1416 10.2766C16.2547 9.16322 16.8193 7.80093 16.8193 6.2271C16.8193 4.65382 16.2549 3.29134 15.1414 2.1776C14.0279 1.0644 12.6654 0.5 11.092 0.5C9.51825 0.5 8.156 1.0644 7.04266 2.17778C5.92931 3.29116 5.36475 4.65363 5.36475 6.2271C5.36475 7.80093 5.92931 9.1634 7.04266 10.2768C8.15636 11.39 9.51879 11.9546 11.092 11.9546ZM8.0281 3.16308C8.88239 2.30877 9.88453 1.89349 11.092 1.89349C12.2993 1.89349 13.3017 2.30877 14.1561 3.16308C15.0104 4.01757 15.4259 5.01992 15.4259 6.2271C15.4259 7.43464 15.0104 8.43681 14.1561 9.2913C13.3017 10.1458 12.2993 10.5611 11.092 10.5611C9.88489 10.5611 8.88275 10.1456 8.0281 9.2913C7.17364 8.43699 6.7582 7.43464 6.7582 6.2271C6.7582 5.01992 7.17364 4.01757 8.0281 3.16308Z"
                                            fill="#5D6374" stroke="#5D6374" stroke-width="0.2" />
                                        <path
                                            d="M21.1339 18.893C21.1012 18.4201 21.0352 17.9043 20.9379 17.3596C20.8397 16.8108 20.7133 16.292 20.562 15.8178C20.4055 15.3277 20.1931 14.8438 19.9301 14.38C19.6575 13.8986 19.3371 13.4794 18.9776 13.1345C18.6016 12.7736 18.1414 12.4835 17.6091 12.2719C17.0787 12.0614 16.4909 11.9547 15.8621 11.9547C15.6152 11.9547 15.3763 12.0564 14.9151 12.3576C14.6313 12.5433 14.2993 12.7581 13.9287 12.9956C13.6118 13.1982 13.1825 13.3879 12.6523 13.5598C12.135 13.7277 11.6098 13.8129 11.0912 13.8129C10.5729 13.8129 10.0477 13.7277 9.53001 13.5598C9.00034 13.3881 8.57088 13.1984 8.25455 12.9958C7.88747 12.7605 7.55527 12.5457 7.26718 12.3574C6.80634 12.0562 6.56753 11.9545 6.32059 11.9545C5.69163 11.9545 5.10401 12.0614 4.57378 12.2721C4.04189 12.4833 3.58143 12.7734 3.20512 13.1347C2.84561 13.4798 2.52522 13.8988 2.25281 14.38C1.99019 14.8438 1.77758 15.3276 1.62108 15.818C1.46993 16.2922 1.34351 16.8108 1.24533 17.3596C1.14788 17.9035 1.082 18.4195 1.04933 18.8935C1.01722 19.3569 1.00098 19.8393 1.00098 20.3266C1.00098 21.5934 1.40238 22.6189 2.19394 23.3752C2.97572 24.1216 4.00996 24.5 5.26808 24.5H16.9157C18.1735 24.5 19.2077 24.1216 19.9897 23.3752C20.7814 22.6194 21.1828 21.5935 21.1828 20.3264C21.1826 19.8374 21.1662 19.3551 21.1339 18.893ZM19.0123 22.3449C18.4957 22.8381 17.8099 23.0779 16.9155 23.0779H5.26808C4.37354 23.0779 3.68773 22.8381 3.17135 22.3451C2.66474 21.8613 2.41854 21.2008 2.41854 20.3266C2.41854 19.8718 2.43349 19.4229 2.46339 18.9918C2.49255 18.569 2.55216 18.1044 2.64056 17.6108C2.72786 17.1233 2.83896 16.6658 2.9711 16.2516C3.09789 15.8545 3.27082 15.4612 3.48527 15.0824C3.68995 14.7214 3.92544 14.4116 4.18529 14.1621C4.42835 13.9286 4.73471 13.7375 5.0957 13.5942C5.42956 13.4616 5.80476 13.3891 6.21208 13.3781C6.26172 13.4046 6.35012 13.4552 6.49334 13.5488C6.78475 13.7394 7.12064 13.9567 7.49197 14.1946C7.91054 14.4624 8.44981 14.7042 9.09409 14.9128C9.75277 15.1265 10.4245 15.235 11.0913 15.235C11.7581 15.235 12.4301 15.1265 13.0884 14.913C13.7333 14.704 14.2723 14.4624 14.6915 14.1943C15.0715 13.9506 15.3979 13.7395 15.6894 13.5488C15.8326 13.4553 15.921 13.4046 15.9706 13.3781C16.3781 13.3891 16.7533 13.4616 17.0874 13.5942C17.4482 13.7375 17.7545 13.9288 17.9976 14.1621C18.2574 14.4114 18.4929 14.7212 18.6976 15.0826C18.9122 15.4612 19.0854 15.8547 19.212 16.2515C19.3443 16.6662 19.4556 17.1235 19.5427 17.6106C19.6309 18.1052 19.6907 18.5699 19.7199 18.992V18.9924C19.7499 19.4218 19.7651 19.8705 19.7653 20.3266C19.7651 21.201 19.5189 21.8613 19.0123 22.3449Z"
                                            fill="#5D6374" stroke="#5D6374" stroke-width="0.2" />
                                    </svg>
                                </button>
                                <div class="user-menu-wrapper bazaron-dropdown">

                                    {{-- SIGN IN BUTTON TOP --}}
                                    @guest
                                        <div class="signin-top">
                                            <a href="{{ route('login') }}" class="signin-btn">Sign In</a>
                                        </div>
                                    @endguest


                                    <div class="dropdown-columns">

                                        {{-- LEFT COLUMN (NEW OPTIONS) --}}
                                        <ul class="account-menu">
                                            <li>
                                                <a
                                                    href="{{ auth()->check() && auth()->user()->user_type == 'vendor'
                                                        ? url('/admin/profile')
                                                        : route('customers.dashboard') }}">
                                                    <span class="me-2">
                                                        <i class="fa-solid fa-user"></i>
                                                    </span>
                                                    Your Account
                                                </a>
                                            </li>

                                            @if (!auth()->check() || auth()->user()->user_type != 'vendor')
                                                <li>
                                                    <a href="{{ route('customers.orderHistory') }}">
                                                        <span class="me-2"><i class="fa-solid fa-tags"></i></span>
                                                        Your Orders
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="{{ route('customers.wishlist') }}">
                                                        <span class="me-2"><i class="fa-solid fa-heart"></i></span>
                                                        Your Wishlist
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="{{ route('seller.page') }}">
                                                        <span class="me-2"><i class="fa-solid fa-store"></i></span>
                                                        Your Seller Account
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>


                                        {{-- RIGHT COLUMN (YOUR ORIGINAL CODE - UNTOUCHED) --}}
                                        <ul class="account-menu">
                                            @auth
                                                @if (auth()->user()->user_type == 'customer')
                                                    <!--<li><a href="{{ route('customers.dashboard') }}"><span-->
                                                    <!--            class="me-2"><i-->
                                                    <!--                class="fa-solid fa-user"></i></span>{{ localize('My Account') }}</a>-->
                                                    <!--</li>-->
                                                    <!--<li><a href="{{ route('customers.orderHistory') }}"><span-->
                                                    <!--            class="me-2"><i-->
                                                    <!--                class="fa-solid fa-tags"></i></span>{{ localize('My Orders') }}</a>-->
                                                    <!--</li>-->
                                                    <!--<li><a href="{{ route('customers.wishlist') }}"><span-->
                                                    <!--            class="me-2"><i-->
                                                    <!--                class="fa-solid fa-heart"></i></span>{{ localize('My Wishlist') }}</a>-->
                                                    <!--</li>-->
                                                @else
                                                    <li><a href="{{ route('admin.dashboard') }}"><span class="me-2"><i
                                                                    class="fa-solid fa-bars"></i></span>{{ localize('Dashboard') }}</a>
                                                    </li>
                                                @endif

                                                <li><a href="{{ route('logout') }}"><span class="me-2"><i
                                                                class="fa-solid fa-arrow-right-from-bracket"></i></span>{{ localize('Sign Out') }}
                                                    </a></li>
                                            @endauth

                                            @guest
                                                <li><a href="{{ route('login') }}"><span class="me-2"><i
                                                                class="fa-solid fa-arrow-right-from-bracket"></i></span>{{ localize('Sign In') }}</a>
                                                </li>
                                            @endguest
                                        </ul>

                                    </div>

                                </div>
                            </div>
                        </div>
                        @if (!auth()->check() || auth()->user()->user_type != 'vendor')
                            <div class="gshop-header-cart position-relative">
                                @php
                                    $carts = [];
                                    if (Auth::check()) {
                                        $carts = App\Models\Cart::where('user_id', Auth::user()->id)
                                            ->where('location_id', session('stock_location_id'))
                                            ->get();
                                    } else {
                                        if (isset($_COOKIE['guest_user_id'])) {
                                            $carts = App\Models\Cart::where(
                                                'guest_user_id',
                                                (int) $_COOKIE['guest_user_id'],
                                            )
                                                ->where('location_id', session('stock_location_id'))
                                                ->get();
                                        }
                                    }
                                @endphp


                                <button type="button" class="header-icon">
                                    <svg width="18" height="25" viewBox="0 0 22 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21.1704 23.9559L19.6264 7.01422C19.5843 6.55156 19.1908 6.19718 18.7194 6.19718H15.5355V4.78227C15.5355 2.14533 13.3583 0 10.6823 0C8.00628 0 5.82937 2.14533 5.82937 4.78227V6.19718H2.6433C2.17192 6.19718 1.77839 6.55156 1.73625 7.01422L0.186259 24.0225C0.163431 24.2735 0.248671 24.5223 0.421216 24.7082C0.593761 24.8941 0.837705 25 1.0933 25H20.2695C20.2702 25 20.2712 25 20.2719 25C20.775 25 21.1826 24.5982 21.1826 24.1027C21.1825 24.0528 21.1784 24.0036 21.1704 23.9559ZM7.65075 4.78227C7.65075 3.1349 9.01071 1.79465 10.6824 1.79465C12.3542 1.79465 13.7142 3.1349 13.7142 4.78227V6.19718H7.65075V4.78227ZM2.08948 23.2055L3.47591 7.99183H5.82937V9.59649C5.82937 10.0921 6.237 10.4938 6.74006 10.4938C7.24313 10.4938 7.65075 10.0921 7.65075 9.59649V7.99183H13.7142V9.59649C13.7142 10.0921 14.1219 10.4938 14.6249 10.4938C15.128 10.4938 15.5356 10.0921 15.5356 9.59649V7.99183H17.8869L19.2733 23.2055H2.08948Z"
                                            fill="#5D6374" />
                                    </svg>
                                    <span
                                        class="cart-counter badge bg-primary rounded-circle p-0 {{ count($carts) > 0 ? '' : 'd-none' }}">{{ count($carts) }}</span>
                                </button>
                                <div class="cart-box-wrapper">
                                    <div class="apt_cart_box theme-scrollbar">
                                        <ul class="at_scrollbar scrollbar cart-navbar-wrapper">
                                            <!--cart listing-->
                                            @include('frontend.default.pages.partials.carts.cart-navbar', [
                                                'carts' => $carts,
                                            ])
                                            <!--cart listing-->

                                        </ul>
                                        <div class="d-flex align-items-center justify-content-between mt-3">
                                            <h6 class="mb-0">{{ localize('Subtotal') }}:</h6>
                                            <span
                                                class="fw-semibold text-secondary sub-total-price">{{ formatPrice(getSubTotal($carts, false)) }}</span>
                                        </div>
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-6">
                                                <a href="{{ route('carts.index') }}"
                                                    class="btn btn-secondary btn-md mt-4 w-100"><span
                                                        class="me-2"><i
                                                            class="fa-solid fa-shopping-bag"></i></span>{{ localize('View Cart') }}</a>
                                            </div>
                                            <div class="col-6">
                                                <a href="{{ route('checkout.proceed') }}"
                                                    class="btn btn-primary btn-md mt-4 w-100"><span class="me-2"><i
                                                            class="fa-solid fa-credit-card"></i></span>{{ localize('Checkout') }}</a>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <div class="modal fade" id="locationModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content amazon-location-modal">

                        <div class="modal-header">
                            <h4>Choose your location</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            @auth

                                @php
                                    $addresses = \App\Models\UserAddress::where('user_id', Auth::id())->get();
                                @endphp

                                @foreach ($addresses as $address)
                                    <div class="amazon-address-card" onclick="selectAddress('{{ $address->pincode }}')">

                                        <strong>{{ $address->name }}</strong>

                                        <div>
                                            {{ $address->address }},
                                            {{ $address->district_name }}
                                            {{ $address->state_name }}
                                            {{ $address->pincode }}
                                        </div>

                                    </div>
                                @endforeach

                            @endauth

                            <div class="amazon-divider">
                                <span>or enter an Indian pincode</span>
                            </div>

                            <div class="d-flex gap-2">
                                <input type="text" id="pincode" class="form-control"
                                    placeholder="Enter Pincode">

                                <button class="btn btn-dark" onclick="saveLocation()">
                                    Apply
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
</header>



{{-- ================= NAVBAR ================= --}}
<div class="navnew header-sticky">
    <div class="container">

        <nav class="gshop-navmenu d-flex align-items-center">

            <!-- 🔥 LEFT : ALL -->
            <div class="nav-left position-relative">
                <a href="javascript:void(0)" style="color:white !important">
                    <i class="fa fa-bars"></i> All
                </a>

                <div class="categories-sidebar mega-menu">
                    <ul>
                        @foreach ($navbarCategories as $cat)
                            <li>
                                    <a href="{{ route('category.landing', [
                                        'slug' => $cat->slug,
                                        'category_code' => $cat->category_code,
                                    ]) }}">
                                        {{ $cat->collectLocalization('name') }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- 🔥 CENTER : MAIN NAVBAR -->
            <ul class="nav-row d-flex flex-grow-1 mb-0">
                @foreach ($navbarCategories as $navCat)
                    <li class="nav-item position-relative">
                        <a
                            href="{{ route('category.landing', [
                                'slug' => $navCat->slug,
                                'category_code' => $navCat->category_code,
                            ]) }}">
                            {{ $navCat->collectLocalization('name') }}
                        </a>

                        {{-- 🔥 FILTER MEGA COLUMNS FROM GLOBAL INJECTED DATA --}}
                        @php
                            $filteredColumns = collect($megaMenuColumns ?? [])->filter(function ($column) use (
                                $navCat,
                            ) {
                                // Safety check
                                if (!isset($column->categories) || $column->categories->isEmpty()) {
                                    return false;
                                }

                                // 🔥 MOST IMPORTANT: Hide variation columns from top orange navbar
                                // (Shop by size, Shop by color etc will NOT show in orange menu)
                                if ($column->type === 'variation') {
                                    return false;
                                }

                                // Only show columns directly mapped to THIS navbar category
                                return $column->categories->pluck('id')->contains($navCat->id);
                            });
                        @endphp

                        @if ($filteredColumns->count() > 0)
                            <div class="mega-menu-dropdown">
                                <div class="row">
                                    @foreach ($filteredColumns as $column)
                                        <div class="col-md-3">
                                            <h6 class="mega-title">{{ $column->title }}</h6>



                                            {{-- BRAND --}}
                                            @if ($column->type === 'brand' && $column->brand_id)
                                                @php
                                                    $brand = \App\Models\Brand::find($column->brand_id);
                                                @endphp

                                                @if ($brand)
                                                    <ul class="mega-links">
                                                        <li>
                                                            <a
                                                                href="{{ route('products.index', ['brand_id' => $brand->id]) }}">
                                                                {{ $brand->name }}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @endif
                                            @endif

                                            {{-- VARIATION --}}

                                            @if (
                                                $column->type === 'variation' &&
                                                    !empty($column->variation) &&
                                                    method_exists($column->variation, 'variationValues') &&
                                                    $column->variation->variationValues->count())
                                                <ul class="mega-links">
                                                    @foreach ($column->variation->variationValues as $val)
                                                        <li>
                                                            <a href="#">{{ $val->name }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </li>
                @endforeach
            </ul>

        </nav>

    </div>
</div>
<div class="searchbar-mobile d-block d-md-none">

    <form class="bazaron-search-form" action="{{ route('products.index') }}">

        <!-- Input -->
        <input type="text" name="search" placeholder="{{ localize('Search Bazaron.in') }}">

        <!-- Camera / Mic Optional -->
        <button type="submit" class="search-btn">
            <i class="fa fa-search"></i>
        </button>

    </form>

</div>

<style>
    .searchbar-mobile {
        background: #e76200;
        padding: 10px 12px;
    }

    .bazaron-search-form {
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 50px;
        overflow: hidden;
        height: 44px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        border: 2px solid transparent;
        transition: 0.3s ease;
    }

    .bazaron-search-form:focus-within {
        border-color: #ff9900;
    }

    /* LEFT ICON */
    .search-icon {
        width: 42px;
        min-width: 42px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        font-size: 15px;
        background: #fff;
    }

    /* INPUT */
    .bazaron-search-form input {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        width: 100%;
        height: 100%;
        padding: 0 10px;
        font-size: 14px;
        background: #fff;
        color: #111;
    }

    .bazaron-search-form input::placeholder {
        color: #777;
        font-size: 13px;
    }

    /* BUTTON */
    .search-btn {
        width: 52px;
        min-width: 52px;
        height: 100%;
        border: none;
        outline: none;
        background: #e76200 !important;
        color: #111;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s ease;
    }

    .search-btn:hover {
        background: #f3a847;
    }


    .become-seller-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        margin-right: 15px;
        border: 1px solid rgba(255, 255, 255, .25);
        border-radius: 8px;
        color: #fff !important;
        text-decoration: none;
        background: rgba(255, 255, 255, .05);
        transition: .3s;
        min-width: 120px;
    }

    .become-seller-btn i {
        font-size: 18px;
    }

    .become-seller-btn span {
        font-size: 13px;
        font-weight: 600;
        line-height: 1.15;
    }

    .become-seller-btn:hover {
        background: #ff9900;
        border-color: #ff9900;
        color: #fff !important;
    }

    .account-text {
        line-height: 1.1;
        text-align: left;
        white-space: nowrap;
    }

    .account-text span {
        display: block;
        font-size: 12px;
    }

    .account-text strong {
        display: block;
        font-size: 14px;
        font-weight: 700;
    }

    .gshop-header-user svg path,
    .gshop-header-cart svg path {
        fill: #ffffff !important;
        stroke: #ffffff !important;
    }

    @media (max-width:767px) {

        .mobile-seller-top {
            background: #e86301;
            color: #fff !important;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            margin-left: 44%;
            white-space: nowrap;
        }

    }
</style>
<script>
    function toggleLang() {
        let dropdown = document.getElementById("langDropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    // outside click close
    document.addEventListener("click", function(e) {
        const wrapper = document.querySelector(".lang-wrapper");
        const dropdown = document.getElementById("langDropdown");

        if (!wrapper.contains(e.target)) {
            dropdown.style.display = "none";
        }
    });

    window.addEventListener("scroll", function() {
        let header = document.querySelector(".gheader");

        if (window.scrollY > 100) {
            header.classList.add("sticky-active");
        } else {
            header.classList.remove("sticky-active");
        }
    });

    function saveLocation() {
        let pincode = document.getElementById('pincode').value;

        fetch("{{ route('save.location') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    pincode: pincode
                })
            })
            .then(res => res.json())
            .then(data => {

                if (data.status) {
                    location.reload();
                } else {
                    alert('Invalid Pincode');
                }

            });
    }
</script>
