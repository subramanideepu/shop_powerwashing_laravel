@extends('admin::layout')

@section('title', 'Revenue Share Dashboard')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h2 class="page-title">Revenue Dashboard</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <form method="GET">
            <label><strong>Filter Revenue</strong></label>

            <select name="filter" class="form-control" onchange="this.form.submit()">
                <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Time</option>
                <option value="monthly" {{ $filter === 'monthly' ? 'selected' : '' }}>This Month</option>
                <option value="yearly" {{ $filter === 'yearly' ? 'selected' : '' }}>This Year</option>
            </select>
        </form>
    </div>
</div>


<div class="row" style="margin-top:15px;">

    <div class="col-md-3">
        <div class="dashboard-card blue">
            <div class="title">Sales Value</div>
            <div class="amount">{{ number_format($revenue, 2) }}</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="dashboard-card purple">
            <div class="title">Share 1</div>
            <div class="amount">{{ number_format($share1, 2) }}</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="dashboard-card orange">
            <div class="title">Share 2</div>
            <div class="amount">{{ number_format($share2, 2) }}</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="dashboard-card green">
            <div class="title">Profit</div>
            <div class="amount">{{ number_format($profit, 2) }}</div>
        </div>
    </div>

</div>

{{-- ORDERS TABLE --}}
<div class="row">
    <div class="col-md-12">
        <div class="card-table">
            <div class="table-header">Recent Orders</div>

            <table class="table modern-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->customer_first_name }}</td>
                            <td><span class="status-badge">{{ ucfirst($order->status) }}</span></td>
                            <td>{{ number_format($order->total, 2) }}</td>
                            <td>{{ $order->created_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card-table">
            <div class="table-header">Product Revenue Breakdown</div>

            <table class="table modern-table" id="product-share-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Revenue</th>
                        <th>Share 1</th>
                        <th>Share 2</th>
                        <th>Profit</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($productStats as $product)
                        <tr>
                            <td>{{ $product->slug }}</td>
                            <td>{{ number_format($product->revenue, 2) }}</td>
                            <td>{{ number_format($product->share1, 2) }}</td>
                            <td>{{ number_format($product->share2, 2) }}</td>
                            <td>{{ number_format($product->profit, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

@endsection


@push('styles')
<style>

.dashboard-card {
    border-radius: 10px;
    padding: 20px;
    color: white;
    margin-bottom: 20px;
}

.dashboard-card .amount {
    font-size: 22px;
    font-weight: bold;
}

.blue { background: #2979ff; }
.purple { background: #7b1fa2; }
.orange { background: #ff9800; }
.green { background: #2e7d32; }

.card-table {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
}

.status-badge {
    background: #eef2ff;
    padding: 4px 10px;
    border-radius: 20px;
}

.text-center {
    text-align: center;
}

</style>
@endpush


@push('scripts')
<script>
$(document).ready(function() {
    $('#product-share-table').DataTable();
});
</script>
@endpush