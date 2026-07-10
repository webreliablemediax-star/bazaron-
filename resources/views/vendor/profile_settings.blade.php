@extends('backend.layouts.master')

@section('title')
    Profile Settings
@endsection

@section('contents')

    <style>
        .profile-page {
            padding: 24px 0 40px;
        }

        .profile-header {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 20px;
        }

        .profile-header h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .profile-header p {
            margin: 5px 0 0;
            color: #6b7280;
            font-size: 13px;
        }

        .profile-section {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .profile-section-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .profile-section-header h5 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .profile-section-body {
            padding: 20px;
        }

        .profile-section .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 6px;
        }

        .profile-section .form-control,
        .profile-section .form-select {
            min-height: 42px;
            font-size: 13px;
            border-radius: 7px;
            border: 1px solid #d1d5db;
        }

        .profile-section textarea.form-control {
            min-height: 90px;
        }

        .profile-section .form-control[readonly],
        .profile-section .form-select:disabled {
            background: #f9fafb;
            color: #374151;
            cursor: default;
        }

        .section-edit-btn {
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 600;
        }

        .locked-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            background: #f3f4f6;
            color: #6b7280;
            font-size: 10px;
            font-weight: 600;
            margin-left: 5px;
        }

        .document-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }

        .profile-submit-area {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px 20px;
            text-align: right;
        }
    </style>


    <section class="tt-section profile-page">

        <div class="container">

            <div class="profile-header">
                <h4>Profile Settings</h4>

                <p>
                    View and manage your business profile information.
                    Profile changes may require admin approval.
                </p>
            </div>


            <form action="{{ route('vendor.profile.settings.update') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf


                {{-- ======================================================= --}}
                {{-- BUSINESS DETAILS --}}
                {{-- ======================================================= --}}

                <div class="profile-section">

                    <div class="profile-section-header">

                        <h5>Business Details</h5>

                        

                    </div>


                    <div class="profile-section-body">

                        <div class="row">


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Business Name
                                    <span class="locked-badge">Locked</span>
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="{{ $vendor->business_name ?: 'NA' }}"
                                       readonly>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Business Type
                                    <span class="locked-badge">Locked</span>
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="{{ $vendor->business_type ?: 'NA' }}"
                                       readonly>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Registration Number
                                    <span class="locked-badge">Locked</span>
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="{{ $vendor->business_reg_no ?: 'NA' }}"
                                       readonly>

                            </div>

                             <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    PAN Number
                                    <span class="locked-badge">Locked</span>
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="{{ $vendor->pan_number ?: 'NA' }}"
                                       readonly>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    GST Number
                                    <span class="locked-badge">Locked</span>
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="{{ $vendor->gst_number ?: 'NA' }}"
                                       readonly>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    IEC Code
                                    <span class="locked-badge">Locked</span>
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="{{ $vendor->iec_code ?: 'NA' }}"
                                       readonly>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Invoice Prefix
                                </label>

                                <input type="text"
                                       class="form-control kyc-field"
                                       name="invoice_prefix"
                                       value="{{ $vendor->invoice_prefix }}"
                                       readonly>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Establishment Date
                                    <span class="locked-badge">Locked</span>
                                </label>

                                <input type="date"
                                       class="form-control"
                                       value="{{ $vendor->establishment_date }}"
                                       readonly>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ======================================================= --}}
                {{-- ADDRESS DETAILS --}}
                {{-- ======================================================= --}}

                <div class="profile-section">

                    <div class="profile-section-header">

                        <h5>Address Details</h5>

                       

                    </div>


                    <div class="profile-section-body">

                        <div class="row">


                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Business Address
                                </label>

                                <textarea class="form-control address-field"
                                          name="business_address"
                                          readonly>{{ $vendor->business_address }}</textarea>

                            </div>


                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    City
                                </label>

                                <input type="text"
                                       class="form-control address-field"
                                       name="city"
                                       value="{{ $vendor->city }}"
                                       readonly>

                            </div>


                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    State
                                </label>

                                <input type="text"
                                       class="form-control address-field"
                                       name="state"
                                       value="{{ $vendor->state }}"
                                       readonly>

                            </div>


                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    ZIP Code
                                </label>

                                <input type="text"
                                       class="form-control address-field"
                                       name="zip"
                                       value="{{ $vendor->zip }}"
                                       readonly>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ======================================================= --}}
                {{-- CONTACT DETAILS --}}
                {{-- ======================================================= --}}

                <div class="profile-section">

                    <div class="profile-section-header">

                        <h5>Contact Details</h5>

                        <button type="button"
                                class="btn btn-sm btn-primary section-edit-btn edit-section-contact">
                            Edit
                        </button>

                    </div>


                    <div class="profile-section-body">

                        <div class="row">


                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Contact Person
                                </label>

                                <input type="text"
                                       class="form-control contact-field"
                                       name="contact_person"
                                       value="{{ $vendor->contact_person }}"
                                       readonly>

                            </div>


                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Designation
                                </label>

                                <input type="text"
                                       class="form-control contact-field"
                                       name="designation"
                                       value="{{ $vendor->designation }}"
                                       readonly>

                            </div>


                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Alternate Phone
                                </label>

                                <input type="text"
                                       class="form-control contact-field"
                                       name="alt_phone"
                                       value="{{ $vendor->alt_phone }}"
                                       readonly>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ======================================================= --}}
                {{-- BANK DETAILS --}}
                {{-- ======================================================= --}}

                <div class="profile-section">

                    <div class="profile-section-header">

                        <h5>Bank Details</h5>

                        <button type="button"
                                class="btn btn-sm btn-primary section-edit-btn edit-section-bank">
                            Edit
                        </button>

                    </div>


                    <div class="profile-section-body">

                        <div class="row">


                            <div class="col-md-6 mb-3">

                                <label class="form-label">Bank Name</label>

                                <input type="text"
                                       class="form-control bank-field"
                                       name="bank_name"
                                       value="{{ $vendor->bank_name }}"
                                       readonly>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">Branch Name</label>

                                <input type="text"
                                       class="form-control bank-field"
                                       name="branch_name"
                                       value="{{ $vendor->branch_name }}"
                                       readonly>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">Account Holder Name</label>

                                <input type="text"
                                       class="form-control bank-field"
                                       name="account_holder_name"
                                       value="{{ $vendor->account_holder_name }}"
                                       readonly>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">Account Number</label>

                                <input type="text"
                                       class="form-control bank-field"
                                       name="account_number"
                                       value="{{ $vendor->account_number }}"
                                       readonly>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">IFSC Code</label>

                                <input type="text"
                                       class="form-control bank-field"
                                       name="ifsc_code"
                                       value="{{ $vendor->ifsc_code }}"
                                       readonly>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ======================================================= --}}
                {{-- PRODUCT DETAILS --}}
                {{-- ======================================================= --}}

                <div class="profile-section">

                    <div class="profile-section-header">

                        <h5>Product Details</h5>

                        <button type="button"
                                class="btn btn-sm btn-primary section-edit-btn edit-section-product">
                            Edit
                        </button>

                    </div>


                    <div class="profile-section-body">

                        <div class="row">


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Product Categories
                                    <span class="locked-badge">Locked</span>
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="{{ optional(\App\Models\Category::find($vendor->product_categories))->name ?? 'NA' }}"
                                       readonly>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Average Order Value
                                </label>

                                <input type="text"
                                       class="form-control product-field"
                                       name="avg_order_value"
                                       value="{{ $vendor->avg_order_value }}"
                                       readonly>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Expected Listing Count
                                </label>

                                <input type="text"
                                       class="form-control product-field"
                                       name="expected_listing_count"
                                       value="{{ $vendor->expected_listing_count }}"
                                       readonly>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Business Model
                                </label>

                                <select class="form-control product-field"
                                        name="business_model"
                                        disabled>

                                    <option value="">
                                        Select Business Model
                                    </option>

                                    <option value="reseller"
                                        {{ strtolower($vendor->business_model ?? '') == 'reseller' ? 'selected' : '' }}>
                                        reseller
                                    </option>

                                    <option value="manufacturer"
                                        {{ strtolower($vendor->business_model ?? '') == 'manufacturer' ? 'selected' : '' }}>
                                        Manufacturer
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Product Certification
                                </label>

                                <input type="text"
                                       class="form-control product-field"
                                       name="product_certification"
                                       value="{{ $vendor->product_certification }}"
                                       readonly>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ======================================================= --}}
                {{-- KYC DETAILS --}}
                {{-- ======================================================= --}}

                <div class="profile-section">

                    <div class="profile-section-header">

                        <h5>KYC Details</h5>

                        <button type="button"
                                class="btn btn-sm btn-primary section-edit-btn edit-section-kyc">
                            Edit Documents
                        </button>

                    </div>


                    <div class="profile-section-body">

                        <div class="row">


                           



                            {{-- KYC DOCUMENT --}}

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    KYC Document
                                </label>


                                @if ($vendor->kyc_docs)

                                    <div class="mb-2">

                                        <a href="{{ asset('storage/' . $vendor->kyc_docs) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-info">

                                            View Current Document

                                        </a>

                                    </div>

                                @else

                                    <div class="mb-2 text-muted">
                                        NA
                                    </div>

                                @endif


                                <input type="file"
                                       class="form-control kyc-file"
                                       name="kyc_docs"
                                       disabled>

                            </div>



                            {{-- DIGITAL SIGNATURE --}}

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Digital Signature
                                </label>


                                @if ($vendor->digital_signature)

                                    <div class="mb-2">

                                        <a href="{{ asset('storage/digital_signature/' . $vendor->digital_signature) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-info">

                                            View Current Signature

                                        </a>

                                    </div>

                                @else

                                    <div class="mb-2 text-muted">
                                        NA
                                    </div>

                                @endif


                                <input type="file"
                                       class="form-control kyc-file"
                                       name="digital_signature"
                                       disabled>

                            </div>



                            {{-- ADDITIONAL DOCUMENTS --}}

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Additional Documents
                                </label>


                                @if ($additionalDocuments->count() > 0)

                                    <div class="document-list">

                                        @foreach ($additionalDocuments as $index => $document)

                                            <a href="{{ asset('storage/' . $document->file_path) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-info">

                                                View Document {{ $index + 1 }}

                                            </a>

                                        @endforeach

                                    </div>

                                @else

                                    <div class="mb-2 text-muted">
                                        NA
                                    </div>

                                @endif


                                <input type="file"
                                       class="form-control kyc-file"
                                       name="additional_documents[]"
                                       multiple
                                       disabled>

                                <small class="text-muted">
                                    You can select multiple files at once.
                                </small>

                                <small class="text-muted d-block mt-2">
    Bazaron may request additional documents or certifications based on the product categories you sell in, such as Electronics, Cosmetics, Beauty, and other applicable categories.
