<?php
 
use Modules\Product\Entities\Product;
use Modules\Support\Money;
use Modules\FlashSale\Entities\FlashSale;
use Modules\Product\Entities\ProductVariant;
 
if (!function_exists('apply_margin')) {
    function apply_margin(float $price, float $percentage): float
    {
        return round($price + ($price * $percentage / 100), 2);
    }
}
 
// if (!function_exists('calculate_margin_price')) {
//     function calculate_margin_price(Product $product): float
//     {
//         $basePrice = $product->hasSpecialPrice()
//             ? $product->getSpecialPrice()->amount()
//             : $product->price->amount();
 
//         if (!is_null($product->margin_percentage)) {
//             return apply_margin($basePrice, (float) $product->margin_percentage);
//         }
 
//         foreach ($product->categories as $category) {
//             if (!is_null($category->margin_percentage)) {
//                 return apply_margin($basePrice, (float) $category->margin_percentage);
//             }
//         }

// $globalMargin = null;
 
// if (!app()->runningInConsole() && app()->bound('setting')) {
//     $globalMargin = setting('global_margin_percentage');
// }
 
//         if (!empty($globalMargin)) {
//             return apply_margin($basePrice, (float) $globalMargin);
//         }
 
//         return $basePrice;
//     }
// }
 
// if (!function_exists('calculate_margin_price')) {
//     function calculate_margin_price(Product $product): float
//     {
//         // ✅ SAFE base price
//         if ($product->hasSpecialPrice()) {
//             $basePrice = $product->getSpecialPrice()->amount();
//         } elseif ($product->price) {
//             $basePrice = $product->price->amount();
//         } else {
//             $basePrice = 0.0;
//         }

//         // 1️⃣ Product-level margin
//         if (!is_null($product->margin_percentage)) {
//             return apply_margin($basePrice, (float) $product->margin_percentage);
//         }

//         // 2️⃣ Category-level margin
//         foreach ($product->categories as $category) {
//             if (!is_null($category->margin_percentage)) {
//                 return apply_margin($basePrice, (float) $category->margin_percentage);
//             }
//         }

//         // 3️⃣ Global-level margin
//         $globalMargin = null;
//         if (!app()->runningInConsole() && app()->bound('setting')) {
//             $globalMargin = setting('global_margin_percentage');
//         }

//         if (!empty($globalMargin)) {
//             return apply_margin($basePrice, (float) $globalMargin);
//         }

//         // ✅ ALWAYS return float
//         return (float) $basePrice;
//     }
// }
if (!function_exists('calculate_margin_price')) {
    function calculate_margin_price(Product $product): float
    {
        /**
         * IMPORTANT RULE:
         * - If product has variants → DO NOT apply margin
         * - Variant price is final
         */
        if ($product->variants()->exists()) {
            return 0.0;
        }

        // ✅ Safe base price
        if ($product->hasSpecialPrice()) {
            $basePrice = $product->getSpecialPrice()->amount();
        } elseif ($product->price) {
            $basePrice = $product->price->amount();
        } else {
            $basePrice = 0.0;
        }

        // 1️⃣ Product-level margin
        if (is_numeric($product->margin_percentage)) {
            return apply_margin($basePrice, (float) $product->margin_percentage);
        }

        // 2️⃣ Category-level margin
        foreach ($product->categories as $category) {
            if (is_numeric($category->margin_percentage)) {
                return apply_margin($basePrice, (float) $category->margin_percentage);
            }
        }

        // 3️⃣ Global margin
        if (!app()->runningInConsole() && app()->bound('setting')) {
            $globalMargin = setting('global_margin_percentage');

            if (is_numeric($globalMargin)) {
                return apply_margin($basePrice, (float) $globalMargin);
            }
        }

        // ✅ Always return float
        return (float) $basePrice;
    }
}

// if (!function_exists('product_price_formatted')) {
//     function product_price_formatted(Product|ProductVariant $item): string
//     {
       
//         if ($item instanceof Product && FlashSale::contains($item)) {
//             $previous = $item->hasSpecialPrice()
//                 ? $item->getSpecialPrice()
//                 : $item->price;
 
//             $flash = FlashSale::pivot($item)->price;
 
//             return "<span class='special-price'>{$flash->convertToCurrentCurrency()->format()}</span>
//                     <span class='previous-price'>{$previous->convertToCurrentCurrency()->format()}</span>";
//         }
 
//         // ✅ Apply margin AFTER special price
//         $finalAmount = calculate_margin_price($item);
 
//         // $formattedFinal = Money::inDefaultCurrency($finalAmount)
//         //     ->convertToCurrentCurrency()
//         //     ->format();
   
//         $formattedFinal = $finalAmount;
 
//         // Show strike-through original if special price exists
//         if ($item->hasSpecialPrice()) {
//             $original = Money::inDefaultCurrency($item->price->amount())
//                 ->convertToCurrentCurrency()
//                 ->format();
 
//             return "<span class='special-price'>{$formattedFinal}</span>
//                     <span class='previous-price'>{$original}</span>";
//         }
 
//         return $formattedFinal;
//     }
// }
 
if (!function_exists('product_price_formatted')) {
    function product_price_formatted(Product|ProductVariant $item): string
    {
        // 🔥 Flash sale (Product only)
        if ($item instanceof Product && FlashSale::contains($item)) {
            $previous = $item->hasSpecialPrice()
                ? $item->getSpecialPrice()
                : $item->price;

            $flash = FlashSale::pivot($item)->price;

            return "<span class='special-price'>{$flash->convertToCurrentCurrency()->format()}</span>
                    <span class='previous-price'>{$previous->convertToCurrentCurrency()->format()}</span>";
        }

        /**
         * ✅ PRODUCT (simple product)
         * Apply margin
         */
        if ($item instanceof Product) {
            $amount = calculate_margin_price($item);

            return Money::inDefaultCurrency($amount)
                ->convertToCurrentCurrency()
                ->format();
        }

        /**
         * ✅ VARIANT
         * NO margin — variant price is final
         */
        return $item->selling_price
            ->convertToCurrentCurrency()
            ->format();
    }
}

 