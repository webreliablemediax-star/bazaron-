@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('Carts') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('breadcrumb-contents')
    <div class="breadcrumb-content">
        <h2 class="mb-2 text-center">{{ localize('Shopping Cart') }}</h2>
        <nav>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item fw-bold" aria-current="page"><a
                        href="{{ route('home') }}">{{ localize('Home') }}</a></li>
                <li class="breadcrumb-item fw-bold" aria-current="page">{{ localize('Carts') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents')
    <!--breadcrumb-->
    @include('frontend.default.inc.breadcrumb')
    <!--breadcrumb-->

    <!--cart section start-->
    <section class="cart-section ptb-120">
        <div class="container" style="margin-top:-102px;">
            <div class="rounded-2 overflow-hidden">
                <table class="cart-table w-100 bg-white">
                    <thead>
                        <th>{{ localize('Image') }}</th>
                        <th>{{ localize('Product Name') }}</th>
                        <th>{{ localize('Price') }}</th>
                        <th>{{ localize('Quantity') }}</th>
                        <th>{{ localize('Total Price') }}</th> 
                        <th>{{ localize('Action') }}</th>
                    </thead>
                    <tbody class="cart-listing">
                        <!--cart listing-->
                        @include('frontend.default.pages.partials.carts.cart-listing', ['carts' => $carts])
                        <!--cart listing-->
                    </tbody>
                </table>
            </div>
            <div class="row g-4">
                <div class="col-xl-7">
                    <div class="voucher-box py-7 px-5 position-relative z-1 overflow-hidden bg-white rounded mt-4">
                        <img src="{{ staticAsset('frontend/default/assets/img/shapes/circle-half.png') }}"
                            alt="circle shape" class="position-absolute end-0 top-0 z--1">
                        <h4 class="mb-4">{{ localize('Have a coupon?') }}</h4>
                        <div class="font-bold mb-2">{{ localize('Apply coupon to get discount.') }}</div>

                        <!-- coupon form -->
                        <form class="d-flex align-items-center coupon-form">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <input type="text" name="code" placeholder="{{ localize('Enter Your Coupon Code') }}"
                                class="theme-input w-100 coupon-input"
                                @if (isset($_COOKIE['coupon_code'])) value="{{ $_COOKIE['coupon_code'] }}" disabled @endif
                                required>

                            @if (isset($_COOKIE['coupon_code']))
                                <button type="submit"
                                    class="btn btn-secondary flex-shrink-0 apply-coupon-btn d-none px-4">{{ localize('Apply Coupon') }}</button>
                                <button type="button" class="btn btn-secondary flex-shrink-0 clear-coupon-btn"><i
                                        class="fas fa-close"></i></button>
                            @else
                                <button type="submit"
                                    class="btn btn-secondary flex-shrink-0 apply-coupon-btn px-4">{{ localize('Apply Coupon') }}</button>
                                <button type="button" class="btn btn-secondary flex-shrink-0 clear-coupon-btn d-none"><i
                                        class="fas fa-close"></i></button>
                            @endif
                        </form>
                        <!-- coupon form -->

                    </div>
                </div>

                <div class="col-xl-5">
                    <div class="cart-summery bg-white rounded-2 pt-4 pb-7 px-5 mt-4">
                        <table class="w-100">
                            <tr>
                                <td class="py-3">
                                    <h5 class="mb-0 fw-medium">{{ localize('Subtotal') }}</h5>
                                </td>
                                <td class="py-3">
                                    <h5 class="mb-0 text-end sub-total-price">
                                        {{ formatPrice(getSubTotal($carts, false)) }}</h5>
                                </td>
                            </tr>

                            <tr class="coupon-discount-wrapper {{ getCoupon() == '' ? 'd-none' : '' }}">
                                <td class="py-3">
                                    <h5 class="mb-0 fw-medium">{{ localize('Coupon Discount') }}</h5>
                                </td>
                                <td class="py-3">
                                    <h5 class="mb-0 text-end coupon-discount-price">
                                        {{ formatPrice(getCouponDiscount(getSubTotal($carts, false), getCoupon())) }}</h5>
                                </td>
                            </tr>

                        </table>
                        <p class="mb-5 mt-2">{{ localize('Shipping options will be updated during checkout.') }}</p>
                        <div class="btns-group d-flex flex-wrap gap-3">

                            <a href="{{ route('home') }}"
                                class="btn btn-outline-secondary border-secondary btn-md rounded-1" style="margin-top: 8px;">{{ localize('Continue Shopping') }}</a>

                            <a href="{{ route('checkout.proceed') }}" type="submit"
                                class="btn btn-primary btn-md rounded-1" style="margin-top:7px;">{{ localize('Checkout') }}</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!--cart section end-->
@endsection

<script>

function handleCartItem(action,id){

fetch("{{ route('carts.update') }}",{

method:'POST',

headers:{
'Content-Type':'application/json',
'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')
},

body:JSON.stringify({
action:action,
id:id
})

})

.then(res=>res.json())

.then(data=>{

if(data.success){

// cart table update
let cartListing=document.querySelector(".cart-listing");

if(cartListing){
cartListing.innerHTML=data.carts;
}

// minicart update
let navCart=document.querySelector(".cart-navbar-wrapper");

if(navCart){
navCart.innerHTML=data.navCarts;
}

// counter update
let counter=document.querySelector(".cart-counter");

if(counter){
counter.innerText=data.cartCount;
}

// subtotal update
let subtotal=document.querySelector(".sub-total-price");

if(subtotal){
subtotal.innerText=data.subTotal;
}

}

})

.catch(err=>console.log(err));

}




</script>


<style>
    /* ===== TABLE RESET ===== */
.cart-table {
    border-collapse: separate;
    border-spacing: 0 12px; /* row gap */
}

/* HEADER */
.cart-table thead th {
    background: #f3f4f6;
    font-size: 13px;
    font-weight: 600;
    color: #555;
    padding: 12px;
    text-align: center;
}

/* ROW CARD STYLE */
.cart-table tbody tr {
    background: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    border-radius: 10px;
    transition: 0.3s;
}

/* HOVER EFFECT */
.cart-table tbody tr:hover {
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

/* CELL */
.cart-table td {
    padding: 15px 12px;
    vertical-align: middle;
    border: none;
}

/* FIRST + LAST BORDER RADIUS */
.cart-table tbody tr td:first-child {
    border-radius: 10px 0 0 10px;
}
.cart-table tbody tr td:last-child {
    border-radius: 0 10px 10px 0;
}

/* IMAGE */
.cart-table img {
    border-radius: 8px;
    width: 80px;
}

/* PRODUCT TITLE */
.product-title h6 {
    font-size: 14px;
    font-weight: 400;
    color: #222;
}

/* PRICE */
.cart-table td span {
    font-size: 14px;
}


/* HEADER ka green/grey remove */
.cart-table thead th {
    background: #ffffff !important;
    color: #333;
    border-bottom: 1px solid #eee;
}

/* Table ka overall tint remove */
.cart-table {
    background: transparent !important;
}

/* Row background pure white */
.cart-table tbody tr {
    background: #ffffff !important;
}

/* Extra safety (kahi aur se aa raha ho to) */
.cart-table th,
.cart-table td {
    background-color: transparent;
}

/* Har column ke baad vertical line */
.cart-table td:not(:last-child),
.cart-table th:not(:last-child) {
    border-right: 1px solid #eee;
}

/* Thoda spacing bhi improve */
.cart-table td {
    padding: 15px 18px;
}

/* Optional: header line thodi darker */
.cart-table thead th:not(:last-child) {
    border-right: 1px solid #ddd;
}

/* Row card padding reduce */
.cart-table td {
    padding: 0px 12px !important;
}

/* Row height control */
.cart-table tbody tr {
    height: auto;
}

/* Product name ka extra space kam */
.product-title h6 {
    margin-bottom: 2px;
    line-height: 1.3;
}

/* Image bhi thoda compact */
.cart-table img {
    width: 70px;
}

/* Quantity box spacing reduce */
.product-qty {
    gap: 5px;
}

/* Action button compact */
.close-btn {
    padding: 4px 8px;
    font-size: 12px;
}


/* ===== PRODUCT NAME AREA COMPACT ===== */
.product-title {
    max-width: 250px; /* width control */
}

.product-title h6 {
    font-size: 13px;
    line-height: 1.2;
}

/* ===== PRICE & TOTAL COMPACT ===== */
.cart-table td:nth-child(3),
.cart-table td:nth-child(5) {
    font-size: 13px;
    white-space: nowrap;
}

/* ===== QUANTITY BOX SMALL ===== */
.product-qty {
    display: inline-flex;
    align-items: center;
    border-radius: 6px;
    overflow: hidden;
}

/* buttons */
.product-qty button {
    width: 20px;
    height: 32px;
    font-size: 14px;
    background: #2f2f2f;
    color: #fff;
    border: none;
}

/* input */
.product-qty input {
    width: 18px;
    height: 32px;
    text-align: center;
    font-size: 13px;
    border: none;
    background: #f5f5f5;
}

/* ===== ACTION BUTTON SMALL ===== */
.close-btn {
    padding: 4px 6px;
    font-size: 12px;
}

/* ===== OVERALL COLUMN WIDTH CONTROL ===== */
.cart-table td:nth-child(1) { width: 100px; }
.cart-table td:nth-child(3) { width: 100px; }
.cart-table td:nth-child(4) { width: 140px; }
.cart-table td:nth-child(5) { width: 120px; }
.cart-table td:nth-child(6) { width: 80px; }




/* ================= MOBILE CART FIX ================= */
@media (max-width: 768px){

    .cart-section .container{
        margin-top:0 !important;
    }

    /* Coupon box */
    .voucher-box{
        padding:20px !important;
    }

    .voucher-box img{
        width:180px;
        opacity:.15;
    }

    /* Coupon form */
    .coupon-form{
        flex-direction:column;
        align-items:stretch !important;
        gap:10px;
    }

    .coupon-form .coupon-input{
        width:100% !important;
    }

    .coupon-form button{
        width:100%;
    }

    /* Summary box */
    .cart-summery{
        padding:20px !important;
    }

    .btns-group{
        flex-direction:column;
    }

    .btns-group a{
        width:100%;
        text-align:center;
        margin-top:0 !important;
    }
}
</style>
