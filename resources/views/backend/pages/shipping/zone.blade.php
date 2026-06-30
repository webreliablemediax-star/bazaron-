@extends('backend.layouts.master')

@section('contents')
    <div class="container-fluid">

        <div class="card">

            <div class="card-header">
                <h4 class="mb-0">
                    Zone Master
                </h4>
            </div>

            <div class="card-body">

                <form method="POST"
                    @if (isset($zone)) action="{{ route('admin.shipping.zone.update', $zone->id) }}"
                @else
                    action="{{ route('admin.shipping.zone.store') }}" @endif>

                    @csrf

                    <div class="row">

                        <div class="col-md-5">

                            <label class="form-label">
                                Zone Name
                            </label>

                            <input type="text" name="zone_name" class="form-control" placeholder="Z-A"
                                value="{{ $zone->zone_name ?? '' }}">

                        </div>
                        <div class="col-md-5">

                            <label class="form-label">
                                Definition
                            </label>

                            <input type="text" name="definition" class="form-control" placeholder="Zone Definition"
                                value="{{ $zone->definition ?? '' }}">

                        </div>

                        <div class="col-md-2 mt-4">

                            <button class="btn btn-primary w-100">

                                @if (isset($zone))
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
                    Zone List
                </h5>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Zone Name</th>
                            <th>Definition</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($zones as $item)
                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $item->zone_name }}</td>
                                <td>{{ $item->definition }}</td>

                                <td>

                                    <a href="{{ route('admin.shipping.zone.edit', $item->id) }}" class="text-info me-2">

                                        <i class="fa fa-edit"></i>

                                    </a>

                                    <a href="{{ route('admin.shipping.zone.delete', $item->id) }}" class="text-danger"
                                        onclick="return confirm('Are you sure you want to delete this item?')">
                                        <i class="fa fa-trash"></i>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection
