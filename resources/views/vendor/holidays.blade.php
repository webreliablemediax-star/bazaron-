@extends('backend.layouts.master')

@section('title')
    Holiday Calendar
@endsection

@section('contents')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4 class="mb-0">Holiday Calendar</h4>
        </div>

        <div class="card-body">

            @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('vendor.holidays.store') }}"
      method="POST"
      class="row g-3 mb-4">

    @csrf

    <div class="col-md-5">
        <label class="form-label">Holiday Name</label>

        <input type="text"
               name="holiday_name"
               class="form-control"
               placeholder="e.g. Diwali"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Holiday Date</label>

        <input type="date"
               name="holiday_date"
               class="form-control"
               required>
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <button type="submit"
                class="btn btn-success w-100">
            + Add Holiday
        </button>
    </div>

</form>

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>
    <tr>
        <th>Holiday Name</th>
        <th>Date</th>
        <th width="100">Action</th>
    </tr>
</thead>

                    <tbody>

                        @forelse($holidays as $holiday)

                           <tr>
    <td>{{ $holiday->holiday_name }}</td>

    <td>
        {{ \Carbon\Carbon::parse($holiday->holiday_date)->format('d M Y') }}
    </td>

    <td>
        <form action="{{ route('vendor.holidays.delete', $holiday->id) }}"
              method="POST"
              onsubmit="return confirm('Delete this holiday?')">

            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn btn-sm btn-danger">
                <i class="fa fa-trash"></i>
            </button>

        </form>
    </td>
</tr>

                        @empty

                            <tr>
                                <td colspan="3" class="text-center">
    No holidays added yet.
</td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection