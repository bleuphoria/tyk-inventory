<?php

namespace App\Observers;

use App\Models\InventoryTransaction;
use App\Models\ActivityLog;

class InventoryTransactionObserver
{
    /**
     * Handle the InventoryTransaction "created" event.
     */
    public function created(InventoryTransaction $inventoryTransaction): void
    {
        $product = $inventoryTransaction->product;
        $type = $inventoryTransaction->type === 'in' ? 'Added' : 'Removed';
        $icon = $inventoryTransaction->type === 'in' ? 'arrow-down' : 'arrow-up';
        $color = $inventoryTransaction->type === 'in' ? 'green' : 'orange';

        ActivityLog::create([
            'action' => "Stock {$type}",
            'description' => "{$inventoryTransaction->quantity} units of {$product->name} {$type}",
            'icon' => $icon,
            'color' => $color,
        ]);
    }

    /**
     * Handle the InventoryTransaction "updated" event.
     */
    public function updated(InventoryTransaction $inventoryTransaction): void
    {
        //
    }

    /**
     * Handle the InventoryTransaction "deleted" event.
     */
    public function deleted(InventoryTransaction $inventoryTransaction): void
    {
        //
    }

    /**
     * Handle the InventoryTransaction "restored" event.
     */
    public function restored(InventoryTransaction $inventoryTransaction): void
    {
        //
    }

    /**
     * Handle the InventoryTransaction "force deleted" event.
     */
    public function forceDeleted(InventoryTransaction $inventoryTransaction): void
    {
        //
    }
}
