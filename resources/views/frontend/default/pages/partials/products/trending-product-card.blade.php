<!-- trendingprodu -->
<div class="vertical-product-card rounded-2 position-relative {{ isset($bgClass) ? $bgClass : '' }}">
   <a href="{{ route('products.show', $product->slug) }}">
      @php
      $discountPercentage = discountPercentage($product);
      @endphp
      @if ($discountPercentage > 0)
      <span class="offer-badge text-white fw-bold fs-xxs bg-danger position-absolute start-0 top-0">
      -{{ discountPercentage($product) }}% <span class="text-uppercase">{{ localize('Off') }}</span>
      </span>
      @endif
      <div class="thumbnail position-relative text-center ">
         <img src="{{ uploadedAsset($product->thumbnail_image) }}" alt="{{ $product->collectLocalization('name') }}"
            class="img-fluid">
         <div class="product-btns position-absolute d-flex gap-2 flex-column">
            <!-- @if (Auth::check() && Auth::user()->user_type == 'customer')
   <a href="javascript:void(0);" class="rounded-btn"><i class="fa-regular fa-heart"
      onclick="addToWishlist({{ $product->id }})"></i></a>
   @elseif(!Auth::check())
   <a href="javascript:void(0);" class="rounded-btn"><i class="fa-regular fa-heart"
      onclick="addToWishlist({{ $product->id }})"></i></a> -->
   @endif
   <!-- <a href="javascript:void(0);" class="rounded-btn" onclick="showProductDetailsModal({{ $product->id }})"><i
      class="fa-regular fa-eye"></i></a> -->
   </div>
   </div>
   <div class="card-content">
      @if (getSetting('enable_reward_points') == 1)
      <span class="fs-xxs fw-bold" data-bs-toggle="tooltip" data-bs-placement="top"
         data-bs-title="{{ localize('Reward Points') }}">
      <i class="fas fa-medal"></i> {{ $product->reward_points }}
      </span>
      @endif
      <!--product category start-->
      <!-- <div class="mb-2 tt-category tt-line-clamp tt-clamp-1">
         @if ($product->categories()->count() > 0)
             @foreach ($product->categories as $category)
                 <a href="{{ route('products.index') }}?&category_id={{ $category->id }}"
                     class="d-inline-block text-muted fs-xxs">{{ $category->collectLocalization('name') }}
                     @if (!$loop->last)
                         ,
                     @endif
                 </a>
             @endforeach
         @endif
         </div> -->
      <!--product category end-->
      <a href="{{ route('products.show', $product->slug) }}"
         class="card-title mb-2 product-title">
      {{ $product->collectLocalization('name') }}
      </a>
      <div class="product-price mt-1 mb-2">
         @include('frontend.default.pages.partials.products.pricing', [
         'product' => $product,
         'onlyPrice' => true,
         ])
      </div>
      @isset($showSold)
      <div class="card-progressbar mt-3 mb-2 rounded-pill">
         <span class="card-progress bg-primary" data-progress="{{ sellCountPercentage($product) }}%"
            style="width: {{ sellCountPercentage($product) }}%;"></span>
      </div>
      <p class="mb-0 fw-semibold">{{ localize('Total Sold') }}: <span
         class="fw-bold text-secondary">{{ $product->total_sale_count }}/{{ $product->sell_target }}</span>
      </p>
      @endisset
   </div>
   <!-- <div class="card-btn bg-white">
      @php
      $isVariantProduct = 0;
      $stock = 0;
      if ($product->variations()->count() > 1) {
      $isVariantProduct = 1;
      } else {
      $stock = $product->variations[0]->product_variation_stock ? $product->variations[0]->product_variation_stock->stock_qty : 0;
      }
      @endphp
      @if ($isVariantProduct)
      <a href="javascript:void(0);" class="btn btn-secondary d-block btn-md rounded-1"
         onclick="showProductDetailsModal({{ $product->id }})">{{ localize('Add to Cart') }}</a>
      @else
      <form action="" class="direct-add-to-cart-form">
         <input type="hidden" name="_token" value="{{ csrf_token() }}">
         <input type="hidden" name="product_variation_id" value="{{ $product->variations[0]->id }}">
         <input type="hidden" value="1" name="quantity">
         @if (!$isVariantProduct && $stock < 1)
         @else
         <a href="javascript:void(0);" onclick="directAddToCartFormSubmit(this)"
            class="btn btn-secondary d-block btn-md rounded-1 w-100 direct-add-to-cart-btn add-to-cart-text">
         {{ localize('Add to Cart') }}
         </a>
         @endif
      </form>
      @endif
   </div> -->
   </a>
</div>
<style>
  .card-content{
    display: flex;
    flex-direction: column;
    flex: 1;
}

.product-title{
    display: -webkit-box !important;
    -webkit-line-clamp: 4 !important;
    -webkit-box-orient: vertical !important;

    overflow: hidden !important;
    text-overflow: ellipsis;

    line-height: 1.3 !important;

    height: calc(1.3em * 4) !important;
    min-height: calc(1.3em * 4) !important;
    max-height: calc(1.3em * 4) !important;

    margin-bottom: 12px !important;
}

.product-price{
    margin-top: auto;
}

.price{
    font-size:12px !important;
    font-weight:500 !important;
}
   .product-price,
   .price{
   font-size:12px !important;   /* thoda small */
   font-weight:500 !important;  /* bold hata diya */
   margin-top:2px;
   }
   @media (max-width:767px){

    .card-content{
        display:flex;
        flex-direction:column;
    }

    .product-title{
        min-height:84px !important;
        margin-bottom:12px !important;
    }

    .product-price{
    margin-top:5px !important;
}
.product-title{
    min-height:auto !important;
    margin-bottom:5px !important;
}

@media (max-width:767px){  

    .product-title{
        height: calc(1.3em * 4) !important;
        min-height: calc(1.3em * 4) !important;
        max-height: calc(1.3em * 4) !important;

        margin-bottom: 8px !important;
    }
@media (max-width:767px){

    .vertical-product-card .thumbnail img{
        width:100% !important;
        height:auto !important;
        display:block;
    }

    .vertical-product-card .thumbnail{
        overflow:hidden;
    }

}
</style>