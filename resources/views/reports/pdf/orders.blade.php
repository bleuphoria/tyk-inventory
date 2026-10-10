<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sales Orders & Revenue Summary Report</title>
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
            border-bottom: 2px solid #059669;
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
        .summary-boxes {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .summary-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 10px 14px;
            border-radius: 6px;
        }
        .summary-box-title {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: bold;
        }
        .summary-box-val {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-top: 4px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background-color: #059669;
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
        .badge-completed {
            background-color: #d1fae5;
            color: #065f46;
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
                    <div class="logo-sub">{{ setting('shop_tagline', 'Specialized Bikes & Components') }} • Customer Sales & Revenue Audit</div>
                </td>
                <td class="report-meta">
                    <div><strong>OFFICIAL SALES ORDERS SUMMARY</strong></div>
                    <div>Generated: {{ date('F d, Y - h:i A') }}</div>
                    <div>Status: Completed Orders</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Summary Metrics -->
    <table class="summary-boxes">
        <tr>
            <td style="width: 32%; padding-right: 10px;">
                <div class="summary-box">
                    <div class="summary-box-title">Total Orders Count</div>
                    <div class="summary-box-val">{{ number_format($orders->count()) }} Orders</div>
                </div>
            </td>
            <td style="width: 32%; padding-right: 10px;">
                <div class="summary-box">
                    <div class="summary-box-title">Items Sold Across Orders</div>
                    <div class="summary-box-val">{{ number_format($orders->sum(function($o){ return $o->items->sum('quantity'); })) }} Units</div>
                </div>
            </td>
            <td style="width: 36%;">
                <div class="summary-box">
                    <div class="summary-box-title">Total Gross Revenue</div>
                    <div class="summary-box-val" style="color: #059669;">{{ currency_symbol() }}{{ number_format($orders->sum('total_amount'), 2) }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 17%;">Order Number</th>
                <th style="width: 14%;">Date / Time</th>
                <th style="width: 25%;">Customer</th>
                <th style="width: 12%;">Payment</th>
                <th style="width: 8%;" class="text-center">Items</th>
                <th style="width: 10%;" class="text-center">Status</th>
                <th style="width: 14%;" class="text-right">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td style="font-family: monospace; font-weight: bold; color: #1e40af;">{{ $order->order_number }}</td>
                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                <td>
                    <strong>{{ $order->customer_name }}</strong>
                    @if($order->customer_phone)
                        <span style="color: #6b7280; font-size: 9.5px; display: block;">{{ $order->customer_phone }}</span>
                    @endif
                </td>
                <td style="text-transform: uppercase;">{{ $order->payment_method }}</td>
                <td class="text-center">{{ $order->items->sum('quantity') }}</td>
                <td class="text-center"><span class="badge-completed">{{ strtoupper($order->status) }}</span></td>
                <td class="text-right" style="font-weight: bold;">{{ currency_symbol() }}{{ number_format($order->total_amount, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px; color: #9ca3af;">
                    No customer orders recorded yet.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($orders->isNotEmpty())
        <tfoot>
            <tr style="background-color: #f3f4f6; font-weight: bold;">
                <td colspan="4" style="text-align: right; padding: 10px;">TOTAL REVENUE:</td>
                <td class="text-center" style="padding: 10px;">{{ $orders->sum(function($o){ return $o->items->sum('quantity'); }) }}</td>
                <td></td>
                <td class="text-right" style="padding: 10px; color: #047857; font-size: 12px;">
                    {{ currency_symbol() }}{{ number_format($orders->sum('total_amount'), 2) }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        {{ setting('shop_name', 'TYK Enterprises') }} Inventory System • Official Financial Record • Page 1 of 1
    </div>

</body>
</html>
