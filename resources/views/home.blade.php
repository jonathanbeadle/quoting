@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard</h1>
    
    <!-- Summary Cards - First Row -->
    <div class="row mb-4">
        <!-- Total Quotes -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Quotes</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalQuotes }}</h5>
                    <a href="{{ route('quote.index') }}" class="btn btn-light btn-sm">View All Quotes</a>
                </div>
            </div>
        </div>
        <!-- Total Orders -->
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Total Orders</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalOrders }}</h5>
                    <a href="{{ route('order.index') }}" class="btn btn-light btn-sm">View All Orders</a>
                </div>
            </div>
        </div>
        <!-- Confirmed Orders -->
        <div class="col-md-4">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-header">Confirmed Orders</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $confirmedOrders }}</h5>
                    <a href="{{ route('order.index') }}" class="btn btn-light btn-sm">View Confirmed</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards - Second Row -->
    <div class="row mb-4">
        <!-- Quotes Sent -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Quotes Sent</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $sentQuotes }}</h5>
                    <a href="{{ route('quote.index') }}" class="btn btn-light btn-sm">View Sent Quotes</a>
                </div>
            </div>
        </div>
        <!-- Total Customers -->
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Total Customers</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalCustomers }}</h5>
                    <a href="{{ route('customer.index') }}" class="btn btn-light btn-sm">View Customers</a>
                </div>
            </div>
        </div>
        <!-- Total Vehicles -->
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Total Vehicles</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalVehicles }}</h5>
                    <a href="{{ route('vehicle.index') }}" class="btn btn-light btn-sm">View Vehicles</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Quotes Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0 h5">Recent Quotes</h2>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Contract Length</th>
                                <th>Monthly Payment</th>
                                <th>Created At</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentQuotes as $quote)
                            <tr>
                                <td>{{ $quote->id }}</td>
                                <td>{{ $quote->customer->name }}</td>
                                <td>{{ $quote->vehicle->make }} {{ $quote->vehicle->model }}</td>
                                <td>{{ $quote->contract_length }} months</td>
                                <td>{{ $quote->monthly_payment }}</td>
                                <td>{{ $quote->created_at->format('Y-m-d') }}</td>
                                <td class="text-right">
                                <a href="{{ route('quote.view', ['token' => $quote->token]) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('quote.review', ['id' => $quote->id]) }}" class="btn btn-sm btn-warning">Review</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('quote.index') }}" class="btn btn-primary">View All Quotes</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h2 class="mb-0 h5">Recent Orders</h2>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Finance Type</th>
                                <th>Monthly Payment</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>000{{ $order->id }}</td>
                                <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                <td>{{ $order->vehicle->make ?? '' }} {{ $order->vehicle->model ?? '' }}</td>
                                <td>{{ $order->finance_type }}</td>
                                <td>£{{ number_format($order->monthly_payment, 2) }}</td>
                                <td>{{ $order->status }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td class="text-right">
                                    <a href="{{ route('order.view', $order->id) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('order.index') }}" class="btn btn-info">View All Orders</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Customers Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h2 class="mb-0 h5">Recent Customers</h2>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Business Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentCustomers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->business_name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td class="text-right">
                                    <a href="{{ route('customer.show', ['id' => $customer->id]) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('customer.index') }}" class="btn btn-warning">View All Customers</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Vehicles Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h2 class="mb-0 h5">Recent Vehicles</h2>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Make &amp; Model</th>
                                <th>Specification</th>
                                <th>Transmission</th>
                                <th>Fuel Type</th>
                                <th>Registration Status</th>
                                <th>Colour</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentVehicles as $vehicle)
                            <tr>
                                <td>{{ $vehicle->id }}</td>
                                <td>{{ $vehicle->make }} {{ $vehicle->model }}</td>
                                <td>{{ $vehicle->specification }}</td>
                                <td>{{ $vehicle->transmission }}</td>
                                <td>{{ $vehicle->fuel_type }}</td>
                                <td>{{ $vehicle->registration_status }}</td>
                                <td>{{ $vehicle->colour }}</td>
                                <td class="text-right">
                                    <a href="{{ route('vehicle.show', ['id' => $vehicle->id]) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('vehicle.index') }}" class="btn btn-danger">View All Vehicles</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
