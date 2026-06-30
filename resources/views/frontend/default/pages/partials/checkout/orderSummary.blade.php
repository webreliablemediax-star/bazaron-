<div style="background:#f6f7f8; border-radius:12px; padding:10px;">
    <div
        style="background:#fff; border-radius:12px; border:1px solid #ececec; padding:20px; max-width:520px; margin:0 auto;">

        @php
            $is_free_shipping = false;
            $grandTotal = getSubTotal($carts, false, '', false);
            $tax = getTotalTax($carts);
            $subTotal = $grandTotal - $tax;

            if (getCoupon() != '' && getCouponDiscount($grandTotal, getCoupon()) > 0) {
                $coupon = \App\Models\Coupon::where('code', getCoupon())->first();
                if (!is_null($coupon) && $coupon->is_free_shipping == 1) {
                    $is_free_shipping = true;
                }
            }

            $shipping = isset($shippingAmount) && !$is_free_shipping ? $shippingAmount : 0;
            $couponDiscount = getCouponDiscount($grandTotal, getCoupon());
            $total = $grandTotal + $shipping - $couponDiscount;
            $totalQty = $carts->sum('qty');

        @endphp

        {{-- Header --}}
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
            <span style="font-size:17px; font-weight:700; color:#1a1a1a;">{{ localize('Order Summary') }}</span>
            <span
                style="background:#f1f1f1; color:#666; font-size:12px; padding:3px 10px; border-radius:6px;">{{ $totalQty }}
                {{ $totalQty == 1 ? localize('Item') : localize('Items') }}</span>
        </div>

        {{-- Free delivery banner --}}
        {{-- @if ($shipping == 0)
            <div
                style="background:#e9f7ef; border-radius:8px; padding:10px 14px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:16px;">
                <span
                    style="font-size:14px; font-weight:600; color:#1a9e54;">{{ localize('Yay! You got FREE Delivery') }}</span>
                <span style="font-size:13px; color:#666;">
                    {{ localize('Delivery by') }}
                    <strong style="color:#1a1a1a;">{{ now()->addDays(2)->format('d M') }}</strong>
                </span>
            </div>
        @endif --}}

        {{-- Items --}}
        @foreach ($carts as $cart)
            @if ($cart->product_variation && $cart->product_variation->product)
                @php
                    $product = $cart->product_variation->product;
                    $variation = $cart->product_variation;
                    $unitPrice = variationDiscountedPrice($product, $variation, false);
                    $itemTotal = $unitPrice * $cart->qty;
                    $thumb = $variation->image ?: $product->thumbnail_image;
                    $imgUrl = null;
                    if ($thumb) {
                        try {
                            $imgUrl = asset('storage/' . $thumb);
                        } catch (\Exception $e) {
                            $imgUrl = null;
                        }
                    }
                @endphp

                <div
                    style="display:flex; gap:14px; padding-bottom:16px; margin-bottom:16px; border-bottom:1px solid #f0f0f0;">

                    @php
                        $thumb = $variation->image ?: $product->thumbnail_image;
                        $imgUrl = $thumb ? uploadedAsset($thumb) : null;
                    @endphp

                    <div
                        style="width:64px; height:64px; border-radius:8px; background:#f5f5f5; flex-shrink:0; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                        @if ($imgUrl)
                            <img src="{{ $imgUrl }}" alt="{{ $product->name }}"
                                style="width:100%; height:100%; object-fit:cover; display:block;"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <i class="las la-mobile" style="font-size:26px; color:#bbb; display:none;"></i>
                        @else
                            <i class="las la-mobile" style="font-size:26px; color:#bbb;"></i>
                        @endif
                    </div>

                    <div style="flex:1; min-width:0;">
                        <p style="font-size:14px; font-weight:600; color:#1a1a1a; margin:0 0 8px; line-height:1.4;">
                            {{ $product->name }}</p>
                        <span
                            style="background:#f1f1f1; color:#666; font-size:12px; padding:3px 10px; border-radius:6px;">{{ localize('Qty') }}:
                            {{ $cart->qty }}</span>
                    </div>

                    <div style="text-align:right; flex-shrink:0;">
                        <p style="font-size:14px; font-weight:600; color:#1a1a1a; margin:0;">
                            {{ formatPrice($itemTotal) }}</p>
                        @if ($cart->qty > 1)
                            <p style="font-size:12px; color:#1a9e54; margin:6px 0 0;">({{ formatPrice($unitPrice) }}
                                {{ localize('each') }})</p>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach

        {{-- Price breakdown --}}
        <div style="display:flex; flex-direction:column; gap:10px; padding-bottom:16px;">
            <div style="display:flex; justify-content:space-between; font-size:14px; color:#666;">
                <span>{{ localize('Subtotal') }} ({{ $totalQty }} {{ localize('Items') }})</span>
                <span style="color:#1a1a1a;">{{ formatPrice($subTotal) }}</span>
            </div>

            @if ($couponDiscount > 0)
                <div style="display:flex; justify-content:space-between; font-size:14px; color:#666;">
                    <span>{{ localize('Discount') }}</span>
                    <span style="color:#1a9e54;">- {{ formatPrice($couponDiscount) }}</span>
                </div>
            @endif

            <div style="display:flex; justify-content:space-between; font-size:14px; color:#666;">
                <span>{{ localize('Delivery Charge') }}</span>
                @if ($shipping == 0)
                    <span style="color:#1a9e54; font-weight:600;">{{ localize('FREE') }}</span>
                @else
                    <span style="color:#1a1a1a;">{{ formatPrice($shipping) }}</span>
                @endif
            </div>

            <div style="display:flex; justify-content:space-between; font-size:14px; color:#666;">
                <span>{{ localize('Tax (GST)') }}</span>
                <span style="color:#1a1a1a;">{{ formatPrice($tax) }}</span>
            </div>
        </div>

        <div style="border-top:1px dashed #e0e0e0; padding-top:16px;">

            {{-- You saved --}}
            @if ($couponDiscount > 0)
                <div
                    style="background:#e9f7ef; border-radius:8px; padding:10px 14px; display:flex; justify-content:space-between; margin-bottom:16px;">
                    <span style="font-size:13px; font-weight:600; color:#1a9e54;">{{ localize('You Saved') }}</span>
                    <span
                        style="font-size:13px; font-weight:600; color:#1a9e54;">{{ formatPrice($couponDiscount) }}</span>
                </div>
            @endif

            {{-- Total --}}
            <div style="display:flex; align-items:baseline; justify-content:space-between; margin-bottom:4px;">
                <span style="font-size:17px; font-weight:700; color:#1a1a1a;">{{ localize('Total Amount') }}</span>
                <span style="font-size:24px; font-weight:700; color:#1a1a1a;">{{ formatPrice($total) }}</span>
            </div>
            <p style="font-size:12px; color:#999; margin:0 0 18px;">{{ localize('Inclusive of all taxes') }}</p>

            {{-- Reward points --}}
            @if (function_exists('getRewardPoints'))
                @php $rewardPoints = getRewardPoints($total); @endphp
                @if ($rewardPoints > 0)
                    <div
                        style="background:#fdf3e7; border-radius:8px; padding:10px 14px; display:flex; align-items:center; gap:8px; margin-bottom:18px;">
                        <span style="font-size:13px; color:#333;">{{ localize('Earn') }} <strong
                                style="color:#d97706;">{{ $rewardPoints }}</strong>
                            {{ localize('Reward Points on this order') }}</span>
                    </div>
                @endif
            @endif

            {{-- Apply coupon --}}
            {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#couponModal"
                style="all:unset; box-sizing:border-box; display:flex; width:100%; justify-content:space-between; align-items:center; padding:12px 14px; font-size:14px; font-weight:600; color:#1a1a1a; border:1px solid #ddd; border-radius:8px; cursor:pointer; margin-bottom:18px; background:#fff;">
                <span>{{ localize('Apply Coupon') }}</span>
                <i class="las la-angle-right"></i>
            </button> --}}

            {{-- Trust badges --}}
            <div
                style="display:grid; grid-template-columns:repeat(3,1fr); gap:8px; text-align:center; margin-bottom:18px;">
                <div>
                    <p
                        style="font-size:12px; font-weight:600; color:#1a1a1a; margin:0; display:flex; align-items:center; justify-content:center; gap:4px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1a9e54"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        {{ localize('Secure Payment') }}
                    </p>
                    <p style="font-size:11px; color:#999; margin:2px 0 0;">{{ localize('100% Protected') }}</p>
                </div>
                <div>
                    <p
                        style="font-size:12px; font-weight:600; color:#1a1a1a; margin:0; display:flex; align-items:center; justify-content:center; gap:4px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                            <path d="M21 3v5h-5"></path>
                            <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                            <path d="M3 21v-5h5"></path>
                        </svg>
                        {{ localize('Easy Returns') }}
                    </p>
                    <p style="font-size:11px; color:#999; margin:2px 0 0;">{{ localize('Hassle Free') }}</p>
                </div>
                <div>
                    <p
                        style="font-size:12px; font-weight:600; color:#1a1a1a; margin:0; display:flex; align-items:center; justify-content:center; gap:4px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d97706"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        {{ localize('Buyer Protection') }}
                    </p>
                    <p style="font-size:11px; color:#999; margin:2px 0 0;">{{ localize('Shop with Confidence') }}</p>
                </div>
            </div>

            {{-- Place order --}}
            <button type="submit" id="btn_cod"
                style="all:unset; box-sizing:border-box; display:flex; align-items:center; justify-content:center; gap:8px; width:100%; background:#ff5722; color:#fff; border-radius:8px; padding:13px; font-size:15px; font-weight:700; cursor:pointer; text-align:center;">
                {{ localize('Place Order') }}
            </button>
            <button type="submit" id="btn_ccavenue"
                style="all:unset; box-sizing:border-box; display:none; align-items:center; justify-content:center; gap:8px; width:100%; background:#ff5722; color:#fff; border-radius:8px; padding:13px; font-size:15px; font-weight:700; cursor:pointer; text-align:center; margin-top:8px;">
                {{ localize('Place Order') }}
            </button>

            <p style="text-align:center; font-size:12px; color:#999; margin:14px 0 0;">
                {{ localize('Safe and Secure Payments. Easy returns. 100% Authentic products.') }}
            </p>
        </div>

    </div>
</div>
