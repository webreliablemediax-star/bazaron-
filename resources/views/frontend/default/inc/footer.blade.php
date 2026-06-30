<!--<div class="footer-curve position-relative overflow-hidden">-->
<!--    <span class="position-absolute section-curve-wrapper top-0 h-100"-->
<!--        data-background="{{ staticAsset('frontend/default/assets/img/shapes/section-curve.png') }}"></span>-->
<!--</div>-->
@if(Route::currentRouteName() == 'home')
<div class="footer-directory-wrapper">

    <div class="directory-header d-flex justify-content-between align-items-center">
       <h4>{{ getSetting('footer_heading') }}</h4>

        <button type="button" id="toggleFooterDirectory">
            +
        </button>
    </div>

    <!-- CONTENT NICHE AAYEGA -->
    <div id="footerDirectory" style="display:none;">
        <div class="footer-brand-directory">
            {!! getSetting('footer_brand_directory') !!}
        </div>
    </div>

</div>
@endif


<footer class="gshop-footer position-relative pt-8 z-1 overflow-hidden" style="background-color:rgb(0, 39, 86);">

    <div class="container">

        <div class="row g-5">
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                <div class="footer-widget">
                    {{-- 🔹 Heading full width & center --}}
                    <h5 class="text-white mb-2">
                        {{ localize('Category') }}
                    </h5>
                    @php
                        $footer_categories =
                            getSetting('footer_categories') != null ? json_decode(getSetting('footer_categories')) : [];

                        $categories = \App\Models\Category::whereIn('id', $footer_categories)->get();
                        $count = $categories->count();
                    @endphp

                    @if ($count <= 10)
                        {{-- ✅ 10 ya kam → ek hi column --}}
                        <ul class="footer-nav">
                            @foreach ($categories as $category)
                                <li>
                                    <a href="{{ route('products.index', ['category_id' => $category->id]) }}">
                                        {{ $category->collectLocalization('name') }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        {{-- ✅ 10 se zyada → 2 column --}}
                        @php
                            $half = ceil($count / 2);
                            $firstHalf = $categories->take($half);
                            $secondHalf = $categories->skip($half);
                        @endphp

                        <div class="row">
                            <div class="col-6">
                                <ul class="footer-nav">
                                    @foreach ($firstHalf as $category)
                                        <li>
                                            <a href="{{ route('products.index', ['category_id' => $category->id]) }}">
                                                {{ $category->collectLocalization('name') }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="col-6">
                                <ul class="footer-nav">
                                    @foreach ($secondHalf as $category)
                                        <li>
                                            <a href="{{ route('products.index', ['category_id' => $category->id]) }}">
                                                {{ $category->collectLocalization('name') }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                <div class="footer-widget">
                    <h5 class="text-white mb-2">{{ localize('Quick Links') }}</h5>
                    @php
                        $quick_links = getSetting('quick_links') != null ? json_decode(getSetting('quick_links')) : [];
                        $pages = \App\Models\Page::whereIn('id', $quick_links)->get();
                    @endphp
                    <ul class="footer-nav">
                        @foreach ($pages as $page)
                            <li><a
                                    href="{{ route('home.pages.show', $page->slug) }}">{{ $page->collectLocalization('title') }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                <div class="footer-widget">
                    <h5 class="text-white mb-2">{{ localize('Customer Pages') }}</h5>
                    <ul class="footer-nav">
                        <li><a href="{{ route('customers.dashboard') }}">{{ localize('Your Account') }}</a></li>
                        <li><a href="{{ route('customers.orderHistory') }}">{{ localize('Your Orders') }}</a></li>
                        <li><a href="{{ route('customers.wishlist') }}">{{ localize('Your Wishlist') }}</a></li>
                        <li><a href="{{ route('customers.address') }}">{{ localize('Address Book') }}</a></li>
                        <li><a href="{{ route('customers.profile') }}">{{ localize('Update Profile') }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                <div class="footer-widget">
                    <h5 class="text-white mb-2">{{ localize('Contact Info') }}</h5>
                    <ul class="footer-nav">
                        <li class="text-white pb-2 fs-xs">{{ getSetting('topbar_location') }}</li>
                        <li class="text-white pb-2 fs-xs">{{ getSetting('navbar_contact_number') }}</li>
                        <li class="text-white pb-2 fs-xs">{{ getSetting('topbar_email') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="footer-copyright pb-3" style="background-color: #131a22">
        <!--     -->
        <div class="container">
            <div class="row align-items-center g-3 text-center">

                {{-- 🔥 LOGO FIRST (upar) --}}
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <img src="{{ uploadedAsset(getSetting('footer_logo')) }}" alt="footer logo"
                        style="max-height: 40px; width: auto;">
                </div>

                {{-- 🔥 COPYRIGHT TEXT NICHE --}}
                <div class="col-12">
                    <div class="copyright-text text-light" style="margin-left:0;font-size:12px;color:white">
                        {!! str_replace(
                            '<a',
                            '<a style="color:#fff !important;text-decoration:none;" onmouseover="this.style.color=\'#fff\'" onmouseout="this.style.color=\'#fff\'"',
                            getSetting('copyright_text'),
                        ) !!}
                    </div>
                </div>

            </div>
            <!-- <div class="col-lg-4">
                    <div class="footer-payments-info d-flex align-items-center justify-content-lg-end gap-2">
                        <div
                            class="rounded-1 d-inline-flex align-items-center justify-content-center p-2 flex-shrink-0">
                            <img src="{{ uploadedAsset(getSetting('accepted_payment_banner')) }}" alt="accepted_payment"
                                class="img-fluid">
                        </div>
                    </div>
                </div> -->
        </div>
    </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const btn = document.getElementById("toggleFooterDirectory");
            const box = document.getElementById("footerDirectory");

            btn.addEventListener("click", function() {

                if (box.style.display == "none" || box.style.display == "") {
                    box.style.display = "block";
                    btn.innerHTML = "-";
                } else {
                    box.style.display = "none";
                    btn.innerHTML = "+";
                }

            });

        });
    </script>
</footer>
