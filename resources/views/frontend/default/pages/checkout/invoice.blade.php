@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('Invoice') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <!--invoice section start-->
    @if (!is_null($orderGroup))
        @php
            $order = $orderGroup->order; // ✅ ye line add kar
            $shippingAddress = \App\Models\UserAddress::find($orderGroup->shipping_address_id);
            $orderItems = $order->orderItems;
        @endphp
        <section class="invoice-section pt-6 pb-120">
            <div class="container">
                <div class="invoice-box bg-white rounded p-4 p-sm-6">
                    <div class="row justify-content-between align-items-start" style="margin-top: -66px;">
                        <div class="col-lg-6">
                            <div class="invoice-title d-flex align-items-center">
                                <h3>{{ localize('Invoice') }}</h3>
                                <span class="badge rounded-pill bg-primary-light text-primary fw-medium ms-3">
                                    {{ ucwords(str_replace('_', ' ', $order->delivery_status)) }}
                                </span>
                            </div>
                            <table class="invoice-table-sm">
                                <tr>
                                    <td><strong>{{ localize('Order Code') }}</strong></td>
                                    <td>{{ getSetting('order_code_prefix') }}{{ $orderGroup->order_code }}</td>
                                </tr>

                                <tr>
                                    <td><strong>{{ localize('Date') }}</strong></td>
                                    <td>{{ date('d M, Y', strtotime($orderGroup->created_at)) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-lg-5 col-md-8">
                            <div class="text-lg-end" style="margin-top:0; padding-top:0;">
                                <a href="{{ route('home') }}"><img src="{{ uploadedAsset(getSetting('navbar_logo')) }}"
                                        alt="logo" class="img-fluid" style="margin-top:0;"></a>

                            </div>
                        </div>
                    </div>
                    <span class="my-3 w-100 d-block border-top"></span>
                    <div class="row justify-content-between g-5">
                        <div class="col-xl-7 col-lg-6">
                            <div class="welcome-message" style="line-height: 1.6;">
                                <h4 class="mb-2">{{ auth()->user()->name }}</h4>

                                <p class="mb-2">
                                    {{ localize('Here are your order details. We thank you for your purchase.') }}
                                </p>

                                <p class="mb-2">
                                    {{ localize('Delivery Type') }}:
                                    <span class="badge bg-primary">
                                        {{ Str::title(Str::replace('_', ' ', $order->shipping_delivery_type)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-6">
                            @if (!$order->orderGroup->is_pos_order)
                                <div class="shipping-address d-flex justify-content-start">
                                    <div class="border-end pe-2">
                                        <h6 class="mb-2">{{ localize('Shipping Address') }}</h6>

                                        @php
                                            $shippingAddress = \App\Models\UserAddress::find(
                                                $orderGroup->shipping_address_id,
                                            );
                                        @endphp

                                        <p class="mb-0">
                                            {{ optional($shippingAddress)->village ?? '' }},
                                            {{ optional($shippingAddress)->district_name ?? '' }},
                                            {{ optional($shippingAddress)->state_name ?? '' }},
                                            {{ optional($shippingAddress)->pincode ?? '' }},
                                            {{ optional($shippingAddress)->country_name ?? '' }}
                                        </p>
                                    </div>

                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive mt-6">
                        <table class="table invoice-table">
                            <tr>
                                <th>{{ localize('S/L') }}</th>
                                <th>{{ localize('Products') }}</th>
                                <th>HSN</th> {{-- 🔥 NEW --}}
                                <th>{{ localize('U.Price') }}</th>
                                <th>{{ localize('QTY') }}</th>
                                <th>{{ localize('T.Price') }}</th>
                                @if (getSetting('enable_refund_system') == 1)
                                    <th>{{ localize('Refund') }}</th>
                                @endif
                            </tr>
                            @foreach ($orderItems as $key => $item)
                                @php
                                    $product = $item->product_variation->product;
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <img src="{{ uploadedAsset($product->thumbnail_image) }}"
                                                alt="{{ $product->collectLocalization('name') }}"
                                                class="img-fluid product-item d-none">
                                            {{-- <div class="ms-2"> --}}
                                            <div class="">
                                                <div style="max-width: 350px; line-height: 1.4;">
                                                    <div
                                                        style="
        display: -webkit-box;
-webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    ">
                                                        {{ $product->collectLocalization('name') }}
                                                    </div>
                                                </div>
                                                <div>
                                                    @foreach (generateVariationOptions($item->product_variation->combinations) as $variation)
                                                        <span class="fs-xs">
                                                            {{ $variation['name'] }}:
                                                            @foreach ($variation['values'] as $value)
                                                                {{ $value['name'] }}
                                                            @endforeach
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $item->product_variation->code ?? '-' }} {{-- ✅ HSN from variation --}}
                                    </td>

                                    <td>{{ formatPrice($item->unit_price) }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ formatPrice($item->total_price) }}</td>

                                    @if (getSetting('enable_refund_system') == 1)
                                        <td>
                                            @if ($item->refundRequest)
                                                @if ($item->refundRequest->refund_status == 'pending')
                                                    <span class="badge bg-info text-capitalize">
                                                        {{ $item->refundRequest->refund_status }}
                                                    </span>
                                                @elseif($item->refundRequest->refund_status == 'refunded')
                                                    <span class="badge bg-primary text-capitalize">
                                                        {{ $item->refundRequest->refund_status }}
                                                    </span>
                                                @else
                                                    <span class="btn badge bg-danger text-capitalize cursor-pointer"
                                                        onclick="showRejectionReason('{{ $item->refundRequest->refund_reject_reason }}')">
                                                        {{ $item->refundRequest->refund_status }}
                                                    </span>
                                                @endif
                                            @else
                                                @php
                                                    $withinDays = (int) getSetting('refund_within_days');

                                                    $checkDate = \Carbon\Carbon::parse($item->created_at)->addDays(
                                                        $withinDays,
                                                    );
                                                    $today = today();

                                                    $count = $checkDate->diffInDays($today);
                                                @endphp
                                                @if ($count > 0)
                                                    <a href="javascript:void(0);"
                                                        onclick="requestRefund({{ $item->id }})"
                                                        class="fw-semibold badge bg-secondary"><i
                                                            class="fas fa-rotate-left me-1"></i>
                                                        {{ localize('Request Refund') }}</a>
                                                @else
                                                    {{ localize('Time Over') }}
                                                @endif
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach


                        </table>
                    </div>
                    <div class="mt-4 table-responsive">
                        <table class="table footer-table">
                            <tr>
                                <td>
                                    <strong class="text-dark d-block text-nowrap">{{ localize('Payment Method') }}</strong>
                                    <span> {{ ucwords(str_replace('_', ' ', $orderGroup->payment_method)) }}</span>
                                </td>

                                <td>
                                    <strong class="text-dark d-block text-nowrap">{{ localize('Sub Total') }}</strong>
                                    <span>{{ formatPrice($orderGroup->sub_total_amount) }}</span>
                                </td>

                                <td>
                                    <strong class="text-dark d-block text-nowrap">{{ localize('Tax') }}</strong>
                                    <span>{{ formatPrice($orderGroup->total_tax_amount) }}</span>
                                </td>

                                <td>
                                    <strong class="text-dark d-block text-nowrap">{{ localize('Tips') }}</strong>
                                    <span>{{ formatPrice($orderGroup->total_tips_amount) }}</span>
                                </td>

                                <td>
                                    <strong class="text-dark d-block text-nowrap">{{ localize('Shipping Cost') }}</strong>
                                    <span>{{ formatPrice($orderGroup->total_shipping_cost) }}</span>
                                </td>
                                @if ($orderGroup->total_coupon_discount_amount > 0)
                                    <td>
                                        <strong
                                            class="text-dark d-block text-nowrap">{{ localize('Coupon Discount') }}</strong>
                                        <span>{{ formatPrice($orderGroup->total_coupon_discount_amount) }}</span>
                                    </td>
                                @endif

                                <td>
                                    <strong class="text-dark d-block text-nowrap">{{ localize('Total Price') }}</strong>
                                    <span
                                        class="text-primary fw-bold">{{ formatPrice($orderGroup->grand_total_amount) }}</span>
                                </td>

                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!--invoice section end-->

    <!--refund modal-->
    <div class="modal fade refundModal" id="refundModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="gstore-product-quick-view bg-white rounded-3 pt-3 pb-6 px-4">
                        <h2 class="modal-title fs-5 mb-3">{{ localize('Request Refund') }}</h2>
                        <form action="{{ route('customers.requestRefund') }}" method="post">
                            @csrf
                            <input type="hidden" name="order_item_id" value="" class="order_item_id">
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <div class="label-input-field">
                                        <label>{{ localize('Refund Reason') }}</label>
                                        <textarea rows="4" placeholder="{{ localize('Type refund reason') }}" name="refund_reason" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 d-flex">
                                <button type="submit"
                                    class="btn btn-secondary btn-md me-3">{{ localize('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--rejection modal-->
    @include('frontend.default.pages.checkout.inc.rejectionModal')


@endsection


@section('scripts')
    <script>
        "use strict";

        // request refund
        function requestRefund(order_item_id) {
            $('#refundModal').modal('show');
            $('.order_item_id').val(order_item_id);
        }
    </script>
@endsection

@section('styles')
    <style>
        /* yaha paste kar */
        .table-responsive {
            overflow-x: hidden !important;
        }

        .invoice-table td.text-nowrap {
            white-space: normal !important;
        }

        .invoice-table td:nth-child(2) {
            width: 350px;
            max-width: 350px;
        }

        .invoice-table td:nth-child(2) span {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection
