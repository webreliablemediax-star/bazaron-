@extends('backend.layouts.master')

@section('title')
Vendor Request Details
@endsection

@section('contents')

<div class="card">

    <div class="card-header">

        <h4>

            Vendor Request Details

        </h4>

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

            @foreach($requestData->request_data as $field => $value)

            <tr>

                <td>

                    {{ ucwords(str_replace('_',' ',$field)) }}

                </td>

                <td>

                    {{ $value['old'] }}

                </td>

                <td>

                    {{ $value['new'] }}

                </td>

            </tr>

            @endforeach

            </tbody>

        </table>

    </div>

    <div class="card-footer d-flex">

        <form method="POST"
              action="{{ route('admin.vendor.profile.request.approve',$requestData->id) }}"
              class="me-2">

            @csrf

            <button type="submit"
                    class="btn btn-success">

                Approve

            </button>

        </form>


        <form method="POST"
              action="{{ route('admin.vendor.profile.request.reject',$requestData->id) }}">

            @csrf

            <button type="submit"
                    class="btn btn-danger">

                Reject

            </button>

        </form>

    </div>

</div>

@endsection