@extends('backend.layouts.master')
@section('title','Manage Address')
@section('contents')
<div class="container py-4">
<div class="card-header text-start py-2" style="background:#2a2b2d;border-radius:5px;">
   <h5 class="mb-0 text-white" style="margin-left:6px;">Manage Address</h5>
</div>
<div class="card-body">
@if(session('success'))
<div class="alert alert-success">
   {{ session('success') }}
</div>
@endif
<div class="row">
   <!-- LEFT SIDE (FORM - SAME LOGIC) -->
   <div class="col-md-6">
      <div class="card p-3">
         <h5>Primary Address</h5>
         <form action="{{ route('vendor.manage.address.update') }}" method="POST">
            @csrf
            {{-- GST Number --}}
            <div class="mb-3">
               <label class="form-label">
               GST Number <span class="text-danger">*</span>
               </label>
               <input type="text"
                  name="gst_number"
                  id="gst_number"
                  class="form-control"
                  maxlength="15"
                  style="text-transform:uppercase"
                  value="{{ $vendor->gst_number ?? '' }}"
                  required>
               <small class="text-muted">
               Enter valid GST number (Example: 22AAAAA0000A1Z5)
               </small>
            </div>
            {{-- Warehouse Address --}}
            <div class="mb-3">
               <label class="form-label">Warehouse Address</label>
               <textarea name="warehouse_address"
                  id="warehouse_address"
                  class="form-control"
                  rows="3">{{ $vendor->warehouse_address ?? '' }}</textarea>
            </div>
            <div class="row">
               <div class="col-md-4 mb-3">
                  <label class="form-label">Pincode</label>
                  <input type="text" name="zip" id="pincode" class="form-control"
                     value="{{ $vendor->zip ?? '' }}">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">City</label>
                  <input type="text" name="city" id="city" class="form-control"
                     value="{{ $vendor->city ?? '' }}">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">State</label>
                  <input type="text" name="state" id="state" class="form-control"
                     value="{{ $vendor->state ?? '' }}">
               </div>
            </div>
            <button type="submit" class="btn btn-dark px-4">
            Update Address
            </button>
         </form>
      </div>
   </div>
   <!-- RIGHT SIDE -->
   <div class="col-md-6">
      <div class="card p-3">
         <h5>Saved Addresses</h5>
         <!-- 1. DEFAULT ADDRESS -->
         <div class="border rounded p-3 mb-3 bg-light">
            <h6>Default Address</h6>
            <p>{{ $vendor->gst_number }}</p>
            <p>{{ $vendor->warehouse_address }}</p>
            <p>{{ $vendor->city }}, {{ $vendor->state }} - {{ $vendor->zip }}</p>
         </div>
         <!-- 2. UPDATED ADDRESS (LIVE FROM LEFT) -->
         <div class="border rounded p-3 mb-3">
            <h6>Address List</h6>
            @if(isset($addresses) && count($addresses))
            @foreach($addresses as $addr)
            <div class="border rounded p-2 mb-2">
               <!-- RADIO -->
               <input type="radio"
               name="default_address"
               {{ $addr->is_default ? 'checked' : '' }}
               onchange="setDefault({{ $addr->id }})">
               <p><b>GST:</b> {{ $addr->gst_number }}</p>
               <p>{{ $addr->warehouse_address }}</p>
               <p>{{ $addr->city }}, {{ $addr->state }} - {{ $addr->zip }}</p>
            </div>
            @endforeach
            @else
            <p class="text-muted">No addresses added yet</p>
            @endif
         </div>
         <!-- 3. ADD NEW -->
         <button type="button" class="btn btn-primary" onclick="toggleNewAddress()">
         + Add New Address
         </button>
         <!-- HIDDEN FORM -->
         <form method="POST" action="{{ route('vendor.address.store') }}">
            @csrf
            <div id="newAddressForm" class="mt-3" style="display:none;">
               <input type="text" name="new_gst_number"
                  class="form-control mb-2"
                  placeholder="GST Number"
                  maxlength="15"
                  style="text-transform:uppercase">
               <textarea name="new_warehouse_address"
                  class="form-control mb-2"
                  placeholder="Warehouse Address"></textarea>
               <div class="row">
                  <div class="col-md-4">
                     <input type="text" name="new_zip" id="new_pincode"
                        class="form-control mb-2"
                        placeholder="Pincode">
                  </div>
                  <div class="col-md-4">
                     <input type="text" name="new_city" id="new_city"
                        class="form-control mb-2"
                        placeholder="City">
                  </div>
                  <div class="col-md-4">
                     <input type="text" name="new_state" id="new_state"
                        class="form-control mb-2"
                        placeholder="State">
                  </div>
               </div>
               <button type="submit" class="btn btn-success btn-sm">Save</button>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection
@section('scripts')
<script>
   document.addEventListener("DOMContentLoaded", function(){
   
   let gstInput = document.querySelector("input[name='gst_number']");
   
   gstInput.addEventListener("input", function(){
   
   this.value = this.value.toUpperCase();
   
   if(this.value.length > 15){
   this.value = this.value.slice(0,15);
   }
   
   });
   
   });
   
   
   
   
   
   document.addEventListener("DOMContentLoaded", function(){
   
   let gstInput = document.getElementById("gst_number");
   
   gstInput.addEventListener("blur", function(){
   
   let gst = this.value.toUpperCase();
   
   let gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[A-Z0-9]{1}Z[A-Z0-9]{1}$/;
   
   if(gst.length === 15 && !gstRegex.test(gst)){
       alert("Invalid GST Number. Please enter a valid GST format like 22AAAAA0000A1Z5");
   }
   
   });
   
   });
   
   
   
   
   function toggleNewAddress(){
   
       let form = document.getElementById('newAddressForm');
   
       if(form.style.display === 'none' || form.style.display === ''){
           form.style.display = 'block';
       } else {
           form.style.display = 'none';
       }
   }
   
   
   document.addEventListener("DOMContentLoaded", function(){
   
       let newPin = document.getElementById("new_pincode");
   
       if(newPin){
           newPin.addEventListener("blur", function(){
   
               let pincode = this.value;
   
               if(pincode.length === 6){
   
                   fetch("https://api.postalpincode.in/pincode/" + pincode)
                   .then(res => res.json())
                   .then(data => {
   
                       if(data[0].Status === "Success"){
   
                           document.getElementById("new_city").value = data[0].PostOffice[0].District;
                           document.getElementById("new_state").value = data[0].PostOffice[0].State;
   
                       }else{
                           alert("Invalid Pincode");
                       }
   
                   });
   
               }
   
           });
       }
   
   });
   
   
   function setDefault(id){
       fetch('/vendor/address/default/' + id, {
           method: 'POST',
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           }
       })
       .then(() => location.reload());
   }
   
   
</script>
@endsection