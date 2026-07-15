@extends('backend.layouts.master')
@php
    $shipping_type = $vendorShippingSetting->shipping_type ?? 'self';
@endphp
@php
    $zoneReadOnly = auth()->user()->user_type == 'vendor';
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



                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
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

                                @php
                                    $today = now()->toDateString();

                                    // Aaj ki date par seller ka holiday hai ya nahi
                                    $holidayCount = \App\Models\VendorHoliday::where('vendor_id', auth()->id())
                                        ->whereDate('holiday_date', $today)
                                        ->count();

                                    // Base handling days
                                    $baseHandlingDays = (int) ($shipping->handling_days ?? 1);

                                    // Weekly off only adds a day when it falls on today.
                                    $weeklyOffCount = strcasecmp(
                                        (string) ($shipping->weekly_off ?? ''),
                                        now($shipping->timezone ?? config('app.timezone'))->format('l')
                                    ) === 0 ? 1 : 0;

                                    // FINAL = Base + Weekly Off + Holiday
                                    $finalHandlingDays = $baseHandlingDays + $weeklyOffCount + $holidayCount;
                                @endphp

                                <div class="col-md-3 mb-3">
                                    <label>Handling Days</label>

                                    <input type="number" class="form-control" id="handling_days"
                                        value="{{ $finalHandlingDays }}" readonly>

                                    <input type="hidden" name="handling_days" id="base_handling_days"
                                        value="{{ $shipping->handling_days ?? 1 }}">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Order Capacity</label>
                                    <input type="number" class="form-control" name="order_capacity"
                                        value="{{ $shipping->order_capacity }}">
                                </div>

                            </div>

                            <hr>

                            <hr>

                            <h5 class="mb-3">Weekly Off</h5>

                            <p class="text-muted">
                                Select one day as your weekly off.
                            </p>

                            <div class="row">

                                @php
                                    $weekDays = [
                                        'monday' => 'Monday',
                                        'tuesday' => 'Tuesday',
                                        'wednesday' => 'Wednesday',
                                        'thursday' => 'Thursday',
                                        'friday' => 'Friday',
                                        'saturday' => 'Saturday',
                                        'sunday' => 'Sunday',
                                    ];
                                @endphp

                                @foreach ($weekDays as $day => $label)
                                    <div class="col-md-2 mb-2">

                                        <div class="form-check">

                                            <input class="form-check-input" type="radio" name="weekly_off"
                                                id="weekly_off_{{ $day }}" value="{{ $day }}"
                                                {{ ($shipping->weekly_off ?? '') == $day ? 'checked' : '' }}>

                                            <label class="form-check-label" for="weekly_off_{{ $day }}">
                                                {{ $label }}
                                            </label>

                                        </div>

                                    </div>
                                @endforeach

                            </div>
                            <hr class="my-4">

                            <hr class="my-4">

                            <h5>Holiday Calendar</h5>

                            <p class="text-muted mb-4">
                                Add holidays when your warehouse or business will remain closed.
                                Orders received on these dates will be processed on the next working day.
                            </p>

                            <div class="row">

                                <div class="col-md-5">
                                    <label>Holiday Name</label>

                                    <input type="text" class="form-control" name="holiday_name" id="holiday_name"
                                        placeholder="e.g. Diwali">
                                </div>

                                <div class="col-md-4">
                                    <label>Holiday Date</label>

                                    <input type="date" class="form-control" name="holiday_date" id="holiday_date">
                                </div>

                                <div class="col-md-3 d-flex align-items-end">

                                    <button type="button" id="addHolidayBtn" class="btn btn-primary w-100">
                                        + Add Holiday
                                    </button>

                                </div>

                            </div>

                            <div class="table-responsive mt-4">

                                <table class="table table-bordered">

                                    <thead class="table-light">
                                        <tr>
                                            <th>Holiday Name</th>
                                            <th>Date</th>
                                            <th width="100">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @forelse($holidays as $holiday)
                                            <tr>

                                                <td>{{ $holiday->holiday_name }}</td>

                                                <td>
                                                    {{ \Carbon\Carbon::parse($holiday->holiday_date)->format('d M Y') }}
                                                </td>

                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger delete-holiday"
                                                        data-id="{{ $holiday->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>

                                            </tr>

                                        @empty

                                            <tr>
                                                <td colspan="3" class="text-center text-muted">
                                                    No holidays added yet
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                            <hr class="my-4">
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

                                    {{-- <h4 class="mb-0">
                {{ $shipping->template_name ?: 'Migrated Template' }}
            </h4> --}}

                                    <small class="text-muted">
                                        Default Template for New SKUs
                                    </small>

                                </div>

                                <div>

                                    {{-- <div class="form-check form-switch">

                <input type="checkbox"
                       class="form-check-input"
                       name="is_default_template"
                       {{ $shipping->is_default_template ? 'checked' : '' }}>

                <label class="form-check-label">
                    Default Template
                </label>

            </div> --}}

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
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="same_day"
                                                    name="delivery_type" value="same_day"
                                                    {{ $shipping->delivery_type == 'same_day' ? 'checked' : '' }}>

                                                <label class="form-check-label" for="same_day">
                                                    Same Day Delivery
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input type="radio" name="delivery_type" value="one_day"
                                                    {{ empty($shipping->delivery_type) || $shipping->delivery_type == 'one_day' ? 'checked' : '' }}>

                                                <label class="form-check-label" for="one_day">
                                                    One Day Delivery
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="two_day"
                                                    name="delivery_type" value="two_day"
                                                    {{ $shipping->delivery_type == 'two_day' ? 'checked' : '' }}>

                                                <label class="form-check-label" for="two_day">
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

                                            <textarea class="form-control" rows="3" name="expedited_regions">{{ $shipping->expedited_regions }}</textarea>
                                        </div>

                                        <div class="col-md-2 mb-3">
                                            <label>Transit Time</label>

                                            <input type="text" class="form-control" name="expedited_transit_time"
                                                value="{{ $shipping->expedited_transit_time }}">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Shipping Fee / Order</label>

                                            <input type="number" step="0.01" class="form-control"
                                                name="expedited_shipping_fee_order"
                                                value="{{ $shipping->expedited_shipping_fee_order }}">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Shipping Fee / Item</label>

                                            <input type="number" step="0.01" class="form-control"
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
                                                    <label>Regions </label>

                                                    <textarea class="form-control" rows="2" name="standard_zone1_regions" {{ $zoneReadOnly ? 'readonly' : '' }}>{{ $shipping->standard_zone1_regions }}</textarea>
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Transit Time</label>

                                                    <input type="text" class="form-control"
                                                        name="standard_zone1_transit_time"
                                                        value="{{ $shipping->standard_zone1_transit_time }}"
                                                        {{ $zoneReadOnly ? 'readonly' : '' }}>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Fee / Order</label>

                                                    <input type="number" step="0.01" class="form-control"
                                                        name="standard_zone1_fee_order"
                                                        value="{{ $shipping->standard_zone1_fee_order }}"
                                                        {{ $zoneReadOnly ? 'readonly' : '' }}>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Fee / Item</label>

                                                    <input type="number" step="0.01" class="form-control"
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

                                                    <textarea class="form-control" rows="2" name="standard_zone2_regions" {{ $zoneReadOnly ? 'readonly' : '' }}>{{ $shipping->standard_zone2_regions }}</textarea>
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Transit Time</label>

                                                    <input type="text" class="form-control"
                                                        name="standard_zone2_transit_time"
                                                        value="{{ $shipping->standard_zone2_transit_time }}"
                                                        {{ $zoneReadOnly ? 'readonly' : '' }}>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Fee / Order</label>

                                                    <input type="number" step="0.01" class="form-control"
                                                        name="standard_zone2_fee_order"
                                                        value="{{ $shipping->standard_zone2_fee_order }}">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Fee / Item</label>

                                                    <input type="number" step="0.01" class="form-control"
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

                                                    <textarea class="form-control" rows="2" name="standard_zone3_regions" {{ $zoneReadOnly ? 'readonly' : '' }}>{{ $shipping->standard_zone3_regions }}</textarea>
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Transit Time</label>

                                                    <input type="text" class="form-control"
                                                        name="standard_zone3_transit_time"
                                                        value="{{ $shipping->standard_zone3_transit_time }}"
                                                        {{ $zoneReadOnly ? 'readonly' : '' }}>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Fee / Order</label>

                                                    <input type="number" step="0.01" class="form-control"
                                                        name="standard_zone3_fee_order"
                                                        value="{{ $shipping->standard_zone3_fee_order }}">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Fee / Item</label>

                                                    <input type="number" step="0.01" class="form-control"
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

                                                    <textarea class="form-control" rows="2" name="standard_zone4_regions" {{ $zoneReadOnly ? 'readonly' : '' }}>{{ $shipping->standard_zone4_regions }}</textarea>
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Transit Time</label>

                                                    <input type="text" class="form-control"
                                                        name="standard_zone4_transit_time"
                                                        value="{{ $shipping->standard_zone4_transit_time }}"
                                                        {{ $zoneReadOnly ? 'readonly' : '' }}>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Fee / Order</label>

                                                    <input type="number" step="0.01" class="form-control"
                                                        name="standard_zone4_fee_order"
                                                        value="{{ $shipping->standard_zone4_fee_order }}">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Fee / Item</label>

                                                    <input type="number" step="0.01" class="form-control"
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

                                                    <textarea class="form-control" rows="2" name="standard_zone5_regions" {{ $zoneReadOnly ? 'readonly' : '' }}>{{ $shipping->standard_zone5_regions }}</textarea>
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Transit Time</label>

                                                    <input type="text" class="form-control"
                                                        name="standard_zone5_transit_time"
                                                        value="{{ $shipping->standard_zone5_transit_time }}"
                                                        {{ $zoneReadOnly ? 'readonly' : '' }}>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Fee / Order</label>

                                                    <input type="number" step="0.01" class="form-control"
                                                        name="standard_zone5_fee_order"
                                                        value="{{ $shipping->standard_zone5_fee_order }}">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Fee / Item</label>

                                                    <input type="number" step="0.01" class="form-control"
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


        function updateHandlingDays() {

            let selectedDelivery = document.querySelector(
                'input[name="delivery_type"]:checked'
            );

            let baseDays = 1;

            if (selectedDelivery) {

                if (selectedDelivery.value === 'same_day') {
                    baseDays = 0;
                } else if (selectedDelivery.value === 'two_day') {
                    baseDays = 2;
                }
            }

            let weeklyOff = document.querySelector(
                'input[name="weekly_off"]:checked'
            );

            const currentWeekday = @json(now($shipping->timezone ?? config('app.timezone'))->format('l'));
            const holidayCount = {{ (int) $holidayCount }};
            let weeklyOffCount = weeklyOff && weeklyOff.value === currentWeekday ? 1 : 0;

            let finalDays = baseDays + holidayCount + weeklyOffCount;

            // Screen par final handling days
            document.getElementById('handling_days').value = finalDays;

            // Backend ko base handling days
            document.getElementById('base_handling_days').value = baseDays;
        }


        // Delivery Type change hone par
        document.querySelectorAll(
            'input[name="delivery_type"]'
        ).forEach(function(radio) {

            radio.addEventListener('change', updateHandlingDays);

        });


        // Weekly Off change hone par
        document.querySelectorAll(
            'input[name="weekly_off"]'
        ).forEach(function(radio) {

            radio.addEventListener('change', updateHandlingDays);

        });



        document.getElementById('addHolidayBtn').addEventListener('click', function() {

            let holidayName = document.getElementById('holiday_name').value;
            let holidayDate = document.getElementById('holiday_date').value;

            if (!holidayName || !holidayDate) {
                alert('Please enter holiday name and date');
                return;
            }

            let form = document.createElement('form');

            form.method = 'POST';
            form.action = "{{ route('vendor.holidays.store') }}";

            form.innerHTML = `
        @csrf
        <input type="hidden" name="holiday_name" value="${holidayName}">
        <input type="hidden" name="holiday_date" value="${holidayDate}">
    `;

            document.body.appendChild(form);

            form.submit();

        });


        document.querySelectorAll('.delete-holiday').forEach(function(btn) {

            btn.addEventListener('click', function() {

                if (!confirm('Delete this holiday?')) {
                    return;
                }

                let id = this.dataset.id;

                let form = document.createElement('form');

                form.method = 'POST';

                form.action = "{{ url('/vendor/holidays') }}/" + id;

                form.innerHTML = `
            @csrf
            <input type="hidden" name="_method" value="DELETE">
        `;

                document.body.appendChild(form);

                form.submit();

            });

        });
    </script>
@endsection
