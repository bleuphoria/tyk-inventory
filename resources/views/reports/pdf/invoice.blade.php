<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            font-size: 11.5px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #111827;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .brand-name {
            font-size: 26px;
            font-weight: 900;
            color: #111827;
            letter-spacing: -0.5px;
        }
        .brand-sub {
            font-size: 11px;
            color: #6b7280;
            margin-top: 3px;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h1 {
            font-size: 22px;
            margin: 0;
            color: #1e40af;
            font-weight: bold;
        }
        .invoice-num {
            font-family: monospace;
            font-size: 14px;
            font-weight: bold;
            color: #374151;
            margin-top: 4px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }
        .meta-table td {
            padding: 10px 14px;
            vertical-align: top;
        }
        .meta-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .meta-val {
            font-size: 12px;
            color: #111827;
            font-weight: bold;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        table.items-table th {
            background-color: #111827;
            color: #ffffff;
            font-size: 10.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
        }
        table.items-table td {
            padding: 9px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        table.items-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-table {
            width: 45%;
            float: right;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 6px 10px;
            font-size: 11.5px;
        }
        .totals-table tr.grand-total td {
            border-top: 2px solid #111827;
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            padding-top: 8px;
        }
        .notes-box {
            width: 50%;
            float: left;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 10.5px;
            color: #4b5563;
        }
        .footer {
            clear: both;
            margin-top: 40px;
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
            font-size: 10px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                <div class="brand-name">{{ setting('shop_name', 'TYK ENTERPRISES') }}</div>
                <div class="brand-sub">{{ setting('shop_tagline', 'Specialized Bikes & Premium Components') }}</div>
                <div style="font-size: 10px; color: #6b7280; margin-top: 4px;">
                    {{ setting('shop_address', 'Davao City, Philippines') }}
                    @if(setting('shop_phone')) • Tel: {{ setting('shop_phone') }} @endif
                    @if(setting('shop_tin')) • TIN: {{ setting('shop_tin') }} @endif
                </div>
                <div style="font-size: 9.5px; color: #9ca3af; margin-top: 2px;">
                    Official Sales Invoice & Warranty Document
                </div>
            </td>
            <td class="invoice-title" style="width: 45%;">
                <h1>SALES INVOICE</h1>
                <div class="invoice-num">{{ $order->order_number }}</div>
                <div style="font-size: 11px; color: #6b7280; margin-top: 3px;">
                    Date: {{ $order->created_at->format('F d, Y — h:i A') }}
                </div>
            </td>
        </tr>
    </table>

    <table class="meta-table">
        <tr>
            <td style="width: 35%;">
                <div class="meta-label">Billed To (Customer)</div>
                <div class="meta-val">{{ $order->customer_name }}</div>
                @if($order->customer_phone)
                    <div style="font-size: 11px; color: #4b5563; margin-top: 2px;">Phone: {{ $order->customer_phone }}</div>
                @endif
            </td>
            <td style="width: 30%;">
                <div class="meta-label">Payment Method</div>
                <div class="meta-val" style="text-transform: uppercase;">{{ $order->payment_method }}</div>
                <div style="font-size: 10.5px; color: #059669; font-weight: bold; margin-top: 2px;">PAID IN FULL</div>
            </td>
            <td style="width: 35%;">
                <div class="meta-label">Order Status</div>
                <div class="meta-val" style="text-transform: uppercase; color: #059669;">{{ $order->status }}</div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 10%;">#</th>
                <th style="width: 50%;">Item Description / Category</th>
                <th style="width: 12%;" class="text-center">Qty</th>
                <th style="width: 14%;" class="text-right">Unit Price</th>
                <th style="width: 14%;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $idx => $item)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>
                    <strong>{{ $item->product->name ?? 'Product' }}</strong>
                    <div style="font-size: 10px; color: #6b7280; font-family: monospace;">
                        SKU: {{ $item->product->sku ?? 'N/A' }}
                        @if(isset($item->product->category))
                            • Category: {{ $item->product->category->name }}
                        @endif
                    </div>
                </td>
                <td class="text-center" style="font-weight: bold;">{{ $item->quantity }}</td>
                <td class="text-right">{{ currency_symbol() }}{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right" style="font-weight: bold;">{{ currency_symbol() }}{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="width: 100%; margin-top: 10px;">
        <div class="notes-box">
            <strong>Notes / Instructions:</strong>
            <p style="margin: 4px 0 0 0;">
                {{ $order->notes ?? 'Thank you for your business! Please keep this invoice for parts warranty and service records.' }}
            </p>
        </div>

        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">{{ currency_symbol() }}{{ number_format($order->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Sales Tax ({{ setting('tax_rate', '12') }}% - {{ strtoupper(setting('tax_mode', 'inclusive')) }}):</td>
                <td class="text-right">Included</td>
            </tr>
            <tr class="grand-total">
                <td>Grand Total:</td>
                <td class="text-right">{{ currency_symbol() }}{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        {{ setting('shop_name', 'TYK Enterprises') }} • {{ setting('receipt_footer', 'Davao City Premier Bike Hub') }}
    </div>

</body>
</html>
