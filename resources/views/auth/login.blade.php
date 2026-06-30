@extends('layouts.auth')
<link rel="shortcut icon" href="{{ uploadedAsset(getSetting('favicon')) }}">
@section('title')
{{ localize('Login') }}
@endsection
@section('contents')
@include('frontend/default/inc/header')
<section class="login-section">
   <div class="container-fluid p-0 m-0">
      <div class="login-row">
         <div class="tt-login-img"
            style="background-image:url('{{ uploadedAsset(getSetting('login_banner')) ?? staticAsset('frontend/default/assets/img/banner/banner2.jpg') }}')"></div>
         <div class="tt-login-col shadow">
            <form class="tt-login-form-wrap p-3 p-md-4 p-lg-4 py-4 w-100" action="{{ route('login') }}" method="POST"
               id="login-form">
               @csrf
               <!--{!! RecaptchaV3::field('recaptcha_token') !!}-->
               <!--<div class="">-->
               <!--    <a href="{{ route('home') }}">-->
               <!--        <img src="{{ uploadedAsset(getSetting('navbar_logo')) }}" alt="logo" style=" width: 50%;">-->
               <!--    </a>-->
               <!--</div>-->
               <h4 class="mb-4 h3">
                  {{ getSetting('login_title') ?? localize('Welcome back to Bazaron.') }}
               </h4>
               <div class="row g-3">
                  <div class="col-sm-12">
                     <div class="input-field">
                        <input type="hidden" name="login_with" class="login_with" value="email">
                        <span class="login-email @if (old('login_with') == 'phone') d-none @endif">
                        <label class="fw-bold text-dark fs-sm mb-1">{{ localize('Email') }}</label>
                        <input type="email" id="email" name="email"
                           placeholder="{{ localize('Enter your email') }}" class="theme-input mb-1"
                           value="{{ old('email') }}" required>
                        <small class="">
                        <a href="javascript:void(0);" class="fs-sm login-with-phone-btn"
                           onclick="handleLoginWithPhone()">
                        {{ localize('Login with phone?') }}</a>
                        </small>
                        </span>
                        <span class="login-phone @if (old('login_with') == 'email' || old('login_with') == '') d-none @endif">
                        <label class="fw-bold text-dark fs-sm mb-1">{{ localize('Phone') }}</label>
                        <input type="text" id="phone" name="phone" placeholder="+xxxxxxxxxx"
                           class="theme-input mb-1" value="{{ old('phone') }}">
                        <small class="">
                        <a href="javascript:void(0);" class="fs-sm login-with-email-btn"
                           onclick="handleLoginWithEmail()">
                        {{ localize('Login with email?') }}</a>
                        </small>
                        </span>
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <div class="input-field check-password">
                        <label class="fw-bold text-dark fs-sm mb-1">{{ localize('Password') }}</label>
                        <div class="check-password">
                           <input type="password" name="password" id="password"
                              placeholder="{{ localize('Password') }}" class="theme-input" required>
                           <span class="eye eye-icon"><i class="fa-solid fa-eye"></i></span>
                           <span class="eye eye-slash"><i class="fa-solid fa-eye-slash"></i></span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="d-flex align-items-center justify-content-between mt-4">
                  <div class="checkbox d-inline-flex align-items-center gap-2">
                     <div class="theme-checkbox flex-shrink-0">
                        <input type="checkbox" id="save-password">
                        <span class="checkbox-field"><i class="fa-solid fa-check"></i></span>
                     </div>
                     <label for="save-password" class="fs-sm"> {{ localize('Remember me') }}</label>
                  </div>
                  <a href="{{ route('password.request') }}" class="fs-sm">{{ localize('Forgot Password') }}</a>
               </div>
               @if (env('DEMO_MODE') == 'On')
               <div class="row mt-5">
                  <div class="col-12">
                     <label class="fw-bold">Admin Access</label>
                     <div
                        class="d-flex flex-wrap align-items-center justify-content-between border-bottom pb-3">
                        <small>admin@themetags.com</small>
                        <small>123456</small>
                        <button class="btn btn-sm btn-secondary py-0 px-2" type="button"
                           onclick="copyAdmin()">Copy</button>
                     </div>
                  </div>
                  <div class="col-12 mt-3">
                     <label class="fw-bold">Customer Access</label>
                     <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <small>customer@themetags.com</small>
                        <small>123456</small>
                        <button class="btn btn-sm btn-secondary py-0 px-2" type="button"
                           onclick="copyCustomer()">Copy</button>
                     </div>
                  </div>
               </div>
               @endif
               <div class="row g-4 mt-3">
                  <div class="col-sm-12">
                     <button type="submit" class="btn btn-primary w-100 sign-in-btn" style="margin-top: -20px;">
                     {{ localize('Sign In') }}
                     </button>
                  </div>
               </div>
               <div class="row g-4 mt-3">
                  <!--social login-->
                  @include('frontend.default.inc.social')
                  <!--social login-->
               </div>
               <p class="mb-0 fs-xs mt-3">{{ localize("Don't have an Account?") }} <a
                  href="{{ route('register') }}">{{ localize('Sign Up') }}</a></p>
               <p class="mt-2 register-links">
                  <!-- <a href="{{ route('register') }}">Register as Customer</a> | -->
                  <a href="{{ route('vendor.register') }}">Become a Seller</a>
               </p>
            </form>
         </div>
      </div>
   </div>
