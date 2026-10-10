<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Shimano Components PH',
                'contact_name' => 'Kenji Takahashi',
                'email' => 'sales@shimano-ph.com',
                'phone' => '+63 2 8123 4567',
                'address' => 'Subic Bay Freeport Zone, Zambales'
            ],
            [
                'name' => 'Trek & Bontrager Distro',
                'contact_name' => 'Marco Santos',
                'email' => 'orders@trekdistro.com',
                'phone' => '+63 2 8987 6543',
                'address' => 'Ortigas Center, Pasig City'
            ],
            [
                'name' => 'SRAM & RockShox Hub',
                'contact_name' => 'Patricia Tan',
                'email' => 'distribution@sramhub.ph',
                'phone' => '+63 2 8456 7890',
                'address' => 'BGC, Taguig City'
            ],
            [
                'name' => 'Giant Bicycle Wholesale',
                'contact_name' => 'David Lee',
                'email' => 'sales@giantwholesale.com',
                'phone' => '+63 2 8321 0987',
                'address' => 'Alabang, Muntinlupa City'
            ],
        ];

        $createdSuppliers = [];
        foreach ($suppliers as $data) {
            $createdSuppliers[] = Supplier::firstOrCreate(['name' => $data['name']], $data);
        }

        // Assign suppliers to existing products that don't have one
        $products = Product::all();
        foreach ($products as $index => $product) {
            if (!$product->supplier_id && count($createdSuppliers) > 0) {
                $supplier = $createdSuppliers[$index % count($createdSuppliers)];
                $product->update(['supplier_id' => $supplier->id]);
            }
        }
    }
}
