@if (getSetting('third_section_products') != null)

    @php
        $thirdProductIds = json_decode(getSetting('third_section_products'));
        $thirdProducts = \App\Models\Product::whereIn('id', $thirdProductIds)
            ->isPublished()
            ->get();
    @endphp

    @if ($thirdProducts->isNotEmpty())
        <section class="section third-products my-5">
        <div class="container-fluid" style="padding-left:10%; padding-right:10%;">

                <h2 class="section-title mb-4 text-center">
    {{ getSetting('third_section_title') ?? localize('Third Product Section') }}
</h2>

@if (getSetting('third_section_subtitle'))
    <p class="text-center text-muted mb-4">
        {{ getSetting('third_section_subtitle') }}
    </p>
@endif

                @if (getSetting('third_section_banner'))
                    <div class="mb-4 text-center">
                        <a href="{{ getSetting('third_section_banner_link') ?? '#' }}" target="_blank">
                            <img src="{{ uploadedAsset(getSetting('third_section_banner')) }}"
                                class="img-fluid"
                                alt="Third Section Banner">
                        </a>
                    </div>
                @endif

                <div class="row justify-content-center g-4">
                    @foreach ($thirdProducts as $product)
                        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-10">
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
