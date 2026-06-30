@extends('layouts.auth')

@section('title', 'Seller Onboarding - Step 2')

@section('content')
    <style>
        /* Halka carbon-black border for inputs */
        input.form-control {
            border: 1px solid #2b2b2b;
            /* carbon black shade */
            box-shadow: none;
            transition: border-color 0.2s ease-in-out;
        }

        input.form-control:focus {
            border-color: #000;
            /* darker when focused */
            outline: none;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
        }


/* ..................................NEW CSS............................................. */

/* Container width thodi compact */
.col-lg-7{
    max-width:650px;
}

/* Card ko premium look */
.card{
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    transition:all .25s ease;
}

/* Card hover effect */
.card:hover{
    transform:translateY(-2px);
    box-shadow:0 14px 35px rgba(0,0,0,0.12);
}

/* Card header typography */
.card-header h3{
    color:whitesmoke;
    letter-spacing:0.4px;
}

/* Card body spacing compact */
.card-body{
    padding:30px 34px;
}

/* Labels compact */
.form-label{
    font-size:13px;
    font-weight:600;
    margin-bottom:4px;
    color:#333;
    margin:0 auto 4px auto;
     display:block;
     width:90%;
}

/* Inputs compact */
.form-control{
    font-size:14px;
    padding:8px 10px;
    height:38px;
    border-radius:6px;
}

/* Input focus effect */
.form-control:focus{
    border-color:#000;
    box-shadow:0 0 4px rgba(0,0,0,0.2);
}

/* spacing between fields */
.mb-3{
    margin-bottom:12px;
}

/* buttons compact */
.btn-lg{
    padding:10px 26px;
    font-size:15px;
}
body{
    background:#f0f6ff;
}
/* progress container */
.progress{
    height:8px;
    background:#e9ecef;
    border-radius:20px;
    overflow:hidden;
}

/* progress bar */
.progress-bar{
    background:#1565c0;
    font-size:12px;
    font-weight:600;
    letter-spacing:.3px;
}

/* remove bootstrap stripes */
.progress-bar-striped{
    background-image:none !important;
}

/* remove animation */
.progress-bar-animated{
    animation:none !important;
}
/* Header color same as Step 1 */
.card-header{
    background:#2a2b2d !important;
}

/* Heading text soft white */
.card-header h3{
    color:whitesmoke !important;
    font-size:15px;
}
/* Progress container */
.progress{
    height:10px;
    background:#e9ecef;
    border-radius:20px;
    overflow:hidden;
}

/* Progress bar color same as Step 1 */
.progress-bar{
    background:linear-gradient(135deg,#007bff,#0056b3) !important;
    color:whitesmoke !important;
    font-size:12px;
    font-weight:600;
}

/* container width */
.col-lg-7{
    max-width:620px;
}

/* inputs width */
.form-control{
    width:90%;
    margin:0 auto;
}
/* button container styling */
.d-flex.justify-content-between.mt-4{
    background:#f0f6ff;
    padding:12px 16px;
    border-radius:8px;
    border:1px solid #dbe9ff;
}
/* Next Step button */
.btn-primary{
    background:#1565c0 !important;
    border-color:#1565c0 !important;
    color:whitesmoke !important;
}

/* hover effect */
.btn-primary:hover{
    background:#0d47a1 !important;
    border-color:#0d47a1 !important;
}

/* Back button */
.btn-outline-secondary{
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

/* hover same pattern */
.btn-outline-secondary:hover{
background:#1f2022 !important;
border-color:#1f2022 !important;
color:#fff !important;
}
body{
    background:linear-gradient(180deg,#f4f8ff,#eef3fb);
}

.btn-primary{
background:#2a2b2d !important;
border-color:#2a2b2d !important;
color:#fff !important;
}

button.btn-primary{
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

.btn-primary:hover,
button.btn-primary:hover{
background:#1f2022 !important;
border-color:#1f2022 !important;
color:#fff !important;
}


    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                 <img src="{{ asset('public/uploads/media/Bazaron-seller-desk-logo.png') }}" height="70"
                            style="display:block;margin:auto;" alt="Bazaron Seller Desk">
                {{-- Wizard Progress Bar --}}
                <!-- <div class="mb-4">
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar"
                            style="width: 66%;" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100">
                            Step 2 of 7
                        </div>
                    </div>
                </div> -->

               <div class="card shadow-lg border-0 rounded-3">
    <div class="card-header bg-primary text-white text-start py-2">
        <h3 class="mb-0">Contact Information</h3>
    </div>
</div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('vendor.onboarding.step2.store') }}">
                            @csrf

                            {{-- Contact Person --}}
                            <div class="mb-3">
                                <label class="form-label">Authorized Contact Person <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="contact_person" class="form-control form-control-lg"
                                    value="{{ old('contact_person', $vendor->contact_person ?? '') }}" required>
                            </div>

                            {{-- Designation --}}
                            <div class="mb-3">
                                <label class="form-label">Designation <span class="text-danger">*</span></label>
                                <input type="text" name="designation" class="form-control form-control-lg"
                                    value="{{ old('designation', $vendor->designation ?? '') }}" required>
                            </div>

                            {{-- Alternate Phone --}}
                            <div class="mb-3">
                                <label class="form-label">Alternate Phone (Optional)</label>
                                <input type="text" name="alt_phone" class="form-control form-control-lg"
                                    value="{{ old('alt_phone', $vendor->alt_phone ?? '') }}">
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('vendor.onboarding.step1') }}"
                                    class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    Next Step <i class="fa-solid fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection