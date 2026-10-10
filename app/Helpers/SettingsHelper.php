<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('currency_symbol')) {
    function currency_symbol(): string
    {
        return Setting::get('currency_symbol', '₱');
    }
}

if (!function_exists('format_currency')) {
    function format_currency(mixed $amount, int $decimals = 2): string
    {
        $symbol = currency_symbol();
        $position = Setting::get('currency_position', 'before');
        $formatted = number_format(floatval($amount), $decimals);

        return $position === 'after' ? "{$formatted} {$symbol}" : "{$symbol}{$formatted}";
    }
}

if (!function_exists('shop_name')) {
    function shop_name(): string
    {
        return Setting::get('shop_name', 'TYK Enterprises');
    }
}

if (!function_exists('low_stock_threshold')) {
    function low_stock_threshold(): int
    {
        return intval(Setting::get('low_stock_threshold', 15));
    }
}
