<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::getAll();

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_tagline' => 'nullable|string|max:255',
            'shop_address' => 'nullable|string|max:500',
            'shop_phone' => 'nullable|string|max:100',
            'shop_email' => 'nullable|email|max:255',
            'shop_tin' => 'nullable|string|max:100',

            'currency_symbol' => 'required|string|max:10',
            'currency_code' => 'required|string|max:10',
            'currency_position' => 'required|in:before,after',

            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_mode' => 'required|in:inclusive,exclusive',

            'low_stock_threshold' => 'required|integer|min:1|max:1000',

            'receipt_footer' => 'nullable|string|max:500',
            'warranty_policy' => 'nullable|string|max:1000',
        ]);

        $fields = [
            'shop_name' => 'general',
            'shop_tagline' => 'general',
            'shop_address' => 'general',
            'shop_phone' => 'general',
            'shop_email' => 'general',
            'shop_tin' => 'general',

            'currency_symbol' => 'localization',
            'currency_code' => 'localization',
            'currency_position' => 'localization',

            'tax_rate' => 'tax',
            'tax_mode' => 'tax',

            'low_stock_threshold' => 'inventory',

            'receipt_footer' => 'receipt',
            'warranty_policy' => 'receipt',
        ];

        foreach ($fields as $key => $group) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key), $group);
            }
        }

        Setting::clearCache();

        return redirect()->route('settings.index')->with('success', 'Store settings and localization preferences updated successfully!');
    }
}
