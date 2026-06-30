@extends('layouts.auth')

@section('title', 'Seller Onboarding - Step 1')

@section('content')
    <div class="container py-5">

        {{-- 🌑 Custom Input Styling --}}
        <style>
            /* Carbon black border styling for all inputs, selects & textareas */
            .form-control,
            .form-select {
                border: 1px solid #2b2b2b !important;
                /* Carbon black */
                border-radius: 6px;
                box-shadow: none;
                transition: all 0.2s ease-in-out;
                background-color: #fafafa;
                /* soft contrast */
            }

            .form-control:focus,
            .form-select:focus {
                border-color: #007bff !important;
                /* Bootstrap blue on focus */
                /* box-shadow: 0 0 4px rgba(0, 123, 255, 0.4); */
                background-color: #fff;
            }

            textarea.form-control {
                resize: vertical;
            }

            /* ...........................................NEW CSS STYLING..................................... */
            /* Page background */
            body {
                background: #f4f6f9;
            }

            .card {
                border-radius: 8px;
                border: 1px solid #e5e7eb;
                box-shadow: none;
                background: #ffffff;
            }

            .card:hover {
                transform: none;
                box-shadow: none;
            }

            /* Header Styling */
            .card-header {
                background: #2a2b2d !important;
                font-weight: 600;
                letter-spacing: .4px;
                color: #fff;
            }

            /* Card Body Spacing */
            .card-body {
                padding: 35px;
            }


            /* Labels compact */
            .form-label {
                font-size: 13px;
                font-weight: 600;
                margin-bottom: 4px;
                color: #333;
                margin-left: 5px;
            }

            /* Inputs compact */
            .form-control,
            .form-select {
                font-size: 14px !important;
                padding: 8px 10px !important;
                height: 38px !important;
                border-radius: 6px;
                width: 100%;
                display: block;
                margin-left: auto;
                margin-right: auto;
            }

            /* textarea compact */
            textarea.form-control {
                min-height: 55px !important;
                padding: 8px 10px !important;
            }

            /* reduce spacing between fields */
            .mb-3 {
                margin-bottom: 12px !important;
            }

            /* location row spacing */
            .row.g-3 {
                --bs-gutter-x: 12px;
                --bs-gutter-y: 12px;
            }

            /* dropdown compact */
            .form-select {
                height: 38px !important;
            }

            /* village dropdown */
            #village {
                font-size: 14px;
            }

            /* button compact */
            .btn-lg {
                padding: 10px 26px;
                font-size: 15px;
            }

            /* Reduce form container width */
            .col-lg-8 {
                max-width: 720px;
            }

            /* center properly */
            .container {
                max-width: 900px;
            }

            #pincode,
            #city,
            #district,
            #state {
                width: 100%;
            }

            .card-header h3 {
                color: whitesmoke !important;
                font-size: 15px;
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

            .required-label::after {
                content: " *";
                color: red;
            }

            .btn-nav,
            .btn-back {
                width: 180px;
                height: 50px;

                border-radius: 30px !important;

                display: flex;
                align-items: center;
                justify-content: center;

                gap: 8px;
                font-size: 16px;
                font-weight: 600;

                padding: 0 20px !important;
            }

            .gst-status-icon {
                position: absolute;
                right: 20px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 18px;
                z-index: 10;
            }

            .field-success {
                position: absolute;
                right: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: #28a745;
                font-size: 18px;
            }

            #gstLoader {
                color: #0d6efd;
            }

            #gstSuccess {
                color: #28a745;
            }


            .card-body {
                padding: 20px !important;
            }

            /* har field ke niche gap kam */
            .mb-3 {
                margin-bottom: 6px !important;
            }

            .mb-4 {
                margin-bottom: 10px !important;
            }

            /* row ke upar niche ka gap kam */
            .row.g-3 {
                --bs-gutter-x: 10px;
                --bs-gutter-y: 4px;
            }

            /* labels aur field ke beech gap kam */
            .form-label {
                margin-bottom: 2px !important;
            }

            /* textarea thoda compact */
            textarea.form-control {
                min-height: 45px !important;
            }
        </style>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                 <img src="{{ asset('public/uploads/media/Bazaron-seller-desk-logo.png') }}" height="70"
                            style="display:block;margin:auto;" alt="Bazaron Seller Desk">

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-primary text-white text-start py-2">
                        <h3 class="mb-0">Business Information</h3>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('vendor.onboarding.step1.store') }}">
                        @csrf

                        {{-- Business Reg/GST --}}
                        <div class="mb-4">

                            <label class="form-label">Business Registration No <span class="text-danger">*</span></label>

                            <div class="position-relative">

                                <input type="text" name="business_reg_no" id="gstin"
                                    class="form-control form-control-lg"
                                    value="{{ old('business_reg_no', $vendor->business_reg_no ?? '') }}" maxlength="15"
                                    pattern="[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]"
                                    style="text-transform:uppercase" required>

                                <span id="gstLoader" class="gst-status-icon d-none">
                                    <i class="fa-solid fa-spinner fa-spin"></i>
                                </span>

                                <span id="gstSuccess" class="gst-status-icon d-none">
                                    <i class="fa-solid fa-circle-check"></i>
                                </span>

                            </div>

                            <small class="text-muted">
                                Example: 07ABCDE1234F1Z5 (15 characters GSTIN)
                            </small>

                        </div>

                        {{-- Business Name --}}
                        <div class="position-relative">
                            <label class="form-label">
                                Business Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="business_name" name="business_name"
                                class="form-control form-control-lg"
                                value="{{ old('business_name', $vendor->business_name ?? '') }}"  readonly required>

                            <span id="business_name_tick" class="field-success d-none">
                                <i class="fa-solid fa-circle-check"></i>
                            </span>
                        </div>

                        <div class="row g-3">

                            <!-- Business Type -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Business Type <span class="text-danger">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="text" id="business_type" name="business_type"
                                            class="form-control form-control-lg"
                                            value="{{ old('business_type', $vendor->business_type ?? '') }}"  readonly required>

                                        <span id="business_type_tick" class="field-success d-none">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Date of Establishment -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Date of Establishment <span class="text-danger">*</span>
                                    </label>

                                    <div class="position-relative">
                                        <input type="date" id="establishment_date" name="establishment_date"
                                            class="form-control form-control-lg"
                                            value="{{ old('establishment_date', $vendor->establishment_date ?? '') }}"
                                         readonly        required>

                                        <span id="establishment_date_tick" class="field-success d-none">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>

                            {{-- Business Address --}}
                            <div class="mb-3">
                                <label class="form-label">
                                    Business Address <span class="text-danger">*</span>
                                </label>

                                <div class="position-relative">

                                    <textarea id="business_address" name="business_address" class="form-control form-control-lg" rows="5"  readonly required>{{ old('business_address', $vendor->business_address ?? '') }}</textarea>

                                    <span id="business_address_tick" class="field-success d-none">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </span>

                                </div>
                            </div>

                            {{-- Location Row --}}
                            <!-- City -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    City <span class="text-danger">*</span>
                                </label>

                                <div class="position-relative">

                                    <input type="text" id="city" name="city" class="form-control form-control-lg"
                                        value="{{ old('city', $vendor->city ?? '') }}" readonly required>

                                    <span id="city_tick" class="field-success d-none">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </span>

                                </div>
                            </div>



                            <!-- District -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    District <span class="text-danger">*</span>
                                </label>

                                <div class="position-relative">

                                    <input type="text" id="district" name="district"
                                        class="form-control form-control-lg"
                                        value="{{ old('district', $vendor->district ?? '') }}" readonly required>

                                    <span id="district_tick" class="field-success d-none">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </span>

                                </div>
                            </div>
                            <!-- State -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    State <span class="text-danger">*</span>
                                </label>

                                <div class="position-relative">

                                    <input type="text" id="state" name="state"
                                        class="form-control form-control-lg"
                                        value="{{ old('state', $vendor->state ?? '') }}" readonly required>

                                    <span id="state_tick" class="field-success d-none">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </span>

                                </div>
                            </div>
                            <!-- Pincode -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    Pincode <span class="text-danger">*</span>
                                </label>

                                <div class="position-relative">

                                    <input type="text" id="pincode" name="zip"
                                        class="form-control form-control-lg" value="{{ old('zip', $vendor->zip ?? '') }}"
                                        maxlength="6"  readonly required>

                                    <span id="pincode_tick" class="field-success d-none">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </span>

                                </div>
                            </div>

                            {{-- PAN Number and GST Status --}}


                            <div class="row g-3">

                                <!-- PAN Number -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            PAN Number <span class="text-danger">*</span>
                                        </label>

                                        <input type="text" name="pan_number" id="pan_no"
                                            class="form-control form-control-lg" maxlength="10"
                                            style="text-transform:uppercase" required>

                                        <small id="pan_msg"></small>
                                    </div>
                                </div>

                                <!-- GST Status -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            GST Status <span class="text-danger">*</span>
                                        </label>

                                        <input type="text" name="gst_number" id="gst_number"
                                            class="form-control form-control-lg" readonly>
                                            <small id="gst_status_msg"></small>
                                            
                                    </div>
                                </div>

                            </div>

                            <div class="col-12 mt-2">

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="same_address">

                                    <label class="form-check-label" for="same_address">
                                        Warehouse Address same as Business Address
                                    </label>
                                </div>

                                <label class="form-label">
                                    Warehouse Address <span class="text-danger">*</span>
                                </label>

                                <textarea id="warehouse_address" name="warehouse_address" class="form-control" rows="3"></textarea>

                            </div>



                            {{-- Village Selector --}}
                            {{-- <div class="mt-3">
                            <label class="form-label">Village / Area <span class="text-danger">*</span></label>
                            <select name="village" id="village" class="form-select form-select-lg" required>
                                <option value="">-- Select Village / Area --</option>
                            </select>
                        </div> --}}

                            {{-- Submit Button --}}
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('vendor.onboarding.step6') }}" class="btn btn-primary btn-back">
                                    <i class="fa-solid fa-arrow-left"></i>
                                    Back
                                </a>

                                <button type="submit" id="submitBtn" class="btn btn-primary btn-nav">
                                    Next Step <i class="fa-solid fa-arrow-right"></i>
                                </button>

                                {{-- <button type="submit" class="btn btn-primary btn-nav">
                                Next Step <i class="fa-solid fa-arrow-right"></i>
                            </button> --}}

                            </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    </div>

    {{-- 📍 Auto Pincode Lookup --}}
    <script>
        document.getElementById('pincode').addEventListener('keyup', function() {
            let pincode = this.value.trim();

            if (pincode.length === 6) {
                fetch(`/pincode/${pincode}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data[0].Status === "Success") {
                            let postOffices = data[0].PostOffice;
                            let first = postOffices[0];

                            document.getElementById('city').value = first.Block || first.Division;
                            document.getElementById('district').value = first.District;
                            document.getElementById('state').value = first.State;

                            // Fill village dropdown
                            let villageSelect = document.getElementById('village');
                            villageSelect.innerHTML = '<option value="">-- Select Village / Area --</option>';

                            postOffices.forEach(po => {
                                let opt = document.createElement('option');
                                opt.value = po.Name;
                                opt.textContent = po.Name;
                                villageSelect.appendChild(opt);
                            });

                            // If only one village, auto select
                            if (postOffices.length === 1) {
                                villageSelect.value = postOffices[0].Name;
                            }

                        } else {
                            clearLocationFields();
                            alert("❌ Invalid Pincode");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        clearLocationFields();
                        alert("⚠️ Could not fetch address details.");
                    });
            }
        });

        function clearLocationFields() {
            document.getElementById('city').value = "";
            document.getElementById('district').value = "";
            document.getElementById('state').value = "";
            document.getElementById('village').innerHTML = '<option value="">-- Select Village / Area --</option>';
        }


        document.querySelector('input[name="business_reg_no"]').addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });
    </script>

  <script>
        let gstPan = '';

        document.querySelector('input[name="business_reg_no"]')
            .addEventListener('blur', function() {
                document.getElementById('gstLoader').classList.remove('d-none');
                document.getElementById('gstSuccess').classList.add('d-none');

                let gstin = this.value.trim();

                if (gstin.length === 15) {

                    fetch(`/gst/${gstin}`)
                        .then(response => response.json())
                        .then(res => {

                            if (res.status == 1 && res.valid) {

                                document.getElementById('gstLoader').classList.add('d-none');
                                document.getElementById('gstSuccess').classList.remove('d-none');

                                let d = res.company_details;

                                // Business Name
                                document.querySelector('input[name="business_name"]').value =
                                    d.trade_name || '';

                                // Business Type
                                document.querySelector('input[name="business_type"]').value =
                                    d.gst_type || '';

                                // GST Number
                                document.querySelector('input[name="business_reg_no"]').value =
                                    res.gstin || '';

                                // Establishment Date
                                document.querySelector('input[name="establishment_date"]').value =
                                    d.registration_date || '';

                                // Business Address
                                document.querySelector('textarea[name="business_address"]').value =
                                    d.pradr?.addr || '';

                                // Pincode
                                document.getElementById('pincode').value =
                                    d.pradr?.pincode || '';

                                // City
                                document.getElementById('city').value =
                                    d.pradr?.loc || d.pradr?.city || '';

                                // District
                                document.getElementById('district').value =
                                    d.pradr?.district || '';

                                // State
                                document.getElementById('state').value =
                                    d.state_info?.name || '';

                                // PAN
                                gstPan = d.pan || '';

                                // GST Status
                                let gstStatusField = document.getElementById('gst_number');
                                gstStatusField.value = d.company_status || '';
                                let status = (d.company_status || '').toLowerCase();

                                if (status === 'active') {

                                    gstStatusField.style.border = '2px solid green';
                                    gstStatusField.style.backgroundColor = '#f0fff4';
                                    gstStatusField.style.color = 'green';

                                    document.getElementById('submitBtn').disabled = false;
                                    document.getElementById('gst_status_msg').innerHTML = '';

                                } else {

                                    gstStatusField.style.border = '2px solid red';
                                    gstStatusField.style.backgroundColor = '#fff5f5';
                                    gstStatusField.style.color = 'red';

                                    document.getElementById('submitBtn').disabled = true;

                                    let gstMsg = document.getElementById('gst_status_msg');

                                    gstMsg.innerHTML =
                                        '✗ Your GST status is ' +
                                        d.company_status +
                                        '. Only Active GSTIN is allowed.';

                                    gstMsg.style.color = 'red';

                                    return;
                                }

                                [
                                    'business_name',
                                    'business_type',
                                    'establishment_date',
                                    'business_address',
                                    'city',
                                    'district',
                                    'state',
                                    'pincode'
                                ].forEach(id => {

                                    let tick = document.getElementById(id + '_tick');

                                    if (tick) {
                                        tick.classList.remove('d-none');
                                    }

                                    let field = document.getElementById(id);

                                    if (field) {
                                        field.style.border = '2px solid #28a745';
                                        field.style.backgroundColor = '#f0fff4';
                                    }
                                });

                            } else {
                                document.getElementById('gstLoader').classList.add('d-none');
                                document.getElementById('gstSuccess').classList.add('d-none');

                                alert('Invalid GSTIN or GST record not found');
                            }

                        })
                        .catch(error => {
                            document.getElementById('gstLoader').classList.add('d-none');
                            document.getElementById('gstSuccess').classList.add('d-none');

                            console.error(error);

                            alert('Unable to fetch GST details');

                        });

                }

            });

        document.querySelector('form').addEventListener('submit', function(e) {

            let pan = document.getElementById('pan_no').value.trim();

            if (pan.length < 10) {

                e.preventDefault();

                alert('PAN Number must contain exactly 10 characters');

                document.getElementById('pan_no').focus();

                return false;
            }

            let status = document.getElementById('gst_number')
                .value
                .trim()
                .toLowerCase();

            if (status !== 'active') {

                e.preventDefault();

                document.getElementById('gst_status_msg').innerHTML =
                    '✗ Only Active GSTIN holders can proceed.';

                document.getElementById('gst_status_msg').style.color = 'red';

                return false;
            }

        });

        // PAN Validation
        document.getElementById('pan_no').addEventListener('input', function() {

            let enteredPan = this.value.toUpperCase().trim();
            this.value = enteredPan;

            let msg = document.getElementById('pan_msg');

            if (enteredPan.length === 10) {

                if (enteredPan === gstPan) {

                    this.style.border = '2px solid green';
                    this.style.backgroundColor = '#f0fff4';

                    msg.innerHTML = '✓ PAN matched with GST record';
                    msg.style.color = 'green';

                } else {

                    this.style.border = '2px solid red';
                    this.style.backgroundColor = '#fff5f5';

                    msg.innerHTML = '✗ PAN does not match GST record';
                    msg.style.color = 'red';
                }
            } else {

                this.style.border = '';
                this.style.backgroundColor = '';
                msg.innerHTML = '✗ PAN Number must be 10 digit';
                msg.style.color = 'red';
            }

        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const checkbox = document.getElementById("same_address");
            const businessAddress = document.getElementById("business_address");
            const warehouseAddress = document.getElementById("warehouse_address");

            checkbox.addEventListener("change", function() {

                if (this.checked) {

                    warehouseAddress.value = businessAddress.value;

                } else {

                    warehouseAddress.value = '';

                }

            });

            businessAddress.addEventListener("input", function() {

                if (checkbox.checked) {
                    warehouseAddress.value = this.value;
                }

            });

        });
    </script>
@endsection
