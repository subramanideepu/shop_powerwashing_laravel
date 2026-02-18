<?php


namespace Modules\Admin\Http\Controllers\Admin;


use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Order\Entities\OrderProduct;
use Illuminate\Support\Facades\DB;

class RevenueShareController  {
    /**
     * Display a listing of the resource.
     */
// public function index(Request $request)
// {
//     $filter = $request->filter ?? 'all';

//     $query = DB::table('order_products')
//         ->join('orders', 'orders.id', '=', 'order_products.order_id');

//     $query->where('orders.status', 'completed');

//     if ($filter === 'monthly') {
//         $query->whereYear('orders.created_at', now()->year)
//               ->whereMonth('orders.created_at', now()->month);
//     }

//     if ($filter === 'yearly') {
//         $query->whereYear('orders.created_at', now()->year);
//     }

    
//     $revenue = $query->sum(
//         DB::raw('order_products.unit_price * order_products.qty')
//     );

//     $share1 = $revenue * (setting('share_1') / 100);
//     $share2 = $revenue * (setting('share_2') / 100);

//     $profit = $revenue - $share1 - $share2;

    
//     $orders = DB::table('orders')
//         ->where('status', 'completed')
//         ->latest()
//         ->limit(10)
//         ->get();

//     return view('admin::revenue-share.index', compact(
//         'revenue',
//         'share1',
//         'share2',
//         'profit',
//         'filter',
//         'orders'
//     ));
// }



    // public function index(Request $request)
    // {
    //     $filterType = $request->filter_type ?? 'all';
    //     $month      = $request->month;
    //     $year       = $request->year ?? now()->year;

    //     $share1Percentage = setting('share_1') ?? 0;
    //     $share2Percentage = setting('share_2') ?? 0;

    //     /*
    //     |--------------------------------------------------------------------------
    //     | ORDERS QUERY (FIRST TABLE)
    //     |--------------------------------------------------------------------------
    //     */

    //     $ordersQuery = DB::table('orders')
    //         ->where('status', 'completed');

    //     if ($filterType === 'monthly' && $month) {
    //         $ordersQuery->whereYear('created_at', $year)
    //                     ->whereMonth('created_at', $month);
    //     }

    //     if ($filterType === 'yearly') {
    //         $ordersQuery->whereYear('created_at', $year);
    //     }

    //     $orders = $ordersQuery->latest()->get();

    //     /*
    //     |--------------------------------------------------------------------------
    //     | MAIN CALCULATION (REVENUE + PROFIT)
    //     |--------------------------------------------------------------------------
    //     */

    //     $calcQuery = DB::table('order_products')
    //         ->join('orders', 'orders.id', '=', 'order_products.order_id')
    //         ->join('products', 'products.id', '=', 'order_products.product_id')
    //         ->where('orders.status', 'completed');

    //     if ($filterType === 'monthly' && $month) {
    //         $calcQuery->whereYear('orders.created_at', $year)
    //                   ->whereMonth('orders.created_at', $month);
    //     }

    //     if ($filterType === 'yearly') {
    //         $calcQuery->whereYear('orders.created_at', $year);
    //     }

    //     $totals = $calcQuery->select(

    //         DB::raw('SUM(order_products.line_total) as revenue'),

    //         DB::raw("
    //             SUM(
    //                 (
    //                     order_products.line_total
    //                     - (order_products.line_total * {$share1Percentage} / 100)
    //                     - (order_products.line_total * {$share2Percentage} / 100)
    //                     - (products.price * order_products.qty)
    //                 )
    //             ) as profit
    //         ")

    //     )->first();

    //     $revenue = $totals->revenue ?? 0;
    //     $profit  = $totals->profit ?? 0;

    //     $share1 = $revenue * ($share1Percentage / 100);
    //     $share2 = $revenue * ($share2Percentage / 100);

    //     /*
    //     |--------------------------------------------------------------------------
    //     | INDIVIDUAL ORDER PRODUCTS (SECOND TABLE)
    //     |--------------------------------------------------------------------------
    //     */

    //     $productRowsQuery = DB::table('order_products')
    //         ->join('orders', 'orders.id', '=', 'order_products.order_id')
    //         ->join('products', 'products.id', '=', 'order_products.product_id')
    //         ->leftJoin('product_variants', 'product_variants.id', '=', 'order_products.product_variant_id')
    //         ->where('orders.status', 'completed')
    //         ->select(
    //             'orders.id as order_id',
    //             'products.slug',
    //             'product_variants.name as variant',
    //             'order_products.qty',
    //             'order_products.unit_price',
    //             'order_products.line_total',
    //             'products.price as cost_price'
    //         );

    //     if ($filterType === 'monthly' && $month) {
    //         $productRowsQuery->whereYear('orders.created_at', $year)
    //                          ->whereMonth('orders.created_at', $month);
    //     }

    //     if ($filterType === 'yearly') {
    //         $productRowsQuery->whereYear('orders.created_at', $year);
    //     }

