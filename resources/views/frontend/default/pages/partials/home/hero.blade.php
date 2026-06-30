<section class="gshop-hero position-relative" style="min-height:auto;">

    {{-- HERO SLIDER --}}
    <div id="heroSlider" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="4000">

        <div class="carousel-inner h-100">

            @foreach ($sliders as $slider)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">

                    @if($loop->first)
                        {{-- FIRST SLIDE = LCP (fast load) --}}
                        
                        <img 
                            src="{{ uploadedAsset($slider->image) }}"
                            class="d-block w-100 hero-img"
                            style="height:auto; object-fit:cover;"
                            alt="hero banner"
                            loading="eager"
                            fetchpriority="high"
                            decoding="async"
                        >
                    @else
                        {{-- OTHER SLIDES (lazy load) --}}
                       
                        <img 
                            src="{{ uploadedAsset($slider->image) }}"
                            class="d-block w-100 hero-img"
                            style="height:auto; object-fit:cover;"
                            alt="hero banner"
                            loading="eager"
                            fetchpriority="high"
                            decoding="async"
                        >
                     @endif

                </div>
            @endforeach

        </div>

        {{-- PREV BUTTON --}}
        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        {{-- NEXT BUTTON --}}
        <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

        {{-- DOT INDICATORS (optional but recommended) --}}
        <!--<div class="carousel-indicators">-->
        <!--    @foreach ($sliders as $slider)-->
        <!--        <button -->
        <!--            type="button" -->
        <!--            data-bs-target="#heroSlider" -->
        <!--            data-bs-slide-to="{{ $loop->index }}" -->
        <!--            class="{{ $loop->first ? 'active' : '' }}">-->
        <!--        </button>-->
        <!--    @endforeach-->
        <!--</div>-->

    </div>

</section>