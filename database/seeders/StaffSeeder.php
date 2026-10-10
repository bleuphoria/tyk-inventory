<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Upgrade original test user to Admin
        $defaultUser = User::where('email', 'test@example.com')->first();
        if ($defaultUser) {
            $defaultUser->update([
                'role' => 'admin',
                'phone' => '0917-100-0001',
                'is_active' => true,
            ]);
        }

        // 2. Dedicated Admin account
        User::updateOrCreate(
            ['email' => 'admin@tyk.com'],
            [
                'name' => 'Eduardo TYK (Owner)',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0917-888-9999',
                'is_active' => true,
            ]
        );

        // 3. Inventory Manager account
        User::updateOrCreate(
            ['email' => 'inventory@tyk.com'],
            [
                'name' => 'Ramon Santos (Warehouse Lead)',
                'password' => Hash::make('password'),
                'role' => 'inventory_manager',
                'phone' => '0918-222-3344',
                'is_active' => true,
            ]
        );

        // 4. POS Cashier account
        User::updateOrCreate(
            ['email' => 'cashier@tyk.com'],
            [
                'name' => 'Carla Diaz (Store Cashier)',
                'password' => Hash::make('password'),
                'role' => 'cashier',
                'phone' => '0920-555-6789',
                'is_active' => true,
            ]
        );
    }
}
