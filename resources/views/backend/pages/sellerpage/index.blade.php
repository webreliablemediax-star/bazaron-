@extends('backend.layouts.master')
@section('title')
    Seller Page {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection
@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body">
                            <h5 class="mb-0">Seller Page Settings</h5>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.seller-page.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <!-- HERO SECTION -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Hero Section</h5>
                                <div class="mb-3">
                                    <label class="form-label">Hero Title</label>
                                    <input type="text" name="hero_title" class="form-control"
                                        value="{{ $sellerPage->hero_title ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hero Subtitle</label>
                                    <textarea name="hero_subtitle" class="form-control">{{ $sellerPage->hero_subtitle ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Button Text</label>
                                    <input type="text" name="hero_button_text" class="form-control"
                                        value="{{ $sellerPage->hero_button_text ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Button Link</label>
                                    <input type="text" name="hero_button_link" class="form-control"
                                        value="{{ $sellerPage->hero_button_link ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hero Image</label>
                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">Choose Hero Image</span>
                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                onclick="showMediaManager(this)" data-selection="single">
                                                <input type="hidden" name="hero_image"
                                                    value="{{ $sellerPage->hero_image ?? '' }}">
                                                <div class="no-avatar rounded-circle">
                                                    <span><i data-feather="plus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- HERO SECTION END -->
                        <!-- FEATURES SECTION -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Features</h5>
                                <div class="mb-3">
    <label>Section Description</label>
    <textarea name="features_description"
        class="form-control">{{ $sellerPage->features_description ?? '' }}</textarea>
</div>
                                <div id="features-wrapper">
                                    @if (isset($features) && count($features))
                                        @foreach ($features as $key => $feature)
                                            <div class="feature-item border rounded p-3 mb-3">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>Feature Icon</label>
                                                        <select name="features[{{ $key }}][icon]"
                                                            class="form-control">
                                                            <option value="fa-user-check"
                                                                {{ $feature->icon == 'fa-user-check' ? 'selected' : '' }}>Easy
                                                                Registration</option>
                                                            <option value="fa-coins"
                                                                {{ $feature->icon == 'fa-coins' ? 'selected' : '' }}>Low
                                                                Commission</option>
                                                            <option value="fa-shield-halved"
                                                                {{ $feature->icon == 'fa-shield-halved' ? 'selected' : '' }}>
                                                                Secure Payments</option>
                                                            <option value="fa-flag"
                                                                {{ $feature->icon == 'fa-flag' ? 'selected' : '' }}>Nationwide
                                                                Customers</option>
                                                            <option value="fa-truck"
                                                                {{ $feature->icon == 'fa-truck' ? 'selected' : '' }}>Fast
                                                                Delivery</option>
                                                            <option value="fa-store"
                                                                {{ $feature->icon == 'fa-store' ? 'selected' : '' }}>Marketplace
                                                            </option>
                                                            <option value="fa-headset"
    {{ $feature->icon == 'fa-headset' ? 'selected' : '' }}>
    Marketing Support
</option>

<option value="fa-user-headset"
    {{ $feature->icon == 'fa-user-headset' ? 'selected' : '' }}>
    Dedicated Seller Support
</option>

<option value="fa-cloud-arrow-up"
    {{ $feature->icon == 'fa-cloud-arrow-up' ? 'selected' : '' }}>
    Product Upload
</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Title</label>
                                                        <input type="text" name="features[{{ $key }}][title]"
                                                            class="form-control" value="{{ $feature->title ?? '' }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Description</label>
                                                        <input type="text"
                                                            name="features[{{ $key }}][description]"
                                                            class="form-control" value="{{ $feature->description ?? '' }}">
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label>Order</label>
                                                        <input type="number"
                                                            name="features[{{ $key }}][display_order]"
                                                            class="form-control"
                                                            value="{{ $feature->display_order ?? '' }}">
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <button type="button"
                                                            class="btn btn-danger remove-feature">X</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" id="add-feature">
                                    + Add Feature
                                </button>
                            </div>
                        </div>
                        <!-- FEATURE SECTION END -->
                        <!-- WHY CHOOSE US SECTION -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Why Choose Us</h5>
                                <div class="mb-3">
                                    <label>Section Title</label>
                                    <input type="text" name="why_choose_title" class="form-control"
                                        value="{{ $whyChoose->section_title ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label>Description</label>
                                    <textarea name="why_choose_description" class="form-control">{{ $whyChoose->section_description ?? '' }}</textarea>
                                </div>
                                <h6 class="mt-4">Bullet Points</h6>
                                <div id="why-wrapper">
                                    @if (isset($whyPoints) && count($whyPoints))
                                        @foreach ($whyPoints as $key => $point)
                                            <div class="why-item border rounded p-3 mb-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Point Title</label>
                                                        <input type="text"
                                                            name="why_points[{{ $key }}][title]"
                                                            class="form-control" value="{{ $point->title ?? '' }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Order</label>
                                                        <input type="number"
                                                            name="why_points[{{ $key }}][display_order]"
                                                            class="form-control"
                                                            value="{{ $point->display_order ?? '' }}">
                                                    </div>
                                                    <div class="col-md-3 d-flex align-items-end">
                                                        <button type="button"
                                                            class="btn btn-danger remove-why">X</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" id="add-why">
                                    + Add Bullet Point
                                </button>
                            </div>
                        </div>
                        <!-- WHY CHOOSE US END -->
                        <!-- STEPS SECTION -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Steps (How it Works)</h5>
                                <div class="mb-3">
    <label>Section Description</label>
    <textarea name="steps_description"
        class="form-control">{{ $sellerPage->steps_description ?? '' }}</textarea>
</div>
                                <div id="steps-wrapper">
                                    @if (isset($steps) && count($steps))
                                        @foreach ($steps as $key => $step)
                                            <div class="step-item border rounded p-3 mb-3">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label>Step Number</label>
                                                        <input type="number"
                                                            name="steps[{{ $key }}][step_number]"
                                                            class="form-control" value="{{ $step->step_number ?? '' }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Title</label>
                                                        <input type="text" name="steps[{{ $key }}][title]"
                                                            class="form-control" value="{{ $step->title ?? '' }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Step Image</label>
                                                        <div class="tt-product-thumb show-selected-files mt-3">

                                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                                data-bs-toggle="offcanvas"
                                                                data-bs-target="#offcanvasBottom"
                                                                onclick="showMediaManager(this)" data-selection="single">

                                                                <input type="hidden"
                                                                    name="steps[{{ $key }}][image]"
                                                                    value="{{ $step->image ?? '' }}">

                                                                @if (!empty($step->image))
                                                                    <img src="{{ uploadedAsset($step->image) }}"
                                                                        class="rounded" width="80">
                                                                @else
                                                                    <div class="no-avatar rounded-circle">
                                                                        <span><i data-feather="plus"></i></span>
                                                                    </div>
                                                                @endif

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label>Description</label>
                                                        <input type="text"
                                                            name="steps[{{ $key }}][description]"
                                                            class="form-control" value="{{ $step->description ?? '' }}">
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <button type="button"
                                                            class="btn btn-danger remove-step">X</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" id="add-step">
                                    + Add Step
                                </button>
                            </div>
                        </div>
                        <!-- STEP SECTION END -->
                        <!-- PRICING SECTION -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Pricing</h5>
                                <div class="mb-3">
    <label>Section Description</label>
    <textarea name="pricing_description"
        class="form-control">{{ $sellerPage->pricing_description ?? '' }}</textarea>
</div>
                                <div class="mb-3">
                                    <label>Section Title</label>
                                    <input type="text" name="pricing_title" class="form-control"
                                        value="{{ $pricing[0]->section_title ?? '' }}">
                                </div>
                                <div id="pricing-wrapper">
                                    @if (isset($pricing) && count($pricing))
                                        @foreach ($pricing as $key => $row)
                                            <div class="pricing-item border rounded p-3 mb-3">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Feature Name</label>
                                                        <input type="text"
                                                            name="pricing[{{ $key }}][feature_name]"
                                                            class="form-control" value="{{ $row->feature_name ?? '' }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Feature Value</label>
                                                        <input type="text"
                                                            name="pricing[{{ $key }}][feature_value]"
                                                            class="form-control" value="{{ $row->feature_value ?? '' }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Order</label>
                                                        <input type="number"
                                                            name="pricing[{{ $key }}][display_order]"
                                                            class="form-control" value="{{ $row->display_order ?? '' }}">
                                                    </div>
                                                    <div class="col-md-2 d-flex align-items-end">
                                                        <button type="button"
                                                            class="btn btn-danger remove-pricing">X</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" id="add-pricing">
                                    + Add Row
                                </button>
                            </div>
                        </div>
                        <!-- PRICING SECTION END -->
                        <!-- DOCUMENTATION SECTION -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Documentation</h5>
                                <div class="mb-3">
                                    <label>Section Title</label>
                                    <input type="text" name="documentation_title" class="form-control"
                                        value="{{ $documentation->section_title ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label>Description</label>
                                    <textarea name="documentation_description" class="form-control">{{ $documentation->section_description ?? '' }}</textarea>
                                </div>
                                <h6 class="mt-4">Bullet Points</h6>
                                <div id="documentation-wrapper">
                                    @if (isset($documentationPoints) && count($documentationPoints))
                                        @foreach ($documentationPoints as $key => $point)
                                            <div class="documentation-item border rounded p-3 mb-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Point Title</label>
                                                        <input type="text"
                                                            name="documentation_points[{{ $key }}][title]"
                                                            class="form-control" value="{{ $point->title ?? '' }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Icon</label>

                                                        <select name="documentation_points[{{ $key }}][icon]"
                                                            class="form-control">

                                                            <option value="fa-id-card"
                                                                {{ ($point->icon ?? '') == 'fa-id-card' ? 'selected' : '' }}>
                                                                Aadhar Card
                                                            </option>

                                                            <option value="fa-credit-card"
                                                                {{ ($point->icon ?? '') == 'fa-credit-card' ? 'selected' : '' }}>
                                                                PAN Card
                                                            </option>

                                                            <option value="fa-building-columns"
                                                                {{ ($point->icon ?? '') == 'fa-building-columns' ? 'selected' : '' }}>
                                                                Bank Account
                                                            </option>

                                                            <option value="fa-location-dot"
                                                                {{ ($point->icon ?? '') == 'fa-location-dot' ? 'selected' : '' }}>
                                                                Pickup Address
                                                            </option>

                                                            <option value="fa-file-invoice"
                                                                {{ ($point->icon ?? '') == 'fa-file-invoice' ? 'selected' : '' }}>
                                                                GST Certificate
                                                            </option>

                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Order</label>
                                                        <input type="number"
                                                            name="documentation_points[{{ $key }}][display_order]"
                                                            class="form-control"
                                                            value="{{ $point->display_order ?? '' }}">
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <button type="button"
                                                            class="btn btn-danger remove-documentation">X</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" id="add-documentation">
                                    + Add Bullet Point
                                </button>
                            </div>
                        </div>
                        <!-- DOCUMENTATION SECTION END -->
                        <!-- CTA SECTION -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">CTA Section</h5>
                                <div class="mb-3">
                                    <label class="form-label">CTA Title</label>
                                    <input type="text" name="cta_title" class="form-control"
                                        value="{{ $sellerPage->cta_title ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">CTA Description</label>
                                    <textarea name="cta_description" class="form-control">{{ $sellerPage->cta_description ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">CTA Description</label>
                                    <textarea name="cta_description" class="form-control">{{ $sellerPage->cta_description ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">CTA Button Text</label>
                                    <input type="text" name="cta_button_text" class="form-control"
                                        value="{{ $sellerPage->cta_button_text ?? '' }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">CTA Button Link</label>
                                    <input type="text" name="cta_button_link" class="form-control"
                                        value="{{ $sellerPage->cta_button_link ?? '' }}">
                                </div>
                                <div class="mb-3">
    <label class="form-label">CTA Image</label>

    <div class="tt-product-thumb show-selected-files mt-3">

        <div class="avatar avatar-xl cursor-pointer choose-media"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasBottom"
            onclick="showMediaManager(this)"
            data-selection="single">

            <input type="hidden"
                name="cta_image"
                value="{{ $sellerPage->cta_image ?? '' }}">

            @if (!empty($sellerPage->cta_image))
                <img src="{{ uploadedAsset($sellerPage->cta_image) }}"
                    class="rounded" width="80">
            @else
                <div class="no-avatar rounded-circle">
                    <span><i data-feather="plus"></i></span>
                </div>
            @endif

        </div>

    </div>
</div>
                                <!-- <div class="mb-3">
                            <label class="form-label">CTA Background Image</label>
                            
                            <div class="tt-image-drop rounded">
                            
                            <span class="fw-semibold">Choose Background</span>
                            
                            <div class="tt-product-thumb show-selected-files mt-3">
                            
                            <div class="avatar avatar-xl cursor-pointer choose-media"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasBottom"
                            onclick="showMediaManager(this)"
                            data-selection="single">
                            
                            <input type="hidden" name="cta_background"
                            value="{{ $sellerPage->cta_background ?? '' }}"> -->
                                <!-- <div class="no-avatar rounded-circle">
                            <span><i data-feather="plus"></i></span>
                            </div> -->
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </div>
        <!-- CTA SECTION END -->



        <div class="text-end">
            <button class="btn btn-primary" style="margin-right:15px;">
                Save Seller Page
            </button>
        </div>
        </div>
        </div>
        </form>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {


            // ================= FEATURE SECTION =================

            let featureIndex = 100;

            document.getElementById('add-feature').onclick = function() {

                let html = `
   
   <div class="feature-item border rounded p-3 mb-3">
   <div class="row">
   
   <div class="col-md-3">
   
   <label>Feature Icon</label>
   
   <select name="features[${featureIndex}][icon]" class="form-control">
   
   <option value="fa-user-check">Easy Registration</option>
   <option value="fa-coins">Low Commission</option>
   <option value="fa-shield-halved">Secure Payments</option>
   <option value="fa-flag">Nationwide Customers</option>
   <option value="fa-truck">Fast Delivery</option>
   <option value="fa-store">Marketplace</option>
   
   </select>
   
   </div>
   
   <div class="col-md-3">
   <label>Title</label>
   <input type="text" name="features[${featureIndex}][title]" class="form-control">
   </div>
   
   <div class="col-md-4">
   <label>Description</label>
   <input type="text" name="features[${featureIndex}][description]" class="form-control">
   </div>
   
   <div class="col-md-1">
   <label>Order</label>
   <input type="number" name="features[${featureIndex}][display_order]" class="form-control">
   </div>
   
   <div class="col-md-1 d-flex align-items-end">
   <button type="button" class="btn btn-danger remove-feature">X</button>
   </div>
   
   </div>
   </div>
   
   `;

                document.getElementById('features-wrapper').insertAdjacentHTML('beforeend', html);

                featureIndex++;


            };



            // ================= STEPS SECTION =================

            let stepIndex = 100;

            document.getElementById('add-step').onclick = function() {

                let html = `
   
   <div class="step-item border rounded p-3 mb-3">
   
   <div class="row">
   
   <div class="col-md-2">
   <label>Step Number</label>
   <input type="number" name="steps[${stepIndex}][step_number]" class="form-control">
   </div>
   
   <div class="col-md-3">
   <label>Title</label>
   <input type="text" name="steps[${stepIndex}][title]" class="form-control">
   </div>
   
   <div class="col-md-2">
   
   <label>Step Image</label>
   
   <div class="tt-product-thumb">
   
   <div class="avatar avatar-xl cursor-pointer choose-media"
   data-bs-toggle="offcanvas"
   data-bs-target="#offcanvasBottom"
   onclick="showMediaManager(this)"
   data-selection="single">
   
   <input type="hidden" name="steps[${stepIndex}][image]">
   
   <div class="no-avatar rounded-circle">
   <span><i data-feather="plus"></i></span>
   </div>
   
   </div>
   
   </div>
   
   </div>
   
   <div class="col-md-4">
   <label>Description</label>
   <input type="text" name="steps[${stepIndex}][description]" class="form-control">
   </div>
   
   <div class="col-md-1 d-flex align-items-end">
   <button type="button" class="btn btn-danger remove-step">X</button>
   </div>
   
   </div>
   
   </div>
   
   `;

                document.getElementById('steps-wrapper').insertAdjacentHTML('beforeend', html);

                stepIndex++;
                feather.replace();

            };



            // ================= WHY CHOOSE SECTION =================

            let whyIndex = 100;

            document.getElementById('add-why').onclick = function() {

                let html = `
   
   <div class="why-item border rounded p-3 mb-3">
   
   <div class="row">
   
   <div class="col-md-6">
   <label>Point Title</label>
   <input type="text" name="why_points[${whyIndex}][title]" class="form-control">
   </div>
   
   <div class="col-md-3">
   <label>Order</label>
   <input type="number" name="why_points[${whyIndex}][display_order]" class="form-control">
   </div>
   
   <div class="col-md-3 d-flex align-items-end">
   <button type="button" class="btn btn-danger remove-why">X</button>
   </div>
   
   </div>
   
   </div>
   
   `;

                document.getElementById('why-wrapper').insertAdjacentHTML('beforeend', html);

                whyIndex++;

            };



            // ================= PRICING SECTION =================

            let pricingIndex = 100;

            document.getElementById('add-pricing').onclick = function() {

                let html = `
   
   <div class="pricing-item border rounded p-3 mb-3">
   
   <div class="row">
   
   <div class="col-md-4">
   <label>Feature Name</label>
   <input type="text" name="pricing[${pricingIndex}][feature_name]" class="form-control">
   </div>
   
   <div class="col-md-4">
   <label>Feature Value</label>
   <input type="text" name="pricing[${pricingIndex}][feature_value]" class="form-control">
   </div>
   
   <div class="col-md-2">
   <label>Order</label>
   <input type="number" name="pricing[${pricingIndex}][display_order]" class="form-control">
   </div>
   
   <div class="col-md-2 d-flex align-items-end">
   <button type="button" class="btn btn-danger remove-pricing">X</button>
   </div>
   
   </div>
   
   </div>
   
   `;

                document.getElementById('pricing-wrapper').insertAdjacentHTML('beforeend', html);

                pricingIndex++;

            };

            // ================= DOCUMENTATION SECTION =================

            let documentationIndex = 100;

            document.getElementById('add-documentation').onclick = function() {

                let html = `
   
   <div class="documentation-item border rounded p-3 mb-3">
   
   <div class="row">
   
   <div class="col-md-6">
   <label>Point Title</label>
   <input type="text" name="documentation_points[${documentationIndex}][title]" class="form-control">
   </div>
   
   <div class="col-md-3">
   <label>Order</label>
   <input type="number" name="documentation_points[${documentationIndex}][display_order]" class="form-control">
   </div>
   
   <div class="col-md-3 d-flex align-items-end">
   <button type="button" class="btn btn-danger remove-documentation">X</button>
   </div>
   
   </div>
   
   </div>
   
   `;

                document.getElementById('documentation-wrapper').insertAdjacentHTML('beforeend', html);

                documentationIndex++;

            };

            // ================= REMOVE BUTTONS =================

            document.addEventListener('click', function(e) {

                if (e.target.classList.contains('remove-feature')) {
                    e.target.closest('.feature-item').remove();
                }

                if (e.target.classList.contains('remove-step')) {
                    e.target.closest('.step-item').remove();
                }

                if (e.target.classList.contains('remove-why')) {
                    e.target.closest('.why-item').remove();
                }


                if (e.target.classList.contains('remove-pricing')) {
                    e.target.closest('.pricing-item').remove();
                }
                if (e.target.classList.contains('remove-documentation')) {
                    e.target.closest('.documentation-item').remove();
                }

            });

        });
    </script>
@endsection
