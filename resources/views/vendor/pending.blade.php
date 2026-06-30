@extends('layouts.auth')

@section('content')
<meta http-equiv="refresh" content="10;url={{ route('home') }}">
<div class="container py-5">
    <div class="card shadow-lg p-5 text-center">
        <h2 class="mb-3 text-warning">⏳ Approval Pending</h2>
        <p class="text-muted">
            Your seller account is under review.<br>
            Please wait until approval.
        </p>
    </div>
</div>
@endsection
