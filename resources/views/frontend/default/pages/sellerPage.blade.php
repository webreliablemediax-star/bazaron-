@extends('frontend.default.layouts.master')
@section('title')
    Start Selling on Bazaron
@endsection
@section('contents')
    <!-- HERO SECTION -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title mb-3">
                        {{ $sellerPage->hero_title ?? '' }}
                    </h1>
                    <p class="hero-subtitle mb-4">
                        {{ $sellerPage->hero_subtitle ?? '' }}
                    </p>
                    <a href="{{ $sellerPage->hero_button_link ?? '#' }}" class="btn btn-primary btn-lg">
                        {{ $sellerPage->hero_button_text ?? 'Create Seller Account' }}
                    </a>
                </div>
                <div class="col-lg-6 text-center">
                    @if (!empty($sellerPage->hero_image))
                        <img src="{{ uploadedAsset($sellerPage->hero_image) }}" class="img-fluid">
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- FEATURES SECTION -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                @foreach ($features as $feature)
                    <div class="col-md-3 mb-4">
                        <div class="card border-0 shadow-sm h-100 text-center p-4">
                            @if ($feature->icon)
                                <i class="fa-solid {{ $feature->icon }} feature-icon"></i>
                            @endif
                            <h5 class="fw-bold">
                                {{ $feature->title }}
                            </h5>
                            <p class="text-muted">
                                {{ $feature->description }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- WHY CHOOSE US -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="fw-bold mb-2">
                {{ $whyChoose->section_title ?? '' }}
            </h2>
            <p class="text-muted mb-4" style="max-width:1800px; margin:auto;">
    {{ $whyChoose->section_description ?? '' }}
</p>
            <div class="row">
                @foreach ($whyPoints as $point)
                    <div class="col-md-4 mb-3">
                        <div class="why-box">
                            {{ $point->title }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- STEPS SECTION -->
    <!-- STEPS SECTION -->
    <section class="py-5">
        <div class="container text-center">

            <h2 class="fw-bold mb-5">
                How Selling on Bazaron Works
            </h2>
            <p class="text-muted mb-4"
   style="max-width:1000px; margin:auto;">
    {{ $sellerPage->steps_description ?? '' }}
</p>

            <div class="row">
                @foreach ($steps as $step)
                    <div class="col-lg-3 col-md-6 mb-4">

                        <div class="step-card">

                            @if ($step->image)
                                <img src="{{ uploadedAsset($step->image) }}" class="img-fluid step-image mb-4">
                            @endif

                            <h3 class="step-number">
                                Step {{ $step->step_number }}
                            </h3>

                            <h5 class="fw-bold mb-3">
                                {{ $step->title }}
                            </h5>

                            <p class="step-desc">
                                {{ $step->description }}
                            </p>

                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </section>
    <!-- PRICING SECTION -->
    <!-- PRICING SECTION -->
    <section class="py-5 bg-light">
        <div class="container">

            <h2 class="pricing-title text-center mb-5">
                {{ $pricing[0]->section_title ?? '' }}
            </h2>
            <p class="text-muted mb-4"
   style="max-width:1000px; margin:auto;">
    {{ $sellerPage->pricing_description ?? '' }}
</p>

            <div class="pricing-box">

                <div class="row">

                    <!-- Left -->
                    <div class="col-md-4 offset-md-2">
                        <h2 class="pricing-heading mb-4">Features</h2>
                        <p class="text-muted mb-4"
   style="max-width:1000px; margin:auto;">
    {{ $sellerPage->features_description ?? '' }}
</p>

                        @foreach ($pricing as $row)
                            <div class="pricing-item">
                                <i class="fa-regular fa-square-check"></i>
                                {{ $row->feature_name }}
                            </div>
                        @endforeach

                    </div>

                    <!-- Right -->
                    <div class="col-md-5">
                        <h2 class="pricing-heading mb-4">Cost</h2>

                        @foreach ($pricing as $row)
                            <div class="pricing-item">
                                <i class="fa-solid fa-arrow-right"></i>
                                {{ $row->feature_value }}
                            </div>
                        @endforeach

                        <div class="pricing-note mt-5">
                            No hidden charges. Only pay commission when you make a sale.
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>
    <!-- DOCUMENTATION -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="fw-bold mb-3">
                {{ $documentation->section_title ?? '' }}
            </h2>
            <p class="text-muted mb-5">
                {{ $documentation->section_description ?? '' }}
            </p>
            <div class="row">
                @foreach ($documentationPoints as $point)
                    <div class="col-md-4 mb-3">
                       <div class="p-3 border rounded bg-white">
    <i class="fa-solid {{ $point->icon }}"></i>
    {{ $point->title }}
</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- CTA SECTION -->
  <section class="cta-section py-5">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6">
                <h2 class="fw-bold mb-3">
                    {{ $sellerPage->cta_title ?? '' }}
                </h2>

                <p class="mb-4">
                    {{ $sellerPage->cta_description ?? '' }}
                </p>

                <a href="{{ $sellerPage->cta_button_link ?? '#' }}"
                   class="btn btn-dark btn-lg rounded-pill px-5">
                    {{ $sellerPage->cta_button_text ?? '' }}
                </a>
            </div>

            <div class="col-lg-6 text-center">
                @if(!empty($sellerPage->cta_image))
                    <img src="{{ uploadedAsset($sellerPage->cta_image) }}"
                         class="img-fluid cta-image">
                @endif
            </div>

        </div>
    </div>
</section>
     <!-- CTA SECTION -->
    
@endsection
<style>
    .hero-title {
        font-size: 75px;
        font-weight: 500;
        line-height: 1.1;
        letter-spacing: -1px;
        color: #0F1111;
        max-width: 650px;
    }

    .hero-subtitle {
        font-size: 18px;
        line-height: 1.6;
        color: #565959;
        max-width: 700px;
    }

    .py-5.text-white p {
        color: #333 !important;
    }

    .feature-icon {
        font-size: 50px;
        margin-bottom: 15px;
        color: #000;
        display: block;
    }

    .py-5.bg-light .bg-white {
        font-weight: 600;
        font-size: 16px;
        color: #0F1111;
    }

    .why-box {
        background: #ebeaeaff;
        border-radius: 6px;
        padding: 35px 20px;
        /* 👈 height increase */
        min-height: 100px;
        /* 👈 box aur bada */
        height: 120px;
        /* 🔥 FIX: fixed height */
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 18px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .why-box:hover {
        background: #cfcfcf;
    }

    /* spacing improve */
    #why-wrapper .col-md-4 {
        margin-bottom: 20px;
    }

    /* hover effect (optional but sexy 😎) */
    .py-5.bg-light .container {
        max-width: 1100px;
    }

    /* WHY SECTION HEADING */
    .py-5.bg-light h2 {
        font-weight: 700;
        /* 👈 bold */
        letter-spacing: 1px;
        /* 👈 words gap */
        margin-bottom: 10px;
    }

    /* DESCRIPTION 2 LINE FIX */
    .py-5.bg-light p.text-muted {
        max-width: 750px;
        /* 👈 width control */
        margin: auto;
        font-size: 16px;
        line-height: 1.7;
        color: #444;
        text-align: center;
    }

    .step-card {
        background: #ececec;
        /* grey card */
        padding: 30px 25px;
        border-radius: 8px;
        height: 100%;
        transition: .3s;
    }

    .step-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, .1);
    }

   .step-image{
    width: 100%;
    max-width: 300px;
    height: 300px;
    object-fit: contain;
    background: #fff;
    padding: 6px;
    border-radius: 0;
}

    .step-number {
        font-size: 24px;
        font-weight: 700;
        color: #000;
        margin-bottom: 25px;
    }

    .step-card h5 {
        font-size: 20px;
        color: #000;
    }

    .step-desc {
        color: #555;
        line-height: 1.6;
        font-size: 17px;
    }

    .pricing-title {
        font-size: 24px;
        font-weight: 400;
        color: #111827;
    }

    .pricing-box {
        background: #ececec;
        padding: 10px 0px;
    }

    .pricing-heading {
        font-size: 24px;
        font-weight: 400;
        color: #000;
    }

    .pricing-item {
        font-size: 16px;
        color: #111;
        padding: 6px 0;
        border-bottom: 1px solid #d5d5d5;
    }

    .pricing-item i {
        margin-right: 15px;
    }

    .pricing-note {
        font-size: 18px;
        font-weight: 300;
        color: #111;
    }

    @media(max-width:768px) {

        .pricing-box {
            padding: 30px;
        }

        .pricing-title {
            font-size: 32px;
        }

        .pricing-heading {
            font-size: 35px;
        }

        .pricing-item {
            font-size: 18px;
        }

    }
.cta-section{
    position: relative;
    overflow: hidden;
    background: #f5f4f2;
    padding:100px 0;
}

.cta-section::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:180px;
    background:#e9e6e2;
    border-bottom-left-radius:50% 100px;
    border-bottom-right-radius:50% 100px;
    z-index:0;
}

.cta-section .container{
    position:relative;
    z-index:2;
}

.cta-image{
    max-height:500px;
    object-fit:contain;
}



</style>
