@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Orders</h1>
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Quote ID</th>
                <th>Customer</th>
                <th>Vehicle</th>
                <th>Finance Type</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>000{{ $order->id }}</td>
                <td>{{ $order->quote_id }}</td>
                <td>{{ $order->customer->name ?? 'N/A' }}</td>
                <td>{{ $order->vehicle->make ?? '' }} {{ $order->vehicle->model ?? '' }}</td>
                <td>{{ $order->finance_type }}</td>
                <td>{{ $order->status }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>
                    <!-- View Order using numeric ID for internal admin view -->
                    <a href="{{ route('order.view', $order->id) }}" class="btn btn-sm btn-info">View</a>
                    <!-- Or, if you want to view using the token, you can also add a button: -->
                    <a href="{{ route('order.viewByToken', ['token' => $order->token]) }}" class="btn btn-sm btn-secondary">Token View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Optionally add a button to create a new order manually if needed -->
</div>
@endsection
