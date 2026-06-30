@extends('backend.layouts.master')

@section('title')
    Vendor Profile Requests
@endsection

@section('contents')
    <div class="card">
        <div class="card-header">
              <h4>Seller Profile Requests</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>

                    <tr>

                         <th>Seller ID</th>

                        <th>Seller</th>

                        <th>Requested On</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($requests as $request)
                        <tr>

                             <td>{{ $request->vendor_id }}</td>
                            <td>

                                {{ optional($request->vendor)->name }}

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

                                <a href="{{ route('admin.vendor.profile.request.show', $request->id) }}"
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
