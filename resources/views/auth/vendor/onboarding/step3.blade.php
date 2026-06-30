@extends('layouts.auth')

@section('title', 'Seller Onboarding - Step 3')

@section('content')
    <style>
        /* 🌑 Carbon black border for input fields */
        input.form-control {
            border: 1px solid #2b2b2b;
            /* subtle carbon black */
            box-shadow: none;
            transition: border-color 0.2s ease-in-out;
        }

        input.form-control:focus {
            border-color: #000;
            outline: none;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.25);
        }

        /* Also apply to input-group (so IFSC + button looks neat) */
        .input-group .form-control {
            border-right: none;
        }

        .input-group .btn {
            border: 1px solid #2b2b2b;
            border-left: none;
        }

        .input-group .btn:focus {
            box-shadow: none;
        }



        /* ............................NEW CSS.............................. */

        /* PAGE BACKGROUND */
body{
    background:#f0f6ff;
}

/* FORM CONTAINER WIDTH */
.col-lg-7{
    max-width:620px;
}

/* CARD STYLE */
.card{
    border-radius:14px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

/* HEADER (green → blue) */
.card-header{
    background:#2a2b2d;
}

.card-header h3{
    color:whitesmoke !important;
    letter-spacing:0.4px;
    font-size:15px;
}

/* PROGRESS BAR (green → blue) */
.progress{
    height:10px;
    border-radius:20px;
}

.progress-bar{
    background:linear-gradient(135deg,#007bff,#0056b3) !important;
    color:whitesmoke;
    font-size:12px;
    font-weight:600;
}

/* REMOVE STRIPES + ANIMATION */
.progress-bar-striped{
    background-image:none !important;
}

.progress-bar-animated{
    animation:none !important;
}

/* INPUT COMPACT SIZE */
.form-control{
    width:90%;
    margin:0 auto;
    font-size:14px;
    padding:8px 10px;
    height:38px;
    border-radius:6px;
}

/* LABEL ALIGNMENT */
.form-label{
    width:90%;
    margin:0 auto 4px auto;
    font-size:13px;
    
   
    margin-left:25px;
    margin-right:auto;
    margin-bottom:4px;
    
}


/* INPUT GROUP FIX (IFSC FIELD) */
.input-group{
    width:90%;
    margin:0 auto;
}

/* SEARCH BUTTON */
.input-group .btn{
    border-radius:0 6px 6px 0;
    color:grey;
}

/* BUTTON CONTAINER */
.d-flex.justify-content-between.mt-4{
    background:#eef4ff;
    padding:12px 16px;
    border-radius:8px;
}

/* NEXT BUTTON */
.btn-success{
background:#2a2b2d !important;
border-color:#2a2b2d !important;
color:#fff !important;
}

button.btn-success{
background:#2a2b2d !important;
border:none !important;
color:#fff !important;
padding:10px 28px;
border-radius:30px;
font-size:14px;
font-weight:500;
display:flex;
align-items:center;
gap:6px;
}

.btn-success:hover,
button.btn-success:hover{
background:#1f2022 !important;
border-color:#1f2022 !important;
color:#fff !important;
}

/* BACK BUTTON */
.btn-outline-success{
background:#2a2b2d !important;
border:none !important;
color:#fff !important;
padding:10px 28px;
border-radius:30px;
font-size:14px;
font-weight:500;
display:flex;
align-items:center;
gap:6px;
}

.btn-outline-success:hover{
background:#1f2022 !important;
border-color:#1f2022 !important;
color:#fff !important;
}
.progress{
    height:18px;
}

.progress-bar{
    display:flex;
    align-items:center;
    justify-content:center;
    color:whitesmoke !important;
    font-size:12px;
    font-weight:600;
}
.form-text{
    width:90%;
    margin:4px auto 0 auto;
    font-size:12px;
}
#bank-details-form .input-group .form-control{
    width:82%;
}

#bank-details-form .input-group .btn{
    margin-left:20px;
}

/* IFSC input group same row me */
.input-group{
width:90%;
margin:0 auto;
display:flex;
align-items:center;
}

/* input full width le */
.input-group .form-control{
flex:1;
height:38px;
}

/* search button small */
.input-group .btn{
height:34px;
padding:6px 10px;
font-size:13px;
margin-left:6px;
border-radius:6px;
background:#2a2b2d;
border:none;
color:#fff;
display:flex;
align-items:center;
justify-content:center;
}



    </style>

    @php
        $currentStep = 3;
        $totalSteps = 7;
        $progress = ($currentStep / $totalSteps) * 100;
    @endphp

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                 <img src="{{ asset('public/uploads/media/Bazaron-seller-desk-logo.png') }}" height="70"
                            style="display:block;margin:auto;" alt="Bazaron Seller Desk">


                {{-- Dynamic Wizard Progress Bar --}}
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
        <h3 class="mb-0">Bank Details</h3>
    </div>
</div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('vendor.onboarding.step3.store') }}" id="bank-details-form">
                            @csrf

                            {{-- IFSC --}}
                            <div class="mb-3">
                                <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" id="ifsc_code" name="ifsc_code"
                                        class="form-control form-control-lg text-uppercase"
                                        value="{{ old('ifsc_code', $vendor->ifsc_code ?? '') }}" maxlength="11" required>
                                    <button type="button" id="ifsc_check_btn" class="btn btn-success" title="Validate IFSC">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                                <div id="ifsc_help" class="form-text">IFSC should be 11 characters (e.g. SBIN0005943)</div>
                                <div id="ifsc_error" class="text-danger mt-2 d-none"></div>
                            </div>

                            {{-- Bank Name --}}
                            <div class="mb-3">
                                <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                <input type="text" id="bank_name" name="bank_name" class="form-control form-control-lg"
                                    value="{{ old('bank_name', $vendor->bank_name ?? '') }}" readonly required>
                            </div>

                            {{-- Branch Name --}}
                            <div class="mb-3">
                                <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                                <input type="text" id="branch_name" name="branch_name" class="form-control form-control-lg"
                                    value="{{ old('branch_name', $vendor->branch_name ?? '') }}" readonly required>
                            </div>

                            {{-- Account Holder Name --}}
                            <div class="mb-3">
                                <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                <input type="text" name="account_holder_name" class="form-control form-control-lg"
                                    value="{{ old('account_holder_name', $vendor->account_holder_name ?? '') }}" required>
                            </div>

                            {{-- Account Number --}}
                            <div class="mb-3">
                                <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                <input type="text" 
    name="account_number" 
    class="form-control form-control-lg"
    value="{{ old('account_number', $vendor->account_number ?? '') }}"
    maxlength="18"
    pattern="[0-9]{9,18}"
    inputmode="numeric"
    required>
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('vendor.onboarding.step2') }}"
                                    class="btn btn-outline-success btn-lg px-4">
                                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                                </a>
                                <button type="submit" id="submit_btn" class="btn btn-success btn-lg px-5">
                                    Next <i class="fa-solid fa-arrow-right ms-2"></i>
                                </button>
                            </div>

                            {{-- Note --}}
                            <p class="mt-3 text-muted small">
                                IFSC lookup is automatic. If branch/bank are not found, please verify IFSC or enter details
                                manually.
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- IFSC lookup script --}}
    <script>
        (function () {
            const ifscInput = document.getElementById('ifsc_code');
            const checkBtn = document.getElementById('ifsc_check_btn');
            const bankName = document.getElementById('bank_name');
            const branchName = document.getElementById('branch_name');
            const ifscError = document.getElementById('ifsc_error');
            const submitBtn = document.getElementById('submit_btn');

            function setLoading(loading) {
                if (loading) {
                    checkBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                    checkBtn.disabled = true;
                    submitBtn.disabled = true;
                    ifscError.classList.add('d-none');
                } else {
                    checkBtn.innerHTML = '<i class="fa-solid fa-magnifying-glass"></i>';
                    checkBtn.disabled = false;
                    submitBtn.disabled = false;
                }
            }

            function clearFields() {
                bankName.value = '';
                branchName.value = '';
            }

            function showError(msg) {
                ifscError.textContent = msg;
                ifscError.classList.remove('d-none');
                clearFields();
            }

            function validIfscFormat(code) {
                return /^[A-Z]{4}0[0-9A-Z]{6}$/.test(code);
            }

            function fetchIfsc(code) {
                setLoading(true);
                fetch(`https://ifsc.razorpay.com/${code}`)
                    .then(response => {
                        if (!response.ok) throw new Error('IFSC not found');
                        return response.json();
                    })
                    .then(data => {
                        bankName.value = data.BANK || '';
                        branchName.value = data.BRANCH || data.ADDRESS || '';
                        ifscError.classList.add('d-none');
                    })
                    .catch(err => {
                        console.error(err);
                        showError('IFSC not found or invalid. Please check and try again.');
                    })
                    .finally(() => setLoading(false));
            }

            checkBtn.addEventListener('click', function () {
                const code = (ifscInput.value || '').trim().toUpperCase();
                if (code.length !== 11 || !validIfscFormat(code)) {
                    showError('Please enter a valid 11 character IFSC code (format: AAAA0BBBBBB).');
                    return;
                }
                fetchIfsc(code);
            });

            ifscInput.addEventListener('blur', function () {
                const code = (this.value || '').trim().toUpperCase();
                if (code.length === 11 && validIfscFormat(code)) {
                    fetchIfsc(code);
                }
            });

            ifscInput.addEventListener('input', function () {
                this.value = this.value.toUpperCase();
                ifscError.classList.add('d-none');
                if (this.value.length < 11) clearFields();
            });

        })();

        
document.querySelector('input[name="account_number"]').addEventListener('input', function () {
    // sirf digits allow
    this.value = this.value.replace(/\D/g, '');

    // max 18 digits
    if (this.value.length > 18) {
        this.value = this.value.slice(0, 18);
    }
});

    </script>
@endsection