    //     $productRows = $productRowsQuery->get()->map(function ($row) use ($share1Percentage, $share2Percentage) {

    //         $row->share1 = $row->line_total * ($share1Percentage / 100);
    //         $row->share2 = $row->line_total * ($share2Percentage / 100);

    //         $row->profit =
    //             $row->line_total
    //             - $row->share1
    //             - $row->share2
    //             - ($row->cost_price * $row->qty);

    //         return $row;
    //     });

    //     return view('admin::revenue-share.index', compact(
    //         'revenue',
    //         'share1',
    //         'share2',
    //         'profit',
    //         'orders',
    //         'productRows',
    //         'filterType',
    //         'month',
    //         'year'
    //     ));
    // }

public function index(Request $request)
{
    $filterType = $request->filter_type ?? 'all';
    $month      = $request->month;
    $year       = $request->year ?? now()->year;

    $share1Percentage = setting('share_1') ?? 0;
    $share2Percentage = setting('share_2') ?? 0;

    /*
    |--------------------------------------------------------------------------
    | ORDERS QUERY
    |--------------------------------------------------------------------------
    */

    $ordersQuery = DB::table('orders')
        ->where('status', 'completed');

    if ($filterType === 'monthly' && $month) {
        $ordersQuery->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
    }

    if ($filterType === 'yearly') {
        $ordersQuery->whereYear('created_at', $year);
    }

    $orders = $ordersQuery->latest()->get();

    /*
    |--------------------------------------------------------------------------
    | MAIN TOTALS (REVENUE + PROFIT)
    |--------------------------------------------------------------------------
    */

    $calcQuery = DB::table('order_products')
        ->join('orders', 'orders.id', '=', 'order_products.order_id')
        ->join('products', 'products.id', '=', 'order_products.product_id')
        ->leftJoin('product_variants', 'product_variants.id', '=', 'order_products.product_variant_id')
        ->where('orders.status', 'completed');

    if ($filterType === 'monthly' && $month) {
        $calcQuery->whereYear('orders.created_at', $year)
                  ->whereMonth('orders.created_at', $month);
    }

    if ($filterType === 'yearly') {
        $calcQuery->whereYear('orders.created_at', $year);
    }

    $totals = $calcQuery->select(

        DB::raw('SUM(order_products.line_total) as revenue'),

        DB::raw("
            SUM(
                (
                    order_products.line_total
                    - (order_products.line_total * {$share1Percentage} / 100)
                    - (order_products.line_total * {$share2Percentage} / 100)
                    - (
                        CASE 
                            WHEN order_products.product_variant_id IS NOT NULL 
                            THEN product_variants.price
                            ELSE products.price
                        END
                        * order_products.qty
                    )
                )
            ) as profit
        ")

    )->first();

    $revenue = $totals->revenue ?? 0;
    $profit  = $totals->profit ?? 0;

    $share1 = $revenue * ($share1Percentage / 100);
    $share2 = $revenue * ($share2Percentage / 100);

    /*
    |--------------------------------------------------------------------------
    | ORDER PRODUCTS BREAKDOWN
    |--------------------------------------------------------------------------
    */

    $productRowsQuery = DB::table('order_products')
        ->join('orders', 'orders.id', '=', 'order_products.order_id')
        ->join('products', 'products.id', '=', 'order_products.product_id')
        ->leftJoin('product_variants', 'product_variants.id', '=', 'order_products.product_variant_id')
        ->where('orders.status', 'completed')
        ->select(
            'orders.id as order_id',
            'products.slug',
            'product_variants.name as variant',
            'order_products.qty',
            'order_products.unit_price',
            'order_products.line_total',

            DB::raw('
                CASE 
                    WHEN order_products.product_variant_id IS NOT NULL 
                    THEN product_variants.price
                    ELSE products.price
                END as cost_price
            ')
        );

    if ($filterType === 'monthly' && $month) {
        $productRowsQuery->whereYear('orders.created_at', $year)
                         ->whereMonth('orders.created_at', $month);
    }

    if ($filterType === 'yearly') {
        $productRowsQuery->whereYear('orders.created_at', $year);
    }

    $productRows = $productRowsQuery->get()->map(function ($row) use ($share1Percentage, $share2Percentage) {

        $row->share1 = $row->line_total * ($share1Percentage / 100);
        $row->share2 = $row->line_total * ($share2Percentage / 100);

        $row->profit =
            $row->line_total
            - $row->share1
            - $row->share2
            - ($row->cost_price * $row->qty);

        return $row;
    });

    return view('admin::revenue-share.index', compact(
        'revenue',
        'share1',
        'share2',
        'profit',
        'orders',
        'productRows',
        'filterType',
        'month',
        'year'
    ));
}
    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view('admin::create');
    // }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request): RedirectResponse
    // {
    //     //
    // }

    /**
     * Show the specified resource.
     */
    // public function show($id)
    // {
    //     return view('admin::show');
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($id)
    // {
    //     return view('admin::edit');
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id): RedirectResponse
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     //
    // }
}
