@extends('backend.layouts.master')

@section('title')
    Purchase Quantity Requests
@endsection

@section('contents')

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h4>Purchase Quantity Requests</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Seller</th>
                        <th>Product</th>
                        <th>Old Qty</th>
                        <th>Requested Qty</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($requests as $key => $request)

                        <tr>
                           <td>{{ $request->product_id }}</td>
                            <td>{{ $request->seller->name ?? '-' }}</td>
                            <td>{{ $request->product->name ?? '-' }}</td>
                            <td>{{ $request->old_quantity }}</td>
                            <td>{{ $request->requested_quantity }}</td>
                            <td>
                                <span class="badge bg-warning">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>
    <a href="{{ route('admin.purchase.quantity.request.show', $request->id) }}"
       class="btn btn-success btn-sm">
        View
    </a>
</td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">
                                No requests found
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection