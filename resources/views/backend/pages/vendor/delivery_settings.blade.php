@extends('backend.layouts.master')

@section('title')
    Delivery Settings
@endsection

@section('contents')

<div class="card">
    <div class="card-header">
        <h4>Delivery Settings</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.delivery.settings.update') }}"
              method="POST">

            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Free Delivery Text
                </label>

                <input type="text"
                       name="free_delivery_text"
                       class="form-control"
                       value="{{ getSetting('free_delivery_text') ?? '3-7 days' }}"
                       placeholder="Example: 3-7 days">

                <small class="text-muted">
                    Frontend par show hoga:
                    <strong>FREE Delivery in 3-7 days</strong>
                </small>

            </div>

            <button type="submit"
                    class="btn btn-primary">
                Save Changes
            </button>

        </form>

    </div>
</div>

@endsection