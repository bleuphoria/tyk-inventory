<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        // Pre-seed default settings customized for Davao City, Philippines
        $defaults = [
            // Shop Details
            ['key' => 'shop_name', 'value' => 'TYK Enterprises', 'group' => 'general'],
            ['key' => 'shop_tagline', 'value' => 'Specialized Bikes & Premium Components', 'group' => 'general'],
            ['key' => 'shop_address', 'value' => 'J.P. Laurel Ave, Bajada, Davao City, 8000 Davao del Sur', 'group' => 'general'],
            ['key' => 'shop_phone', 'value' => '(082) 224-8990 / 0917-555-8952', 'group' => 'general'],
            ['key' => 'shop_email', 'value' => 'tykdavao@gmail.com', 'group' => 'general'],
            ['key' => 'shop_tin', 'value' => '402-179-883-000', 'group' => 'general'],

            // Localization & Currency (Philippine Peso ₱)
            ['key' => 'currency_symbol', 'value' => '₱', 'group' => 'localization'],
            ['key' => 'currency_code', 'value' => 'PHP', 'group' => 'localization'],
            ['key' => 'currency_position', 'value' => 'before', 'group' => 'localization'], // before or after

            // Tax
            ['key' => 'tax_rate', 'value' => '12', 'group' => 'tax'],
            ['key' => 'tax_mode', 'value' => 'inclusive', 'group' => 'tax'], // inclusive or exclusive

            // Inventory
            ['key' => 'low_stock_threshold', 'value' => '15', 'group' => 'inventory'],

            // Receipt & Policy
            ['key' => 'receipt_footer', 'value' => "Davao City's Premier Bike Hub. Daghang Salamat sa inyong pagsalig!", 'group' => 'receipt'],
            ['key' => 'warranty_policy', 'value' => '7-day workmanship warranty on tuning and brake adjustments. Manufacturer warranty applies to genuine Shimano, Trek, and Maxxis components. Unclaimed repairs after 30 days are subject to storage charges.', 'group' => 'receipt'],
        ];

        foreach ($defaults as $setting) {
            $setting['created_at'] = now();
            $setting['updated_at'] = now();
            DB::table('settings')->insert($setting);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