</small>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ======================================================= --}}
                {{-- LOGISTICS DETAILS --}}
                {{-- ======================================================= --}}

                <div class="profile-section">

                    <div class="profile-section-header">

                        <h5>Logistics Details</h5>

                        <button type="button"
                                class="btn btn-sm btn-primary section-edit-btn edit-section-logistics">
                            Edit
                        </button>

                    </div>


                    <div class="profile-section-body">

                        <div class="row">


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Has Own Logistics
                                </label>

                                <select class="form-control logistics-field"
                                        name="has_own_logistics"
                                        disabled>

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

                                <label class="form-label">
                                    Preferred Shipping
                                </label>

                                <input type="text"
                                       class="form-control logistics-text"
                                       name="preferred_shipping"
                                       value="{{ $vendor->preferred_shipping }}"
                                       readonly>

                            </div>


                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Warehouse Address
                                </label>

                                <textarea class="form-control logistics-text"
                                          rows="4"
                                          name="warehouse_address"
                                          readonly>{{ $vendor->warehouse_address }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ======================================================= --}}
                {{-- SUBMIT --}}
                {{-- ======================================================= --}}

                <div class="profile-submit-area">

                    <button type="submit"
                            class="btn btn-primary">

                        Update Profile

                    </button>

                </div>

            </form>

        </div>

    </section>



    <script>

        document.addEventListener('DOMContentLoaded', function() {


            /*
            |--------------------------------------------------------------------------
            | EMPTY READONLY FIELDS ME NA
            |--------------------------------------------------------------------------
            */

            document.querySelectorAll(
                'input[type="text"][readonly], textarea[readonly]'
            ).forEach(function(field) {

                if (field.value.trim() === '') {

                    field.value = 'NA';

                    field.dataset.wasEmpty = 'true';

                }

            });



            /*
            |--------------------------------------------------------------------------
            | COMMON ENABLE FUNCTION
            |--------------------------------------------------------------------------
            */

            function enableFields(selector) {

                document.querySelectorAll(selector).forEach(function(field) {

                    if (field.dataset.wasEmpty === 'true') {

                        field.value = '';

                    }

                    field.removeAttribute('readonly');

                    field.removeAttribute('disabled');

                });

            }



            /*
            |--------------------------------------------------------------------------
            | ADDRESS EDIT
            |--------------------------------------------------------------------------
            */

            document.querySelector('.edit-section-address')
                ?.addEventListener('click', function() {

                    enableFields('.address-field');

                });



            /*
            |--------------------------------------------------------------------------
            | CONTACT EDIT
            |--------------------------------------------------------------------------
            */

            document.querySelector('.edit-section-contact')
                ?.addEventListener('click', function() {

                    enableFields('.contact-field');

                });



            /*
            |--------------------------------------------------------------------------
            | BANK EDIT
            |--------------------------------------------------------------------------
            */

            document.querySelector('.edit-section-bank')
                ?.addEventListener('click', function() {

                    enableFields('.bank-field');

                });



            /*
            |--------------------------------------------------------------------------
            | PRODUCT EDIT
            |--------------------------------------------------------------------------
            */

            document.querySelector('.edit-section-product')
                ?.addEventListener('click', function() {

                    enableFields('.product-field');

                });



            /*
            |--------------------------------------------------------------------------
            | KYC DOCUMENT EDIT
            |--------------------------------------------------------------------------
            */

            document.querySelector('.edit-section-kyc')
                ?.addEventListener('click', function() {

                    enableFields('.kyc-field');

                    document.querySelectorAll('.kyc-file')
                        .forEach(function(field) {

                            field.removeAttribute('disabled');

                        });

                });



            /*
            |--------------------------------------------------------------------------
            | LOGISTICS EDIT
            |--------------------------------------------------------------------------
            */

            document.querySelector('.edit-section-logistics')
                ?.addEventListener('click', function() {

                    enableFields('.logistics-text');

                    document.querySelectorAll('.logistics-field')
                        .forEach(function(field) {

                            field.removeAttribute('disabled');

                        });

                });



            /*
            |--------------------------------------------------------------------------
            | SUBMIT SAFETY
            |--------------------------------------------------------------------------
            */

            const profileForm = document.querySelector(
                'form[action="{{ route('vendor.profile.settings.update') }}"]'
            );


            profileForm?.addEventListener('submit', function() {

                document.querySelectorAll('[data-was-empty="true"]')
                    .forEach(function(field) {

                        if (field.value === 'NA') {

                            field.value = '';

                        }

                    });

            });


        });

    </script>

@endsection