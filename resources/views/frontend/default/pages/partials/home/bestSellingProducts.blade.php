@if (getSetting('best_selling_products') != null)

    @php
        $bestSellingProductIds = json_decode(getSetting('best_selling_products'));
        $bestSellingProducts = \App\Models\Product::whereIn('id', $bestSellingProductIds)
            ->isPublished()
            ->get();
    @endphp

    @if ($bestSellingProducts->isNotEmpty())
        <section class="section best-selling my-5 best-selling-section">

            {{-- 🔥 FULL WIDTH BANNER --}}
            @if (getSetting('best_selling_banner'))
                <div class="container-fluid px-0 mb-5">
<a href="{{ getSetting('best_selling_banner_link') ?? '#' }}" target="_blank">
                        <img src="{{ uploadedAsset(getSetting('best_selling_banner')) }}"
                             alt="Best Selling Banner"
                             class="img-fluid w-100 d-block">
                    </a>    
                </div>
            @endif

            {{-- 🔥 NORMAL CONTAINER FOR PRODUCTS --}}
            <div class="container">

<h2 class="section-title mb-3 text-center" style="margin-left:5px;">
                    {{ getSetting('best_selling_title') ?? localize('Best Selling Products') }}
                </h2>

                <!-- @if (getSetting('best_selling_subtitle'))
                    <p class="text-center text-muted mb-4">
                        {{ getSetting('best_selling_subtitle') }}
                    </p>
                @endif -->

                <div class="row g-2 justify-content-center">
                    @foreach ($bestSellingProducts as $product)
         <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-4 col-6">

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
