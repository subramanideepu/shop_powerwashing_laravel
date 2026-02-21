<?php

namespace Modules\Shipping\Services;

use Modules\Cart\Facades\Cart;

class CategoryShippingCalculator
{
    public function calculate()
    {
        $rates = [];

        foreach (Cart::items() as $item) {
             dd(Cart::items());

            $product = $item->product;

            if (!$product) {
                return null;
            }

            // ✅ Ensure categories loaded
            $product->loadMissing('categories');

            $category = $product->categories
                ->whereNotNull('shipping_type')
                ->first();

            if (!$category) {
                return null;
            }

            $rate = $this->resolveRate($category->shipping_type);

            if (is_null($rate)) {
                return null;
            }

            $rates[] = (float) $rate;
        }

        return empty($rates) ? 0 : max($rates);
    }

    private function resolveRate($type)
    {
        return match ($type) {
            'super_light' => setting('super_light_rate'),
            'light' => setting('light_rate'),
            'medium' => setting('medium_rate'),
            'heavy' => setting('heavy_rate'),
            'super_heavy' => setting('super_heavy_rate'),
            default => 0,
        };
    }
}