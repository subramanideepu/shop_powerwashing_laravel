@extends('admin::layout')

@section('title', 'Revenue Share Dashboard')

@section('content')

<h2 class="mb-4">Revenue Dashboard</h2>

<form method="GET" class="row mb-4">

    <div class="col-md-3">
        <label>Filter Type</label>
        <select name="filter_type" id="filterType" class="form-control">
            <option value="all" {{ $filterType == 'all' ? 'selected' : '' }}>All Time</option>
            <option value="monthly" {{ $filterType == 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="yearly" {{ $filterType == 'yearly' ? 'selected' : '' }}>Yearly</option>
        </select>
    </div>

    <div class="col-md-3 filter-field" id="monthField">
        <label>Month</label>
        <select name="month" class="form-control">
            <option value="">Select Month</option>
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                    {{ date('F', mktime(0,0,0,$m,1)) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 filter-field" id="yearField">
        <label>Year</label>
        <select name="year" class="form-control">
            @foreach(range(now()->year - 5, now()->year) as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label>&nbsp;</label>
        <button class="btn btn-primary btn-block">Apply Filter</button>
    </div>

</form>

<div class="row mb-4 revenue-cards" style="margin-top: 10px;">

    {{-- SALES --}}
    <div class="col-md-3">
        <div class="stat-card sales">
            <div class="stat-info">
                <div class="stat-label">Sales Value</div>
                <div class="stat-number">${{ number_format($revenue, 2) }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>

    {{-- SHARE 1 --}}
    <div class="col-md-3">
        <div class="stat-card share1">
            <div class="stat-info">
                <div class="stat-label">Share 1</div>
                <div class="stat-number">${{ number_format($share1, 2) }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    {{-- SHARE 2 --}}
    <div class="col-md-3">
        <div class="stat-card share2">
            <div class="stat-info">
                <div class="stat-label">Share 2</div>
                <div class="stat-number">${{ number_format($share2, 2) }}</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-user-friends"></i>
            </div>
        </div>
    </div>

    {{-- PROFIT --}}
    <div class="col-md-3">
        <div class="stat-card {{ $profit < 0 ? 'profit-loss' : 'profit' }}">

            <div class="stat-info">
                <div class="stat-label">Profit/Loss</div>

                <div class="stat-number">
                    ${{ number_format($profit, 2) }}

                    {{-- 🔥 ARROW INDICATOR --}}
                    <span class="profit-indicator">




                        @if($profit > 0)
                            <i class="fas fa-arrow-up"></i>
                        @elseif($profit < 0)
                            <i class="fas fa-arrow-down"></i>
                        @else
                            <i class="fas fa-minus"></i>
                        @endif
                    </span>
                </div>
            </div>

            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

</div>

<div class="card-table">
    <h4>Orders</h4>
    <table class="table">
        <thead>
        <tr>
            <th>Order</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Total</th>
            <th>Discount</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td><strong>#{{ $order->id }}</strong></td>
                <td>{{ $order->customer_first_name }} </td>
                <td><span class="status-badge">{{ ucfirst($order->status) }}</span></td>
                <td>{{ ucfirst($order->payment_method) }}</td>
                <td><strong>{{ number_format($order->total, 2) }}</strong></td>
                <td>{{ $order->discount > 0 ? number_format($order->discount, 2) : '—' }}</td>
                <td>{{ $order->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="card-table">
    <h4>Order Products Breakdown</h4>
    <table class="table">
        <thead>
        <tr>
            <th>Order</th>
            <th>Product</th>
            <th>Variant</th>
            <th>Qty</th>
            <th>Base_Price</th>
            <th>Selling_Price</th>
            <th>Share 1</th>
            <th>Share 2</th>
            <th>Profit/Loss</th>
        </tr>
        </thead>
        <tbody>
        @foreach($productRows as $row)
            <tr>
                <td>#{{ $row->order_id }}</td>
                <td>{{ $row->slug }}</td>
                <td>{{ $row->variant ?? '—' }}</td>
                <td>{{ $row->qty }}</td>
                <td>{{ number_format($row->cost_price ?? 0, 2) }}</td>
                <td>{{ number_format($row->line_total, 2) }}</td>
                <td>{{ number_format($row->share1, 2) }}</td>
                <td>{{ number_format($row->share2, 2) }}</td>
                <td>
                    <strong style="color: {{ $row->profit < 0 ? 'red' : 'green' }}">
                        {{ number_format($row->profit, 2) }}
                    </strong>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@endsection

@push('styles')
<style>
.dashboard-card { border-radius: 10px; padding: 20px; color: white; }
.blue { background: #2979ff; }
.purple { background: #7b1fa2; }
.orange { background: #ff9800; }
.green { background: #2e7d32; }

.card-table { background: white; padding: 20px; border-radius: 10px; margin-top: 20px; }
.red-card { 
    background: #d32f2f;   /* clean professional red */
}
.status-badge {
    background: #eef2ff;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.filter-field { transition: all 0.25s ease; }

.stat-card {
    border-radius: 12px;
    padding: 18px 20px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-height: 90px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    transition: all 0.25s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 30px rgba(0,0,0,0.12);
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 13px;
    opacity: 0.9;
    margin-bottom: 6px;
}

.stat-number {
    font-size: 22px;
    font-weight: 700;
    display: flex;
    align-items: center;
}

.stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

/* PROFIT INDICATOR */

.profit-indicator {
    margin-left: 10px;
    font-size: 14px;
    opacity: 0.9;
}

/* COLORS */

.sales {
    background: linear-gradient(135deg, #2979ff, #1565c0);
}

.share1 {
    background: linear-gradient(135deg, #7b1fa2, #4a148c);
}

.share2 {
    background: linear-gradient(135deg, #ff9800, #ef6c00);
}

.profit {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
}

.profit-loss {
    background: linear-gradient(135deg, #d32f2f, #b71c1c);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const filterType = document.getElementById("filterType");
    const monthField = document.getElementById("monthField");
    const yearField  = document.getElementById("yearField");

    function handleFilters() {

        if (filterType.value === "all") {
            monthField.style.display = "none";
            yearField.style.display  = "none";
        } 
        else if (filterType.value === "monthly") {
            monthField.style.display = "block";
            yearField.style.display  = "block";
        } 
        else if (filterType.value === "yearly") {
            monthField.style.display = "none";
            yearField.style.display  = "block";
        }
    }

    handleFilters();
    filterType.addEventListener("change", handleFilters);

});
</script>

@endpush