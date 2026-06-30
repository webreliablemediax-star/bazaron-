<?php
use Illuminate\Support\Facades\DB;
$countries = DB::table('countries')->get();

?>

<div class="modal fade addAddressModal" id="addAddressModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>

                <div class="gstore-product-quick-view bg-white rounded-3 py-6 px-4">
                    <h2 class="modal-title fs-5 mb-3">{{ localize('Add New Address') }}</h2>
                    <div class="row align-items-center g-4 mt-3">
                        <form action="{{ route('address.store') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('Full Name') }}</label>
                                        <input type="text" name="name" placeholder="Enter your full name"
                                            required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('Phone Number') }}</label>
                                        <input type="text" name="phone" placeholder="Enter your phone number"
                                            required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('Pincode') }}</label>
                                        <input type="text" id="pincode" name="pincode"
                                            placeholder="Enter 6-digit pincode" maxlength="6" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('State') }}</label>
                                        <input type="text" id="state" name="state_name" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('District') }}</label>
                                        <input type="text" id="district" name="district_name" readonly>
                                    </div>
                                </div>

                                <!-- <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('Taluka / Sub-District') }}</label>
                                        <input type="text" id="taluka" name="taluka_name"
                                            placeholder="Enter Taluka name">
                                    </div>
                                </div> -->

                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('Village / Area') }}</label>
                                        <select id="village" name="village" class="form-select">
                                            <option value="">{{ localize('Select Village / Area') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="label-input-field">
                                        <label>{{ localize('House / Building No.') }}</label>
                                        <input type="text" name="house_no" placeholder="Ex: H.No. 45, Green Villa"
                                            required>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="label-input-field">
                                        <label>{{ localize('Landmark (Optional)') }}</label>
                                        <input type="text" name="landmark" placeholder="Nearby school, temple, etc.">
                                    </div>
                                </div>




                                <div class="col-sm-6">
                                    <div class="w-100 label-input-field">
                                        <label>{{ localize('Default Address?') }}</label>
                                        <select class="select2Address" name="is_default">
                                            <option value="0">{{ localize('No') }}</option>
                                            <option value="1">{{ localize('Set Default') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="label-input-field">
                                        <label>{{ localize('Address') }}</label>
                                        <textarea rows="4" placeholder="{{ localize('2/5 Elephant Road, New Town') }}" name="address" required></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="mt-6 d-flex">
                                <button type="submit" class="btn btn-secondary btn-md me-3"
                                    style="margin-top: -25px;">{{ localize('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade editAddressModal" id="editAddressModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="gstore-product-quick-view bg-white rounded-3 py-6 px-4">
                    <h2 class="modal-title fs-5 mb-3">{{ localize('Update Address') }}</h2>

                    <div class="spinner pt-6 pb-8 d-none">
                        <div class="row align-items-center g-4 mt-3">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="edit-address d-none">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade deleteAddressModal" id="deleteAddressModal">
    <div class="modal-dialog address-delete-modal modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="bg-white rounded-3 py-6 px-4">
                    <h2 class="modal-title fs-5 mb-3">{{ localize('Delete Address') }}</h2>
                    <div class="pt-6 pb-8 text-center">
                        <h6>{{ localize('Want to delete this address?') }}</h6>
                    </div>
                    <div class="text-center">
                        <a href="" class="btn btn-secondary delete-address-link">{{ localize('Delete') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@section('scripts')
    <script>
        "use strict";

        var parent = '.addAddressModal';



        $(document).ready(function() {
            $('#pincode').on('keyup', function() {
                let pincode = $(this).val();

                if (pincode.length === 6) {
                    // API call to India Post
                    $.ajax({
                        url: `https://api.postalpincode.in/pincode/${pincode}`,
                        method: 'GET',
                        success: function(response) {
                            if (response[0].Status === "Success") {
                                let data = response[0].PostOffice[0];
                                let offices = response[0].PostOffice;

                                // Auto-fill State and District
                                $('#state').val(data.State);
                                $('#district').val(data.District);

                                // Populate Village dropdown
                                let villageSelect = $('#village');
                                villageSelect.empty();
                                villageSelect.append(
                                    `<option value="">Select Village / Area</option>`);
                                offices.forEach(function(office) {
                                    villageSelect.append(
                                        `<option value="${office.Name}">${office.Name}</option>`
                                        );
                                });
                            } else {
                                $('#state').val('');
                                $('#district').val('');
                                $('#village').empty().append(
                                    `<option value="">No Data Found</option>`);
                                alert('Invalid Pincode or not found in India Post records.');
                            }
                        },
                        error: function() {
                            alert('Unable to fetch data. Please check internet connection.');
                        }
                    });
                }
            });
        });



        // runs when the document is ready --> for media files
        $(document).ready(function() {
            if ($("input[name='shipping_address_id']").is(':checked')) {
                let city_id = $("input[name='shipping_address_id']:checked").data('city_id');
                getLogistics(city_id);
            }
        });


        //  new address
        function addNewAddress() {
            $('#addAddressModal').modal('show');
            parent = '.addAddressModal';
            addressModalSelect2(parent);
        }

        // 🧠 Edit Address Function
        function editAddress(id) {
            if (!id) {
                alert('Invalid Address ID');
                return;
            }

            // Show modal + loader
            $('#editAddressModal').modal('show');
            $('#editAddressModal .spinner').removeClass('d-none');
            $('#editAddressModal .edit-address').addClass('d-none');

            // AJAX call to fetch edit form
            $.ajax({
                url: `/address/${id}/edit`, // 👈 make sure route bana hua hai
                type: 'GET',
                success: function(response) {
                    // Hide loader, show form
                    $('#editAddressModal .spinner').addClass('d-none');
                    $('#editAddressModal .edit-address').removeClass('d-none').html(response);
                },
                error: function() {
                    $('#editAddressModal .spinner').addClass('d-none');
                    alert('Failed to load address data. Please try again.');
                }
            });
        }



        $(document).ready(function() {

            let phoneInput = $("input[name='phone']");

            // Default value set karo
            phoneInput.val('+91 ');

            phoneInput.on('input', function() {
                let val = $(this).val();

                // Ensure +91 always rahe
                if (!val.startsWith('+91 ')) {
                    val = '+91 ' + val.replace(/^\+91\s*/, '');
                }

                // Sirf numbers allow (after +91)
                let numbers = val.replace('+91 ', '').replace(/\D/g, '');

                // Max 10 digits
                numbers = numbers.substring(0, 10);

                $(this).val('+91 ' + numbers);
            });

            // Prevent deleting +91
            phoneInput.on('keydown', function(e) {
                if ($(this).val().length <= 4 && (e.key === "Backspace" || e.key === "Delete")) {
                    e.preventDefault();
                }
            });

        });
    </script>
@endsection

<style>
    /* Modal Width Improve */
    .addAddressModal .modal-dialog,
    .editAddressModal .modal-dialog {
        max-width: 800px;
    }

    /* Modal Box Styling */
    .gstore-product-quick-view {
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        padding: 0px !important;
    }

    /* Title */
    .modal-title {
        font-size: 20px;
        font-weight: 600;
        color: #222;
    }

    /* Label */
    .label-input-field label {
        font-size: 13px;
        font-weight: 500;
        color: #666;
        margin-bottom: 6px;
        display: block;
    }

    /* Inputs + Select + Textarea */
    .label-input-field input,
    .label-input-field select,
    .label-input-field textarea {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
        transition: all 0.25s ease;
        background: #fafafa;
    }

    /* Focus Effect */
    .label-input-field input:focus,
    .label-input-field select:focus,
    .label-input-field textarea:focus {
        border-color: #ff6a00;
        background: #fff;
        box-shadow: 0 0 0 2px rgba(255, 106, 0, 0.1);
        outline: none;
    }

    /* Readonly Fields */
    input[readonly] {
        background: #f3f4f6 !important;
        color: #888;
    }

    /* Select Dropdown Arrow Fix */
    .label-input-field select {
        cursor: pointer;
    }

    /* Spacing Fix */
    .row.g-4>div {
        margin-bottom: -10px;
    }

    /* Button Styling */
    .btn-secondary {
        background: linear-gradient(135deg, #ff6a00, #ff8c00);
        border: none;
        color: #fff;
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 8px;
        transition: 0.3s;
    }

    /* Button Hover */
    .btn-secondary:hover {
        background: linear-gradient(135deg, #e85d00, #ff6a00);
        transform: translateY(-1px);
    }

    /* Close Button */
    .btn-close {
        background-size: 12px;
        opacity: 0.6;
    }

    .btn-close:hover {
        opacity: 1;
    }

    /* Default Address Select Fix */
    .select2Address {
        width: 100% !important;
    }

    /* Village Dropdown Better Look */
    #village {
        background: #fafafa;
    }

    /* Scroll Fix (if form large) */
    .modal-body {
        max-height: 75vh;
        overflow-y: auto;
    }


    /* Heading ke neeche ka gap kam karo */
    .modal-title {
        margin-bottom: -14px !important;
    }

    /* Heading ke baad jo row hai uska top margin hatao */
    .gstore-product-quick-view .row.mt-3 {
        margin-top: 0 !important;
    }
</style>
