<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Stock Movements Ledger Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 15px;
        }
        .header {
            border-bottom: 2px solid #7c3aed;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .logo-title {
            font-size: 24px;
            font-weight: bold;
            color: #111827;
        }
        .logo-sub {
            font-size: 11px;
            color: #6b7280;
            margin-top: 3px;
        }
        .report-meta {
            text-align: right;
            font-size: 11px;
            color: #4b5563;
        }
        .report-meta strong {
            color: #111827;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background-color: #7c3aed;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 7px 8px;
            font-size: 10px;
            text-transform: uppercase;
        }
        table.data-table td {
            padding: 7px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10.5px;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .badge-in {
            background-color: #dcfce7;
            color: #15803d;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9.5px;
        }
        .badge-out {
            background-color: #f3e8ff;
            color: #7e22ce;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9.5px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 25px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            font-size: 10px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="logo-title">{{ setting('shop_name', 'TYK ENTERPRISES') }}</div>
                    <div class="logo-sub">{{ setting('shop_tagline', 'Specialized Bikes & Components') }} • Stock Movements Audit</div>
                </td>
                <td class="report-meta">
                    <div><strong>OFFICIAL STOCK MOVEMENTS LEDGER</strong></div>
                    <div>Generated: {{ date('F d, Y - h:i A') }}</div>
                    <div>Total Transactions Logged: {{ number_format($transactions->count()) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 14%;">Date / Time</th>
                <th style="width: 13%;">Product SKU</th>
                <th style="width: 28%;">Product Name</th>
                <th style="width: 11%;" class="text-center">Movement</th>
                <th style="width: 8%;" class="text-center">Qty</th>
                <th style="width: 14%;">Reference</th>
                <th style="width: 12%;">Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $tx)
            <tr>
                <td>{{ $tx->created_at->format('M d, Y H:i') }}</td>
                <td style="font-family: monospace; font-weight: bold; color: #4b5563;">{{ $tx->product->sku ?? 'N/A' }}</td>
                <td><strong>{{ $tx->product->name ?? 'Deleted Item' }}</strong></td>
                <td class="text-center">
                    @if($tx->type === 'in')
                        <span class="badge-in">STOCK IN (+)</span>
                    @else
                        <span class="badge-out">STOCK OUT (-)</span>
                    @endif
                </td>
                <td class="text-center" style="font-weight: bold; color: {{ $tx->type === 'in' ? '#15803d' : '#7e22ce' }};">
                    {{ $tx->type === 'in' ? '+' : '-' }}{{ $tx->quantity }}
                </td>
                <td style="font-family: monospace;">{{ $tx->reference ?? '—' }}</td>
                <td style="color: #6b7280;">{{ $tx->notes ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ setting('shop_name', 'TYK Enterprises') }} Inventory System • Official Audit Record • Page 1 of 1
    </div>

</body>
</html>
