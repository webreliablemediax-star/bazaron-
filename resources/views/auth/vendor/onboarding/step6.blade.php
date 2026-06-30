    @extends('layouts.auth')

    @section('title', 'Seller Onboarding - Step 6')

    @section('content')
        @php
            $currentStep = 6;
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
                                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar"
                                        style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                        Step {{ $currentStep }} of {{ $totalSteps }}
                                    </div>
                                </div>
                            </div> -->

                    {{-- Card --}}
                    <div class="card shadow-lg border-0 rounded-3">
                        <div class="card-header d-flex justify-content-between align-items-center py-2">
                            <h3 class="mb-0">Logistics & Fulfillment</h3>

                            <!-- Info Button -->
                            <a href="#" data-bs-toggle="modal" data-bs-target="#shippingInfoModal"
                                style="color:#fff; font-size:18px;">
                                <i class="fa-solid fa-circle-info"></i>
                            </a>
                        </div>


                        <div class="card-body p-4">

                            {{-- Custom Border Styling --}}
                            <style>
                                .form-control,
                                .form-select,
                                textarea.form-control {
                                    border: 1.8px solid #2b2b2b !important;
                                    /* carbon black border */
                                    border-radius: 8px !important;
                                    box-shadow: none !important;
                                    transition: all 0.2s ease-in-out;
                                }

                                .form-control:focus,
                                .form-select:focus,
                                textarea.form-control:focus {
                                    border-color: #000 !important;
                                    box-shadow: 0 0 0 0.2rem rgba(43, 43, 43, 0.2);
                                }

                                /* ......................NEW CSS.......................... */
                                /* page background */
                                body {
                                    background: #f0f6ff;
                                }

                                /* container width same as other steps */
                                .col-lg-7 {
                                    max-width: 620px;
                                }

                                /* header compact */
                                .card-header {
                                    background: #2a2b2d !important;
                                    padding: 10px 16px !important;
                                }

                                .card-header h3 {
                                    font-size: 15px;
                                    font-weight: 500;
                                    margin: 0;
                                    color: #fff;
                                }

                                /* input fields soft border */
                                .form-control,
                                .form-select,
                                textarea.form-control {
                                    border: 1px solid #d1d5db !important;
                                    border-radius: 6px;
                                    box-shadow: none;
                                    font-size: 14px;
                                }

                                /* input focus */
                                .form-control:focus,
                                .form-select:focus,
                                textarea.form-control:focus {
                                    border-color: #9ca3af !important;
                                    box-shadow: none;
                                    outline: none;
                                }

                                /* labels */
                                .form-label {
                                    font-weight: 400 !important;
                                    color: #4b5563;
                                    font-size: 14px;
                                }

                                /* remove bootstrap bold */
                                .fw-bold {
                                    font-weight: 400 !important;
                                    color: #4b5563;
                                }

                                /* spacing compact */
                                .mb-3 {
                                    margin-bottom: 14px !important;
                                }

                                /* textarea height */
                                textarea.form-control {
                                    height: 80px;
                                }

                                /* button container */
                                .d-flex.justify-content-between {
                                    background: #e9f1ff;
                                    padding: 12px 16px;
                                    border-radius: 10px;
                                }

                                /* back button */
                                .btn-outline-primary {
                                    border-color: #1e6bd6;
                                    color: #1e6bd6;
                                }

                                .btn-outline-success:hover {
                                    background: #1565c0 !important;
                                    color: #fff !important;
                                }

                                /* next button */
                                .btn-primary {
                                    background: #1e6bd6;
                                    border-color: #1e6bd6;
                                }

                                .btn-primary:hover {
                                    background: #1557b0;
                                }

                                /* warehouse address textarea height kam */
                                textarea[name="warehouse_address"] {
                                    height: 60px !important;
                                    resize: vertical;
                                    font-size: 14px;
                                }

                                /* NEXT BUTTON STYLE (same as Step-4) */
                                button.btn-primary,
                                button.btn-success {

                                    background: #1e6bd6 !important;
                                    border: none !important;
                                    color: #fff;
                                    padding: 10px 28px;
                                    border-radius: 30px;
                                    font-size: 14px;
                                    font-weight: 500;
                                    display: flex;
                                    align-items: center;
                                    gap: 6px;
                                }

                                /* hover */
                                button.btn-primary:hover,
                                button.btn-success:hover {
                                    background: #1557b0 !important;
                                }

                                /* FORCE BLUE HOVER FOR BACK BUTTON */
                                a.btn-outline-primary:hover {
                                    background: #1565c0 !important;
                                    border-color: #1565c0 !important;
                                    color: #fff !important;
                                }


                                #preferred_shipping_field,
                                #warehouse_address_field {
                                    display: none;
                                }

                                .btn-primary {
                                    background: #2a2b2d !important;
                                    border-color: #2a2b2d !important;
                                    color: #fff !important;
                                }

                                button.btn-primary {
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

                                .btn-primary:hover,
                                button.btn-primary:hover {
                                    background: #1f2022 !important;
                                    border-color: #1f2022 !important;
                                    color: #fff !important;
                                }
                                
                                .alert-infs {
                                    background-color: #ececec;
                                    border: 1px solid #b2ebf2;
                                    color: #464646;
                                    padding: 15px;
                                    border-radius: 8px;
                                    font-size: 14px;
                                }
                            </style>

                            <form method="POST" action="{{ route('vendor.onboarding.step6.store') }}">
                                @csrf

                                {{-- Own Logistics --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Shipping Method <span
                                            class="text-danger">*</span></label>
                                    <select id="shipping_method" name="has_own_logistics" class="form-select form-select-lg"
                                        required>
                                        <option value="">Select</option>
                                        <option value="1"
                                            {{ old('has_own_logistics', $vendor->has_own_logistics ?? '') == 1 ? 'selected' : '' }}>
                                            Self Shipping</option>
                                        <option value="0"
                                            {{ old('has_own_logistics', $vendor->has_own_logistics ?? '') == 0 ? 'selected' : '' }}>
                                            Bazaron Shipping</option>
                                    </select>
                                </div>
                                

                                {{-- Preferred Shipping --}}
                                <div class="mb-3" id="preferred_shipping_field">
                                    <label class="form-label fw-bold">Preferred Shipping Partners</label>
                                    <input type="text" name="preferred_shipping" class="form-control form-control-lg"
                                        value="{{ old('preferred_shipping', $vendor->preferred_shipping ?? '') }}">
                                </div>
                                
                                 <div id="instructionBox" class="alert alert-infs mt-3" style="display:none;">
                                </div>

                                {{-- Warehouse Address --}}
                                <div class="mb-3" id="warehouse_address_field">
                                    <label class="form-label fw-bold">Warehouse Address</label>
                                    <textarea name="warehouse_address" class="form-control form-control-lg" rows="4">{{ old('warehouse_address', $vendor->warehouse_address ?? '') }}</textarea>
                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex justify-content-between mt-4">
                                    <!-- <a href="{{ route('vendor.onboarding.step5') }}"
                                                class="btn btn-outline-primary btn-lg px-4">
                                                <i class="fa-solid fa-arrow-left me-2"></i> Back
                                            </a> -->

                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        Next <i class="fa-solid fa-arrow-right ms-2"></i>
                                    </button>
                                </div>

                                <p class="mt-3 text-muted small">
                                    Provide accurate logistics and warehouse information to ensure smooth order fulfillment.
                                </p>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- SHIPPING INFO MODAL -->
        <div class="modal fade" id="shippingInfoModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Shipping Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <h6>🚚 Self Shipping</h6>
                        <p>
                            You will handle order delivery yourself. This includes managing courier partners,
                            packaging, and shipment tracking on your own.
                        </p>

                        <hr>

                        <h6>📦 Bazaron Shipping</h6>
                        <p>
                            Bazaron will handle pickup and delivery of your orders. You only need to provide
                            your warehouse address, and the rest of the logistics will be managed by us.
                        </p>

                    </div>

                </div>
            </div>
        </div>
<script>
            const instructions = {
                @foreach ($instractions as $item)
                    "{{ strtolower(trim($item->title)) }}": `{!! addslashes($item->description) !!}`,
                @endforeach
            };
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const shippingMethod = document.getElementById("shipping_method");
                const preferredField = document.getElementById("preferred_shipping_field");
                const warehouseField = document.getElementById("warehouse_address_field");

                function toggleFields() {

                    if (shippingMethod.value === "1") {
                        // Self Shipping
                        preferredField.style.display = "block";
                        warehouseField.style.display = "none";
                    } else if (shippingMethod.value === "0") {
                        // Bazaron Shipping
                        preferredField.style.display = "none";
                        warehouseField.style.display = "none";
                    } else {
                        preferredField.style.display = "none";
                        warehouseField.style.display = "none";
                    }

                    const instructionBox =
                        document.getElementById("instructionBox");

                    let selectedText =
                        shippingMethod.options[
                            shippingMethod.selectedIndex
                        ].text.trim().toLowerCase();

                    if (instructions[selectedText]) {

                        instructionBox.innerHTML =
                            instructions[selectedText];

                        instructionBox.style.display = "block";

                    } else {

                        instructionBox.style.display = "none";

                    }

                }

                // page load par check
                toggleFields();

                // change hone par run
                shippingMethod.addEventListener("change", toggleFields);

            });
        </script>
    @endsection
