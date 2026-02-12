<?php

namespace Modules\Product\Admin;

use Modules\Admin\Ui\AdminTable;
use Illuminate\Http\JsonResponse;
use Modules\Product\Entities\Product;
use Modules\Support\Money;

class ProductTable extends AdminTable
{
    /**
     * Raw columns that will not be escaped.
     *
     * @var array
     */
    protected array $rawColumns = ['in_stock'];


    /**
     * Make table response for the resource.
     *
     * @return JsonResponse
     */
    public function make()
    {
        return $this->newTable()
            ->editColumn('thumbnail', function ($product) {
                return view('admin::partials.table.image', [
                    'file' => ($product->variant && $product->variant->base_image->id) ? $product->variant->base_image : $product->base_image,
                ]);
            })
//      ->editColumn('price', function (Product $product) {
//     return $product->selling_price
//         ->convertToCurrentCurrency()
//         ->format();
// })
->editColumn('price', function (Product $product) {

    // ✅ If product has variants → show default variant price
    if ($product->variants()->exists()) {
        $defaultVariant = $product->variants()
            ->where('is_default', 1)
            ->first();

        if ($defaultVariant && $defaultVariant->selling_price) {
            return $defaultVariant->selling_price
                ->convertToCurrentCurrency()
                ->format();
        }
    }

    // ✅ Simple product → show product price
    if ($product->selling_price) {
        return $product->selling_price
            ->convertToCurrentCurrency()
            ->format();
    }

    // ✅ Fallback
    return Money::inDefaultCurrency(0)
        ->convertToCurrentCurrency()
        ->format();
})
     

        ->editColumn('in_stock', function (Product $product) {
                $item = $product->variant ?? $product;
                $in_stock = $item->in_stock && (!$item->manage_stock || $item->qty > 0);

                return $in_stock ? "<span class='badge badge-primary'>" . trans('product::products.form.stock_availability_states.1') . "</span>" : "<span class='badge badge-danger'>" . trans('product::products.form.stock_availability_states.0') . "</span>";
            });
    }
}
