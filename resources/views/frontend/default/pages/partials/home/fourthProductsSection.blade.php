<!-- @if (getSetting('fourth_section_products') != null) -->

    @php
        $fourthProductIds = json_decode(getSetting('fourth_section_products'));
        $fourthProducts = \App\Models\Product::whereIn('id', $fourthProductIds)
            ->isPublished()
            ->get();
    @endphp

    @if ($fourthProducts->isNotEmpty())
        <section class="section fourth-products my-0">
    <div class="container-fluid px-3"> {{-- FIX: equal left-right gap --}}

      

        <!-- @if (getSetting('fourth_section_subtitle'))
            <p class="text-center text-muted mb-0">
                {{ getSetting('fourth_section_subtitle') }}
            </p>
        @endif -->
        

        {{-- Optional Banner --}}
        @if (getSetting('fourth_section_banner'))
            <div class="mb-4 text-center">
                <a href="{{ getSetting('fourth_section_banner_link') ?? '#' }}" target="_blank">
                    <img src="{{ uploadedAsset(getSetting('fourth_section_banner')) }}"
                         alt="fourth Section Banner"
                         class="img-fluid">
                </a>
            </div>
        @endif
<h2 class="section-title mb-4 text-center">
            {{ getSetting('fourth_section_title') ?? localize('fourth Product Section') }}
        </h2>

        <div class="row g-2"> {{-- REMOVE justify-content-center --}}
            @foreach ($fourthProducts as $product)
                <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-4 col-6">
                    @include(
                        'frontend.default.pages.partials.products.trending-product-card',
                        ['product' => $product]
                    )
                </div>
            @endforeach
        </div>

    </div>
</section>
    @endif
@endif


