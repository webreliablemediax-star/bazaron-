@php
    $top_category_ids = getSetting('top_category_ids') != null ? json_decode(getSetting('top_category_ids')) : [];
    $topCategories = \App\Models\Category::whereIn('id', $top_category_ids)->get();
@endphp

<section class="top-categories-section py-5">
    <div class="">
        {{-- <div class="container-fluid px-4"> --}}
        <!-- ✅ बस row ko swiper me convert kiya -->
        <div class="swiper categorySwiper">
            <div class="swiper-wrapper p-0">

                @foreach ($topCategories as $category)
                    <!-- ✅ बस col ko slide me convert kiya -->
                    <div class="swiper-slide col-xl-2-4 col-lg-2 col-md-4 col-12 p-0">

                        <a href="{{ route('category.landing', [
                            'slug' => $category->slug,
                            'category_code' => $category->category_code,
                        ]) }}"
                        class="category-lg text-decoration-none">

                            <h2 class="category-title-lg">
                                {{ $category->collectLocalization('name') }}
                            </h2>

                            <div class="category-img-lg">
                                <img src="{{ $category->thumbnail_image ? uploadedAsset($category->thumbnail_image) : asset('frontend/default/images/placeholder.png') }}"
                                    alt="{{ $category->collectLocalization('name') }}">
                            </div>

                        </a>

                    </div>
                @endforeach

            </div>



        </div>

    </div>
</section>

@section('scripts')
    <script>
        new Swiper(".categorySwiper", {

            slidesPerView: 5.2,
            spaceBetween: 15,
            loop: true,

            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },

            speed: 800,

            breakpoints: {

                // MOBILE → 1 full card + 0.4 next card preview
                320: {
                    slidesPerView: 1.3,
                    spaceBetween: 15
                },

                // SMALL TABLET
                576: {
                    slidesPerView: 2,
                    spaceBetween: 15
                },

                // TABLET
                768: {
                    slidesPerView: 3,
                    spaceBetween: 15
                },

                // DESKTOP
                1024: {
                    slidesPerView: 5,
                    spaceBetween: 15
                }

            }
        });
    </script>
@endsection
