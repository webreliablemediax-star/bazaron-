@extends('backend.layouts.master')

@section('title')
    Request Approvals
@endsection

@section('contents')

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Request Approvals</h4>
        </div>

        <div class="card-body">

            <ul class="nav nav-tabs" id="requestTabs">

               <li class="nav-item">
    <button class="nav-link active"
            data-bs-toggle="tab"
            data-bs-target="#brands">

        Brand Requests

        <span class="badge bg-primary ms-1">
            {{ $brandRequests->count() }}
        </span>

    </button>
</li>

<li class="nav-item">
    <button class="nav-link"
            data-bs-toggle="tab"
            data-bs-target="#variations">

        Variation Requests

        <span class="badge bg-info ms-1">
            {{ $variationRequests->count() }}
        </span>

    </button>
</li>

<li class="nav-item">
    <button class="nav-link"
            data-bs-toggle="tab"
            data-bs-target="#quantity">

        Purchase Quantity

        <span class="badge bg-success ms-1">
            {{ $purchaseRequests->count() }}
        </span>

    </button>
</li>

            </ul>

            <div class="tab-content mt-4">

                <div class="tab-pane fade show active" id="brands">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Brand Name</th>
                                    <th>Status</th>
                                    <th>Requested On</th>
                                </tr>
                            </thead>

                           <tbody>

@forelse($brandRequests as $request)

    <tr>
        <td>{{ $request->brand_name }}</td>

        <td>
            @if($request->status == 'approved')
                <span class="badge bg-success">Approved</span>

            @elseif($request->status == 'rejected')
                <span class="badge bg-danger">Rejected</span>

            @else
                <span class="badge bg-warning">Pending</span>
            @endif
        </td>

        <td>{{ $request->created_at->format('d M Y') }}</td>
    </tr>

@empty

    <tr>
        <td colspan="3" class="text-center">
            No brand requests found
        </td>
    </tr>

@endforelse

</tbody>

                        </table>
                    </div>
                </div>

               <div class="tab-pane fade" id="variations">

    <div class="table-responsive">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Product</th>
                    <th>Variation</th>
                    <th>Values</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            @forelse($variationRequests as $request)

                <tr>

                    <td>{{ $request->product->name ?? '-' }}</td>

                    <td>{{ $request->variation_name }}</td>

                    <td>
    {{ is_array($request->variation_values)
        ? implode(', ', $request->variation_values)
        : $request->variation_values }}
</td>

                    <td>

                        @if($request->status == 'approved')
                            <span class="badge bg-success">Approved</span>

                        @elseif($request->status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>

                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="4" class="text-center">
                        No variation requests found
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

               <div class="tab-pane fade" id="quantity">

    <div class="table-responsive">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Product</th>
                    <th>Old Qty</th>
                    <th>Requested Qty</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            @forelse($purchaseRequests as $request)

                <tr>

                    <td>{{ $request->product->name ?? '-' }}</td>

                    <td>{{ $request->old_quantity }}</td>

                    <td>{{ $request->requested_quantity }}</td>

                    <td>

                        @if($request->status == 'approved')
                            <span class="badge bg-success">Approved</span>

                        @elseif($request->status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>

                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="4" class="text-center">
                        No purchase quantity requests found
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

            </div>

        </div>
    </div>

</div>

@endsection