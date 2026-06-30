@extends('backend.layouts.master')

@section('title')
    Profile Settings
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Profile Settings</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('vendor.profile.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <ul class="nav nav-tabs mb-4" id="profileTab" role="tablist">

                            <li class="nav-item">
                                <button type="button" class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#business">
                                    Business Details
                                </button>
                            </li>

                            <li class="nav-item">
                                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#address">
                                    Address Details
                                </button>
                            </li>

                            <li class="nav-item">
                                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#contact">
                                    Contact Details
                                </button>
                            </li>

                            <li class="nav-item">
                                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bank">
                                    Bank Details
                                </button>
                            </li>

                            <li class="nav-item">
                                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#product">
                                    Product Details
                                </button>
                            </li>

                            <li class="nav-item">
                                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#kyc">
                                    KYC Details
                                </button>
                            </li>

                            <li class="nav-item">
                                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#logistics">
                                    Logistics
                                </button>
                            </li>

                        </ul>


                        <div class="tab-content">

                            <div class="tab-pane fade show active" id="business">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>Business Details</h5>

                                    <button type="button" class="btn btn-sm btn-primary edit-section"
                                        data-section="business">
                                        Edit
                                    </button>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Business Name</label>
                                        <input type="text" class="form-control business-field" name="business_name"
                                            value="{{ $vendor->business_name }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Business Type</label>
                                        <input type="text" class="form-control business-field" name="business_type"
                                            value="{{ $vendor->business_type }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Registration Number</label>
                                        <input type="text" class="form-control business-field" name="business_reg_no"
                                            value="{{ $vendor->business_reg_no }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Establishment Date</label>
                                        <input type="date" class="form-control" name="establishment_date"
                                            value="{{ $vendor->establishment_date }}">
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="address">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>Address Details</h5>

                                    <button type="button" class="btn btn-sm btn-primary edit-section-address">
                                        Edit
                                    </button>
                                </div>

                                <hr>

                                <div class="row">

                                    <div class="col-md-12 mb-3">
                                        <label>Business Address</label>
                                        <textarea class="form-control address-field" name="business_address" readonly>{{ $vendor->business_address }}</textarea>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>City</label>
                                        <input type="text" class="form-control address-field" name="city"
                                            value="{{ $vendor->city }}" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>State</label>
                                        <input type="text" class="form-control address-field" name="state"
                                            value="{{ $vendor->state }}" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Zip</label>
                                        <input type="text" class="form-control address-field" name="zip"
                                            value="{{ $vendor->zip }}" readonly>
                                    </div>

                                </div>
                            </div>


                            <div class="tab-pane fade" id="contact">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>Contact Details</h5>

                                    <button type="button" class="btn btn-sm btn-primary edit-section-contact">
                                        Edit
                                    </button>
                                </div>

                                <hr>
                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label>Contact Person</label>
                                        <input type="text" class="form-control contact-field" name="contact_person"
                                            value="{{ $vendor->contact_person }}" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Designation</label>
                                        <input type="text" class="form-control contact-field" name="designation"
                                            value="{{ $vendor->designation }}" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Alternate Phone</label>
                                        <input type="text" class="form-control contact-field" name="alt_phone"
                                            value="{{ $vendor->alt_phone }}" readonly>
                                    </div>

                                </div>

                            </div>


                            <div class="tab-pane fade" id="bank">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>Bank Details</h5>

                                    <button type="button" class="btn btn-sm btn-primary edit-section-bank">
                                        Edit
                                    </button>
                                </div>

                                <hr>
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label>Bank Name</label>
                                        <input type="text" class="form-control bank-field" name="bank_name"
                                            value="{{ $vendor->bank_name }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Branch Name</label>
                                        <input type="text" class="form-control bank-field" name="branch_name"
                                            value="{{ $vendor->branch_name }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Account Holder Name</label>
                                        <input type="text" class="form-control bank-field" name="account_holder_name"
                                            value="{{ $vendor->account_holder_name }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Account Number</label>
                                        <input type="text" class="form-control bank-field" name="account_number"
                                            value="{{ $vendor->account_number }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>IFSC Code</label>
                                        <input type="text" class="form-control bank-field" name="ifsc_code"
                                            value="{{ $vendor->ifsc_code }}" readonly>
                                    </div>

                                </div>
                            </div>


                            <div class="tab-pane fade" id="product">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>Product Details</h5>

                                    <button type="button" class="btn btn-sm btn-primary edit-section-product">
                                        Edit
                                    </button>
                                </div>

                                <hr>

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Product Categories</label>
                                        <input type="text" class="form-control product-field"
                                            name="product_categories" value="{{ $vendor->product_categories }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Average Order Value</label>
                                        <input type="text" class="form-control product-field" name="avg_order_value"
                                            value="{{ $vendor->avg_order_value }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Expected Listing Count</label>
                                        <input type="text" class="form-control product-field"
                                            name="expected_listing_count" value="{{ $vendor->expected_listing_count }}"
                                            readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Business Model</label>
                                        <input type="text" class="form-control product-field" name="business_model"
                                            value="{{ $vendor->business_model }}" readonly>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Product Certification</label>
                                        <input type="text" class="form-control product-field"
                                            name="product_certification" value="{{ $vendor->product_certification }}"
                                            readonly>
                                    </div>

                                </div>
                            </div>


                            <div class="tab-pane fade" id="kyc">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>KYC Details</h5>

                                    <button type="button" class="btn btn-sm btn-primary edit-section-kyc">
                                        Edit
                                    </button>
                                </div>

                                <hr>

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PAN Number</label>
                                        <input type="text" class="form-control kyc-field" name="pan_number"
                                            value="{{ $vendor->pan_number }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">GST Number</label>
                                        <input type="text" class="form-control kyc-field" name="gst_number"
                                            value="{{ $vendor->gst_number }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">IEC Code</label>
                                        <input type="text" class="form-control kyc-field" name="iec_code"
                                            value="{{ $vendor->iec_code }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Invoice Prefix</label>
                                        <input type="text" class="form-control kyc-field" name="invoice_prefix"
                                            value="{{ $vendor->invoice_prefix }}" readonly>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">KYC Document</label>

                                        @if ($vendor->kyc_docs)
                                            <div class="mb-2">
                                                <a href="{{ asset('storage/' . $vendor->kyc_docs) }}" target="_blank"
                                                    class="btn btn-sm btn-info">
                                                    View Current Document
                                                </a>
                                            </div>
                                        @endif

                                        <input type="file" class="form-control kyc-file" name="kyc_docs" disabled>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Digital Signature</label>

                                        @if ($vendor->digital_signature)
                                            <a href="{{ asset('storage/digital_signature/' . $vendor->digital_signature) }}"
                                                target="_blank" class="btn btn-sm btn-info">
                                                View Current Signature
                                            </a>
                                        @endif

                                        <input type="file" class="form-control kyc-file" name="digital_signature"
                                            disabled>
                                    </div>

                                </div>
                            </div>


                            <div class="tab-pane fade" id="logistics">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>Logistics Details</h5>

                                    <button type="button" class="btn btn-sm btn-primary edit-section-logistics">
                                        Edit
                                    </button>
                                </div>

                                <hr>

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Has Own Logistics</label>

                                        <select class="form-control logistics-field" name="has_own_logistics" disabled>
                                            <option value="1"
                                                {{ $vendor->has_own_logistics == 1 ? 'selected' : '' }}>
                                                Yes
                                            </option>

                                            <option value="0"
                                                {{ $vendor->has_own_logistics == 0 ? 'selected' : '' }}>
                                                No
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Preferred Shipping</label>

                                        <input type="text" class="form-control logistics-text"
                                            name="preferred_shipping" value="{{ $vendor->preferred_shipping }}" readonly>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Warehouse Address</label>

                                        <textarea class="form-control logistics-text" rows="4" name="warehouse_address" readonly>{{ $vendor->warehouse_address }}</textarea>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                Update Profile
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </section>
    <script>
        document.querySelector('.edit-section').addEventListener('click', function() {

            document.querySelectorAll('.business-field').forEach(function(field) {

                field.removeAttribute('readonly');

            });

        });



        document.querySelector('.edit-section-address').addEventListener('click', function() {

            document.querySelectorAll('.address-field').forEach(function(field) {

                field.removeAttribute('readonly');

            });

        });



        document.querySelector('.edit-section-contact').addEventListener('click', function() {

            document.querySelectorAll('.contact-field').forEach(function(field) {

                field.removeAttribute('readonly');

            });

        });



        document.querySelector('.edit-section-bank').addEventListener('click', function() {

            document.querySelectorAll('.bank-field').forEach(function(field) {

                field.removeAttribute('readonly');

            });

        });



        document.querySelector('.edit-section-product').addEventListener('click', function() {

            document.querySelectorAll('.product-field').forEach(function(field) {

                field.removeAttribute('readonly');

            });

        });



        document.querySelector('.edit-section-kyc').addEventListener('click', function() {

            document.querySelectorAll('.kyc-field').forEach(function(field) {

                field.removeAttribute('readonly');

            });

            document.querySelectorAll('.kyc-file').forEach(function(field) {

                field.removeAttribute('disabled');

            });

        });



        document.querySelector('.edit-section-logistics').addEventListener('click', function() {

            document.querySelectorAll('.logistics-text').forEach(function(field) {

                field.removeAttribute('readonly');

            });

            document.querySelectorAll('.logistics-field').forEach(function(field) {

                field.removeAttribute('disabled');

            });

        });
    </script>
@endsection
