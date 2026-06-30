@extends('layouts.auth')

@section('title', 'Seller Onboarding - Step 5')

@section('content')
    @php
        $currentStep = 5;
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
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header text-white text-start py-2" style="background:#2a2b2d;">
                        <h3 class="mb-0">Tax & Compliance</h3>
                    </div>
                    <div class="card-body p-4">



                        {{-- Custom Border Styling --}}
                        <style>
                            .form-control,
                            .form-select {
                                border: 1.8px solid #2b2b2b !important;
                                /* carbon black border */
                                border-radius: 8px !important;
                                box-shadow: none !important;
                                transition: all 0.2s ease-in-out;
                            }

                            .form-control:focus,
                            .form-select:focus {
                                border-color: #000 !important;
                                box-shadow: 0 0 0 0.2rem rgba(43, 43, 43, 0.2);
                            }


                            /* .................NEW CSS................... */
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
                                color: whitesmoke;
                            }

                            /* progress bar blue */
                            .progress-bar {
                                background: #1e6bd6 !important;
                            }

                            /* input fields light grey */
                            .form-control {
                                border: 1px solid #d1d5db !important;
                                border-radius: 6px;
                                box-shadow: none;
                                height: 36px;
                                font-size: 14px;
                            }

                            .form-control:focus {
                                border-color: #9ca3af !important;
                                box-shadow: none;
                                outline: none;
                            }

                            /* labels normal */
                            .form-label {
                                font-weight: 400 !important;
                                color: #4b5563;
                                font-size: 14px;
                            }

                            /* remove bootstrap fw-bold */
                            .fw-bold {
                                font-weight: 400 !important;
                                color: #4b5563;
                            }

                            /* spacing compact */
                            .mb-4 {
                                margin-bottom: 14px !important;
                            }

                            /* helper text alignment */
                            .text-muted {
                                margin-left: 4px;
                            }

                            /* button container */
                            .d-flex.justify-content-between {
                                background: #e9f1ff;
                                padding: 12px 16px;
                                border-radius: 10px;
                            }

                            /* back button */
                            /* BACK BUTTON */
                            .btn-outline-success {
                                background: #2a2b2d !important;
                                border: none !important;
                                color: #fff !important;
                                border-radius: 30px;
                                padding: 10px 28px;
                                font-size: 14px;
                                font-weight: 500;
                                display: flex;
                                align-items: center;
                                gap: 6px;
                            }

                            .btn-outline-success:hover {
                                background: #1f2022 !important;
                                color: #fff !important;
                            }

                            /* next button same as step 4 */
                            /* NEXT BUTTON */
                            .btn-success {
                                background: #2a2b2d !important;
                                border: none !important;
                                color: #fff !important;
                                border-radius: 30px;
                                padding: 10px 28px;
                                font-size: 14px;
                                font-weight: 500;
                                display: flex;
                                align-items: center;
                                gap: 6px;
                            }

                            .btn-success:hover {
                                background: #1f2022 !important;
                            }



                            /* SIGNATURE BOX */
                            .signature-box {
                                position: relative;
                                border: 2px dashed #d1d5db;
                                border-radius: 10px;
                                background: #fafafa;
                                height: 160px;
                                transition: all 0.2s ease;
                            }

                            .signature-box:hover {
                                border-color: #2a2b2d;
                                background: #f5f7fa;
                            }

                            #preview {
                                border: 1px solid #ddd;
                                border-radius: 6px;
                                padding: 4px;
                                background: #fff;
                            }

                            .form-check {
                                margin-top: 15px;
                                margin-bottom: 15px;
                            }

                            .form-check-label a {
                                color: #1e6bd6;
                                text-decoration: none;
                            }

                            .form-check-label a:hover {
                                text-decoration: underline;
                            }
                        </style>

                        <form method="POST" action="{{ route('vendor.onboarding.step5.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            {{-- PAN Number --}}
                            {{-- <div class="mb-4">
                                <label class="form-label fw-bold">PAN Number <span class="text-danger">*</span></label>
                                <input type="text" name="pan_number" id="pan_number" maxlength="10"
                                    placeholder="ABCDE1234F" class="form-control form-control-lg"
                                    value="{{ old('pan_number', $vendor->pan_number ?? '') }}" required>

                                <small class="text-muted">
                                    Format: 5 letters, 4 numbers, 1 letter (e.g. ABCDE1234F)
                                </small>
                            </div> --}}

                            {{-- GST Number --}}
                            {{-- <div class="mb-4">
                                <label class="form-label fw-bold">GST Number</label>
                                <input type="text" name="gst_number" class="form-control form-control-lg"
                                    value="{{ old('gst_number', $vendor->gst_number ?? '') }}">
                            </div> --}}

                            {{-- Invoice Number --}}
                            {{-- Invoice Number --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Invoice Format <span class="text-danger">*</span>
                                </label>

                                <div class="d-flex align-items-end gap-2">

                                    {{-- Prefix --}}
                                    <div style="flex:1;">
                                        <label class="form-label small mb-1">Prefix</label>
                                        <input type="text" name="invoice_prefix" class="form-control" placeholder="INV"
                                            value="{{ old('invoice_prefix', $vendor->invoice_prefix ?? 'INV') }}">
                                    </div>



                                    {{-- Serial --}}
                                    <div style="flex:1;">
                                        <label class="form-label small mb-1">Serial</label>
                                        <input type="text" name="invoice_serial" class="form-control text-center"
                                            placeholder="0001"
                                            value="{{ old('invoice_serial', $vendor->invoice_serial ?? '0001') }}">
                                    </div>

                                </div>

                                <small class="text-muted">
                                    Example: INV-0001
                                </small>

                                @error('invoice_prefix')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- IEC Code --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">IEC Code</label>
                                <input type="text" name="iec_code" class="form-control form-control-lg"
                                    value="{{ old('iec_code', $vendor->iec_code ?? '') }}">
                            </div>

                            {{-- KYC Documents --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    KYC Documents <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="kyc_docs[]" class="form-control form-control-lg" multiple>
                                <small class="text-muted">You can upload multiple files (PDF, JPG, PNG).</small>

                                @if (isset($vendor) && $vendor->kyc_docs)
                                    <div class="mt-2">
                                        <strong>Already uploaded:</strong>
                                        <ul>
                                            @foreach (explode(',', $vendor->kyc_docs) as $file)
                                                <li><a href="{{ asset('storage/kyc_docs/' . $file) }}"
                                                        target="_blank">{{ $file }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            {{-- Digital Signature --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    Upload Signature <span class="text-danger">*</span>
                                </label>

                                <!-- file input -->
                                <input type="file" name="digital_signature" class="form-control"
                                    accept="image/png, image/jpg, image/jpeg" onchange="previewSignature(event)" required>

                                <small class="text-muted">
                                    Upload a clear signature image (PNG/JPG),White background preferred,Max size 200KB.
                                    This signature will be used in your text invoice.
                                </small>

                                <!-- 🔥 preview image -->
                                <img id="preview" style="display:none;height:80px;margin-top:10px;">

                                @if (isset($vendor) && $vendor->digital_signature)
                                    <div class="mt-2">
                                        <strong>Current Signature:</strong><br>
                                        <img src="{{ asset('storage/signatures/' . $vendor->digital_signature) }}"
                                            height="80">
                                    </div>
                                @endif
                            </div>


                            <!-- YAHAN TERMS WALA CODE DALNA HAI -->

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="agreed_terms" class="form-check-input" id="agreed_terms"
                                    value="1" required>

                                <label class="form-check-label" for="agreed_terms">
                                    I have read and agree to the
                                    <a href="#">Vendor Terms & Conditions</a>.
                                </label>
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('vendor.onboarding.step4') }}"
                                    class="btn btn-outline-success btn-lg px-4">
                                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                                </a>
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    Submit <i class="fa-solid fa-arrow-right ms-2"></i>
                                </button>
                            </div>

                            <p class="mt-3 text-muted small">
                                Upload KYC documents required for tax & compliance. Multiple files allowed.
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function previewSignature(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');

            if (!file) return;

            // file size check
            if (file.size > 200 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'Signature must be under 200KB'
                });
                event.target.value = "";
                preview.style.display = "none";
                return;
            }

            const img = new Image();
            const objectUrl = URL.createObjectURL(file);

            img.onload = function() {
                const width = img.width;
                const height = img.height;



                preview.src = objectUrl;
                preview.style.display = 'block';
            };

            img.src = objectUrl;
        }



        document.getElementById('pan_number').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();

            // invalid characters remove
            value = value.replace(/[^A-Z0-9]/g, '');

            let formatted = '';

            for (let i = 0; i < value.length && i < 10; i++) {
                if (i < 5 && /[A-Z]/.test(value[i])) {
                    formatted += value[i];
                } else if (i >= 5 && i < 9 && /[0-9]/.test(value[i])) {
                    formatted += value[i];
                } else if (i === 9 && /[A-Z]/.test(value[i])) {
                    formatted += value[i];
                }
            }

            e.target.value = formatted;
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            const pan = document.getElementById('pan_number').value;
            const regex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;

            if (!regex.test(pan)) {
                e.preventDefault();

                Swal.fire({
                    icon: 'error',
                    title: 'Invalid PAN',
                    text: 'Enter valid PAN format (ABCDE1234F)'
                });
            }
        });
    </script>

@endsection
