        @extends('layouts.auth')
        <link rel="shortcut icon" href="{{ uploadedAsset(getSetting('favicon')) }}">

        @section('title')
            {{ localize('Sign Up') }}
        @endsection


        @section('contents')
            @include('frontend/default/inc/header')
            <section class="login-section">
                <div class="container-fluid p-0 m-0">
                    <div class="login-row">
                        {{-- todo:: make banner dynamic --}}
                        <div class="tt-login-img"
                            style="background-image:url('{{ uploadedAsset(getSetting('login_banner')) ?? staticAsset('frontend/default/assets/img/banner/banner2.jpeg') }}')">
                        </div>
                        <div class="tt-login-col shadow">
                            <form class="tt-login-form-wrap w-100 " action="{{ route('register') }}" method="POST"
                                id="login-form">
                                @csrf

                                {!! RecaptchaV3::field('recaptcha_token') !!}
                                @if (session()->has('flash_notification'))
                                    @include('flash::message')
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger mb-3">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <h2 class="mb-4 h3">
                                    <br>{{ localize('Register as a Customer.') }}
                                </h2>

                                <div class="row g-3">
                                    <div class="col-sm-12">
                                        <div class="input-field">
                                            <label class="fw-bold text-dark fs-sm mb-1">{{ localize('Full name') }}<sup
                                                    class="text-danger">*</sup>
                                            </label>
                                            <input type="text" id="name" name="name"
                                                placeholder="{{ localize('Enter your name') }}" class="theme-input"
                                                value="{{ old('name') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="input-field">
                                            <label class="fw-bold text-dark fs-sm mb-1">
                                                @if (getSetting('registration_with') == 'email_and_phone')
                                                    {{ localize('Phone') }}<sup class="text-danger">*</sup>
                                                @else
                                                    {{ localize('Phone') }}
                                                @endif
                                                <small>({{ localize('Enter phone number with country code') }})</small>
                                            </label>
                                            <div style="display:flex;align-items:center;">

                                                <span
                                                    style="
background:#f5f5f5;
padding:6px 10px;
border:1px solid #e5e5e5;
border-right:none;
border-radius:8px 0 0 8px;
font-size:13px;
">
                                                    +91
                                                </span>

                                                <input type="text" id="phone" name="phone" class="theme-input"
                                                    style="border-radius:0 8px 8px 0;" placeholder="XXXXXXXXXX"
                                                    value="{{ old('phone') }}" pattern="[0-9]{10}" maxlength="10"
                                                    inputmode="numeric" @if (getSetting('registration_with') == 'email_and_phone') required @endif>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="input-field">
                                            <label class="fw-bold text-dark fs-sm mb-1">{{ localize('Email') }}<sup
                                                    class="text-danger">*</sup></label>
                                            <input type="email" id="email" name="email"
                                                placeholder="{{ localize('Enter your email') }}" class="theme-input"
                                                value="{{ old('email') }}" pattern="[^@\s]+@[^@\s]+\.[^@\s]+"
                                                title="Please enter a valid email address" required>
                                        </div>
                                    </div>



                                    <div class="col-sm-12">
                                        <div class="input-field check-password">
                                            <label class="fw-bold text-dark fs-sm mb-1">{{ localize('Password') }}<sup
                                                    class="text-danger">*</sup></label>
                                            <div class="check-password">
                                                <input type="password" name="password"
                                                    placeholder="{{ localize('Password') }}" class="theme-input" required>
                                                <span class="eye eye-icon"><i class="fa-solid fa-eye"></i></span>
                                                <span class="eye eye-slash"><i class="fa-solid fa-eye-slash"></i></span>
                                            </div>
                                            <!-- Password hint -->
                                            <small class="text-muted">
                                                Password must be at least 6 characters.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="input-field check-password">
                                            <label
                                                class="fw-bold text-dark fs-sm mb-1">{{ localize('Confirm Password') }}<sup
                                                    class="text-danger">*</sup></label>
                                            <div class="check-password">
                                                <input type="password" name="password_confirmation"
                                                    placeholder="{{ localize('Confirm Password') }}" class="theme-input"
                                                    required>
                                                <span class="eye eye-icon"><i class="fa-solid fa-eye"></i></span>
                                                <span class="eye eye-slash"><i class="fa-solid fa-eye-slash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4 mt-3">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary w-100 sign-in-btn"
                                            onclick="handleSubmit()">{{ localize('Sign Up') }}</button>
                                    </div>

                                </div>
                                <p class="mb-0 fs-xs mt-4">{{ localize('Already have an Account?') }} <a
                                        href="{{ route('login') }}">{{ localize('Sign In') }}</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        @endsection

        @section('scripts')
            <script>
                "use strict";

                // disable login button
                function handleSubmit() {
                    $('#login-form').on('submit', function(e) {
                        $('.sign-in-btn').prop('disabled', true);
                    });
                }
            </script>
            @include('frontend/default/inc/footer')
        @endsection
        <style>
            /* register page compact layout */

            /* FULL SCREEN LAYOUT */

            /* REMOVE ANY PAGE PADDING */

            body {
                margin: 0;
                padding: 0;
            }

            /* FULL SCREEN */

            .container-fluid {
                padding: 0 !important;
                margin: 0 !important;
            }

            /* FLEX ROW */

            .login-section {
                padding: 0 !important;
                margin: 0;
                width: 100%;
                display: block !important;
                min-height: auto !important;
            }

            .login-row {
                display: flex;
                width: 100%;
                height: calc(100vh - 70px);
                /* header height */
                align-items: stretch;
            }

            /* LEFT SIDE */

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
                padding: 0;
            }

            /* FORM CARD */

            .tt-login-form-wrap {
                background: white;
                padding: 22px 28px;
                border-radius: 14px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                max-width: 460px;
                width: 100%;
            }

            /* INPUT */

            .theme-input {
                height: 42px;
                font-size: 14px;
                padding: 8px 10px;
                border-radius: 8px;
            }

            /* BUTTON */

            .sign-in-btn {
                height: 38px;
                font-size: 14px;
                border-radius: 8px;
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
                    padding: 15px;
                    align-items: flex-start;
                    justify-content: flex-start;
                }

                .tt-login-form-wrap {
                    max-width: 100%;
                    width: 100%;
                    margin-top: 0 !important;
                    padding: 20px;
                    border-radius: 12px;
                    box-shadow: none;
                }

                .tt-login-form-wrap h2 {
                    margin-top: 0 !important;
                    padding-top: 0 !important;
                    font-size: 32px;
                    line-height: 1.2;
                }

                .theme-input {
                    height: 48px;
                }

                .sign-in-btn {
                    height: 50px;
                    font-size: 16px;
                }
            }
        </style>
