@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>All Orders ({{ $orders->total() }})</h1>
        <div class="d-flex align-items-center">
            {{ $orders->links() }}
            <form action="{{ route('order.index') }}" method="GET" class="d-flex ms-3">
                <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary">Search</button>
            </form>
        </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 8%">Order ID</th>
                    <th style="width: 8%">Quote ID</th>
                    <th style="width: 15%">Customer</th>
                    <th style="width: 20%">Vehicle</th>
                    <th style="width: 15%">Finance Type</th>
                    <th style="width: 10%">Status</th>
                    <th style="width: 10%">Created At</th>
                    <th style="width: 14%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td class="text-nowrap">000{{ $order->id }}</td>
                    <td class="text-nowrap">{{ $order->quote_id }}</td>
                    <td class="text-nowrap">{{ $order->customer->name ?? 'N/A' }}</td>
                    <td class="text-nowrap">{{ $order->vehicle->make ?? '' }} {{ $order->vehicle->model ?? '' }}</td>
                    <td class="text-nowrap">{{ $order->finance_type }}</td>
                    <td class="text-nowrap">{{ $order->status }}</td>
                    <td class="text-nowrap">{{ $order->created_at->format('d/m/Y') }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('order.view', $order->id) }}" class="btn btn-sm btn-info" target="_blank">View</a>
                        <a href="{{ route('order.viewByToken', ['token' => $order->token]) }}" class="btn btn-sm btn-secondary" target="_blank">Token View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
