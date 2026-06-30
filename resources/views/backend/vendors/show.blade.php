@extends('backend.layouts.master')
@section('title')
    Seller Details
@endsection

@section('contents')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

        :root {
            --primary: #4f46e5;
            --primary-light: #eef2ff;
            --primary-dark: #3730a3;
            --success: #10b981;
            --success-light: #d1fae5;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --surface: #ffffff;
            --surface-2: #f8fafc;
            --border: #e2e8f0;
            --text-primary: #0f172a;
            --text-muted: #64748b;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, .08);
            --shadow-lg: 0 10px 40px rgba(0, 0, 0, .12);
            --radius: 12px;
            --radius-sm: 8px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── HERO HEADER ── */
        .seller-hero {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            border-radius: var(--radius);
            padding: 20px 20px;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
        }

        .seller-hero::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
        }

        .seller-hero::after {
            content: '';
            position: absolute;
            bottom: -40px;
            left: 40%;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
        }

        .seller-hero .hero-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            border: 3px solid rgba(255, 255, 255, .3);
            flex-shrink: 0;
        }

        .seller-hero h2 {
            color: #fff;
            font-weight: 700;
            font-size: 22px;
            margin: 0;
        }

        .seller-hero .hero-meta {
            color: rgba(255, 255, 255, .65);
            font-size: 13.5px;
            margin-top: 4px;
        }

        .seller-hero .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 99px;
            font-size: 12.5px;
            font-weight: 600;
            background: rgba(255, 255, 255, .15);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .25);
            backdrop-filter: blur(4px);
        }

        .hero-badge .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--success);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, .3);
        }

        .hero-badge .dot.warning {
            background: var(--warning);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .3);
        }

        /* ── TABS ── */
        .sd-tabs {
            display: flex;
            gap: 4px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 6px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .sd-tabs .nav-item {
            flex: 1;
            min-width: 100px;
        }

        .sd-tabs .nav-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-muted);
            border: none;
            background: transparent;
            transition: all .2s ease;
            white-space: nowrap;
            text-decoration: none;
        }

        .sd-tabs .nav-link:hover {
            color: var(--primary);
            background: var(--primary-light);
        }

        .sd-tabs .nav-link.active {
            background: var(--surface);
            color: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        .sd-tabs .nav-link .tab-icon {
            font-size: 16px;
        }

        /* ── CARDS ── */
        .sd-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .sd-card-header {
            padding: 16px 22px;
            border-bottom: 1px solid var(--border);
            background: var(--surface-2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sd-card-header h6 {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .06em;
            margin: 0;
        }

        .sd-card-body {
            padding: 22px;
        }

        /* ── INFO GRID ── */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;
        }

        .info-item {
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 14px 16px;
        }

        .info-item .label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .07em;
            margin-bottom: 5px;
        }

        .info-item .value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            word-break: break-word;
        }

        .info-item .value.mono {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
        }

        .info-item .value a {
            color: var(--primary);
            text-decoration: none;
        }

        .info-item .value a:hover {
            text-decoration: underline;
        }

        /* ── BADGES ── */
        .sd-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
        }

        .sd-badge.success {
            background: var(--success-light);
            color: #065f46;
        }

        .sd-badge.warning {
            background: var(--warning-light);
            color: #92400e;
        }

        .sd-badge.danger {
            background: var(--danger-light);
            color: #7f1d1d;
        }

        .sd-badge.info {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        /* ── TABLES ── */
        .sd-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sd-table thead th {
            background: var(--surface-2);
            border-bottom: 2px solid var(--border);
            padding: 11px 16px;
            font-size: 11.5px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .06em;
            text-align: left;
        }

        .sd-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .15s;
        }

        .sd-table tbody tr:last-child {
            border-bottom: none;
        }

        .sd-table tbody tr:hover {
            background: var(--surface-2);
        }

        .sd-table td {
            padding: 13px 16px;
            font-size: 13.5px;
            color: var(--text-primary);
            vertical-align: middle;
        }

        .sd-table .order-id {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--primary);
        }

        /* ── PRODUCT ROW ── */
        .product-thumb {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--border);
            flex-shrink: 0;
        }

        .product-name {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .price-range {
            font-size: 12.5px;
            color: var(--text-muted);
        }

        /* ── ACTION BUTTONS ── */
        .btn-sd {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 7px;
            font-size: 12.5px;
            font-weight: 600;
            border: 1.5px solid transparent;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
        }

        .btn-sd.primary {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .btn-sd.primary:hover {
            background: var(--primary-dark);
        }

        .btn-sd.success {
            background: var(--success);
            color: #fff;
            border-color: var(--success);
        }

        .btn-sd.success:hover {
            background: #059669;
        }

        .btn-sd.danger {
            background: var(--danger);
            color: #fff;
            border-color: var(--danger);
        }

        .btn-sd.danger:hover {
            background: #dc2626;
        }

        .btn-sd.outline-success {
            background: transparent;
            color: var(--success);
            border-color: var(--success);
        }

        .btn-sd.outline-success:hover {
            background: var(--success-light);
        }

        .btn-sd.outline-danger {
            background: transparent;
            color: var(--danger);
            border-color: var(--danger);
        }

        .btn-sd.outline-danger:hover {
            background: var(--danger-light);
        }

        .btn-sd.info {
            background: var(--primary-light);
            color: var(--primary);
            border-color: #c7d2fe;
        }

        .btn-sd.info:hover {
            background: #e0e7ff;
        }

        /* ── BRAND REQUEST CARD ── */
        .brand-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
        }

        .brand-card-top {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid var(--border);
            padding: 18px 22px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-card-top h5 {
            font-weight: 700;
            font-size: 16px;
            color: var(--text-primary);
            margin: 0;
        }

        .brand-images-row {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 14px;
            padding: 20px 22px;
        }

        .brand-img-box {
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--surface-2);
            padding: 12px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
        }

        .brand-img-box:hover {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .brand-img-box .img-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 8px;
        }

        .brand-img-box img {
            max-height: 90px;
            object-fit: contain;
            border-radius: 6px;
        }

        .brand-desc-box {
            margin: 0 22px 20px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 16px;
        }

        .brand-desc-box .img-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 8px;
        }

        .brand-desc-box p {
            font-size: 14px;
            color: var(--text-primary);
            margin: 0;
            line-height: 1.6;
        }

        .brand-footer {
            padding: 14px 22px;
            border-top: 1px solid var(--border);
            background: var(--surface-2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* ── PRODUCT MODAL TABLE ── */
        .product-details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-details-table th {
            width: 30%;
            background: var(--surface-2);
            border: 1px solid var(--border);
            padding: 10px 14px;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .product-details-table td {
            border: 1px solid var(--border);
            padding: 10px 14px;
            font-size: 13.5px;
            color: var(--text-primary);
        }

        .product-details-table tr:hover td {
            background: #fafbff;
        }

        .top-grey-row td,
        .top-grey-row th {
            background: #f3f4f6 !important;
        }

        .product-details-table img {
            max-height: 80px;
            border-radius: 6px;
            margin-right: 5px;
            cursor: pointer;
            transition: .2s;
        }

        .product-details-table img:hover {
            transform: scale(1.05);
        }

        /* ── EMPTY STATES ── */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .empty-state .icon {
            font-size: 40px;
            margin-bottom: 12px;
            opacity: .4;
        }

        /* ── IMAGE MODAL ── */
        #imageModal .modal-content {
            background: transparent;
            border: none;
        }

        #modalImage {
            transition: transform .2s ease;
            cursor: zoom-in;
            border-radius: 10px;
        }

        /* ── Z-INDEX ── */
        #productModal {
            z-index: 1055;
        }

        #imageModal {
            z-index: 1065;
        }

        .modal-backdrop.show:nth-of-type(2) {
            z-index: 1060;
        }
    </style>

    {{-- ═══════════════════════════════════════════ --}}
    {{--  HERO HEADER                                --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="seller-hero d-flex align-items-center gap-4">
        <div class="hero-avatar">{{ strtoupper(substr($vendor->name, 0, 1)) }}</div>
        <div class="flex-grow-1">
            <h2>{{ $vendor->name }}</h2>
            <div class="hero-meta">{{ $vendor->email }} · {{ $vendor->phone ?? 'No phone' }}</div>

            @if ($vendor->vendorProfile->has_own_logistics == 0)
                <h2 class="hero-badge mt-2">Bazaron Shipping<h2>
                    @else
                        <h2 class="hero-badge mt-2">Self Shipping</h2>
            @endif

        </div>
        <div>
            <span class="hero-badge">
                <span class="dot {{ $vendor->status == 'approved' ? '' : 'warning' }}"></span>
                {{ ucfirst($vendor->status) }}
            </span>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{--  TABS                                       --}}
    {{-- ═══════════════════════════════════════════ --}}
    <ul class="nav sd-tabs" id="vendorTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="basic-tab" data-bs-toggle="tab" href="#basic" role="tab">
                <span class="tab-icon">👤</span> Seller Info
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" id="orders-tab" data-bs-toggle="tab" href="#orders" role="tab">
                <span class="tab-icon">📦</span> Orders
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="products-tab" data-bs-toggle="tab" href="#products" role="tab">
                <span class="tab-icon">🛍️</span> Products
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="brand-requests-tab" data-bs-toggle="tab" href="#brandRequests" role="tab">
                <span class="tab-icon">✨</span> Brand Requests
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pincode-list" data-bs-toggle="tab" href="#pincodelist" role="tab">
                <span class="tab-icon">✨</span> Pincode List
            </a>
        </li>
    </ul>

    {{-- ═══════════════════════════════════════════ --}}
    {{--  TAB CONTENT                                --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="tab-content">

        {{-- ── BASIC INFO ── --}}
        <div class="tab-pane fade show active" id="basic" role="tabpanel">
            <div class="sd-card">
                <div class="sd-card-header">
                    <h6>Account Information</h6>
                </div>
                <div class="sd-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="label">Seller ID</div>
                            <div class="value mono">#{{ str_pad($vendor->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Full Name</div>
                            <div class="value">{{ $vendor->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Email Address</div>
                            <div class="value mono">{{ $vendor->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Phone Number</div>
                            <div class="value">{{ $vendor->phone ?? '—' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Account Status</div>
                            <div class="value">
                                <span class="sd-badge {{ $vendor->status == 'approved' ? 'success' : 'warning' }}">
                                    {{ ucfirst($vendor->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($vendor->vendorProfile)
                {{-- Business --}}
                <div class="sd-card">
                    <div class="sd-card-header">
                        <h6>Business Details</h6>
                    </div>
                    <div class="sd-card-body">
                        <div class="info-grid">
                            @foreach ([
            'Business Name' => $vendor->vendorProfile->business_name,
            'Business Type' => $vendor->vendorProfile->business_type,
            'Registration No.' => $vendor->vendorProfile->business_reg_no,
            'Establishment Date' => $vendor->vendorProfile->establishment_date,
            'Business Model' => $vendor->vendorProfile->business_model,
            // 'Product Categories' => $vendor->vendorProfile->product_categories,
            'Primary Category' => optional(\App\Models\Category::find($vendor->vendorProfile->product_categories))->name,
            'Avg. Order Value' => $vendor->vendorProfile->avg_order_value,
            'No. of Product Listing' => $vendor->vendorProfile->expected_listing_count,
            'Product Certification' => $vendor->vendorProfile->product_certification,
        ] as $label => $val)
                                <div class="info-item">
                                    <div class="label">{{ $label }}</div>
                                    <div class="value">{{ $val ?? '—' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="sd-card">
                    <div class="sd-card-header">
                        <h6>Address & Logistics</h6>
                    </div>
                    <div class="sd-card-body">
                        <div class="info-grid">
                            @foreach ([
            'Business Address' => $vendor->vendorProfile->business_address,
            'City' => $vendor->vendorProfile->city,
            'State' => $vendor->vendorProfile->state,
            'ZIP Code' => $vendor->vendorProfile->zip,
            'Warehouse Address' => $vendor->vendorProfile->warehouse_address,
            'Preferred Shipping' => $vendor->vendorProfile->preferred_shipping,
            'Has Own Logistics' => $vendor->vendorProfile->has_own_logistics ? 'Yes' : 'No',
        ] as $label => $val)
                                <div class="info-item">
                                    <div class="label">{{ $label }}</div>
                                    <div class="value">{{ $val ?? '—' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
                <div class="sd-card">
                    <div class="sd-card-header">
                        <h6>Contact Person</h6>
                    </div>
                    <div class="sd-card-body">
                        <div class="info-grid">
                            @foreach ([
            'Contact Person' => $vendor->vendorProfile->contact_person,
            'Designation' => $vendor->vendorProfile->designation,
            'Alternate Phone' => $vendor->vendorProfile->alt_phone,
        ] as $label => $val)
                                <div class="info-item">
                                    <div class="label">{{ $label }}</div>
                                    <div class="value">{{ $val ?? '—' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Banking --}}
                <div class="sd-card">
                    <div class="sd-card-header">
                        <h6>Banking & Tax</h6>
                    </div>
                    <div class="sd-card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="label">Bank Name</div>
                                <div class="value">{{ $vendor->vendorProfile->bank_name ?? '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">Branch</div>
                                <div class="value">{{ $vendor->vendorProfile->branch_name ?? '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">Account Holder</div>
                                <div class="value">{{ $vendor->vendorProfile->account_holder_name ?? '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">Account Number</div>
                                <div class="value mono">{{ $vendor->vendorProfile->account_number ?? '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">IFSC Code</div>
                                <div class="value mono">{{ $vendor->vendorProfile->ifsc_code ?? '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">PAN Number</div>
                                <div class="value mono">{{ $vendor->vendorProfile->pan_number ?? '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">GST Number</div>
                                <div class="value mono">{{ $vendor->vendorProfile->business_reg_no  ?? '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">IEC Code</div>
                                <div class="value mono">{{ $vendor->vendorProfile->iec_code ?? '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">Invoice Number</div>
                                <div class="value mono">
                                    {{ $vendor->vendorProfile->invoice_prefix }}{{ $vendor->vendorProfile->invoice_last_number+1 }}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="label">Cheque Copy</div>
                                <div class="value">
                                    @if ($vendor->vendorProfile->cheque_copy)
                                        <a href="{{ asset('storage/' . $vendor->vendorProfile->cheque_copy) }}"
                                            target="_blank">View Document →</a>
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="label">KYC Docs</div>
                                <div class="value">
                                    @if ($vendor->vendorProfile->kyc_docs)
                                        <a href="{{ asset('storage/' . $vendor->vendorProfile->kyc_docs) }}"
                                            target="_blank">View Document →</a>
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="sd-card">
                    <div class="empty-state">
                        <div class="icon">📋</div>
                        <div>No profile found for this vendor.</div>
                    </div>
                </div>
            @endif
        </div>

        {{-- ── ORDERS ── --}}
        <div class="tab-pane fade" id="orders" role="tabpanel">
            <div class="sd-card">
                <div class="sd-card-header">
                    <h6>Order History</h6>
                    <span class="sd-badge info">{{ $orders->count() }} orders</span>
                </div>
                @if ($orders->count())
                    <div style="overflow-x:auto;">
                        <table class="sd-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td><span class="order-id">#{{ $order->id }}</span></td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td><strong>{{ $order->total_price }}</strong></td>
                                        <td>
                                            @php
                                                $st = strtolower($order->delivery_status);
                                                $cls =
                                                    $st == 'delivered'
                                                        ? 'success'
                                                        : ($st == 'cancelled'
                                                            ? 'danger'
                                                            : 'warning');
                                            @endphp
                                            <span
                                                class="sd-badge {{ $cls }}">{{ ucfirst($order->delivery_status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="icon">📦</div>
                        <div>No orders found for this vendor.</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── PRODUCTS ── --}}
        <div class="tab-pane fade" id="products" role="tabpanel">
            <div class="sd-card">
                <div class="sd-card-header">
                    <h6>Product Listings</h6>
                    <span class="sd-badge info">{{ $products->count() }} products</span>
                </div>
                @if ($products->count())
                    <div style="overflow-x:auto;">
                        <table class="sd-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price Range</th>
                                    <th>Stock</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <a href="{{ route('products.show', $product->slug) }}" target="_blank"
                                                class="d-flex align-items-center gap-3 text-decoration-none">
                                                <img class="product-thumb"
                                                    src="{{ uploadedAsset($product->thumbnail_image) }}"
                                                    alt="{{ $product->collectLocalization('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ staticAsset('backend/assets/img/placeholder-thumb.png') }}';">
                                                <div>
                                                    <div class="product-name">{{ $product->collectLocalization('name') }}
                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="price-range">{{ $product->min_price }} –
                                                {{ $product->max_price }}</div>
                                        </td>
                                        <td><strong>{{ $product->stock_qty }}</strong></td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                                <button onclick="openProductModal({{ $product->id }})"
                                                    class="btn-sd info">
                                                    🔍 View
                                                </button>

                                                @if ($product->status == 'pending')
                                                    <form action="{{ route('vendor.product.approve', $product->id) }}"
                                                        method="POST" style="display:inline">
                                                        @csrf
                                                        <button type="submit" class="btn-sd success">✓ Approve</button>
                                                    </form>
                                                    <form action="{{ route('vendor.product.reject', $product->id) }}"
                                                        method="POST" style="display:inline">
                                                        @csrf
                                                        <button type="submit" class="btn-sd danger">✕ Reject</button>
                                                    </form>
                                                @elseif ($product->status == 'approved')
                                                    <span class="sd-badge success">✓ Approved</span>
                                                    <form action="{{ route('vendor.product.reject', $product->id) }}"
                                                        method="POST" style="display:inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn-sd outline-danger">Reject</button>
                                                    </form>
                                                @elseif ($product->status == 'rejected')
                                                    <span class="sd-badge danger">✕ Rejected</span>
                                                    <form action="{{ route('vendor.product.approve', $product->id) }}"
                                                        method="POST" style="display:inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn-sd outline-success">Approve</button>
                                                    </form>
                                                @else
                                                    <span class="sd-badge" style="background:#f1f5f9;color:#94a3b8;">No
                                                        Action</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="icon">🛍️</div>
                        <div>No products found for this vendor.</div>
                    </div>
                @endif
            </div>

            {{-- Hidden product data for modal --}}
            @foreach ($products as $product)
                <div id="product-data-{{ $product->id }}" style="display:none;">
                    <h5 class="mb-3 fw-bold">{{ $product->collectLocalization('name') }}</h5>
                    <div class="table-responsive">
                        <table class="product-details-table">
                            <tbody>
                                @foreach ($product->getAttributes() as $key => $value)
                                    @php
                                        $beforeImageFields = [
                                            'id',
                                            'category_id',
                                            'vendor_id',
                                            'shop_id',
                                            'added_by',
                                            'brand_id',
                                            'unit_id',
                                        ];
                                    @endphp
                                    <tr class="{{ in_array($key, $beforeImageFields) ? 'top-grey-row' : '' }}">
                                        <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                        <td>
                                            @if ($key == 'thumbnail_image' && $value)
                                                <img src="{{ uploadedAsset($value) }}"
                                                    onclick="openImageModal('{{ uploadedAsset($value) }}')">
                                            @elseif ($key == 'gallery_images')
                                                @php $images = $value ? explode(',', $value) : []; @endphp
                                                @foreach ($images as $imgId)
                                                    @php $img = \App\Models\MediaManager::find($imgId); @endphp
                                                    @if ($img)
                                                        <img src="{{ asset($img->media_file) }}"
                                                            onclick="openImageModal('{{ asset($img->media_file) }}')">
                                                    @endif
                                                @endforeach
                                            @elseif (in_array($key, ['description', 'short_description']))
                                                {!! $product->collectLocalization($key) !!}
                                            @elseif (in_array($key, ['additional_info', 'product_info', 'about_items', 'brand_specs', 'icon_slider']))
                                                @php $data = json_decode($value, true); @endphp
                                                @if ($data)
                                                    <ul class="mb-0 ps-3">
                                                        @foreach ($data as $item)
                                                            <li>{{ $item['title'] ?? '' }}: {{ $item['value'] ?? '' }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            @else
                                                {{ $value ?? '—' }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── BRAND REQUESTS ── --}}
        <div class="tab-pane fade" id="brandRequests" role="tabpanel">
            @if ($brandRequests->count())
                @foreach ($brandRequests as $req)
                    <div class="brand-card">
                        <div class="brand-card-top">
                            <span style="font-size:22px;">✨</span>
                            <div>
                                <h5>{{ $req->brand_name }}</h5>
                            </div>
                            <div class="ms-auto">
                                @if ($req->status == 'pending')
                                    <span class="sd-badge warning">Pending Review</span>
                                @elseif ($req->status == 'approved')
                                    <span class="sd-badge success">✓ Approved</span>
                                @else
                                    <span class="sd-badge danger">✕ Rejected</span>
                                @endif
                            </div>
                        </div>

                        <div class="brand-images-row">
                            @foreach ([
            'Logo' => $req->logo,
            'Product Image' => $req->product_image,
            'Packaging' => $req->packaging_image,
            'Hand Image' => $req->hand_image,
        ] as $imgLabel => $imgVal)
                                <div class="brand-img-box" onclick="openImageModal('{{ uploadedAsset($imgVal) }}')">
                                    <div class="img-label">{{ $imgLabel }}</div>
                                    <img src="{{ uploadedAsset($imgVal) }}" alt="{{ $imgLabel }}">
                                </div>
                            @endforeach
                        </div>

                        <div class="brand-desc-box">
                            <div class="img-label">Description</div>
                            <p>{{ $req->description ?? '—' }}</p>
                        </div>

                        @if ($req->status == 'pending')
                            <div class="brand-footer">
                                <span class="text-muted" style="font-size:13px;">Review this brand request:</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.brand.approve', $req->id) }}" class="btn-sd success">✓
                                        Approve</a>
                                    <a href="{{ route('admin.brand.reject', $req->id) }}" class="btn-sd danger">✕
                                        Reject</a>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="sd-card">
                    <div class="empty-state">
                        <div class="icon">✨</div>
                        <div>No brand requests found.</div>
                    </div>
                </div>
            @endif
        </div>
        {{-- pincode --}}
        <div class="tab-pane fade" id="pincodelist" role="tabpanel">
            <div class="sd-card">
                <div class="sd-card-header">
                    <h6>Pincode List</h6>
                </div>

                <div class="info-grid">
                    <div class="info-item">


                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($vendor->vendorProfile->pincodes as $item)
                                <div class="border rounded px-3 py-2 bg-light">
                                    <div class="fw-semibold">{{ $item->pincode }}</div>
                                    <small class="text-muted">
                                        {{ $item->village }},
                                        {{ $item->district }},
                                        {{ $item->state }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    </div>{{-- end tab-content --}}


    {{-- ═══════════════════════════════════════════ --}}
    {{--  IMAGE PREVIEW MODAL                        --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="text-end mb-2">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="text-center">
                    <img id="modalImage" src="" class="img-fluid rounded shadow-lg" style="max-height:80vh;">
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{--  PRODUCT DETAIL MODAL                       --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content"
                style="border-radius:var(--radius); border:1px solid var(--border); overflow:hidden;">
                <div class="modal-header" style="background:var(--surface-2); border-bottom:1px solid var(--border);">
                    <h5 class="modal-title fw-bold" style="font-size:15px;">Product Details</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="productModalBody" style="padding:24px;">
                    Loading...
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function openProductModal(id) {
            const data = document.getElementById('product-data-' + id);
            document.getElementById('productModalBody').innerHTML = data.innerHTML;
            new bootstrap.Modal(document.getElementById('productModal')).show();
        }

        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }

        // Click images inside product modal table
        document.addEventListener('click', function(e) {
            if (e.target.tagName === 'IMG' && e.target.closest('.product-details-table')) {
                const src = e.target.getAttribute('src');
                if (src) openImageModal(src);
            }
        });

        // Zoom on hover for modal image
        const modalImg = document.getElementById('modalImage');
        modalImg.addEventListener('mousemove', function(e) {
            const rect = modalImg.getBoundingClientRect();
            const xP = ((e.clientX - rect.left) / rect.width) * 100;
            const yP = ((e.clientY - rect.top) / rect.height) * 100;
            modalImg.style.transformOrigin = `${xP}% ${yP}%`;
            modalImg.style.transform = 'scale(2)';
        });
        modalImg.addEventListener('mouseleave', function() {
            modalImg.style.transform = 'scale(1)';
        });
    </script>
@endsection
