@extends('backend.layouts.master')

@section('contents')
    <style>
        :root {
            --green: #3db843;
            --green-dark: #2e9933;
            --green-light: #edf7ee;
            --green-mid: #d0ecd1;
            --green-glow: rgba(61, 184, 67, 0.13);
            --green-border: rgba(61, 184, 67, 0.28);
            --bg-page: #f4f6f8;
            --bg-card: #ffffff;
            --bg-thead: #f8f9fa;
            --bg-input: #f5f5f5;
            --bg-hover: #f0faf0;
            --border: #e3e7ec;
            --border-focus: #3db843;
            --text-primary: #1a2332;
            --text-secondary: #4a5568;
            --text-muted: #94a3b8;
            --badge-zone: rgba(61, 184, 67, 0.12);
            --badge-weight: rgba(37, 99, 235, 0.08);
            --blue: #2563eb;
            --blue-light: rgba(37, 99, 235, 0.1);
            --danger: #ef4444;
            --danger-light: rgba(239, 68, 68, 0.08);
            --shadow-card: 0 2px 16px rgba(0, 0, 0, 0.08);
            --radius: 12px;
            --radius-sm: 7px;
            --transition: 0.16s cubic-bezier(.4, 0, .2, 1);
        }

        .sc-page {
            background: var(--bg-page);
            min-height: 100vh;
            padding: 14px 10px 40px;
            color: var(--text-primary);
        }

        .sc-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-top: 3px solid var(--green);
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            margin-bottom: 12px;
        }

        .sc-card-header {
            display: flex;
            align-items: center;
            padding: 8px 14px;
            border-bottom: 1px solid var(--border);
            background: var(--bg-card);
        }

        .sc-card-header-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--green);
            flex-shrink: 0;
            margin-right: 7px;
        }

        .sc-card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            white-space: nowrap;
        }

        .sc-card-body {
            padding: 10px 14px;
        }

        /* FORM */
        .sc-field label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 3px;
        }

        .sc-field select,
        .sc-field input[type="text"] {
            width: 100%;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-size: 13px;
            padding: 6px 10px;
            outline: none;
            transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
            appearance: none;
        }

        .sc-field select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            padding-right: 24px;
            cursor: pointer;
        }

        .sc-field select:focus,
        .sc-field input[type="text"]:focus {
            border-color: var(--border-focus);
            background: #fff;
            box-shadow: 0 0 0 3px var(--green-glow);
        }

        .sc-btn {
            width: 100%;
            padding: 7px 14px;
            background: var(--green);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background var(--transition), box-shadow var(--transition), transform var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            white-space: nowrap;
        }

        .sc-btn:hover {
            background: var(--green-dark);
            box-shadow: 0 4px 14px rgba(61, 184, 67, 0.3);
            transform: translateY(-1px);
        }

        .sc-btn:active {
            transform: translateY(0);
        }

        /* FILTER BAR */
        .sc-filter-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: nowrap;
        }

        .sc-filter-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .sc-filter-select {
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            font-size: 12px;
            padding: 4px 22px 4px 8px;
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='9' height='9' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 7px center;
            cursor: pointer;
            min-width: 100px;
            transition: border-color var(--transition);
        }

        .sc-filter-select:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 2px var(--green-glow);
            outline: none;
        }

        .sc-filter-reset {
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 500;
            padding: 4px 9px;
            cursor: pointer;
            transition: all var(--transition);
            display: flex;
            align-items: center;
            gap: 3px;
            white-space: nowrap;
        }

        .sc-filter-reset:hover {
            border-color: var(--danger);
            color: var(--danger);
        }

        .sc-count-badge {
            font-size: 11px;
            color: var(--text-muted);
            white-space: nowrap;
            padding-left: 2px;
        }

        .sc-count-badge strong {
            color: var(--green-dark);
        }

        /* ── TABLE — key fix: table-layout fixed + tiny padding ── */
        .sc-table-wrap {
            overflow-x: hidden;
            width: 100%;
        }

        .sc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
            table-layout: fixed;
        }

        /* Column widths — total must fit ~100% */
        .sc-table col.c-id {
            width: 3%;
        }

        .sc-table col.c-zone {
            width: 7%;
        }

        .sc-table col.c-weight {
            width: 9%;
        }

        .sc-table col.c-num {
            width: 5.5%;
        }

        .sc-table col.c-act {
            width: 5%;
        }

        .sc-table thead tr {
            background: var(--bg-thead);
            border-top: 1px solid var(--border);
            border-bottom: 2px solid var(--border);
        }

        .sc-table thead th {
            padding: 7px 5px;
            font-size: 10px;
            font-weight: 700;
            color: var(--text-secondary);
            text-align: center;
            white-space: normal;
            word-break: break-word;
            line-height: 1.2;
            border: none;
            overflow: hidden;
        }

        .sc-table thead th.tl {
            text-align: left;
        }

        .sc-th-ship {
            background: var(--green-light);
            color: var(--green-dark) !important;
            border-left: 2px solid var(--green-border);
        }

        .sc-th-cod {
            background: var(--blue-light);
            color: var(--blue) !important;
            border-left: 2px solid rgba(37, 99, 235, 0.2);
        }

        .sc-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background var(--transition);
        }

        .sc-table tbody tr:last-child {
            border-bottom: none;
        }

        .sc-table tbody tr:hover {
            background: var(--bg-hover);
        }

        .sc-table tbody tr.sc-hidden {
            display: none;
        }

        .sc-table td {
            padding: 7px 5px;
            color: var(--text-primary);
            vertical-align: middle;
            border: none;
            text-align: center;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sc-table td.tl {
            text-align: left;
        }

        .sc-td-ship {
            border-left: 2px solid var(--green-mid);
        }

        .sc-td-cod {
            border-left: 2px solid rgba(37, 99, 235, 0.15);
        }

        .sc-idx {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 5px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            font-size: 10px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .sc-zone-badge {
            display: inline-flex;
            align-items: center;
            background: var(--badge-zone);
            border: 1px solid var(--green-border);
            color: var(--green-dark);
            border-radius: 20px;
            padding: 2px 7px;
            font-size: 11px;
            font-weight: 600;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sc-weight-badge {
            display: inline-flex;
            align-items: center;
            background: var(--badge-weight);
            border: 1px solid rgba(37, 99, 235, 0.2);
            color: var(--blue);
            border-radius: 20px;
            padding: 2px 7px;
            font-size: 11px;
            font-weight: 500;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sc-gst {
            color: var(--text-muted);
        }

        .sc-total {
            font-weight: 700;
            color: var(--green-dark);
        }

        .sc-total-cod {
            font-weight: 700;
            color: var(--blue);
        }

        .sc-tip-icon {
            color: var(--green);
            font-size: 11px;
            cursor: pointer;
            transition: color var(--transition);
        }

        .sc-tip-icon:hover {
            color: var(--green);
        }

        .sc-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .sc-action-btn {
            width: 24px;
            height: 24px;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            text-decoration: none;
            transition: all var(--transition);
            border: 1px solid transparent;
        }

        .sc-action-btn.edit {
            background: var(--blue-light);
            color: var(--blue);
            border-color: rgba(37, 99, 235, 0.2);
        }

        .sc-action-btn.edit:hover {
            background: rgba(37, 99, 235, 0.18);
            border-color: var(--blue);
        }

        .sc-action-btn.delete {
            background: var(--danger-light);
            color: var(--danger);
            border-color: rgba(239, 68, 68, 0.2);
        }

        .sc-action-btn.delete:hover {
            background: rgba(239, 68, 68, 0.16);
            border-color: var(--danger);
        }

        .sc-empty {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
        }

        .sc-empty i {
            font-size: 28px;
            margin-bottom: 8px;
            opacity: 0.3;
            display: block;
        }

        .sc-empty p {
            margin: 0;
            font-size: 13px;
        }
    </style>

    <div class="sc-page">

        {{-- FORM CARD --}}
        <div class="sc-card">
            <div class="sc-card-header">
                <div class="sc-card-header-dot"></div>
                <h4 class="sc-card-title">
                    @if (isset($charge))
                        Edit Shipping Charge
                    @else
                        Add New Charge
                    @endif
                </h4>
            </div>
            <div class="sc-card-body">
                <form method="POST"
                    @if (isset($charge)) action="{{ route('admin.shipping.charge.update', $charge->id) }}"
                @else
                    action="{{ route('admin.shipping.charge.store') }}" @endif>
                    @csrf
                    <div class="row g-2 align-items-end">

                        <div class="col-md-2 sc-field">
                            <label>Zone</label>
                            <select name="zone_id">
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}" @if (isset($charge) && $charge->zone_id == $zone->id) selected @endif>
                                        {{ $zone->zone_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 sc-field">
                            <label>Weight</label>
                            <select name="weight_id">
                                @foreach ($weights as $weight)
                                    <option value="{{ $weight->id }}" @if (isset($charge) && $charge->weight_id == $weight->id) selected @endif>
                                        {{ $weight->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 sc-field">
                            <label>Admin SC</label>
                            <input type="text" name="admin_charge" placeholder="0.00"
                                value="{{ $charge->admin_charge ?? '' }}">
                        </div>

                        <div class="col-md-2 sc-field">
                            <label>Seller SC</label>
                            <input type="text" name="charge" placeholder="0.00" value="{{ $charge->charge ?? '' }}">
                        </div>

                        <div class="col-md-1 sc-field">
                            <label>Admin COD</label>
                            <input type="text" name="admin_cod_charge" placeholder="0.00"
                                value="{{ $charge->admin_cod_charge ?? '' }}">
                        </div>

                        <div class="col-md-1 sc-field">
                            <label>Seller COD</label>
                            <input type="text" name="cod_charge" placeholder="0.00"
                                value="{{ $charge->cod_charge ?? '' }}">
                        </div>

                        <div class="col-md-2">
                            <button class="sc-btn" type="submit">
                                @if (isset($charge))
                                    <i class="fa fa-check"></i> Update
                                @else
                                    <i class="fa fa-save"></i> Save
                                @endif
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>


        {{-- TABLE CARD --}}
        <div class="sc-card">

            <div class="sc-card-header">
                <div class="sc-card-header-dot"></div>
                <div class="row w-100 align-items-center g-0">

                    <div class="col-md-2">
                        <h4 class="sc-card-title">Shipping Charges List</h4>
                    </div>

                    <div class="col-md-10">
                        <div class="sc-filter-wrap justify-content-end">
                            <span class="sc-filter-label"><i class="fa fa-filter"></i> Filter:</span>

                            <select class="sc-filter-select" id="filterZone" onchange="applyFilters()">
                                <option value="">All Zones</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->zone_name }}</option>
                                @endforeach
                            </select>

                            <select class="sc-filter-select" id="filterWeight" onchange="applyFilters()">
                                <option value="">All Weights</option>
                                @foreach ($weights as $weight)
                                    <option value="{{ $weight->id }}">{{ $weight->title }}</option>
                                @endforeach
                            </select>

                            <button class="sc-filter-reset" onclick="resetFilters()" type="button">
                                <i class="fa fa-times"></i> Reset
                            </button>

                            <div class="sc-count-badge">
                                Showing <strong id="visibleCount">{{ $charges->count() }}</strong> of
                                <strong>{{ $charges->count() }}</strong>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="sc-table-wrap">
                <table class="sc-table" id="chargesTable">

                    <colgroup>
                        <col class="c-id">
                        <col class="c-zone">
                        <col class="c-weight">

                        {{-- Shipping (7 cols) --}}
                        <col class="c-num">
                        <col class="c-num">
                        <col class="c-num">
                        <col class="c-num">
                        <col class="c-num">
                        <col class="c-num">
                        <col class="c-num">

                        {{-- COD (7 cols) --}}
                        <col class="c-num">
                        <col class="c-num">
                        <col class="c-num">
                        <col class="c-num">
                        <col class="c-num">
                        <col class="c-num">
                        <col class="c-num">

                        <col class="c-act">
                    </colgroup>

                    <thead>
                        <tr>

                            <th class="tl">ID</th>
                            <th class="tl">Zone</th>
                            <th class="tl">Weight</th>

                            {{-- Shipping --}}
                            <th class="sc-th-ship">Admin SC

                            </th>
                            <th class="sc-th-ship">GST 18%</th>
                            <th class="sc-th-ship">AT
                                <i class="fa fa-info-circle sc-tip-icon" data-bs-toggle="tooltip"
                                    title="Admin Total Shipping Charge (Admin Shipping Charge + GST)"></i>
                            </th>



                            <th class="sc-th-ship">Seller SC</th>
                            <th class="sc-th-ship">GST 18%</th>
                            <th class="sc-th-ship">ST
                                <i class="fa fa-info-circle sc-tip-icon" data-bs-toggle="tooltip"
                                    title="Seller Total Shipping Charge (Seller Shipping Charge + GST)"></i>
                            </th>

                            <th class="sc-th-ship">
                                ASM
                                <i class="fa fa-info-circle sc-tip-icon" data-bs-toggle="tooltip"
                                    title="Admin Margin on Shipping (Seller Total Shipping Charge -Admin Total Shipping Charge)"></i>
                            </th>

                            {{-- COD --}}
                            <th class="sc-th-cod">Admin COD</th>
                            <th class="sc-th-cod">GST 18%</th>
                            <th class="sc-th-cod">AT COD</th>



                            <th class="sc-th-cod">Seller COD</th>
                            <th class="sc-th-cod">GST 18%</th>
                            <th class="sc-th-cod">ST COD</th>

                            <th class="sc-th-cod">
                                ACM
                                <i class="fa fa-info-circle sc-tip-icon" data-bs-toggle="tooltip"
                                    title="Admin Margin on COD (Seller Total COD Charge - Admin Total COD Charge)"></i>
                            </th>

                            <th>Action</th>

                        </tr>
                    </thead>

                    <tbody>

                        @forelse($charges as $item)
                            <tr data-zone="{{ $item->zone_id }}" data-weight="{{ $item->weight_id }}">

                                <td class="tl">
                                    <span class="sc-idx">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>

                                <td class="tl">
                                    <span class="sc-zone-badge d-inline-flex align-items-center gap-1">
                                        {{ $item->zone->zone_name ?? '—' }}
                                        <i class="fa fa-info-circle sc-tip-icon" data-bs-toggle="tooltip"
                                            title="Zone: {{ $item->zone->definition ?? 'No description' }}"></i>
                                    </span>
                                </td>

                                <td class="tl">
                                    <span class="sc-weight-badge">
                                        {{ $item->weight->title ?? '—' }}
                                    </span>
                                </td>

                                {{-- SHIPPING --}}
                                <td class="sc-td-ship">
                                    {{ number_format($item->admin_charge, 2) }}
                                </td>

                                <td class="sc-gst sc-td-ship">
                                    {{ number_format($item->admin_shipping_gst, 2) }}
                                </td>

                                <td class="sc-total sc-td-ship">
                                    {{ number_format($item->admin_total_charge, 2) }}
                                </td>



                                <td class="sc-td-ship">
                                    {{ number_format($item->charge, 2) }}
                                </td>

                                <td class="sc-gst sc-td-ship">
                                    {{ number_format($item->shipping_gst, 2) }}
                                </td>

                                <td class="sc-total sc-td-ship">
                                    {{ number_format($item->total_charge, 2) }}
                                </td>

                                {{-- SHIPPING MARGIN --}}
                                <td class="sc-total sc-td-ship">
                                    {{ number_format($item->admin_margin_shipping, 2) }}
                                </td>

                                {{-- COD --}}
                                <td class="sc-td-cod">
                                    {{ number_format($item->admin_cod_charge, 2) }}
                                </td>

                                <td class="sc-gst sc-td-cod">
                                    {{ number_format($item->admin_cod_gst, 2) }}
                                </td>

                                <td class="sc-total-cod sc-td-cod">
                                    {{ number_format($item->admin_total_charge_with_cod, 2) }}
                                </td>



                                <td class="sc-td-cod">
                                    {{ number_format($item->cod_charge, 2) }}
                                </td>

                                <td class="sc-gst sc-td-cod">
                                    {{ number_format($item->cod_gst, 2) }}
                                </td>

                                <td class="sc-total-cod sc-td-cod">
                                    {{ number_format($item->total_charge_with_cod, 2) }}
                                </td>

                                {{-- COD MARGIN --}}
                                <td class="sc-total-cod sc-td-cod">
                                    {{ number_format($item->admin_margin_cod, 2) }}
                                </td>

                                <td>
                                    <div class="sc-actions">

                                        <a href="{{ route('admin.shipping.charge.edit', $item->id) }}"
                                            class="sc-action-btn edit" title="Edit">

                                            <i class="fa fa-edit"></i>

                                        </a>

                                        <a href="{{ route('admin.shipping.charge.delete', $item->id) }}"
                                            class="sc-action-btn delete" title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this record?')">

                                            <i class="fa fa-trash"></i>

                                        </a>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="18">

                                    <div class="sc-empty">

                                        <i class="fa fa-truck"></i>

                                        <p>No shipping charges found.</p>

                                    </div>

                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>
        </div>

    </div>

    <script>
        function applyFilters() {
            const zoneVal = document.getElementById('filterZone').value;
            const weightVal = document.getElementById('filterWeight').value;
            const rows = document.querySelectorAll('#chargesTable tbody tr[data-zone]');
            let visible = 0;
            rows.forEach(row => {
                const zm = !zoneVal || row.dataset.zone === zoneVal;
                const wm = !weightVal || row.dataset.weight === weightVal;
                if (zm && wm) {
                    row.classList.remove('sc-hidden');
                    visible++;
                } else {
                    row.classList.add('sc-hidden');
                }
            });
            document.getElementById('visibleCount').textContent = visible;
        }

        function resetFilters() {
            document.getElementById('filterZone').value = '';
            document.getElementById('filterWeight').value = '';
            applyFilters();
        }
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof bootstrap !== 'undefined') {
                document.querySelectorAll('[data-bs-toggle="tooltip"]')
                    .forEach(el => new bootstrap.Tooltip(el));
            }
        });
    </script>
@endsection
