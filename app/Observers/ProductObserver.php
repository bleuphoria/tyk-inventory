<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ActivityLog;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        ActivityLog::create([
            'action' => 'Product Added',
            'description' => "New product catalogued: {$product->name}",
            'icon' => 'plus',
            'color' => 'blue',
        ]);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Don't log if only stock quantity changed (handled by transaction observer)
        if ($product->wasChanged('stock_quantity') && count($product->getChanges()) === 1) {
            return;
        }

        ActivityLog::create([
            'action' => 'Product Updated',
            'description' => "Details updated for: {$product->name}",
            'icon' => 'pen',
            'color' => 'yellow',
        ]);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        ActivityLog::create([
            'action' => 'Product Deleted',
            'description' => "Product removed: {$product->name}",
            'icon' => 'trash',
            'color' => 'red',
        ]);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
