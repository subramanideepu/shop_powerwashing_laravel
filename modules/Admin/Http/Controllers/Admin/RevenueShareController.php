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

 public function index(Request $request)
    {
        $filter = $request->filter ?? 'all';    

        $ordersQuery = DB::table('orders')
            ->where('status', 'completed');

        if ($filter === 'monthly') {
            $ordersQuery->whereYear('created_at', now()->year)
                        ->whereMonth('created_at', now()->month);
        }

        if ($filter === 'yearly') {
            $ordersQuery->whereYear('created_at', now()->year);
        }

        $orders = $ordersQuery
            ->latest()
            ->limit(10)
            ->get();      

        $profitQuery = DB::table('order_products')
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->where('orders.status', 'completed');

        if ($filter === 'monthly') {
            $profitQuery->whereYear('orders.created_at', now()->year)
                        ->whereMonth('orders.created_at', now()->month);
        }

        if ($filter === 'yearly') {
            $profitQuery->whereYear('orders.created_at', now()->year);
        }

        $revenue = $profitQuery->sum(
            DB::raw('(order_products.unit_price - order_products.unit_price) * order_products.qty')
        );       

        $share1 = $revenue * (setting('share_1') / 100);
        $share2 = $revenue * (setting('share_2') / 100);

        $profit = $revenue - $share1 - $share2;

       

        $productStatsQuery = DB::table('order_products')
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->join('products', 'products.id', '=', 'order_products.product_id')
            ->where('orders.status', 'completed')
            ->select(
                'products.slug',
                DB::raw('SUM((order_products.unit_price - order_products.unit_price) * order_products.qty) as revenue')
            )
            ->groupBy('products.slug');

        if ($filter === 'monthly') {
            $productStatsQuery->whereYear('orders.created_at', now()->year)
                              ->whereMonth('orders.created_at', now()->month);
        }

        if ($filter === 'yearly') {
            $productStatsQuery->whereYear('orders.created_at', now()->year);
        }

        $productStats = $productStatsQuery->get()->map(function ($item) {

            $share1 = $item->revenue * (setting('share_1') / 100);
            $share2 = $item->revenue * (setting('share_2') / 100);

            $item->share1 = $share1;
            $item->share2 = $share2;
            $item->profit = $item->revenue - $share1 - $share2;

            return $item;
        });

        return view('admin::revenue-share.index', compact(
            'revenue',
            'share1',
            'share2',
            'profit',
            'filter',
            'orders',
            'productStats'
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
