@extends('backend.layouts.master')

@section('title')
    Variation Requests
@endsection

@section('contents')

<div class="card">

    <div class="card-header">
        <h4>Variation Requests</h4>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Product Code</th>
                    <th>Seller</th>
                    <th>Variation</th>
                    <th>Requested On</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($requests as $request)

                    <tr>

                         <td>{{ $request->product->product_code ?? '-' }}</td>

                        <td>
                            {{ optional($request->seller)->name }}
                        </td>

                        <td>
                            {{ $request->variation_name }}
                        </td>

                        <td>
                            {{ $request->created_at }}
                        </td>

                        <td>

                            @if ($request->status == 'pending')
                                <span class="badge bg-warning">
                                    Pending
                                </span>

                            @elseif($request->status == 'approved')
                                <span class="badge bg-success">
                                    Approved
                                </span>

                            @else
                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                            @endif

                        </td>

                        <td>

                            <a href="{{ route('admin.variation.request.show', $request->id) }}"
                               class="btn btn-primary btn-sm">

                                View

                            </a>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection