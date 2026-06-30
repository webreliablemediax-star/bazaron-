@extends('frontend.default.layouts.master')
@section('title')
    {{ localize('Checkout') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection
@section('breadcrumb-contents')
    <div class="breadcrumb-content">
        <h2 class="mb-2 text-center">{{ localize('Check Out') }}</h2>
        <nav>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item fw-bold" aria-current="page"><a
                        href="{{ route('home') }}">{{ localize('Home') }}</a></li>
                <li class="breadcrumb-item fw-bold" aria-current="page">{{ localize('Checkout') }}</li>
            </ol>
        </nav>
    </div>
@endsection
@section('contents')
    <!--breadcrumb-->
    @include('frontend.default.inc.breadcrumb')
    <!--breadcrumb-->
    <!--checkout form start-->
    <form class="checkout-form" action="{{ route('checkout.complete') }}" method="POST">
        @csrf
        @php
            $address =
                $addresses->where('id', request('shipping_address_id'))->first() ??
                ($addresses->where('is_default', 1)->first() ?? $addresses->first());
        @endphp
        @if ($address)
            <input type="hidden" id="selected_shipping_address" name="shipping_address_id" value="{{ $address->id }}">
        @else
            <div class="alert alert-warning text-center">
                Please add address first to continue checkout
            </div>
        @endif
        <div class="checkout-section ptb-120">
            <div class="container">
                <div class="row g-4">

                    <!-- LEFT SIDE (form data) -->
                    <div class="col-xl-7">
                        <div class="checkout-steps">

                            <!-- shipping address -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">
                                    Delivering to {{ $address->name ?? '' }}
                                </h4>
                            </div>

                            @if ($address)
                                <div class="col-12">
                                    <div class="tt-address-content">
                                        <input type="radio" class="tt-custom-radio" name="shipping_address_preview"
                                            id="shipping-{{ $address->id }}" value="{{ $address->id }}"
                                            @if (request('shipping_address_id') == $address->id || $address->is_default) checked @endif
                                            data-city_id="{{ $address->city_id }}">

                                        <label for="shipping-{{ $address->id }}"
                                            class="tt-address-info bg-white rounded p-4 position-relative"
                                            style="width: 68%; margin-top: -14px;">

                                            <div id="selectedAddress">
                                                @include('frontend.default.inc.address', [
                                                    'address' => $address,
                                                ])
                                            </div>

                                            <a href="javascript:void(0);" onclick="editAddress({{ $address->id }})"
                                                class="tt-edit-address checkout-radio-link position-absolute">
                                                {{ localize('Edit') }}
                                            </a>

                                            <a href="javascript:void(0);" onclick="openAddressModal()"
                                                class="checkout-radio-link position-absolute change-address-btn">
                                                Change
                                            </a>

                                        </label>
                                    </div>
                                </div>
                            @else
                                <div class="col-12 mt-5">
                                    <div class="tt-address-content">
                                        <div class="alert alert-secondary text-center">
                                            {{ localize('Add your address to checkout') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- payment methods -->
                            <h4 class="mt-7">{{ localize('Payment Method') }}</h4>
                            @include('frontend.default.pages.checkout.inc.paymentMethods', [
                                'carts' => $carts,
                            ])
                            <!-- payment methods -->

                        </div>

                        <!-- checkout-logistics -->
                        <div class="checkout-logistics"></div>
                        <!-- checkout-logistics -->

                    </div>
                    <!-- ✅ LEFT COLUMN CLOSED PROPERLY HERE -->


                    <!-- RIGHT SIDE (order summary) -->
                    <div class="col-xl-5">
                        <div class="checkout-sidebar">
                            @include('frontend.default.pages.partials.checkout.orderSummary', [
                                'carts' => $carts,
                            ])
                        </div>
                    </div>

                </div>
                <!-- order summary -->
            </div>
        </div>
        </div>
    </form>
    <!--checkout form end-->
    <!--add address modal start-->
    @include('frontend.default.inc.addressForm', ['countries' => $countries])
    <!--add address modal end-->
    <div class="modal fade" id="addressChangeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Select Delivery Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- yaha next step me address list load karenge -->
                    <div class="address-list">
                        @foreach ($addresses as $addr)
                            <div class="border p-3 mb-3 rounded">
                                <div class="form-check">
                                    <input class="form-check-input address-radio" type="radio" name="modal_address"
                                        value="{{ $addr->id }}" data-name="{{ $addr->name }}"
                                        data-address="{{ $addr->village }}, {{ $addr->district_name }}, {{ $addr->state_name }}, {{ $addr->pincode }}, {{ $addr->country_name }}"
                                        data-phone="{{ $addr->phone }}">
                                    <label class="form-check-label">
                                        <strong>{{ $addr->name }}</strong><br>
                                        {{ $addr->village }},
                                        {{ $addr->district_name }},
                                        {{ $addr->state_name }},
                                        {{ $addr->pincode }},
                                        {{ $addr->country_name }}
                                        <br>
                                        Phone: {{ $addr->phone }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                        <button class="btn btn-warning mt-3" onclick="selectAddress()">
                            Deliver to this address
                        </button>
                        <div class="mt-3">
                            <a href="javascript:void(0);" onclick="openNewAddressModal()" class="d-block text-primary mb-2">
                                Add a new delivery address
                            </a>
                            <a href="javascript:void(0);" onclick="multipleAddress()" class="d-block text-primary">
                                Deliver to multiple addresses
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .change-address-btn {
        right: 60px;
        top: 5px;
        font-weight: 600;
        color: #0d6efd;
    }
</style>
<script>
    function openAddressModal() {
        $('#addressChangeModal').modal('show');
    }


    function selectAddress() {

        let selected = document.querySelector('.address-radio:checked');

        if (!selected) {
            alert("Please select address");
            return;
        }

        let addressText = `
   <strong>${selected.dataset.name || ''}</strong><br>
   ${selected.dataset.address || ''}<br>
   Phone: ${selected.dataset.phone || ''}
   `;

        document.getElementById("selectedAddress").innerHTML = addressText;

        // edit button update
        document.querySelector('.tt-edit-address')
            .setAttribute('onclick', 'editAddress(' + selected.value + ')');

        // hidden field update (VERY IMPORTANT)
        document.getElementById('selected_shipping_address').value = selected.value;

        $('#addressChangeModal').modal('hide');

    }

    function multipleAddress() {
        alert("Multiple address delivery feature coming soon");
    }

    function openNewAddressModal() {

        // pehla modal close karo
        $('#addressChangeModal').modal('hide');

        // thoda delay deke address form open karo
        setTimeout(function() {
            addNewAddress();
        }, 300);

    }

    function editAddress(id) {

        $.ajax({
            url: "/address/" + id + "/edit",
            type: "GET",

            success: function(res) {

                $('#addressFormModal .modal-body').html(res);
                $('#addressFormModal').modal('show');

            },

            error: function() {
                alert("Unable to load address form");
            }

        });

    }
</script>
<style>
    /* ===== REMOVE UGLY ORANGE BORDER ===== */
    .tt-address-info {
        border: 1px solid #e5e7eb !important;
        background: #fff !important;
    }

    /* remove dashed focus style */
    .tt-custom-radio:checked+.tt-address-info {
        border: 1px solid #f97316 !important;
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.08);
    }

    /* ===== ADDRESS CARD COMPACT ===== */
    .tt-address-info {
        padding: 14px 16px !important;
        border-radius: 10px !important;
    }

    /* heading spacing */
    .checkout-steps h4 {
        font-size: 18px;
        margin-bottom: 10px !important;
    }

    /* Delivering text */
    .checkout-steps .d-flex h4 {
        font-size: 18px;
        font-weight: 600;
    }

    /* address text */
    #selectedAddress {
        font-size: 14px;
        line-height: 1.5;
        color: #444;
    }

    /* name bold */
    #selectedAddress strong {
        font-size: 15px;
        color: #111;
    }

    /* ===== CHANGE + EDIT BUTTON ===== */
    .tt-edit-address,
    .change-address-btn {
        top: 8px !important;
        font-size: 13px;
        font-weight: 500;
    }

    .tt-edit-address {
        right: 10px !important;
    }

    .change-address-btn {
        right: 60px !important;
    }

    /* ===== DELIVERY BOX ===== */
    .checkout-steps .tt-address-info .d-flex {
        font-size: 14px;
    }

    .checkout-steps .tt-address-info p {
        font-size: 13px;
        color: #666;
    }

    /* ===== PERSONAL INFO FORM ===== */
    .checkout-form.bg-white {
        padding: 18px !important;
        border-radius: 10px !important;
    }

    /* input fields */
    .label-input-field input,
    .label-input-field textarea {
        padding: 8px 10px !important;
        font-size: 14px;
        border-radius: 6px;
    }

    /* labels */
    .label-input-field label {
        font-size: 13px;
        margin-bottom: 4px;
    }

    /* reduce gaps */
    .checkout-form .row.g-4>div {
        margin-bottom: -8px;
    }

    /* ===== SECTION SPACING ===== */
    .mt-7 {
        margin-top: 20px !important;
    }

    /* ===== RIGHT SIDEBAR ===== */
    .checkout-sidebar {
        position: sticky;
        top: 80px;
    }

    /* ===== REMOVE EXTRA BIG HEIGHT FEEL ===== */
    .checkout-section {
        padding-top: 40px !important;
        padding-bottom: 40px !important;
    }

    /* ===== MODAL ADDRESS LIST CLEAN ===== */
    .address-list .border {
        border: 1px solid #e5e7eb !important;
        border-radius: 8px;
        padding: 12px !important;
    }

    /* hover effect */
    .address-list .border:hover {
        border-color: #f97316;
        background: #fff7ed;
    }

    /* ===== PAGE BACKGROUND ===== */
    body {
        background: #f5f5f5 !important;
    }

    /* ===== MAIN SECTION ===== */
    .checkout-section {
        padding-top: 30px !important;
        padding-bottom: 30px !important;
    }

    /* ===== LEFT CONTENT AREA ===== */
    .checkout-steps {
        background: #fff;
        padding: 8px;
        border-radius: 6px;
    }

    /* ===== SECTION HEADINGS ===== */
    .checkout-steps h4 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 12px !important;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 8px;
    }

    /* ===== ADDRESS BOX ===== */
    .tt-address-info {
        background: #fff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 6px !important;
        padding: 14px 16px !important;
        transition: 0.2s;
    }

    /* selected state */
    .tt-custom-radio:checked+.tt-address-info {
        border: 1px solid #f97316 !important;
        background: #fff7ed !important;
    }

    /* address text */
    #selectedAddress {
        font-size: 14px;
        line-height: 1.5;
        color: #333;
    }

    #selectedAddress strong {
        font-size: 15px;
        font-weight: 600;
    }

    /* ===== CHANGE + EDIT ===== */
    .tt-edit-address,
    .change-address-btn {
        font-size: 13px;
        top: 8px !important;
    }

    .tt-edit-address {
        right: 10px !important;
    }

    .change-address-btn {
        right: 60px !important;
    }

    /* ===== DELIVERY BOX ===== */
    .tt-address-content {
        margin-bottom: 10px;
    }

    /* delivery label */
    .tt-address-info .d-flex span {
        font-size: 14px;
        font-weight: 500;
    }

    .tt-address-info p {
        font-size: 13px;
        color: #666;
    }

    /* ===== PERSONAL INFO ===== */
    .checkout-form.bg-white {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 16px !important;
    }

    /* inputs */
    .label-input-field input,
    .label-input-field textarea {
        padding: 8px 10px !important;
        font-size: 14px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        background: #fff;
    }

    /* focus */
    .label-input-field input:focus,
    .label-input-field textarea:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 1px rgba(249, 115, 22, 0.2);
    }

    /* labels */
    .label-input-field label {
        font-size: 13px;
        margin-bottom: 4px;
    }

    /* ===== RIGHT SIDEBAR ===== */
    .checkout-sidebar {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 0px;
        position: sticky;
        top: 80px;
    }

    /* order total highlight */
    .checkout-sidebar h5,
    .checkout-sidebar h4 {
        font-weight: 600;
    }

    /* ===== BUTTON (Use this payment method) ===== */
    .checkout-sidebar .btn-primary {
        width: 100%;
        border-radius: 20px;
        background: #ffd814;
        border: 1px solid #fcd200;
        color: #111;
        font-weight: 600;
    }

    /* hover */
    .checkout-sidebar .btn-primary:hover {
        background: #f7ca00;
    }

    /* ===== REMOVE BIG SPACING ===== */
    .mt-7 {
        margin-top: 18px !important;
    }

    .row.g-4>div {
        margin-bottom: -6px;
    }

    /* ===== MODAL LIST ===== */
    .address-list .border {
        border: 1px solid #e5e7eb !important;
        border-radius: 6px;
        padding: 10px !important;
    }

    .address-list .border:hover {
        border-color: #f97316;
        background: #fff7ed;
    }

    /* container ka left padding hata do */
    .checkout-section .container {
        max-width: 100% !important;
        padding-left: 20px !important;
        padding-right: 20px !important;
    }
</style>
