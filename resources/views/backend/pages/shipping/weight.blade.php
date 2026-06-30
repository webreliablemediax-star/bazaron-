@extends('backend.layouts.master')

@section('contents')
    <div class="container-fluid">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="mb-0">
                    Weight
                </h4>

            </div>

            <div class="card-body">

                <form method="POST"
                    @if (isset($weight)) action="{{ route('admin.shipping.weight.update', $weight->id) }}"
                @else
                    action="{{ route('admin.shipping.weight.store') }}" @endif>

                    @csrf

                    <div class="row">

                        <div class="col-md-3">

                            <label class="form-label">
                                Title
                            </label>

                            <input type="text" name="title" class="form-control" placeholder="0-2 KG"
                                value="{{ $weight->title ?? '' }}">

                        </div>

                        <div class="col-md-2">

                            <label class="form-label">
                                Min Weight
                            </label>

                            <input type="number" step="0.01" name="min_weight" class="form-control"
                                value="{{ $weight->min_weight ?? '' }}">

                        </div>

                        <div class="col-md-2">

                            <label class="form-label">
                                Max Weight
                            </label>

                            <input type="number" step="0.01" name="max_weight" class="form-control"
                                value="{{ $weight->max_weight ?? '' }}">

                        </div>

                        <div class="col-md-3">

                            <label class="form-label">
                                Unit
                            </label>

                            <select name="unit" class="form-control">

                                <option value="">
                                    Select Unit
                                </option>

                                <option value="gram" {{ isset($weight) && $weight->unit == 'gram' ? 'selected' : '' }}>
                                    Gram
                                </option>

                                <option value="kg" {{ isset($weight) && $weight->unit == 'kg' ? 'selected' : '' }}>
                                    KG
                                </option>

                            </select>

                        </div>

                        <div class="col-md-2 mt-4">

                            <button class="btn btn-primary w-100">

                                @if (isset($weight))
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
                    Weight List
                </h5>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Title</th>

                            <th>Min Weight</th>

                            <th>Max Weight</th>

                            <th>Unit</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($weights as $item)
                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $item->title }}</td>

                                <td>{{ $item->min_weight }}</td>

                                <td>{{ $item->max_weight }}</td>

                                <td>{{ strtoupper($item->unit) }}</td>

                                <td>

                                    <a href="{{ route('admin.shipping.weight.edit', $item->id) }}" class="text-info me-2">

                                        <i class="fa fa-edit"></i>

                                    </a>

                                    <a href="{{ route('admin.shipping.weight.delete', $item->id) }}" class="text-danger"
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
