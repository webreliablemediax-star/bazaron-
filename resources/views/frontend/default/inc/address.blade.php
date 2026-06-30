<div class="bazaron-address-box">

    <div class="d-flex justify-content-between">

        <div>
            <strong>{{ $address->name ?? '' }}</strong><br>

            {{ $address->house_no ?? '' }},
            {{ $address->address }},
            
            {{ $address->village ?? '' }},
            {{ $address->district_name ?? '' }},
            {{ $address->state_name ?? '' }},
            {{ $address->pincode ?? '' }},
            {{ $address->country_name ?? 'India' }}
            <br>
            <span class="">
                Landmark : {{ $address->landmark ?? '' }}
            </span><br>
            <span class="text-muted">
                Phone: {{ $address->phone ?? '' }}
            </span>
        </div>

    </div>

</div>


<style>
    .bazaron-address{
    font-size:14px;
    line-height:1.6;
    color:#111;
}

.bazaron-address strong{
    font-size:15px;
    font-weight:600;
}
.tt-address-info{
border:1px solid #ddd;
}

.tt-address-info:hover{
border:1px solid #ff9900;
}
</style>