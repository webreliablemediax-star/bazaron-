<!-- bannerstwo -->
@php
    $bannerOne = getSetting('banner_section_two_banner_one');
    $bannerTwo = getSetting('banner_section_two_banner_two');
    $linkOne = getSetting('banner_section_two_banner_one_link');
    $linkTwo = getSetting('banner_section_two_banner_two_link');
@endphp

@if($bannerOne || $bannerTwo)
<section class="position-relative banner-section z-1 py-0">

    <div class="container-fluid px-3">
        <div class="row g-0 mx-0">

            {{-- First Banner --}}
            @if(!empty($bannerOne))
            <div class="col-md-6">
                <a href="{{ $linkOne ?? '#' }}">
                    <img 
                        src="{{ uploadedAsset($bannerOne) }}" 
                        alt="Banner One"
                        class="img-fluid w-100 rounded"
                        loading="lazy"
                    >
                </a>
            </div>
            @endif

            {{-- Second Banner --}}
            @if(!empty($bannerTwo))
            <div class="col-md-6">
                <a href="{{ $linkTwo ?? '#' }}">
                    <img 
                        src="{{ uploadedAsset($bannerTwo) }}" 
                        alt="Banner Two"
                        class="img-fluid w-100 rounded"
                        loading="lazy"
                    >
                </a>
            </div>
            @endif

        </div>
    </div>

</section>
@endif
