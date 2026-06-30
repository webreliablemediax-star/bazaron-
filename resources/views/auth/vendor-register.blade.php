@extends('layouts.auth')
<link rel="shortcut icon" href="{{ uploadedAsset(getSetting('favicon')) }}">
@section('title')
    {{ localize('Bazaron.in - Seller Signup') }}
@endsection

@section('contents')
    @include('frontend/default/inc/header')

    <section class="login-section">

        <div class="container-fluid p-0 m-0">

            <div class="login-row">

                {{-- LEFT IMAGE --}}
                <div class="tt-login-img"
                    style="background-image:url('{{ getSetting('vendor_register_banner') ? uploadedAsset(getSetting('vendor_register_banner')) : staticAsset('frontend/default/assets/img/banner/banner2.jpg') }}')">
                </div>

                {{-- RIGHT FORM --}}
                <div class="tt-login-col shadow">

                    <div class="tt-login-form-wrap w-100" style="margin-top:10%;">
                        <img src="{{ asset('public/uploads/media/Bazaron-seller-desk-logo.png') }}" height="70"
                            style="display:block;margin:auto;" alt="Bazaron Seller Desk">
                        <form action="{{ route('vendor.register.submit') }}" method="POST" id="vendor-register-form">

                            @csrf
                            @if ($errors->any())
                                <div style="background:#ffe6e6;padding:10px;margin-bottom:15px;border:1px solid red;">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {!! RecaptchaV3::field('recaptcha_token') !!}


                            <!--<h2 class="vendor-title">-->
                            <!--    Create your Seller Account-->
                            <!--</h2>-->

                            <p class="vendor-subtitle">
                                Start selling on Bazaron and reach customers across India.
                            </p>


                            <div class="form-fields">

                                {{-- NAME --}}
                                <div class="mb-3">
                                    <label class="vendor-label">
                                        {{ localize('Company name') }} <span>*</span>
                                    </label>

                                    <input type="text" name="name" class="vendor-input"
                                        placeholder="{{ localize('Enter your name') }}" value="{{ old('name') }}"
                                        required>
                                </div>


                                {{-- PHONE --}}
                                <div class="mb-3">

                                    <label class="vendor-label">

                                        @if (getSetting('registration_with') == 'email_and_phone')
                                            {{ localize('Phone') }} <span>*</span>
                                        @else
                                            {{ localize('Phone') }}
                                        @endif



                                    </label>

                                    <div class="phone-group">

                                        <span
                                            style="
                                            background:#f5f5f5;
                                            padding:8px 10px;
                                            border:1px solid #e5e5e5;
                                            border-right:none;
                                            border-radius:8px 0 0 8px;
                                            font-size:13px;
                                            ">
                                            +91
                                        </span>

                                        <input type="text" name="phone" class="vendor-input"
                                            style="border-radius:0 8px 8px 0;" placeholder="XXXXXXXXXX"
                                            value="{{ old('phone') }}" pattern="[0-9]{10}" maxlength="10"
                                            inputmode="numeric" required>

                                    </div>

                                </div>


                                {{-- EMAIL --}}
                                <div class="mb-3">

                                    <label class="vendor-label">
                                        {{ localize('Email') }} <span>*</span>
                                    </label>

                                    <input type="email" name="email" class="vendor-input"
                                        placeholder="{{ localize('Enter your email') }}" value="{{ old('email') }}"
                                        pattern="[^@\s]+@[^@\s]+\.[^@\s]+" title="Please enter a valid email address"
                                        required>

                                </div>





                                {{-- PASSWORD --}}
                                <div class="mb-3">

                                    <label class="vendor-label">
                                        {{ localize('Password') }} <span>*</span>
                                    </label>

                                    <div class="password-wrapper">

                                        <input type="password" name="password" class="vendor-input password-input"
                                            placeholder="{{ localize('Password') }}" required>

                                        <i class="fa-solid fa-eye password-toggle"></i>

                                    </div>
                                    <small style="color:#777;font-size:11px;">
                                        Password must be at least 8 characters
                                    </small>
                                </div>


                                {{-- CONFIRM PASSWORD --}}
                                {{-- <div class="mb-3">

                                    <label class="vendor-label">
                                        {{ localize('Confirm Password') }} <span>*</span>
                                    </label>

                                    <div class="password-wrapper">

                                        <input type="password" name="password_confirmation"
                                            class="vendor-input password-input"
                                            placeholder="{{ localize('Confirm Password') }}" required>

                                        <i class="fa-solid fa-eye password-toggle"></i>

                                    </div>

                                </div> --}}

                            </div>


                            <input type="hidden" name="user_type" value="vendor">


                            <div class="mt-4 mb-2">
                                <button type="submit" class="vendor-register-btn w-100 sign-in-btn">

                                    {{ localize('Register as Seller') }}

                                </button>

                            </div>


                            <p class="vendor-login-text">

                                {{ localize('Already have a Seller Account?') }}

                                <a href="{{ route('login') }}">
                                    {{ localize('Login') }}
                                </a>

                            </p>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </section>
