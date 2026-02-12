@extends('admin::layout')

@section('title', trans('admin::dashboard.dashboard'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Revenue Share Dashboard</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="box">
                <h4>Share 1</h4>
                <h3>{{ number_format($share1, 2) }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="box">
                <h4>Share 2</h4>
                <h3>{{ number_format($share2, 2) }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="box">
                <h4>Profit</h4>
                <h3>{{ number_format($profit, 2) }}</h3>
            </div>
        </div>
    </div>
@endsection