@extends('backend.layouts.master')

@section('title')
    Invoice Configuration
@endsection

@section('contents')

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Invoice Configuration</h4>
        </div>

        <div class="card-body">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('vendor.invoice.config.save') }}" method="POST">
                @csrf

                <div class="row align-items-end">

                    {{-- Prefix --}}
                    <div class="col-md-5">
                        <label class="form-label">Invoice Prefix</label>
                        <input type="text" 
                            name="invoice_prefix" 
                            class="form-control"
                            value="{{ $vendor->invoice_prefix }}"
                            placeholder="Enter Prefix (e.g. INV)">
                    </div>

                   

                    {{-- Serial --}}
                    <div class="col-md-5">
                        <label class="form-label">Starting Serial</label>
                        <input type="number" 
                            name="invoice_serial" 
                            class="form-control"
                            value="{{ $vendor->invoice_last_number + 1 }}"
                            placeholder="Enter Starting Number">
                    </div>

                </div>

                <br>

                {{-- Preview --}}
                <div class="alert alert-info">
                    <strong>Preview:</strong>
                    {{ $vendor->invoice_prefix }}{{ $vendor->invoice_last_number + 1 }}
                    <!--{{ str_pad($vendor->invoice_last_number + 1, 4, '0', STR_PAD_LEFT) }}-->
                </div>

                <button type="submit" class="btn btn-success">
                    Update Invoice Settings
                </button>

            </form>

        </div>
    </div>

</div>

@endsection