@endsection



@section('scripts')
    <script>
        function handlevendorsubmit() {

            "use strict";

            $('#vendor-register-form').on('submit', function() {
                $('.sign-in-btn').prop('disabled', true);
            });

        }


        /* PASSWORD SHOW / HIDE */

        document.querySelectorAll('.password-toggle').forEach(function(icon) {

            icon.addEventListener('click', function() {

                let input = this.previousElementSibling;

                if (input.type === "password") {
                    input.type = "text";
                    this.classList.remove("fa-eye");
                    this.classList.add("fa-eye-slash");
                } else {
                    input.type = "password";
                    this.classList.remove("fa-eye-slash");
                    this.classList.add("fa-eye");
                }

            });

        });
    </script>

    @include('frontend/default/inc/footer')
@endsection

<style>
    /* FULL SCREEN */

    body {
        margin: 0;
        padding: 0;
    }

    .container-fluid {
        padding: 0 !important;
        margin: 0 !important;
    }

    .login-section {
        padding: 0 !important;
        margin: 0;
        width: 100%;
        display: block !important;
    }

    /* SPLIT LAYOUT */

    .login-row {
        display: flex;
        width: 100%;
        height: calc(100vh - 70px);
        align-items: stretch;
    }

    /* LEFT IMAGE */

    .tt-login-img {
        flex: 0 0 50%;
        max-width: 50%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    /* RIGHT SIDE */

    .tt-login-col {
        flex: 0 0 50%;
        max-width: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f7;
    }

    /* FORM CARD */

    .tt-login-form-wrap {
        background: white;
        padding: 22px 28px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        max-width: 420px;
        width: 100%;
    }

    /* INPUT SAME AS LOGIN */

    .vendor-input {
        width: 100%;
        height: 42px;
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #e5e5e5;
    }

    .vendor-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 6px;
        color: #333;
    }

    /* BUTTON SAME STYLE */

    .vendor-register-btn {
        height: 42px;
        font-size: 14px;
        border-radius: 8px;
        background: #ff7a00;
        color: #fff;
        border: none;
        font-weight: 600;
        margin-bottom: 12px;
        /* 🔥 ADD THIS */
    }

    .vendor-login-text {
        font-size: 13px;
        margin-top: 10px;
        line-height: 1.5;
        text-align: center;
    }

    .phone-group {
        display: flex;
        width: 100%;
    }

    .phone-group span {
        background: #f5f5f5;
        padding: 8px 10px;
        border: 1px solid #e5e5e5;
        border-right: none;
        border-radius: 8px 0 0 8px;
        font-size: 13px;
    }

    .phone-group input {
        flex: 1;
        border-radius: 0 8px 8px 0;
    }

    .password-wrapper {
        position: relative;
    }

    .vendor-title {
        font-size: 24px;
        font-weight: 600;
    }


    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #777;
    }

    .password-input {
        padding-right: 40px;
    }



    /* MOBILE */

    @media (max-width:900px) {

        .tt-login-img {
            display: none;
        }

        .login-row {
            height: auto !important;
            min-height: auto !important;
        }

        .tt-login-col {
            flex: 0 0 100%;
            max-width: 100%;
            padding: 20px 15px;
            align-items: flex-start;
        }

        .tt-login-form-wrap {
            max-width: 100%;
            margin-top: 0 !important;
            padding: 20px;
            border-radius: 12px;
            box-shadow: none;
        }

        .vendor-title {
            font-size: 24px;
            line-height: 1.2;
            margin-bottom: 8px;
        }

        .vendor-subtitle {
            font-size: 15px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .vendor-input {
            height: 48px;
            font-size: 15px;
        }

        .vendor-register-btn {
            height: 50px;
            font-size: 16px;
        }
    }
</style>
