@extends('layouts.auth')

@section('title', 'Seller Onboarding - Step 4')

@section('content')
    <style>
        /* 🌑 Carbon black border for all form fields */
        input.form-control,
        select.form-select {
            border: 1px solid #2b2b2b;
            /* subtle carbon black border */
            box-shadow: none;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        input.form-control:focus,
        select.form-select:focus {
            border-color: #000;
            outline: none;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.25);
        }

        /* Multiple select styling improvement */
        select[multiple] {
            height: auto;
            min-height: 120px;
        }


        /* ................NEW CSS..................... */
        /* PAGE BACKGROUND */
        body {
            background: #f0f6ff;
        }

        /* FORM WIDTH */
        .col-lg-7 {
            max-width: 620px;
        }

        /* CARD STYLE */
        .card {
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        /* HEADER GREEN → BLUE */
        .card-header {
            background: #2a2b2d !important;
            padding: 10px 16px !important;
        }

        .card-header h3 {
            color: whitesmoke !important;
        }

        /* PROGRESS BAR */
        .progress {
            height: 18px;
        }

        .progress-bar {
            background: linear-gradient(135deg, #007bff, #0056b3) !important;
            display: flex;
            align-items: center;
            justify-content: center;
            color: whitesmoke;
            font-size: 12px;
        }

        /* REMOVE STRIPES */
        .progress-bar-striped {
            background-image: none !important;
        }

        .progress-bar-animated {
            animation: none !important;
        }

        /* INPUTS */
        .form-control,
        .form-select {
            width: 90%;
            margin: 0 auto;
            font-size: 14px;
        }

        /* LABEL ALIGNMENT */
        .form-label {
            width: 90%;
            margin: 0 auto 4px auto;
            font-size: 13px;
            margin-left: 27px
        }

        /* MULTIPLE SELECT */
        select[multiple] {
            width: 90%;
            margin: 0 auto;
        }

        /* SMALL TEXT */
        .text-muted {
            width: 90%;
            margin: 4px auto 0 auto;
        }

        /* BUTTON CONTAINER */
        .d-flex.justify-content-between.mt-4 {
            background: #eef4ff;
            padding: 12px 16px;
            border-radius: 8px;
        }

        /* BUTTON COLORS */
        .btn-success {
            background: #1565c0 !important;
            border-color: #1565c0 !important;
        }

        .btn-outline-success {
            color: #1565c0 !important;
            border-color: #1565c0 !important;
        }

        .btn-outline-success:hover {
            background: #1565c0 !important;
            color: #fff !important;
        }

        .text-muted {
            width: 90%;
            margin-left: 5%;
            margin-top: 4px;
            text-align: left;
        }

        /* Card padding thoda kam */
        .card-body {
            padding: 20px 26px !important;
        }

        /* Field spacing kam */
        .mb-4 {
            margin-bottom: 14px !important;
        }

        /* Inputs compact */
        .form-control,
        .form-select {
            height: 36px;
            font-size: 14px;
        }

        /* Multiple select height kam */
        select[multiple] {
            min-height: 80px !important;
        }

        /* Button container compact */
        .d-flex.justify-content-between.mt-4 {
            padding: 10px 14px;
        }

        /* Input fields light grey border */
        .form-control,
        .form-select {
            border: 1px solid #d1d5db !important;
            /* light grey */
            border-radius: 6px;
            box-shadow: none;
            transition: all .2s ease;
        }

        /* focus state */
        .form-control:focus,
        .form-select:focus {
            border-color: #9ca3af !important;
            /* darker grey on focus */
            box-shadow: none;
            outline: none;
        }

        /* labels normal weight + dark grey */
        .form-label {
            font-weight: 400 !important;
            color: #4b5563;
            font-size: 14px;
        }

        /* remove bold from labels */
        .fw-bold {
            font-weight: 400 !important;
            color: #4b5563;
        }

        /* placeholder subtle */
        ::placeholder {
            color: #9ca3af;
        }

        /* blue header container height kam */
        .card-header {
            padding: 10px 16px !important;
        }

        /* header text size kam */
        .card-header h3 {
            font-size: 15px;
            margin: 0;
            font-weight: 500;
        }

        /* NEXT BUTTON */
        .btn-success {
            background: #2a2b2d !important;
            border-color: #2a2b2d !important;
            color: #fff !important;
        }

        button.btn-success {
            background: #2a2b2d !important;
            border: none !important;
            color: #fff !important;
            padding: 10px 28px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* hover */
        .btn-success:hover,
        button.btn-success:hover {
            background: #1f2022 !important;
            border-color: #1f2022 !important;
            color: #fff !important;
        }

        /* BACK BUTTON */
        .btn-outline-success {
            background: #2a2b2d !important;
            border: none !important;
            color: #fff !important;
            padding: 10px 28px !important;
            border-radius: 30px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* hover */
        .btn-outline-success:hover {
            background: #1f2022 !important;
            border-color: #1f2022 !important;
            color: #fff !important;
        }
    </style>

    @php
        $currentStep = 4;
        $totalSteps = 7;
        $progress = ($currentStep / $totalSteps) * 100;
    @endphp

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                 <img src="{{ asset('public/uploads/media/Bazaron-seller-desk-logo.png') }}" height="70"
                            style="display:block;margin:auto;" alt="Bazaron Seller Desk">

                {{-- Progress Bar --}}
                <!-- <div class="mb-4">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar"
                                style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0"
                                aria-valuemax="100">
                                Step {{ $currentStep }} of {{ $totalSteps }}
                            </div>
                        </div>
                    </div> -->

                {{-- Card --}}
                <div class="card-header text-white text-start py-2" style="background:#2a2b2d;">
                    <h3 class="mb-0">Product Information</h3>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('vendor.onboarding.step4.store') }}">
                        @csrf

                        {{-- Product Categories --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                Product Category / Categories <span class="text-danger">*</span>
                            </label>

                            <select name="product_categories" class="form-select form-select-lg" required>
                                @foreach ($categories->where('level', 0) as $category)
                                    <option value="{{ $category->id }}" @if (isset($vendor) && in_array($category->id, explode(',', $vendor->product_categories ?? ''))) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <small class="text-muted">
                                Select the category you want to sell in.
                            </small>
                        </div>

                        {{-- Average Order Value --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                Average Order Value <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="avg_order_value" class="form-control form-control-lg"
                                placeholder="e.g., 5000"
                                value="{{ old('avg_order_value', $vendor->avg_order_value ?? '') }}" required>
                        </div>

                        {{-- Expected Product Listing Count --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                Expected Product Listing Count <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="expected_listing_count" class="form-control form-control-lg"
                                placeholder="e.g., 100"
                                value="{{ old('expected_listing_count', $vendor->expected_listing_count ?? '') }}" required>
                        </div>

                        {{-- Business Model --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                Do you manufacture or resell products? <span class="text-danger">*</span>
                            </label>
                            <select name="business_model" class="form-select form-select-lg" required>
                                <option value="">Select</option>
                                <option value="Manufacturer" @if (old('business_model', $vendor->business_model ?? '') == 'Manufacturer') selected @endif>Manufacturer
                                </option>
                                <option value="Reseller" @if (old('business_model', $vendor->business_model ?? '') == 'Reseller') selected @endif>Reseller</option>
                            </select>
                        </div>

                        {{-- Product Certification --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Product Certification (if any)</label>
                            <input type="text" name="product_certification" class="form-control form-control-lg"
                                placeholder="e.g., ISO 9001"
                                value="{{ old('product_certification', $vendor->product_certification ?? '') }}">
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('vendor.onboarding.step3') }}" class="btn btn-outline-success btn-lg px-4">
                                <i class="fa-solid fa-arrow-left me-2"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                Next <i class="fa-solid fa-arrow-right ms-2"></i>
                            </button>
                        </div>

                        <p class="mt-3 text-muted small">
                            Select the categories you intend to sell products in.
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection
