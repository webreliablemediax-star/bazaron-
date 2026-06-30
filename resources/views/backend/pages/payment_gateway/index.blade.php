@extends('backend.layouts.master')

@section('contents')
    <div class="container-fluid">

        <div class="card">

            <div class="card-header">
                <h4 class="mb-0">
                    Payment Gateway
                </h4>
            </div>

            <div class="card-body">

                <form method="POST"
                    @if (isset($payment_gateway)) action="{{ route('admin.payment.gateway.update', $payment_gateway->id) }}"
                @else
                    action="{{ route('admin.payment.gateway.store') }}" @endif>

                    @csrf

                    <div class="row">

                        <div class="col-md-10">

                            <label class="form-label">
                                Payment Gateway
                            </label>

                            <input type="number" step="any" name="name" class="form-control"
                                placeholder="Enter Payment Gateway" value="{{ $payment_gateway->name ?? '' }}">

                        </div>

                        <div class="col-md-2 mt-4">

                            <button class="btn btn-primary w-100">

                                @if (isset($payment_gateway))
                                    Update
                                @else
                                    Save
                                @endif

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        <div class="card mt-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Payment Gateway List
                </h5>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Payment Gateway (%)</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($payment_gateways as $item)
                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $item->name }}%</td>

                                <td>

                                    <a href="{{ route('admin.payment.gateway.edit', $item->id) }}" class="text-info me-2">

                                        <i class="fa fa-edit"></i>

                                    </a>

                                    <a href="{{ route('admin.payment.gateway.delete', $item->id) }}" class="text-danger"
                                        onclick="return confirm('Are you sure you want to delete this item?')">

                                        <i class="fa fa-trash"></i>

                                    </a>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection
