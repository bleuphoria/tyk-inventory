<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\InventoryTransaction;
use App\Models\Order;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function stockSummaryPdf(Request $request)
    {
        $products = Product::with(['category', 'supplier'])->orderBy('name')->get();
        $pdf = Pdf::loadView('reports.pdf.stock_summary', compact('products'));
        $filename = 'stock_summary_report_' . date('Y-m-d') . '.pdf';

        return $request->has('preview') ? $pdf->stream($filename) : $pdf->download($filename);
    }

    public function transactionsPdf(Request $request)
    {
        $transactions = InventoryTransaction::with('product')->latest()->take(100)->get();
        $pdf = Pdf::loadView('reports.pdf.transactions', compact('transactions'));
        $filename = 'stock_movements_report_' . date('Y-m-d') . '.pdf';

        return $request->has('preview') ? $pdf->stream($filename) : $pdf->download($filename);
    }

    public function ordersPdf(Request $request)
    {
        $orders = Order::with('items.product')->latest()->get();
        $pdf = Pdf::loadView('reports.pdf.orders', compact('orders'));
        $filename = 'sales_orders_report_' . date('Y-m-d') . '.pdf';

        return $request->has('preview') ? $pdf->stream($filename) : $pdf->download($filename);
    }

    public function stockSummary()
    {
        $products = Product::with('category')->get();
        
        $csvFileName = 'stock_summary_' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = ['ID', 'SKU', 'Name', 'Category', 'Stock Quantity', 'Price', 'Total Value'];

        $callback = function() use($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($products as $product) {
                $row['ID']  = $product->id;
                $row['SKU'] = $product->sku;
                $row['Name'] = $product->name;
                $row['Category'] = $product->category->name ?? 'N/A';
                $row['Stock Quantity'] = $product->stock_quantity;
                $row['Price'] = $product->price;
                $row['Total Value'] = $product->stock_quantity * $product->price;

                fputcsv($file, array($row['ID'], $row['SKU'], $row['Name'], $row['Category'], $row['Stock Quantity'], $row['Price'], $row['Total Value']));
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function transactionsSummary()
    {
        $transactions = InventoryTransaction::with('product')->latest()->get();

        $csvFileName = 'stock_transactions_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Date Time', 'Product SKU', 'Product Name', 'Movement Type', 'Quantity', 'Reference', 'Notes'];

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $tx) {
                fputcsv($file, [
                    $tx->id,
                    $tx->created_at->format('Y-m-d H:i:s'),
                    $tx->product->sku ?? 'N/A',
                    $tx->product->name ?? 'Deleted Product',
                    strtoupper($tx->type),
                    ($tx->type === 'in' ? '+' : '-') . $tx->quantity,
                    $tx->reference ?? '',
                    $tx->notes ?? '',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function ordersSummary()
    {
        $orders = \App\Models\Order::with('items.product')->latest()->get();

        $csvFileName = 'sales_orders_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Order ID', 'Order Number', 'Date', 'Customer Name', 'Customer Phone', 'Payment Method', 'Status', 'Items Count', 'Total Amount ($)'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->customer_name,
                    $order->customer_phone ?? '',
                    strtoupper($order->payment_method),
                    strtoupper($order->status),
                    $order->items->sum('quantity'),
                    number_format($order->total_amount, 2, '.', ''),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
