@php
    $firstVariation = $product->variations->first();

    $sellingPrice = $firstVariation?->price ?? 0;
    $mrp = $product->max_selling_price ?? 0;

    $discountPercentage = 0;

    if ($mrp > $sellingPrice && $mrp > 0) {
        $discountPercentage = round((($mrp - $sellingPrice) / $mrp) * 100);
    }
@endphp

<div class="product-price-box">

    @if ($discountPercentage > 0)
        <div class="d-flex align-items-center gap-2 mb-1">

            <span id="discount-percentage"
                class="text-danger fs-5 fw-normal">
                -{{ $discountPercentage }}%
            </span>

            <span id="product-price"
                class="fw-bold text-dark"
                style="font-size:16px;">
                ₹{{ number_format($sellingPrice, 0) }}
            </span>

        </div>

        <div class="text-muted" style="font-size:14px;">
            M.R.P.:
            <span id="mrp-price" class="text-decoration-line-through">
                ₹{{ number_format($mrp, 0) }}
            </span>
        </div>

    @else

        <span id="product-price"
            class="fw-bold text-dark"
            style="font-size:16px;">
            ₹{{ number_format($sellingPrice, 0) }}
        </span>

    @endif

</div>
<style>
    .price-share-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
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

    .share-btn:hover {
        background: #f5f5f5;
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
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        z-index: 999;
    }

    .share-dropdown a {
        display: block;
        padding: 10px;
        text-decoration: none;
        color: #333;
    }

    .share-dropdown a:hover {
        background: #f5f5f5;
    }

    .close-share {
        position: absolute;
        top: 6px;
        right: 8px;
        font-size: 14px;
        cursor: pointer;
        color: #555;
        background: #f3f3f3;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-share:hover {
        color: red;
    }

    .product-price-box .discount {
        color: #000;
        font-weight: 700;
    }

    .product-price-box .mrp {
        color: #565959;
        font-size: 13px;
    }

    .product-price-box .mrp span {
        text-decoration: line-through;
    }
</style>
<script>
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
</script>