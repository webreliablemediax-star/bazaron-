@extends('backend.layouts.master')

@section('contents')
    <div class="container-fluid">

        {{-- Add / Update GST --}}

        <div class="card">

            <div class="card-header">

                <h4 class="mb-0">
                    GST
                </h4>

            </div>

            <div class="card-body">

                <form method="POST"
                    @if (isset($gst)) action="{{ route('admin.gst.update', $gst->id) }}"
                @else
                    action="{{ route('admin.gst.store') }}" @endif>

                    @csrf

                    <div class="row">

                        <div class="col-md-10">

                            <label class="form-label">
                                GST
                            </label>

                            <input type="number" step="any" name="tax" class="form-control" placeholder="Enter GST"
                                value="{{ $gst->tax ?? '' }}">

                        </div>

                        <div class="col-md-2 mt-4">

                            <button class="btn btn-primary w-100">

                                @if (isset($gst))
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


        {{-- GST List --}}

        <div class="card mt-4">

            <div class="card-header">

                <h5 class="mb-0">
                    GST List
                </h5>

            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>GST</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($gsts as $item)
                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $item->tax }}%</td>

                                <td>

                                    <a href="{{ route('admin.gst.edit', $item->id) }}" class="text-info me-2">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <a href="{{ route('admin.gst.delete', $item->id) }}" class="text-danger"
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
