<section class="section featured-products fs" style="margin-top:280px">
   <div class="container-fluid px-3"> {{-- MATCHED WITH TOP CATS --}}

        <h2 class="section-title mb-4 text-center">
            Featured Products
        </h2>

       <div class="row g-4">
            @foreach ($featuredProductsLeft->take(18) as $product)
               <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-4 col-6">
                    @include('frontend.default.pages.partials.products.trending-product-card', [
                        'product' => $product,
                    ])
                </div>
            @endforeach
        </div>

    </div>
</section>
