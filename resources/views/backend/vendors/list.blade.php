@extends('backend.layouts.master')

@section('title')
    vendors List
@endsection

@section('contents')
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Sellers List</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
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
                            <td>{{ $vendor->name }}</td>
                            <td>{{ $vendor->email }}</td>
                            <td>
                                {{ $vendor->vendorProfile->business_name ?? '-' }}
                            </td>

                            <td>
                                @if (optional($vendor->vendorProfile)->has_own_logistics)
                                    <span class="badge bg-success">Self Shipping</span>
                                @else
                                    <span class="badge bg-dark">Bazaron Shipping</span>
                                @endif
                            </td>
                            
                            <td>
                                <span
                                    class="badge btn-sm
                                    @if ($vendor->status == 'approved') bg-success
                                    @elseif($vendor->status == 'rejected')
                                        bg-danger
                                    @else
                                        bg-warning text-dark @endif
                                ">
                                    {{ ucfirst($vendor->status) }}
                                </span>
                            </td>
                            <td>
    <label class="switch">
        <input type="checkbox"
               value="{{ $vendor->id }}"
               onchange="updateVendorStatus(this)"
               {{ $vendor->is_active ? 'checked' : '' }}>
        <span class="slider round"></span>
    </label>
</td>
       
                            <td>
                                <a href="{{ route('admin.vendors.show', $vendor->id) }}"
                                    class="btn btn-sm btn-primary">View</a>


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
