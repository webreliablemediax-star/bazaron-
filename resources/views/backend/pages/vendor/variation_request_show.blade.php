@extends('backend.layouts.master')

@section('title')
    Variation Request Details
@endsection

@section('contents')

<div class="card">

  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Variation Request Details</h4>
</div>
<div class="card-body">

    <div class="row g-4">

        <div class="col-md-6">
            <div class="border rounded p-3 h-100">
                <h6 class="text-muted mb-3">Product Information</h6>

                <p class="mb-2">
    <strong>Product Code :</strong>

    <span class="text-dark">
        {{ $requestData->product->product_code ?? '-' }}
    </span>
</p>

                <p class="mb-0">
                    <strong>Seller :</strong>
                    <span class="text-dark">
                        {{ optional($requestData->seller)->name ?? '-' }}
                    </span>
                </p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="border rounded p-3 h-100">
                <h6 class="text-muted mb-3">Variation Information</h6>

                <p class="mb-3">
                    <strong>Variation Name :</strong>
                    <span class="text-dark">
                        {{ $requestData->variation_name }}
                    </span>
                </p>

                <div>
    <strong>Requested Values :</strong>

    <div class="mt-3">

        @php
            $values = json_decode($requestData->variation_values, true);

            if (!is_array($values)) {
                $values = explode(',', $requestData->variation_values);
            }
        @endphp

        @foreach($values as $value)

            <div class="border-bottom py-2">
                {{ trim($value) }}
            </div>

        @endforeach

    </div>
</div>
            </div>
        </div>

    </div>

    <hr class="my-4">

    <div class="d-flex align-items-center gap-3 flex-wrap">

        <strong>Status :</strong>

        @if($requestData->status == 'pending')

            <span class="badge bg-warning px-3 py-2">
                Pending
            </span>

        @elseif($requestData->status == 'approved')

            <span class="badge bg-success px-3 py-2">
                Approved
            </span>

        @else

            <span class="badge bg-danger px-3 py-2">
                Rejected
            </span>

        @endif

    </div>

    @if($requestData->status == 'pending')

        <div class="mt-4 d-flex gap-2">

            <form
                action="{{ route('admin.variation.request.approve', $requestData->id) }}"
                method="POST">

                @csrf

                <button class="btn btn-success">
                    <i class="fas fa-check me-1"></i>
                    Approve
                </button>

            </form>

            <form
                action="{{ route('admin.variation.request.reject', $requestData->id) }}"
                method="POST">

                @csrf

                <button class="btn btn-danger">
                    <i class="fas fa-times me-1"></i>
                    Reject
                </button>

            </form>

        </div>

    @endif

</div>

</div>

@endsection