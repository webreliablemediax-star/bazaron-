@extends('backend.layouts.master')

@section('title')
    Purchase Quantity Request
@endsection

@section('contents')

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h4>Purchase Quantity Request</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>Field Name</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>Maximum Purchase Quantity</td>
                        <td>{{ $requestData->old_quantity }}</td>
                        <td>{{ $requestData->requested_quantity }}</td>
                    </tr>

                </tbody>

            </table>

            @if($requestData->status == 'pending')

                <div class="mt-4">

                    <form
                        action="{{ route('admin.purchase.quantity.request.approve', $requestData->id) }}"
                        method="POST"
                        style="display:inline-block;">

                        @csrf

                        <button class="btn btn-success">
                            Approve
                        </button>

                    </form>


                    <form
                        action="{{ route('admin.purchase.quantity.request.reject', $requestData->id) }}"
                        method="POST"
                        style="display:inline-block;">

                        @csrf

                        <button class="btn btn-danger">
                            Reject
                        </button>

                    </form>

                </div>

            @endif

        </div>
    </div>

</div>

@endsection