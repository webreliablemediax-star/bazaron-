







<div class="top-categories-overlay position-absolute w-100 start-0">
    <div class="container">
        <div class="row g-4 justify-content-center">
            @foreach ($topCategories as $category)
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="{{ route('category.landing', [
                        'slug' => $category->slug,
                        'category_code' => $category->category_code,
                    ]) }}"
                    class="category-card text-center">
                        <img src="{{ uploadedAsset($category->image) }}" alt="">
                        <h6>{{ $category->collectLocalization('name') }}</h6>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
