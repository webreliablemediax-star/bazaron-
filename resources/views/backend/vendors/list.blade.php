@extends('backend.layouts.master')

@section('title')
    vendors List
@endsection

@section('contents')
   <div class="card border-0 shadow-sm rounded-4">
      <div class="card-header bg-white border-0 py-3 px-4">
          <div class="d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-0">Sellers List</h4>
        <small class="text-muted">Manage all registered sellers</small>
    </div>

    <span class="badge rounded-pill px-3 py-2"
      style="background:#dcfce7;color:#15803d;font-size:14px;">
    {{ $vendors->total() }} Sellers
</span>
</div>
        </div>
       <div class="table-responsive px-3 pb-3">
    <table class="table table-hover align-middle mb-0 vendor-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Business Name</th>
                        <th>Logistics</th>
                        <th>Status</th>
                        <th>Login Access</th>
                        <th>Action</th>


                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $vendor)
                        <tr>
                            <td>{{ $vendor->id }}</td>
                           <td>
    <div class="d-flex align-items-center">


       <div>
            <div class="fw-bold">{{ $vendor->name }}</div>
            <small class="text-muted">
                Seller #{{ $vendor->id }}
            </small>
        </div>

    </div>
</td>
                          <td>
    <div class="fw-semibold">
        {{ $vendor->email }}
    </div>
</td>
                          <td>
    <div class="fw-semibold">
        {{ $vendor->vendorProfile->business_name ?? '-' }}
    </div>
</td>

                            <td>
                              @if (optional($vendor->vendorProfile)->has_own_logistics)
    <span class="badge rounded-pill px-3 py-2"
          style="background:#dcfce7;color:#15803d;border:1px solid #86efac;">
        <i class="bi bi-truck"></i> Self Shipping
    </span>
@else
    <span class="badge rounded-pill px-3 py-2"
          style="background:#fff7ed;color:#ea580c;border:1px solid #fdba74;">
        <i class="bi bi-box-seam"></i> Bazaron Shipping
    </span>
@endif
                            </td>
                            
                           <td>
    @if($vendor->status == 'approved')
        <span class="badge rounded-pill bg-success px-3 py-2">
            <i class="bi bi-check-circle-fill"></i> Approved
        </span>

    @elseif($vendor->status == 'rejected')

        <span class="badge rounded-pill bg-danger px-3 py-2">
            <i class="bi bi-x-circle-fill"></i> Rejected
        </span>

    @else

        <span class="badge rounded-pill bg-warning text-dark px-3 py-2">
            <i class="bi bi-clock-fill"></i> Pending
        </span>

    @endif
</td>
                           <td class="text-center">
    <div class="form-check form-switch d-flex justify-content-center">
        <input class="form-check-input"
               type="checkbox"
               value="{{ $vendor->id }}"
               onchange="updateVendorStatus(this)"
               {{ $vendor->is_active ? 'checked' : '' }}>
    </div>
</td>
       
                            <td>
                              <a href="{{ route('admin.vendors.show',$vendor->id) }}"
   class="btn btn-sm btn-outline-success rounded-pill px-4">
    <i class="bi bi-eye me-1"></i> View
</a>

                               
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No sellers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            {{ $vendors->links() }}
        </div>
    </div>
@endsection
<script>
function updateVendorStatus(el)
{
    $.ajax({
        url: "{{ route('admin.vendor.login.status') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: el.value,
            status: el.checked ? 1 : 0
        },
        success: function(response) {

           if(el.checked){
    toastr.success('Seller login access enabled successfully');
}else{
    toastr.warning('Seller login access disabled successfully');
}

            console.log(response);
        },
        error: function(xhr) {

            alert('❌ Something went wrong. Please try again.');

            // Toggle ko wapas previous state me le jao
            el.checked = !el.checked;

            console.log(xhr.responseText);
        }
    });
}
</script>
