@extends('backend.layouts.master')

@section('contents')
    <style>
        :root {
            --green: #3db843;
            --green-dark: #2a8f2f;
            --green-light: #edf7ee;
            --green-glow: rgba(61, 184, 67, 0.12);
            --green-border: rgba(61, 184, 67, 0.3);

            --amber: #f59e0b;
            --amber-light: #fffbeb;
            --amber-border: rgba(245, 158, 11, 0.3);

            --red: #ef4444;
            --red-light: rgba(239, 68, 68, 0.08);
            --red-border: rgba(239, 68, 68, 0.2);

            --blue: #2563eb;
            --blue-light: rgba(37, 99, 235, 0.08);
            --blue-border: rgba(37, 99, 235, 0.2);

            --bg-page: #f1f4f8;
            --bg-card: #ffffff;
            --bg-inner: #f8fafc;
            --bg-thead: #f1f5f9;

            --border: #e2e8f0;
            --text-1: #0f172a;
            --text-2: #475569;
            --text-3: #94a3b8;

            --shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 4px 16px rgba(0, 0, 0, 0.06);
            --shadow-hover: 0 4px 8px rgba(0, 0, 0, 0.08), 0 12px 32px rgba(0, 0, 0, 0.1);
            --radius: 14px;
            --radius-sm: 8px;
            --tr: 0.18s ease;
        }

        .py-page {
            background: var(--bg-page);
            min-height: 100vh;
            padding: 24px 20px 60px;
        }

        /* ── PAGE HEADER ── */
        .py-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
            padding: 12px 22px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-top: 3px solid var(--green);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .py-header-left {
            display: flex;
            align-items: flex-start;
            gap: 14px;

            flex: 1;
        }

        .py-header-icon {
            width: 44px;
            height: 44px;
            background: var(--green-light);
            border: 1.5px solid var(--green-border);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--green);
            font-size: 20px;
            flex-shrink: 0;
        }

        .py-header-title {
            font-size: 19px;
            font-weight: 700;
            color: var(--text-1);
            margin: 0;
            letter-spacing: -0.3px;
        }

        .py-header-sub {
            font-size: 12.5px;
            color: var(--text-3);
            margin: 2px 0 0;
        }

        .py-header-product {
            font-size: 14px;
            color: var(--text-2);
            margin: 2px 0 0;
        }

        .line-clamp-2 {
            display: -webkit-box;

            -webkit-line-clamp: 2;

            -webkit-box-orient: vertical;

            overflow: hidden;

            word-break: break-word;

            line-height: 1.5;
        }

        .py-weight-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;

            background: var(--green-light);
            border: 1.5px solid var(--green-border);

            color: var(--green-dark);

            border-radius: 20px;

            padding: 6px 16px;

            font-size: 13px;

            font-weight: 700;

            margin-left: auto;

            flex-shrink: 0;

            align-self: flex-start;
        }

        /* ── GRID ── */

        .py-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        /* ── PAYOUT CARD ── */
        .py-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: box-shadow var(--tr), transform var(--tr);
        }

        .py-card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }

        /* Card top accent bar */
        .py-card-top {
            height: 3px;
            background: linear-gradient(90deg, var(--green) 0%, #6ee77a 100%);
        }

        /* Card header */
        .py-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 14px;
            border-bottom: 1px solid var(--border);
        }

        .py-zone-name {
            font-size: 13px;
            font-weight: 800;
            color: var(--text-1);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .py-zone-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--green);
            flex-shrink: 0;
        }

        .py-ship-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: var(--green-light);
            border: 1px solid var(--green-border);
            color: var(--green-dark);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12.5px;
            font-weight: 700;
        }

        /* ── TABLE ── */
        .py-card-body {
            padding: 0 14px 8px;
            flex: 1;
        }

        .py-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 4px;
        }

        .py-table thead tr {
            background: var(--bg-thead);
            border-bottom: 1.5px solid var(--border);
        }

        .py-table thead th {
            padding: 5px 8px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-3);
            border: none;
        }

        .py-table thead th:last-child {
            text-align: right;
        }

        .py-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background var(--tr);
        }

        .py-table tbody tr:hover {
            background: #f8fafc;
        }

        .py-table tbody tr:last-child {
            border-bottom: none;
        }

        .py-table td {
            padding: 4px 8px;
            color: var(--text-2);
            border: none;
            vertical-align: middle;
        }

        .py-table td:last-child {
            text-align: right;
            color: var(--text-1);
            font-weight: 500;
        }

        /* row type classes */
        .py-row-highlight td {
            background: rgba(61, 184, 67, 0.05);
            color: var(--green-dark) !important;
            font-weight: 700 !important;
        }

        .py-row-cod td {
            background: rgba(245, 158, 11, 0.05);
            color: var(--amber) !important;
            font-weight: 700 !important;
        }

        .py-row-deduct th {
            color: var(--red) !important;
            font-size: 13px;
        }

        .py-row-deduct th:last-child {
            text-align: right;
        }

        .py-row-deduct {
            border-top: 1.5px solid var(--border) !important;
        }

        .py-row-sep td {
            padding: 2px 0;
        }

        /* section divider labels */
        .py-section-label {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-3);
            padding: 6px 8px 2px;
        }

        .py-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
            min-width: 40px;
        }

        /* ── PAYOUT BOXES ── */
        .py-payouts {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            padding: 8px 14px 12px;
            border-top: 1px solid var(--border);
        }

        .py-payout-box {
            border-radius: var(--radius-sm);
            padding: 8px 10px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .py-payout-box.green {
            background: var(--green-light);
            border: 1.5px solid var(--green-border);
        }

        .py-payout-box.amber {
            background: var(--amber-light);
            border: 1.5px solid var(--amber-border);
        }

        .py-payout-label {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .py-payout-box.green .py-payout-label {
            color: var(--green-dark);
        }

        .py-payout-box.amber .py-payout-label {
            color: #b45309;
        }

        .py-payout-amount {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .py-payout-box.green .py-payout-amount {
            color: var(--green-dark);
        }

        .py-payout-box.amber .py-payout-amount {
            color: var(--amber);
        }

        .py-payout-sub {
            font-size: 10px;
            margin-top: 0;
        }

        .py-payout-box.green .py-payout-sub {
            color: var(--green);
        }

        .py-payout-box.amber .py-payout-sub {
            color: #d97706;
        }

        /* deduction pill */
        .py-deduct-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: var(--red-light);
            border: 1px solid var(--red-border);
            color: var(--red);
            border-radius: 20px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 600;
        }

        /* percent tag */
        .py-pct {
            display: inline-block;
            background: var(--bg-inner);
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-3);
            padding: 1px 6px;
            margin-left: 3px;
        }

        /* empty state */
        .py-empty {
            grid-column: 1/-1;
            text-align: center;
            padding: 60px 20px;
            color: var(--text-3);
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }

        .py-empty i {
            font-size: 36px;
            margin-bottom: 12px;
            display: block;
            opacity: 0.3;
        }
    </style>

    <div class="py-page">

        {{-- PAGE HEADER --}}
        <div class="py-header">
            <div class="py-header-left">
                <div class="py-header-icon">
                    <img src="{{ uploadedAsset($product->thumbnail_image) }}" alt="Product Image"
                        style="width:26px; height:26px; object-fit:cover; border-radius:4px;">
                </div>
                <div>

                    <h2 class="py-header-title">Estimated Seller Payout</h2>
                    <p class="py-header-sub">Based on Product Price, Shipping, Commission, Taxes &amp; Charges</p>
                    <p class="py-header-product line-clamp-2"> {{ $product->name ?? '—' }} </p>
                </div>
            </div>
            <div class="py-weight-badge">
                <i class="fa fa-weight"></i>
                Weight Slab: {{ $weight->title }}
            </div>
        </div>

        {{-- PAYOUT CARDS --}}
        <div class="py-grid">

            @forelse($shippings as $shipping)
                @php
                    $shippingCharge = $shipping->charge ?? 0;

                    $shippingGst = $shipping->shipping_gst ?? 0;

                    $totalShipping = $shipping->total_charge ?? 0;

                    $codCharge = $shipping->cod_charge ?? 0;

                    $codGst = $shipping->cod_gst ?? 0;

                    $totalShippingWithCod = $shipping->total_charge_with_cod ?? 0;

                    /*
                    |--------------------------------------------------------------------------
                    | COMMISSION
                    |--------------------------------------------------------------------------
                    */

                    $commissionAmount = ($productPrice * $commissionPercent) / 100;

                    /*
                    |--------------------------------------------------------------------------
                    | GST ON COMMISSION
                    |--------------------------------------------------------------------------
                    */

                    $gstPercent = $gst->tax ?? 0;

                    $gstOnCommission = ($commissionAmount * $gstPercent) / 100;

                    /*
                    |--------------------------------------------------------------------------
                    | PAYMENT GATEWAY
                    |--------------------------------------------------------------------------
                    */

                    $paymentGatewayPercent = $paymentGateway->name ?? 0;

                    $paymentGatewayCharge = ($productPrice * $paymentGatewayPercent) / 100;
                    $gstOnPaymentGateway = ($paymentGatewayCharge * $gstPercent) / 100;
                    /*
                    |--------------------------------------------------------------------------
                    | TDS
                    |--------------------------------------------------------------------------
                    */

                    $tdsPercent = $tds->name ?? 0;

                    $tdsAmount = ($productPrice * $tdsPercent) / 100;

                    /*
                    |--------------------------------------------------------------------------
                    | TOTAL DEDUCTION
                    |--------------------------------------------------------------------------
                    */

                    $totalDeduction =
                        $commissionAmount +
                        $gstOnCommission +
                        $paymentGatewayCharge +
                        $gstOnPaymentGateway +
                        $tdsAmount +
                        $totalShipping +
                        $totalShippingWithCod;

                    /*
                        |--------------------------------------------------------------------------
                        | FINAL PAYOUT
                        |--------------------------------------------------------------------------
                        |
                        | PREPAID:
                        | Product Price
                        | - Total Shipping
                        | - Total Deduction
                        |
                        | COD:
                        | Product Price
                        | - Total Shipping With COD
                        | - Total Deduction
                        |
                        */

                    $finalPayout = $productPrice - $totalDeduction + $totalShippingWithCod;

                    $finalCodPayout = $productPrice - $totalDeduction;
                @endphp

                <div class="py-card">

                    <div class="py-card-top"></div>

                    {{-- CARD HEAD --}}
                    <div class="py-card-head">
                        <div class="py-zone-name">
                            <span class="py-zone-dot"></span>
                            {{ strtoupper($shipping->zone->zone_name ?? '—') }}
                        </div>
                        <span class="py-ship-chip">
                            <i class="fa fa-truck" style="font-size:11px"></i>
                            ₹{{ number_format($shippingCharge, 2) }}
                        </span>
                    </div>

                    {{-- TABLE --}}
                    <div class="py-card-body">

                        {{-- SHIPPING SECTION --}}
                        <div class="py-section-label"><i class="fa fa-truck" style="font-size:9px"></i> Shipping</div>
                        <table class="py-table">
                            <tbody>
                                <tr>
                                    <td>Product Selling Price</td>
                                    <td><strong style="color:var(--text-1)">₹{{ number_format($productPrice, 2) }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Shipping Charge</td>
                                    <td>₹{{ number_format($shippingCharge, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Shipping GST (18%)</td>
                                    <td style="color:var(--text-3)">₹{{ number_format($shippingGst, 2) }}</td>
                                </tr>
                                <tr class="py-row-highlight">
                                    <td>Total Shipping</td>
                                    <td>₹{{ number_format($totalShipping, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>COD Charge</td>
                                    <td>₹{{ number_format($codCharge, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>COD GST (18%)</td>
                                    <td style="color:var(--text-3)">₹{{ number_format($codGst, 2) }}</td>
                                </tr>
                                <tr class="py-row-cod">
                                    <td>Total COD</td>
                                    <td>₹{{ number_format($totalShippingWithCod, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Payment Gateway</td>
                                    <td>
                                        <span class="py-pct">{{ $paymentGatewayPercent }}%</span>
                                        ₹{{ number_format($paymentGatewayCharge, 2) }}

                                    </td>
                                </tr>
                                <tr>
                                    <td>GST on Payment Gateway</td>
                                    <td>
                                        <span class="py-pct">{{ $gstPercent }}%</span>
                                        ₹{{ number_format($gstOnPaymentGateway, 2) }}

                                    </td>
                                </tr>
                                <tr class="py-row-highlight">
                                    <td>Total Payment Gateway Charge</td>
                                    <td>₹{{ number_format($paymentGatewayCharge + $gstOnPaymentGateway, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- DEDUCTIONS SECTION --}}
                        <div class="py-section-label" style="color:var(--red);"><i class="fa fa-minus-circle"
                                style="font-size:9px"></i> Deductions</div>
                        <table class="py-table">
                            <tbody>
                                <tr>
                                    <td>Bazaron Commission</td>
                                    <td>
                                        <span class="py-pct">{{ $commissionPercent }}%</span>
                                        ₹{{ number_format($commissionAmount, 2) }}

                                    </td>
                                </tr>
                                <tr>
                                    <td>GST on Commission</td>
                                    <td>
                                        <span class="py-pct">{{ $gstPercent }}%</span>
                                        ₹{{ number_format($gstOnCommission, 2) }}

                                    </td>
                                </tr>
                                <tr>
                                    <td>Payment Gateway</td>
                                    <td>
                                        <span class="py-pct">{{ $paymentGatewayPercent }}%</span>
                                        ₹{{ number_format($paymentGatewayCharge, 2) }}

                                    </td>
                                </tr>
                                <tr>
                                    <td>GST on Payment Gateway</td>
                                    <td>
                                        <span class="py-pct">{{ $gstPercent }}%</span>
                                        ₹{{ number_format($gstOnPaymentGateway, 2) }}

                                    </td>
                                </tr>
                                <tr>
                                    <td>TDS (194-O)</td>
                                    <td>
                                        <span class="py-pct">{{ $tdsPercent }}%</span>
                                        ₹{{ number_format($tdsAmount, 2) }}

                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Shipping</td>
                                    <td>₹{{ number_format($totalShipping, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Total COD</td>
                                    <td>₹{{ number_format($totalShippingWithCod, 2) }}</td>
                                </tr>
                                <tr class="py-row-deduct">
                                    <th>Total Deductions</th>
                                    <th>
                                        <span class="py-deduct-pill">
                                            <i class="fa fa-minus" style="font-size:9px"></i>
                                            ₹{{ number_format($totalDeduction, 2) }}
                                        </span>
                                    </th>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    {{-- PAYOUT BOXES --}}
                    <div class="py-payouts">

                        <div class="py-payout-box green">
                            <div class="py-payout-label">
                                <i class="fa fa-check-circle" style="font-size:11px"></i>
                                Estimated Prepaid Payout
                            </div>
                            <div class="py-payout-amount">₹{{ number_format($finalPayout, 2) }}</div>
                            <div class="py-payout-sub">After all deductions</div>
                        </div>

                        <div class="py-payout-box amber">
                            <div class="py-payout-label">
                                <i class="fa fa-money-bill-wave" style="font-size:11px"></i>
                                Estimated COD Payout
                            </div>
                            <div class="py-payout-amount">₹{{ number_format($finalCodPayout, 2) }}</div>
                            <div class="py-payout-sub">With COD charges</div>
                        </div>

                    </div>

                </div>

            @empty
                <div class="py-empty">
                    <i class="fa fa-calculator"></i>
                    <p>No shipping zones found for this weight slab.</p>
                </div>
            @endforelse

        </div>

    </div>
@endsection
