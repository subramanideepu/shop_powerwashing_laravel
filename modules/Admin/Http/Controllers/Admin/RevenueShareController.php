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
    public function index()
{
    $revenue = OrderProduct::sum(
        DB::raw('unit_price * qty')
    );

    $share1 = $revenue * (setting('share_1') / 100);
    $share2 = $revenue * (setting('share_2') / 100);

    $profit = $revenue - $share1 - $share2;

    return view('admin::revenue-share.index', compact(
        'revenue',
        'share1',
        'share2',
        'profit'
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
