@php
    $trending_product_ids = getSetting('top_trending_products') 
        ? json_decode(getSetting('top_trending_products')) 
        : [];

    $products = !empty($trending_product_ids)
        ? \App\Models\Product::whereIn('id', $trending_product_ids)->isPublished()->get()
        : collect([]);
@endphp

@if($products->count() > 0)
<section class="pt-0 pb-100 bg-white position-relative overflow-hidden z-1 trending-products-area">
    
     <div class="container-fluid px-3">
        <div class="row align-items-center">
            <div class="col-xl-5">
                    <div class="section-title text-center text-xl-start ps-3">
    <h3 class="mb-0" style="font-size:20px">
        {{ localize('Top Trending Products') }}
    </h3>
</div>
            </div>
            <div class="col-xl-7">
                <div class="filter-btns gshop-filter-btn-group text-center text-xl-end mt-4 mt-xl-0" style="margin-bottom: 4px;">
                    @php
                        $trending_product_categories = getSetting('trending_product_categories') != null 
                            ? json_decode(getSetting('trending_product_categories')) 
                            : [];
                        $categories = \App\Models\Category::whereIn('id', $trending_product_categories)->get();
                    @endphp


                    @foreach ($categories as $category)
                        <button data-filter=".{{ $category->id }}">
                            {{ $category->collectLocalization('name') }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

       <div class="row g-2 filter_group">
            @foreach ($products as $product)
                 <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-4 col-6 filter_item
@foreach ($product->categories as $category)
    {{ $category->id }}
@endforeach">
                    
                    @include('frontend.default.pages.partials.products.trending-product-card', [
                        'product' => $product,
                    ])
                </div>
            @endforeach
        </div>

    </div>
</section>
@endif
