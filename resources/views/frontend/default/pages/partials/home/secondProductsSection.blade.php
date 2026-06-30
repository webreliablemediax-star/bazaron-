@if (getSetting('second_section_products') != null)

    @php
        $secondProductIds = json_decode(getSetting('second_section_products'));
        $secondProducts = \App\Models\Product::whereIn('id', $secondProductIds)
            ->isPublished()
            ->get();
    @endphp

    @if ($secondProducts->isNotEmpty())
        <section class="section second-products my-6">
    <div class="container-fluid px-0"> {{-- FIX: equal left-right gap --}}

        

        <!-- @if (getSetting('second_section_subtitle'))
            <p class="text-center text-muted mb-4">
                {{ getSetting('second_section_subtitle') }}
            </p>
        @endif -->

        {{-- Optional Banner --}}
        @if (getSetting('second_section_banner'))
            <div class="mb-4 text-center">
                <a href="{{ getSetting('second_section_banner_link') ?? '#' }}" target="_blank">
                    <img src="{{ uploadedAsset(getSetting('second_section_banner')) }}"
                        alt="Second Section Banner"
                        class="img-fluid">
                </a>
            </div>
        @endif
        <h2 class="section-title mb-4 text-center">
            {{ getSetting('second_section_title') ?? localize('') }}
        </h2>

        <div class="row g-2"> {{-- IMPORTANT: mx-0 for equal spacing --}}
            @foreach ($secondProducts as $product)
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