</section>
@endsection
@section('scripts')
<script>
   "use strict";
   
   // copyAdmin
   function copyAdmin() {
       $('#email').val('admin@themetags.com');
       $('#password').val('123456');
   }
   
   // copyCustomer
   function copyCustomer() {
       $('#email').val('customer@themetags.com');
       $('#password').val('123456');
   }
   
   // change input to phone
   function handleLoginWithPhone() {
       $('.login_with').val('phone');
   
       $('.login-email').addClass('d-none');
       $('.login-email input').prop('required', false);
   
       $('.login-phone').removeClass('d-none');
       $('.login-phone input').prop('required', true);
   }
   
   // change input to email
   function handleLoginWithEmail() {
       $('.login_with').val('email');
       $('.login-email').removeClass('d-none');
       $('.login-email input').prop('required', true);
   
       $('.login-phone').addClass('d-none');
       $('.login-phone input').prop('required', false);
   }
   
   
   // disable login button
   $(document).ready(function(){
   $('#login-form').on('submit', function(){
   $('.sign-in-btn').prop('disabled', true);
   });
   });
</script>
@include('frontend/default/inc/footer')
@endsection
<style>
   body{
   margin:0;
   padding:0;
   }
   .container-fluid{
   padding:0 !important;
   margin:0 !important;
   }
   .login-section{
   padding:0 !important;
   margin:0;
   width:100%;
   display:block !important;
   min-height:auto !important;
   }
   .login-row{
   display:flex;
   width:100%;
   height:calc(100vh - 70px);
   align-items:stretch;
   }
   .tt-login-img{
   flex:0 0 50%;
   max-width:50%;
   height:100%;
   background-size:cover;
   background-position:center;
   background-repeat:no-repeat;
   }
   .tt-login-col{
   flex:0 0 50%;
   max-width:50%;
   display:flex;
   align-items:center;
   justify-content:center;
   background:#f3f4f7;
   padding:0;
   }
   .tt-login-form-wrap{
   background:white;
   padding:22px 28px;
   border-radius:14px;
   box-shadow:0 10px 25px rgba(0,0,0,0.08);
   max-width:460px;
   width:100%;
   }
   .theme-input{
   height:42px;
   font-size:14px;
   padding:8px 10px;
   border-radius:8px;
   }

   /* MOBILE */

   
  @media (max-width:900px){

    .tt-login-img{
        display:none;
    }

    .login-row{
        height:auto !important;
        min-height:auto !important;
    }

    .tt-login-col{
        flex:0 0 100%;
        max-width:100%;
        padding:15px;
        align-items:flex-start;
        justify-content:flex-start;
    }

    .tt-login-form-wrap{
        width:100%;
        max-width:100%;
        padding:20px;
        margin-top:0 !important;
        border-radius:12px;
        box-shadow:none;
    }
}
</style>