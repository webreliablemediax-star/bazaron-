@extends('backend.layouts.master')
@php
    $shipping_type = $vendorShippingSetting->shipping_type ?? 'self';
@endphp
@section('title')
    Shipment Settings
@endsection

@section('contents')
    <div class="container mt-4">

        <div class="card">

            <div class="card-header">
                <h4>Shipment Settings</h4>
            </div>

            <div class="card-body">

                <form action="{{ route('vendor.shipment.settings.update') }}" method="POST">
                    @csrf

                    <ul class="nav nav-tabs mb-4">

                        <li class="nav-item">
                            <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#settings">
                                Settings
                            </button>
                        </li>

                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bazaron">
                                Bazaron Shipping
                            </button>
                        </li>

                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#selfship">
                                Self Shipping
                            </button>
                        </li>

                    </ul>


                    <div class="tab-content">

                        <!-- SETTINGS TAB -->
                        <div class="tab-pane fade show active" id="settings">

                            <h5 class="mb-4">Shipment Settings</h5>

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>Address Name</label>
                                    <input type="text" class="form-control" name="address_name"
                                        value="{{ $shipping->address_name }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Timezone</label>
                                    <input type="text" class="form-control" name="timezone"
                                        value="{{ $shipping->timezone }}">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label>Full Address</label>
                                    <textarea class="form-control" rows="3" name="full_address">{{ $shipping->full_address }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Cutoff Time</label>
                                    <input type="time" class="form-control" name="cutoff_time"
                                        value="{{ $shipping->cutoff_time }}">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Handling Days</label>
                                    <input type="number" class="form-control" name="handling_days"
                                        value="{{ $shipping->handling_days }}">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Order Capacity</label>
                                    <input type="number" class="form-control" name="order_capacity"
                                        value="{{ $shipping->order_capacity }}">
                                </div>

                            </div>

                            <hr>

                            <h5 class="mb-3">Operating Days</h5>

                            <div class="row">

                                <div class="col-md-2 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="monday" value="1"
                                            {{ $shipping->monday ? 'checked' : '' }}>
                                        <label class="form-check-label">Monday</label>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tuesday" value="1"
                                            {{ $shipping->tuesday ? 'checked' : '' }}>
                                        <label class="form-check-label">Tuesday</label>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="wednesday" value="1"
                                            {{ $shipping->wednesday ? 'checked' : '' }}>
                                        <label class="form-check-label">Wednesday</label>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="thursday" value="1"
                                            {{ $shipping->thursday ? 'checked' : '' }}>
                                        <label class="form-check-label">Thursday</label>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="friday" value="1"
                                            {{ $shipping->friday ? 'checked' : '' }}>
                                        <label class="form-check-label">Friday</label>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="saturday" value="1"
                                            {{ $shipping->saturday ? 'checked' : '' }}>
                                        <label class="form-check-label">Saturday</label>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sunday" value="1"
                                            {{ $shipping->sunday ? 'checked' : '' }}>
                                        <label class="form-check-label">Sunday</label>
                                    </div>
                                </div>

                            </div>
                            <hr class="my-4">

                            <h5>Holidays</h5>

                            <div class="row mt-3">

                                <div class="col-md-4">
                                    <label>Holiday Year</label>

                                    <select class="form-control" name="holiday_year">

                                        <option value="2025">2025</option>

                                        <option value="2026" selected>2026</option>

                                        <option value="2027">2027</option>

                                    </select>
                                </div>

                            </div>
                            <hr class="my-4">


                            <hr class="my-4">

                            <h5>Ship Through</h5>

                            <div class="alert alert-light border">

                                <p class="mb-2">
                                    @if ($shipping_type == 'self')
                                        You currently ship through
                                        <b class="text-success">Self Shipping</b>.
                                    @elseif($shipping_type == 'bazaron')
                                        You currently ship through
                                        <b class="text-success">Bazaron Shipping</b>.
                                    @else
                                        You currently ship through both
                                        <b class="text-success">Bazaron Shipping</b>
                                        and
                                        <b class="text-success">Self Shipping</b>.
                                    @endif
                                </p>

                            </div>
                            <div class="card mb-3">

                                <div class="card-body">

                                    <h6>I want only Bazaron Shipping</h6>

                                    <p class="text-muted">

                                        Bazaron will pick up orders from your registered address and
                                        deliver them to customers.

                                    </p>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="bazaron_only"
                                            name="bazaron_only" {{ $shipping_type == 'bazaron' ? 'checked' : '' }}>
                                    </div>

                                </div>

                            </div>
                            <div class="card">

                                <div class="card-body">

                                    <h6>Select Self Shipping</h6>

                                    <p class="text-muted">

                                        Receive orders only from regions selected by you.

                                    </p>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="self_ship_only"
                                            name="self_ship_only" {{ $shipping_type == 'self' ? 'checked' : '' }}>
                                    </div>

                                </div>

                            </div>

                            <div class="mt-4 text-end">
                                <button class="btn btn-primary">
                                    Update Shipment Settings
                                </button>
                            </div>

                        </div>
                          <!-- SETTINGS TAB -->


                        <!-- TAB 2 -->
                       
                        <div class="tab-pane fade" id="bazaron">

                            <h5 class="mb-4">Bazaron Shipping Configuration</h5>

                            <!-- Local -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Standard Delivery Local</h6>
                                </div>

                                <div class="card-body">

                                    <div class="row">

                                        <div class="col-md-6 mb-3">
                                            <label>Regions</label>

                                            <textarea class="form-control" rows="3" name="local_regions">{{ $shipping->local_regions }}</textarea>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Transit Time</label>

                                            <input type="text" class="form-control" name="local_transit_time"
                                                value="{{ $shipping->local_transit_time }}">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Shipping Fee / Order</label>

                                            <input type="number" step="0.01" class="form-control"
                                                name="local_shipping_fee_order"
                                                value="{{ $shipping->local_shipping_fee_order }}">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Shipping Fee / Item</label>

                                            <input type="number" step="0.01" class="form-control"
                                                name="local_shipping_fee_item"
                                                value="{{ $shipping->local_shipping_fee_item }}">
                                        </div>

                                    </div>

                                </div>
                            </div>


                            <!-- Regional -->
                            <div class="card mb-4">

                                <div class="card-header">
                                    <h6 class="mb-0">Standard Delivery Regional</h6>
                                </div>

                                <div class="card-body">

                                    <div class="row">

                                        <div class="col-md-6 mb-3">
                                            <label>Regions</label>

                                            <textarea class="form-control" rows="3" name="regional_regions">{{ $shipping->regional_regions }}</textarea>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Transit Time</label>

                                            <input type="text" class="form-control" name="regional_transit_time"
                                                value="{{ $shipping->regional_transit_time }}">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Shipping Fee / Order</label>

                                            <input type="number" step="0.01" class="form-control"
                                                name="regional_shipping_fee_order"
                                                value="{{ $shipping->regional_shipping_fee_order }}">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Shipping Fee / Item</label>

                                            <input type="number" step="0.01" class="form-control"
                                                name="regional_shipping_fee_item"
                                                value="{{ $shipping->regional_shipping_fee_item }}">
                                        </div>

                                    </div>

                                </div>

                            </div>


                            <!-- National -->
                            <div class="card">

                                <div class="card-header">
                                    <h6 class="mb-0">Standard Delivery National</h6>
                                </div>

                                <div class="card-body">

                                    <div class="row">

                                        <div class="col-md-6 mb-3">
                                            <label>Regions</label>

                                            <textarea class="form-control" rows="3" name="national_regions">{{ $shipping->national_regions }}</textarea>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Transit Time</label>

                                            <input type="text" class="form-control" name="national_transit_time"
                                                value="{{ $shipping->national_transit_time }}">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Shipping Fee / Order</label>

                                            <input type="number" step="0.01" class="form-control"
                                                name="national_shipping_fee_order"
                                                value="{{ $shipping->national_shipping_fee_order }}">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Shipping Fee / Item</label>

                                            <input type="number" step="0.01" class="form-control"
                                                name="national_shipping_fee_item"
                                                value="{{ $shipping->national_shipping_fee_item }}">
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                         <!-- TAB 2 -->


                        <!-- TAB 3 -->
                       <div class="tab-pane fade" id="selfship">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-0">
                {{ $shipping->template_name ?: 'Migrated Template' }}
            </h4>

            <small class="text-muted">
                Default Template for New SKUs
            </small>

        </div>

        <div>

            <div class="form-check form-switch">

                <input type="checkbox"
                       class="form-check-input"
                       name="is_default_template"
                       {{ $shipping->is_default_template ? 'checked' : '' }}>

                <label class="form-check-label">
                    Default Template
                </label>

            </div>

        </div>

    </div>


    <div class="card border-info mb-4">

        <div class="card-body">

            <h5 class="text-info">

                <i class="fa fa-info-circle"></i>

                You are eligible for Same Day, One-Day and Two-Day delivery.

            </h5>

            <p class="text-muted mt-3">

                Enable Same Day, One-Day and Two-Day delivery
                on your SKUs assigned to this template.

            </p>


            <div class="row mt-4">

                <div class="col-md-4">

                    <div class="form-check form-switch">

                        <input type="checkbox"
                               class="form-check-input"
                               name="same_day_enabled"
                               {{ $shipping->same_day_enabled ? 'checked' : '' }}>

                        <label class="form-check-label">

                            Same Day Delivery

                        </label>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="form-check form-switch">

                        <input type="checkbox"
                               class="form-check-input"
                               name="one_day_enabled"
                               {{ $shipping->one_day_enabled ? 'checked' : '' }}>

                        <label class="form-check-label">

                            One Day Delivery

                        </label>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="form-check form-switch">

                        <input type="checkbox"
                               class="form-check-input"
                               name="two_day_enabled"
                               {{ $shipping->two_day_enabled ? 'checked' : '' }}>

                        <label class="form-check-label">

                            Two Day Delivery

                        </label>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">

                Domestic Shipping

            </h5>

        </div>

        <div class="card-body">

          <h5 class="mb-4">Expedited Delivery</h5>

<div class="row">

    <div class="col-md-4 mb-3">
        <label>Regions</label>

        <textarea class="form-control"
                  rows="3"
                  name="expedited_regions">{{ $shipping->expedited_regions }}</textarea>
    </div>

    <div class="col-md-2 mb-3">
        <label>Transit Time</label>

        <input type="text"
               class="form-control"
               name="expedited_transit_time"
               value="{{ $shipping->expedited_transit_time }}">
    </div>

    <div class="col-md-3 mb-3">
        <label>Shipping Fee / Order</label>

        <input type="number"
               step="0.01"
               class="form-control"
               name="expedited_shipping_fee_order"
               value="{{ $shipping->expedited_shipping_fee_order }}">
    </div>

    <div class="col-md-3 mb-3">
        <label>Shipping Fee / Item</label>

        <input type="number"
               step="0.01"
               class="form-control"
               name="expedited_shipping_fee_item"
               value="{{ $shipping->expedited_shipping_fee_item }}">
    </div>

</div>

<hr class="my-5">

<h5 class="mb-4">Standard Delivery</h5>
<div class="card mb-3">

    <div class="card-header">
        <strong>Zone 1</strong>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <label>Regions</label>

                <textarea class="form-control"
                          rows="2"
                          name="standard_zone1_regions">{{ $shipping->standard_zone1_regions }}</textarea>
            </div>

            <div class="col-md-2">
                <label>Transit Time</label>

                <input type="text"
                       class="form-control"
                       name="standard_zone1_transit_time"
                       value="{{ $shipping->standard_zone1_transit_time }}">
            </div>

            <div class="col-md-3">
                <label>Fee / Order</label>

                <input type="number"
                       step="0.01"
                       class="form-control"
                       name="standard_zone1_fee_order"
                       value="{{ $shipping->standard_zone1_fee_order }}">
            </div>

            <div class="col-md-3">
                <label>Fee / Item</label>

                <input type="number"
                       step="0.01"
                       class="form-control"
                       name="standard_zone1_fee_item"
                       value="{{ $shipping->standard_zone1_fee_item }}">
            </div>

        </div>

    </div>

</div>
<div class="card mb-3">

    <div class="card-header">
        <strong>Zone 2</strong>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <label>Regions</label>

                <textarea class="form-control"
                          rows="2"
                          name="standard_zone2_regions">{{ $shipping->standard_zone2_regions }}</textarea>
            </div>

            <div class="col-md-2">
                <label>Transit Time</label>

                <input type="text"
                       class="form-control"
                       name="standard_zone2_transit_time"
                       value="{{ $shipping->standard_zone2_transit_time }}">
            </div>

            <div class="col-md-3">
                <label>Fee / Order</label>

                <input type="number"
                       step="0.01"
                       class="form-control"
                       name="standard_zone2_fee_order"
                       value="{{ $shipping->standard_zone2_fee_order }}">
            </div>

            <div class="col-md-3">
                <label>Fee / Item</label>

                <input type="number"
                       step="0.01"
                       class="form-control"
                       name="standard_zone2_fee_item"
                       value="{{ $shipping->standard_zone2_fee_item }}">
            </div>

        </div>

    </div>

</div>
<div class="card mb-3">

    <div class="card-header">
        <strong>Zone 3</strong>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <label>Regions</label>

                <textarea class="form-control"
                          rows="2"
                          name="standard_zone3_regions">{{ $shipping->standard_zone3_regions }}</textarea>
            </div>

            <div class="col-md-2">
                <label>Transit Time</label>

                <input type="text"
                       class="form-control"
                       name="standard_zone3_transit_time"
                       value="{{ $shipping->standard_zone3_transit_time }}">
            </div>

            <div class="col-md-3">
                <label>Fee / Order</label>

                <input type="number"
                       step="0.01"
                       class="form-control"
                       name="standard_zone3_fee_order"
                       value="{{ $shipping->standard_zone3_fee_order }}">
            </div>

            <div class="col-md-3">
                <label>Fee / Item</label>

                <input type="number"
                       step="0.01"
                       class="form-control"
                       name="standard_zone3_fee_item"
                       value="{{ $shipping->standard_zone3_fee_item }}">
            </div>

        </div>

    </div>

</div>

<div class="card mb-3">

    <div class="card-header">
        <strong>Zone 4</strong>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <label>Regions</label>

                <textarea class="form-control"
                          rows="2"
                          name="standard_zone4_regions">{{ $shipping->standard_zone4_regions }}</textarea>
            </div>

            <div class="col-md-2">
                <label>Transit Time</label>

                <input type="text"
                       class="form-control"
                       name="standard_zone4_transit_time"
                       value="{{ $shipping->standard_zone4_transit_time }}">
            </div>

            <div class="col-md-3">
                <label>Fee / Order</label>

                <input type="number"
                       step="0.01"
                       class="form-control"
                       name="standard_zone4_fee_order"
                       value="{{ $shipping->standard_zone4_fee_order }}">
            </div>

            <div class="col-md-3">
                <label>Fee / Item</label>

                <input type="number"
                       step="0.01"
                       class="form-control"
                       name="standard_zone4_fee_item"
                       value="{{ $shipping->standard_zone4_fee_item }}">
            </div>

        </div>

    </div>

</div>

<div class="card mb-3">

    <div class="card-header">
        <strong>Zone 5</strong>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <label>Regions</label>

                <textarea class="form-control"
                          rows="2"
                          name="standard_zone5_regions">{{ $shipping->standard_zone5_regions }}</textarea>
            </div>

            <div class="col-md-2">
                <label>Transit Time</label>

                <input type="text"
                       class="form-control"
                       name="standard_zone5_transit_time"
                       value="{{ $shipping->standard_zone5_transit_time }}">
            </div>

            <div class="col-md-3">
                <label>Fee / Order</label>

                <input type="number"
                       step="0.01"
                       class="form-control"
                       name="standard_zone5_fee_order"
                       value="{{ $shipping->standard_zone5_fee_order }}">
            </div>

            <div class="col-md-3">
                <label>Fee / Item</label>

                <input type="number"
                       step="0.01"
                       class="form-control"
                       name="standard_zone5_fee_item"
                       value="{{ $shipping->standard_zone5_fee_item }}">
            </div>

        </div>

    </div>

</div>

        </div>

    </div>

</div>
                         <!-- TAB 3 -->

                    </div>

                </form>

            </div>

        </div>

    </div>
    <script>
        document.getElementById('bazaron_only').addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('self_ship_only').checked = false;
            }
        });

        document.getElementById('self_ship_only').addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('bazaron_only').checked = false;
            }
        });
    </script>
@endsection
