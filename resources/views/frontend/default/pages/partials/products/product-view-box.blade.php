    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    @php
        $stripCategories = $stripCategories ?? collect();
    @endphp
    {{-- 🔥 DYNAMIC SUBCATEGORY STRIP (CATEGORY BASED) --}}

    @if (isset($stripCategories) && $stripCategories->count())
        <div class="subcategory-strip bg-white border-bottom py-3">
            <ul class="subcat-list d-flex align-items-center gap-4 mb-0 justify-content-center">
                @foreach ($stripCategories as $subCat)
                    <li class="subcat-item position-relative">
<a href="{{ route('category.landing', [
    'slug' => $subCat->slug,
    'category_code' => $subCat->category_code,
]) }}" class="fw-medium text-dark subcat-link">                            {{ $subCat->collectLocalization('name') }}
                        </a>
                        {{-- FLYOUT --}}
                        @if ($subCat->childrenCategories && $subCat->childrenCategories->count())
                            <div class="nav-fullWidthSubnavFlyout">
                                <div class="mega-inner d-flex flex-wrap">
                                    @foreach ($subCat->childrenCategories as $child)
    <div class="col-md-3">
        <h6 class="fw-bold mb-2">
            <a href="{{ route('category.landing', [
                'slug' => $child->slug,
                'category_code' => $child->category_code,
            ]) }}"
                class="text-dark text-decoration-none">
                {{ $child->collectLocalization('name') }}
            </a>
        </h6>
                                            @foreach ($child->childrenCategories as $subChild)
                                            <a href="{{ route('category.landing', [
                                                'slug' => $subChild->slug,
                                                'category_code' => $subChild->category_code,
                                            ]) }}"                                                    class="d-block mb-1 small text-dark">
                                                    {{ $subChild->collectLocalization('name') }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <hr>
    {{-- 🔥 bazaron STYLE DYNAMIC BREADCRUMB --}}
    @php
        $breadcrumbCategories = collect();

        $category = $product->categories()->with('parent')->first();

        while ($category) {
            $breadcrumbCategories->prepend($category);
            $category = $category->parent;
        }

        // product short name
        $shortProductName = \Illuminate\Support\Str::limit($product->collectLocalization('name'), 30, '...');
    @endphp
    <div class="bazaron-breadcrumb d-none d-md-block">

        @foreach ($breadcrumbCategories as $cat)
            <a
            href="{{ route('category.landing', [
                'slug' => $cat->slug,
                'category_code' => $cat->category_code,
            ]) }}">
            {{ $cat->collectLocalization('name') }}
        </a>

            @if (!$loop->last)
                <span>›</span>
            @endif
        @endforeach



    </div>
    <div class="gstore-product-quick-view py-0 px-4">
        <div class="row g-0 product-main-row" style="margin-bottom: -80px">
            <!-- ================= LEFT : IMAGE ================= -->
            <!-- LEFT : FULL STICKY COLUMN -->
            <div class="col-lg-5 left-sticky-col">
                <h1 class="mb-2 d-block d-md-none">
                    {{ $product->collectLocalization('name') }}
                </h1>
                @include('frontend.default.pages.partials.products.sliders', compact('product'))
            </div>
            <!-- ================= MIDDLE : PRODUCT DETAILS ================= -->
            <div class="col-lg-5">
                <h1 class="mb-2 d-none d-md-block">
                    {{ $product->collectLocalization('name') }}
                </h1>
                <hr>
                <!-- @php
                    // ⭐ Backend Eloquent Reviews (NOT raw DB)
                    $reviews = $product->reviews ?? collect();

                    $totalReviews = $reviews->count();

                    $averageRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;

                    // ⭐ bazaron style rating distribution
                    $starCounts = [
                        5 => $reviews->where('rating', 5)->count(),
                        4 => $reviews->where('rating', 4)->count(),
                        3 => $reviews->where('rating', 3)->count(),
                        2 => $reviews->where('rating', 2)->count(),
                        1 => $reviews->where('rating', 1)->count(),
                    ];
                @endphp -->
                <!-- <div class="mb-2">
                <span class="limited-deal-badge">Limited time deal</span>
                </div> -->
                <div class="bought-this-month mb-2">
                    <!-- <span class="bought-count">
                {{ rand(1, 9) }}K+ bought this month
                </span> -->
                </div>
                <div class="pricing all-pricing mb-3 d-flex align-items-center justify-content-between">

                    <!-- PRICE -->
                    <div>
                        @include('frontend.default.pages.partials.products.pricing', compact('product'))
                    </div>

                    <!-- SHARE BUTTON -->
                    <div class="share-wrapper position-relative">
                        <button class="share-btn" onclick="toggleShare()">
                            <i class="fas fa-share-alt"></i>
                        </button>

                        <div class="share-dropdown" id="shareDropdown">
                            <!-- ❌ CLOSE BUTTON -->
                            <span class="close-share" onclick="closeShare()">✕</span>
                            <a href="#" onclick="shareEmail()">📧 Email</a>
                            <a href="https://pinterest.com/pin/create/button/?url={{ url()->current() }}" target="_blank">
                                <i class="fab fa-pinterest"></i> Pinterest
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank">
                                <i class="fab fa-facebook"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}" target="_blank">
                                <i class="fab fa-twitter"></i> X
                            </a>
                            <a href="#" onclick="copyLink()">🔗 Copy Link</a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ url()->current() }}"
                                target="_blank">
                                <i class="fab fa-linkedin"></i> LinkedIn
                            </a>
                        </div>
                    </div>

                </div>
                <div class="product-variations-wrapper mb-3">
                    @include('frontend.default.pages.partials.products.variations', compact('product'))
                </div>

                @php
                    $variationData = $product->variations
                        ->map(function ($v) {
                            return [
                                'key' => $v->variation_key,
                                'stock' => optional($v->product_variation_stock)->stock_qty ?? 0,
                                'price' => $v->price,
                                'id' => $v->id,
                                // 🔥 ADD THIS
                                'image' => $v->image,
                            ];
                        })
                        ->values();
                @endphp

                <script>
                    let variations = @json($variationData);
                </script>
                <p class="text-muted mb-4">
                    {{ $product->collectLocalization('short_description') }}
                </p>
                <!-- PRODUCT SPECS -->
                <!-- PRODUCT SPECS (DYNAMIC FROM BACKEND) -->
                @php
                    // Priority: Brand Specs → Product Info (fallback)
                    $brandSpecs = is_array($product->brand_specs ?? null)
                        ? $product->brand_specs
                        : json_decode($product->brand_specs ?? '[]', true);
                    $productInfos = is_array($product->product_info ?? null)
                        ? $product->product_info
                        : json_decode($product->product_info ?? '[]', true);
                @endphp
                <div class="product-specs mb-3">
                    {{-- 1️⃣ BRAND SPECS (if added from admin) --}}
                    @if (!empty($brandSpecs) && count($brandSpecs) > 0)
                        @foreach ($brandSpecs as $spec)
                            @if (!empty($spec['title']) && !empty($spec['value']))
                                <div class="spec-row">
                                    <span class="label">{{ $spec['title'] }}</span>
                                    <span class="value">{{ $spec['value'] }}</span>
                                </div>
                            @endif
                        @endforeach
                        {{-- 2️⃣ Fallback to Product Info (if brand specs empty) --}}
                    @elseif(!empty($productInfos) && count($productInfos) > 0)
                        @foreach ($productInfos as $info)
                            @if (!empty($info['title']) && !empty($info['value']))
                                <div class="spec-row">
                                    <span class="label">{{ $info['title'] }}</span>
                                    <span class="value">{{ $info['value'] }}</span>
                                </div>
                            @endif
                        @endforeach
                        {{-- 3️⃣ Final fallback --}}
                    @else
                        <div class="spec-row">
                            <span class="label">Specifications</span>
                            <!-- <span class="value text-muted">No specs added from admin</span> -->
                        </div>
                    @endif
                </div>
                <!-- bazaron TRUST ICON SLIDER -->
                {{-- 🔥 Dynamic Icon Slider From Backend --}}
                <!-- ⭐ Dynamic Icon Slider (From Admin Panel) -->
                @php
                    $iconSliders = $product->icon_slider ?? [];
                @endphp
                @if (!empty($iconSliders))
                    <div class="bazaron-icon-slider mt-4">
                        @foreach ($iconSliders as $item)
                            <div class="bazaron-icon-item">
                                <div class="bazaron-icon-circle">
                                    <i class="{{ $item['icon'] ?? 'las la-truck' }}"></i>
                                </div>
                                <span class="bazaron-icon-title">
                                    {{ $item['title'] ?? '' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
                <h5 class="about-title">About this item</h5>
                @php
                    // ⭐ FORCE FIX: Always decode DB JSON correctly
                    $aboutPoints = [];
                    if (!empty($product->about_items)) {
                        // Case 1: Already array (if model casted)
                        if (is_array($product->about_items)) {
                            $aboutPoints = $product->about_items;
                        }
                        // Case 2: JSON stored in DB (most common)
                        elseif (is_string($product->about_items)) {
                            $decoded = json_decode($product->about_items, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $aboutPoints = $decoded;
                            } else {
                                // Case 3: Single text saved instead of array
                                $aboutPoints = [$product->about_items];
                            }
                        }
                    }
                    // Final clean
                    $aboutPoints = array_filter($aboutPoints, function ($item) {
                        return !empty(trim($item));
                    });
                @endphp
                @if (!empty($aboutPoints))
                    <ul class="about-list">
                        @foreach ($aboutPoints as $point)
                            <li>{{ $point }}</li>
                        @endforeach
                    </ul>
                @else
                    <ul class="about-list">
                        <li>No product highlights available.</li>
                    </ul>
                @endif
            </div>
            <!-- ================= RIGHT : bazaron BUY BOX ================= -->
            <div class="col-lg-2 buy-box-column">
                @php
                    $activeVariation = $product->variations->where('is_active', 1)->first();
                @endphp
                <div class="bazaron-buy-box">
                    @php
                        $stock =
                            $activeVariation && $activeVariation->product_variation_stock
                                ? $activeVariation->product_variation_stock->stock_qty
                                : 0;
                    @endphp
                    <p class="fw-semibold text-success">
                        {{ $stock > 0 ? 'In Stock' : 'Out of Stock' }}
                    </p>
                     <p class="small">
    <strong>FREE Delivery</strong>
    in {{ getSetting('free_delivery_text') ?? '3-7 days' }}
</p>
           
                @php
                    $vendorName = $product->vendorProfile->business_name ?? 'Seller';
                    // $isSelfShipping = $product->vendorProfile->has_own_logistics;
                    $isSelfShipping = optional($product->vendorProfile)->has_own_logistics ?? 0;
                    // dd($isSelfShipping);
                @endphp

                <div class="bazaron-meta align-items-right">

                    <div class="meta-row">
                        <span class="meta-label">Ships from</span>
                        <span class="meta-value">
                            @if ($isSelfShipping == 1)
                                <span class="text-success">{{ $vendorName }}</span>
                            @else
                                <span class="text-secondary">Bazaron Shipping</span>
                            @endif
                            {{-- {{ $isSelfShipping ? $vendorName : 'Bazaron' }} --}}
                        </span>
                    </div>

                    <div class="meta-row">
                        <span class="meta-label">Sold by</span>
                        <span class="meta-value seller-link">
                            {{ $vendorName }}
                        </span>
                    </div>

                </div>
                    
                    
                    <form action="{{ route('carts.store') }}" method="POST" class="add-to-cart-form" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" id="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="product_variation_id" value="{{ $activeVariation->id ?? '' }}">
                        <div class="mb-3" style="margin-left: 18px;">
                            <label class="fw-semibold mb-1 d-block">Quantity</label>
                            <div class="product-qty d-flex align-items-center">
                                <button type="button" class="decrease"
                                    style="background-color:white !important;color:black !important;border:1px solid black !important">-</button>
                                <input type="number" value="1" name="quantity" min="1">
                                <button type="button" class="increase"
                                    style="background-color:white !important;color:black !important;border:1px solid black !important">+</button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 mb-2">
                            Add to Cart
                        </button>
                       <button type="button" id="buyNowBtn" class="btn btn-orange w-100 mb-2"style="margin-top:6px;">
                            Buy Now
                        </button>
                       <button type="button" id="wishlistBtn" data-product="{{ $product->id }}" class="btn w-100"
                            style="border-radius:27px !important;background-color:white !important;color:black !important;margin-top:7px;border:2px solid black !important;padding:16px 24px !important">
                            Add to Wish List
                        </button>
                        <small style="margin-left: 5px;
}">Check Delivery Availability  </small>
                        <!-- </form>
                    <hr>
                    
                    <!-- PINCODE -->
                        <!-- <form action="{{ route('check.delivery') }}" method="POST"> -->
                        @csrf
                        <input type="hidden" name="vendor_id" value="{{ $product->vendor_id }}">
                        <div class="mb-2">
                        <div class="delivery-card">

        {{-- <h5 class="delivery-title">
            Delivery Details
        </h5> --}}

        <div class="delivery-box">

        <div class="delivery-left">

            <i class="fas fa-map-marker-alt location-icon"></i>

            <input
                type="text"
                id="deliveryPincode"
                name="pincode"
                 maxlength="6"
       minlength="6"
       pattern="[0-9]{6}"
       inputmode="numeric"
                placeholder="Pincode">

        </div>
        

        <button
            type="button"
            id="checkDeliveryBtn"
            class="delivery-btn">
            APPLY
        </button>

    </div>


        

    </div>
    <p id="deliveryMessage" style="margin-top:8px;font-size:13px;"></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* ===== BAZARON ICON SLIDER (THEME STYLE) ===== */
        .bazaron-icon-slider {
            display: flex;
            align-items: flex-start;
            gap: 24px;
            margin-top: 12px;
            overflow-x: auto;
            /* smooth horizontal like ecom */
            padding-bottom: 5px;
        }

        /* Hide scrollbar (clean look) */
        .bazaron-icon-slider::-webkit-scrollbar {
            display: none;
        }

        /* EACH ICON BLOCK (icon upar, title niche) */
        .bazaron-icon-item {
            display: flex;
            flex-direction: column;
            /* IMPORTANT: upar niche layout */
            align-items: center;
            text-align: center;
            min-width: 90px;
            transition: 0.3s ease;
        }

        /* CIRCLE ICON BOX (bazaron clean style) */
        .bazaron-icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* ICON */
        .bazaron-icon-circle i {
            font-size: 22px;
            color: #f97316;
            /* bazaron orange */
        }

        /* TITLE BELOW ICON */
        .bazaron-icon-title {
            margin-top: 6px;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            line-height: 1.3;
        }

        /* HOVER (premium feel) */
        .bazaron-icon-item:hover .bazaron-icon-circle {
            border-color: #f97316;
            transform: translateY(-2px);
        }

        .bazaron-icon-slider .icon-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 12px 10px;
            border: 1px solid #eee;
            border-radius: 10px;
            background: #fff;
            transition: 0.3s;
        }

        .bazaron-icon-slider .icon-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        /* ⭐ Stars */
        .bazaron-stars i {
            font-size: 16px;
            margin-right: 2px;
        }

        .star-filled {
            color: #ffa41ccf !important;
            /* bazaron orange */
        }

        .star-empty {
            color: #ddddddff !important;
        }

        /* Popup */
        .bazaron-rating-popup {
            display: none;
            position: absolute;
            top: 28px;
            left: 0;
            width: 280px;
            background: #ffffffff;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            padding: 15px;
            z-index: 999;
        }

        /* Show on hover */
        .bazaron-rating-wrapper:hover .bazaron-rating-popup {
            display: block;
        }

        /* Rating bars */
        .rating-row {
            display: flex;
            align-items: center;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .rating-row span:first-child {
            width: 60px;
        }

        .rating-bar {
            flex: 1;
            height: 8px;
            background: hsla(0, 0%, 93%, 1.00);
            margin: 0 8px;
            border-radius: 3px;
        }

        .rating-fill {
            height: 8px;
            background: #FFA41C;
            border-radius: 3px;
        }

        .subcategory-strip {
            background: #fff;
            border-bottom: 1px solid #eee;
        }

        .subcat-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .subcat-link {
            font-size: 12px;
            font-weight: 300;
            color: #111;
            text-decoration: none;
        }

        .subcat-link:hover {
            color: #f97316;
        }

        .nav-fullWidthSubnavFlyout {
            display: none;
            position: absolute;
            top: 75%;
            left: 0;
            background: #fff;
            width: 100vw;
            z-index: 9999;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .subcat-item:hover .nav-fullWidthSubnavFlyout {
            display: block;
        }

        .bazaron-breadcrumb {
            font-size: 13px;
            color: #555;
            padding: 10px 0;
            padding-left: 25px;   /* right side move */
        }

        .bazaron-breadcrumb a {
            color: #333;
            text-decoration: none;
        }

        .bazaron-breadcrumb a:hover {
            color: #f97316;
        }

        .breadcrumb-product {
            color: #333;
        }

        /* Buy Now - Green */
        #buyNowBtn {
            background-color: green !important;
            color: #fff !important;
            border: none !important;
        }

        .bazaron-meta p {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .bazaron-meta {
            margin-top: 10px;
        }

        .meta-row {
            display: flex;
            align-items: center;
            margin-bottom: 6px;
        }

        .meta-label {
            width: 110px;

            font-size: 13px;
            color: #565959;

        }

        .meta-value {
            font-size: 13px;
            color: #0F1111;

            font-weight: 500;
        }

        /* seller link style */
        .seller-link {
            color: #007185;

            cursor: pointer;
        }

        .seller-link:hover {
            color: #C7511F;
            text-decoration: underline;
        }



        .share-wrapper {
            position: relative;
        }

        .share-btn {
            border: 1px solid #ddd;
            background: #fff;
            padding: 6px 10px;
            border-radius: 50%;
            cursor: pointer;
        }

        .share-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 40px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 180px;
            z-index: 999;
        }
    .buy-box-column{
        display:flex;
        justify-content:flex-end;
    }

    .bazaron-buy-box{
        width:300px;
        max-width:320px;
        border:1px solid #ddd;
        border-radius:15px;
        padding:20px;
        background:#fff;
    }
    .delivery-card{
        background:linear-gradient(180deg,#f57149,#f8f1ce);
        border-radius:10px;
        padding:5px;
    }

    .delivery-box{
        height:40px;
        border-radius:10px;
        overflow:hidden;
        background:#fff;
        border:2px solid #d8d8d8;
        display:flex;
        align-items:center;
    }

    .delivery-left{
        flex:1;
        display:flex;
        align-items:center;
        padding:0 14px;
    }

    .location-icon{
        color:#ef4444;
        font-size:11px;
        margin-right:2px;
    }

    .delivery-left input{
        border:none !important;
        outline:none !important;
        background:transparent;
        width:100%;
        font-size:15px;
        color:#555;
    }

    .delivery-left input::placeholder{
        color:#666;
    }

    .delivery-btn{
        min-width:50px;
        width:auto;
        padding:0 2px;
        height:100%;
        border:none;
        background:#22242d;
        color:#fff;
        font-size:14px;
        font-weight:700;
    }



    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // ===== SELECT ELEMENTS =====
            const qtyInput = document.querySelector('input[name="quantity"]');
            const increaseBtn = document.querySelector('.increase');
            const decreaseBtn = document.querySelector('.decrease');
            const cartForm = document.getElementById('addToCartForm');

            const buyNowBtn = document.getElementById('buyNowBtn');


            // ===== QUANTITY INCREASE =====
            if (increaseBtn && qtyInput) {
                increaseBtn.addEventListener('click', function() {
                    let current = parseInt(qtyInput.value) || 1;
                    qtyInput.value = current + 1;
                });
            }

            // ===== QUANTITY DECREASE =====
            if (decreaseBtn && qtyInput) {
                decreaseBtn.addEventListener('click', function() {
                    let current = parseInt(qtyInput.value) || 1;
                    if (current > 1) {
                        qtyInput.value = current - 1;
                    }
                });
            }
            document.querySelectorAll(".add-to-cart-form").forEach(function(cartForm) {

                cartForm.addEventListener("submit", function(e) {
            @php
    $vendorProfile = \App\Models\VendorProfile::where(
        'user_id',
        $product->vendor_id
    )->first();
    @endphp

    let selfShipping = {{ ($vendorProfile && $vendorProfile->has_own_logistics) ? 1 : 0 }};

    console.log("SELF SHIPPING =", selfShipping);

    if(selfShipping == 1 && !deliveryVerified){

        e.preventDefault();

        alert(
            "Please check delivery availability first"
        );

        return false;
    }

                    e.preventDefault();

                    let formData = new FormData(cartForm);

                    fetch(cartForm.action, {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {

                            console.log("Cart Response:", data);

                            // 🔴 ERROR ALERT (SELLER BLOCK)
                            // 🔴 ERROR ALERT (SELLER BLOCK)
                            if (!data.success) {

                                let alertBox = document.createElement('div');
                                alertBox.innerText = data.message;

                                alertBox.style.position = 'fixed';
                                alertBox.style.top = '20px';
                                alertBox.style.right = '20px';
                                alertBox.style.background = '#ff4d4f';
                                alertBox.style.color = '#fff';
                                alertBox.style.padding = '12px 20px';
                                alertBox.style.borderRadius = '8px';
                                alertBox.style.zIndex = '999999';
                                alertBox.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
                                alertBox.style.fontSize = '14px';

                                document.body.appendChild(alertBox);

                                setTimeout(() => {
                                    alertBox.remove();
                                }, 4000);

                                return;
                            }


                            // 🟢 SUCCESS ALERT (NORMAL USER)
                            if (data.success) {

                                let alertBox = document.createElement('div');
                                alertBox.innerText = data.message || "Product added to cart";

                                alertBox.style.position = 'fixed';
                                alertBox.style.top = '120px';
                                alertBox.style.right = '20px';
                                alertBox.style.background = '#28a745'; // green
                                alertBox.style.color = '#fff';
                                alertBox.style.padding = '12px 20px';
                                alertBox.style.borderRadius = '8px';
                                alertBox.style.zIndex = '9999';
                                alertBox.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
                                alertBox.style.fontSize = '14px';
                                alertBox.style.opacity = '1';
                                alertBox.style.transition = 'opacity 0.5s';

                                document.body.appendChild(alertBox);

                                setTimeout(() => {
                                    alertBox.style.opacity = '0';
                                    setTimeout(() => alertBox.remove(), 500);
                                }, 4000);


                                // 👉 existing cart update code (same rehne de)
                                let cartListing = document.querySelector(".cart-listing");
                                if (cartListing) {
                                    cartListing.innerHTML = data.carts;
                                }

                                let navCart = document.querySelector(".cart-navbar-wrapper");
                                if (navCart) {
                                    navCart.innerHTML = data.navCarts;
                                }

                                let counter = document.querySelector(".cart-counter");
                                if (counter) {
                                    counter.innerText = data.cartCount;
                                }
                            }

                        })
                        .catch(error => {
                            console.log("Add to cart error:", error);
                        });

                });

            });


            // ===== WISHLIST BUTTON =====
            const wishlistBtn = document.getElementById("wishlistBtn");

            if (wishlistBtn) {

                wishlistBtn.addEventListener("click", function() {

                    let productId = this.dataset.product;

                    fetch("{{ route('customers.wishlist.store') }}", {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                product_id: productId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert(data.message);
                        })
                        .catch(err => {
                            console.log(err);
                        });

                });

            }









            // ===== bazaron COLOR VARIANT SELECT =====
            document.querySelectorAll('.bazaron-variant-card input').forEach(input => {
                input.addEventListener('change', function() {

                    // remove active from all cards
                    document.querySelectorAll('.bazaron-variant-card')
                        .forEach(card => card.classList.remove('active'));

                    // add active to selected
                    this.closest('.bazaron-variant-card').classList.add('active');

                    // update "Color: Name"
                    const name = this.dataset.name;
                    const label = document.getElementById('selectedColorName');
                    if (label) label.textContent = name;
                });
            });

        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const priceBox = document.getElementById('product-price');
            const mrp = {{ $product->max_selling_price }};
            const discountBox = document.getElementById('discount-percentage');
            //  function getFilteredVariations(){
            //      return variations.filter(v => v.stock > 0);
            //  }

            function updateVariationUI() {

                let selected = [];

                document.querySelectorAll('.product-variations-wrapper input[type=radio]:checked')
                    .forEach(radio => selected.push(radio.value));



                let found = variations.find(v => {

                    let keys = v.key.split('/').filter(Boolean);

                    return keys.length === selected.length &&
                        keys.every(k => selected.includes(k.split(':')[1])) &&
                        v.stock > 0;
                });
if (found) {

                let sellingPrice = parseFloat(found.price);

                priceBox.innerText = "₹" + sellingPrice.toFixed(2);

                let discount = Math.round(
                    ((mrp - sellingPrice) / mrp) * 100
                );

                if (discountBox) {
                    discountBox.innerText = "-" + discount + "%";
                }

                let input = document.querySelector('input[name="product_variation_id"]');
                if (input) {
                    input.value = found.id || '';
                }
            }
                // if (found) {
                //     priceBox.innerText = "₹" + parseFloat(found.price).toFixed(2);

                //     let input = document.querySelector('input[name="product_variation_id"]');
                //     if (input) {
                //         input.value = found.id || '';

                //     }

                // }
            }

            function filterOptions() {
                let selectedMap = {};

                document.querySelectorAll('.product-variations-wrapper input[type=radio]:checked')
                    .forEach(r => {
                        selectedMap[r.dataset.variationId] = r.value;
                    });

                let selected = [];

                document.querySelectorAll('.product-variations-wrapper input[type=radio]:checked')
                    .forEach(radio => selected.push(radio.value));



                document.querySelectorAll('.variation-option').forEach(input => {

                    let varId = input.dataset.variationId;
                    let valueId = input.value;
                    let label = input.closest('label');

                    let isValid = false;

                    for (let v of variations) {

                        if (v.stock <= 0) continue;

                        let map = {};
                        v.key.split('/').forEach(k => {
                            if (k) {
                                let [vid, val] = k.split(':');
                                map[vid] = val;
                            }
                        });

                        if (map[varId] != valueId) continue;

                        let match = true;

                        for (let selectedVar in selectedMap) {

                            if (selectedVar == varId) continue;

                            if (map[selectedVar] != selectedMap[selectedVar]) {
                                match = false;
                                break;
                            }
                        }

                        if (match) {
                            isValid = true;
                            break;
                        }
                    } // 🔥 IMPORTANT: agar koi bhi variation exist hi nahi karta
                    if (!isValid) {
                        input.checked = false;
                    }



                    // ✅ 3. stock check
                    // return v.stock > 0;
                });




                // 🔥 AUTO SELECT
                document.querySelectorAll('.product-variations-wrapper').forEach(wrapper => {

                    let radios = wrapper.querySelectorAll('input[type=radio]');
                    let anyChecked = Array.from(radios).some(r => r.checked);

                    if (!anyChecked) {
                        let firstValid = Array.from(radios).find(r => !r.disabled);
                        if (firstValid) {
                            firstValid.checked = true;
                        }
                    }
                });

                updateVariationUI();
            }

            // 🔥 EVENTS
            document.querySelectorAll('.product-variations-wrapper input[type=radio]').forEach(el => {
                el.addEventListener('change', function() {
                    filterOptions();
                    updateVariationUI();
                });
            });

            // 🔥 INIT
            filterOptions();
            updateVariationUI();

        });



        function toggleShare() {
            let dropdown = document.getElementById("shareDropdown");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        function shareFacebook() {
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${location.href}`);
        }

        function shareTwitter() {
            window.open(`https://twitter.com/intent/tweet?url=${location.href}`);
        }

        function shareEmail() {
            window.location.href = `mailto:?subject=Check this&body=${location.href}`;
        }

        function sharePinterest() {
            window.open(`https://pinterest.com/pin/create/button/?url=${location.href}`);
        }

        function copyLink() {
            navigator.clipboard.writeText(location.href);
            alert("Link copied!");
        }

        function closeShare() {
            document.getElementById("shareDropdown").style.display = "none";
        }

        function shareLinkedIn() {
            window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${location.href}`);
        }


        document.addEventListener("click", function(e) {

            if (e.target.closest("#buyNowBtn")) {

                let userType = @json(auth()->check() ? auth()->user()->user_type : 'guest');

                console.log("UserType:", userType); // debug

                // ❌ BLOCK ALL NON-CUSTOMERS
                if (userType !== 'customer') {

                    let alertBox = document.createElement('div');
                    alertBox.innerText = "Please login with a customer account to buy products";

                    alertBox.style.position = 'fixed';
                    alertBox.style.top = '20px';
                    alertBox.style.right = '20px';
                    alertBox.style.background = '#ff4d4f';
                    alertBox.style.color = '#fff';
                    alertBox.style.padding = '12px 20px';
                    alertBox.style.borderRadius = '8px';
                    alertBox.style.zIndex = '999999';

                    document.body.appendChild(alertBox);

                    setTimeout(() => alertBox.remove(), 4000);

                    e.preventDefault();
                    e.stopImmediatePropagation(); // 🔥 MOST IMPORTANT
                    return false;
                }

                // ✅ CUSTOMER → ADD TO CART TRIGGER
                document.getElementById("addToCartForm")
                    .dispatchEvent(new Event('submit', {
                        cancelable: true
                    }));


            }

        });

        document.addEventListener("click", function(e) {
            if (e.target.closest("#buyNowBtn")) {
                console.log("BUY NOW CLICKED");
            }
        });

    

    let deliveryVerified = false;

    document.addEventListener("DOMContentLoaded", function () {

        let btn = document.getElementById("checkDeliveryBtn");

        if(btn){

            btn.addEventListener("click", function(){

                let pincode = document.getElementById("deliveryPincode").value;
                let msg = document.getElementById("deliveryMessage");

    // purana message hata do
    msg.innerHTML = "";
                let productId = document.getElementById("product_id").value;

           if (pincode.length !== 6) {

    document.getElementById("deliveryMessage").innerHTML =
        '<span style="color:red;font-weight:600;">Please enter a valid 6-digit pincode</span>';

    return;
}

                fetch("{{ route('check.delivery') }}", {

                    method: "POST",

                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content
                    },

                    body: JSON.stringify({
                        product_id: productId,
                        pincode: pincode
                    })

                })
                .then(res => res.json())
            .then(data => {

        let msg = document.getElementById("deliveryMessage");

        if(data.success){

            deliveryVerified = true;

            msg.innerHTML =
                '<span style="color:green;font-weight:600;">Delivery Available</span>';

        }else{

            deliveryVerified = false;

            msg.innerHTML =
                '<span style="color:red;font-weight:600;">Delivery Not Available</span>';
        }

    });

            });

        }

    });




 document.getElementById("deliveryPincode").addEventListener("input", function () {

    // sirf numbers allow karo
    this.value = this.value.replace(/\D/g, '').slice(0, 6);

    deliveryVerified = false;

    document.getElementById("deliveryMessage").innerHTML =
        '<span style="color:#666;">Checking...</span>';

});


    </script>
