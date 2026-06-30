@if (count(generateVariationOptions($product->variation_combinations)) > 0)

    @foreach (generateVariationOptions($product->variation_combinations) as $variation)
        <input type="hidden" name="variation_id[]" value="{{ $variation['id'] }}" class="variation-for-cart">

        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="mb-2 fw-semibold" style="font-size:14px;">

            {{ $variation['name'] }}

        </div>

        @php

            $hasImage = collect($product->variations)->whereNotNull('image')->count() > 0;

        @endphp


        {{-- IMAGE CARD VARIATIONS --}}
        @if ($hasImage && $loop->last)
            <div class="product-variations-wrapper bazaron-color-grid mb-4">

                @foreach ($variation['values'] as $value)
                    @php

                        $imageVariation = null;

                        foreach ($product->variations as $v) {
                            if (!$v->image) {
                                continue;
                            }

                            $keys = explode('/', $v->variation_key);

                            foreach ($keys as $k) {
                                if (str_contains($k, ':' . $value['id'])) {
                                    $imageVariation = $v;

                                    break 2;
                                }
                            }
                        }

                    @endphp

                    <label class="bazaron-color-box">

                        <input type="radio" class="variation-option" data-variation-id="{{ $variation['id'] }}"
                            name="variation_value_for_variation_{{ $variation['id'] }}" value="{{ $value['id'] }}"
                            hidden>

                        <div class="color-card">

                            @if ($imageVariation && $imageVariation->image)
                                <img src="{{ uploadedAsset($imageVariation->image) }}" class="variation-color-image"
                                    data-value-id="{{ $value['id'] }}" data-variation-id="{{ $variation['id'] }}">
                            @endif

                            <div class="color-name">

                                {{ $value['name'] }}

                            </div>

                        </div>

                    </label>
                @endforeach

            </div>
        @else
            {{-- NORMAL BUTTON VARIATIONS --}}
            <div class="product-variations-wrapper bazaron-size-grid mb-3">

                @foreach ($variation['values'] as $value)
                    <label class="bazaron-size-box">

                        <input type="radio" class="variation-option" data-variation-id="{{ $variation['id'] }}"
                            name="variation_value_for_variation_{{ $variation['id'] }}" value="{{ $value['id'] }}"
                            hidden>

                        <span>

                            {{ $value['name'] }}

                        </span>

                    </label>
                @endforeach

            </div>
        @endif
    @endforeach

@endif


<style>
    .bazaron-color-grid {

        display: flex;

        gap: 16px;

        flex-wrap: wrap;

        align-items: flex-start;

    }

    .bazaron-color-box {

        margin: 0;

        cursor: pointer;

        display: block;

    }

    .color-card {

        width: 85px;

        min-height: 95px;

        padding: 7px;

        display: flex;

        flex-direction: column;

        align-items: center;

        justify-content: flex-start;

        background: #fff;

        border: 1px solid #dfe3ea;

        border-radius: 18px;

        transition: all .18s ease;

        overflow: hidden;

    }

    .bazaron-color-box:hover .color-card {

        transform: translateY(-2px);

        box-shadow:
            0 8px 18px rgba(0, 0, 0, .08);

    }

    .bazaron-color-box input:checked+.color-card {

        border: 2px solid #0d6efd;

        box-shadow:
            0 0 0 4px rgba(13, 110, 253, .12);

    }

    .variation-color-image {

        width: 78px;

        height: 78px;

        display: block;

        object-fit: cover;

        border-radius: 14px;

        background: #f5f6f8;

        margin-bottom: 5px;

        flex-shrink: 0;

    }

    .color-name {

        font-size: 14px;

        font-weight: 600;

        line-height: 18px;

        color: #232323;

        text-align: center;

        margin: 0;

        padding: 0;

        max-width: 100%;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;

    }
</style>


<script>
    window.variationImages = {

        @foreach ($product->variations as $v)

            @if ($v->image)

                "{{ $v->image }}": "{{ uploadedAsset($v->image) }}",
            @endif
        @endforeach

    };
</script>




<script>
    document.addEventListener(
        'DOMContentLoaded',
        function() {



            // 🔥 MAIN UPDATE FUNCTION

            function updateUI() {

                let selectedKeys = [];

                document
                    .querySelectorAll(
                        '.variation-option:checked'
                    )
                    .forEach(r => {

                        selectedKeys.push(
                            r.dataset.variationId +
                            ':' +
                            r.value
                        );

                    });






                // 🔥 UPDATE ONLY VARIATION CARD IMAGES

                document
                    .querySelectorAll(
                        '.variation-color-image'
                    )
                    .forEach(function(img) {

                        let currentValueId =
                            img.dataset.valueId;

                        let currentVariationId =
                            img.dataset.variationId;





                        // selected except current variation
                        let otherSelected = [];

                        document
                            .querySelectorAll(
                                '.variation-option:checked'
                            )
                            .forEach(r => {

                                if (
                                    r.dataset.variationId !==
                                    currentVariationId
                                ) {

                                    otherSelected.push(
                                        r.dataset.variationId +
                                        ':' +
                                        r.value
                                    );

                                }

                            });






                        // build exact combination for THIS card
                        let tempKeys = [

                            ...otherSelected,

                            currentVariationId +
                            ':' +
                            currentValueId

                        ];






                        // exact variation match
                        let matched =
                            variations.find(v => {

                                if (!v.image)
                                    return false;

                                let keys =
                                    v.key
                                    .split('/')
                                    .filter(Boolean);

                                return (
                                    keys.length ===
                                    tempKeys.length &&
                                    tempKeys.every(
                                        k =>
                                        keys.includes(k)
                                    )
                                );

                            });






                        if (
                            matched &&
                            window.variationImages[
                                matched.image
                            ]
                        ) {

                            let url =
                                window
                                .variationImages[
                                    matched.image
                                ];



                            // ✅ ONLY CARD IMAGE CHANGE
                            img.src =
                                url;

                        }

                    });

            }






            // 🔥 EVENTS

            document
                .querySelectorAll(
                    '.variation-option'
                )
                .forEach(el => {

                    el.addEventListener(
                        'change',
                        updateUI
                    );

                });






            // 🔥 DEFAULT SELECT

            document
                .querySelectorAll(
                    '.product-variations-wrapper'
                )
                .forEach(wrapper => {

                    let first =
                        wrapper.querySelector(
                            '.variation-option'
                        );

                    if (first) {

                        first.checked = true;

                    }

                });






            // 🔥 INIT

            updateUI();


            // 🔥 PERFECT HOVER PREVIEW

            document
                .querySelectorAll(
                    '.variation-color-image'
                )
                .forEach(function(img) {

                    img.addEventListener(
                        'mouseenter',
                        function() {

                            let mainImage =
                                document.getElementById(
                                    'mainImage'
                                );

                            if (!mainImage)
                                return;


                            // ALWAYS SAVE CURRENT IMAGE
                            mainImage.dataset.tempOriginal =
                                mainImage.src;


                            // TEMP PREVIEW
                            mainImage.src =
                                this.src;

                        });






                    img.addEventListener(
                        'mouseleave',
                        function() {

                            let mainImage =
                                document.getElementById(
                                    'mainImage'
                                );

                            if (
                                !mainImage ||
                                !mainImage.dataset.tempOriginal
                            )
                                return;


                            // RESTORE
                            mainImage.src =
                                mainImage.dataset.tempOriginal;

                        });

                });



        });
</script>
