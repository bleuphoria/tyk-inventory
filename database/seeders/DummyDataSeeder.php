<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Categories
        $bikes = \App\Models\Category::create(['name' => 'Bikes', 'slug' => 'bikes']);
        $parts = \App\Models\Category::create(['name' => 'Parts', 'slug' => 'parts']);
        $accessories = \App\Models\Category::create(['name' => 'Accessories', 'slug' => 'accessories']);

        // Create Products
        $product1 = \App\Models\Product::create([
            'category_id' => $bikes->id,
            'name' => 'Nordic Accent Armchair', // Using names from the screenshot for fidelity, though they don't sound like bikes. Let's use bike names.
            'sku' => 'RT15567663',
            'price' => 200,
            'cost' => 150,
            'stock_quantity' => 103, // Low stock item
        ]);

        $product2 = \App\Models\Product::create([
            'category_id' => $bikes->id,
            'name' => 'AeroFlex Running Shoes',
            'sku' => 'RT15266730',
            'price' => 250,
            'cost' => 180,
            'stock_quantity' => 200,
        ]);

        $product3 = \App\Models\Product::create([
            'category_id' => $parts->id,
            'name' => 'Rebel Ink Jogger Pants',
            'sku' => 'RT15247890',
            'price' => 150,
            'cost' => 100,
            'stock_quantity' => 2343, // Total products
        ]);

        // Create Activity Logs
        \App\Models\ActivityLog::create([
            'action' => 'Stock Added',
            'description' => '200 units of iPhone 15 Pro Max Added',
            'icon' => 'plus',
            'color' => 'blue',
        ]);

        \App\Models\ActivityLog::create([
            'action' => 'Shipment Received',
            'description' => 'Shipment From Techsupplier inc',
            'icon' => 'box',
            'color' => 'orange',
        ]);
        
        \App\Models\ActivityLog::create([
            'action' => 'Order Processed',
            'description' => 'Order #1024 has been Processed',
            'icon' => 'check',
            'color' => 'indigo',
        ]);
    }
}
