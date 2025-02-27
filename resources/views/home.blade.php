@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard</h1>
    
    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Total Quotes -->
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Quotes</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalQuotes }}</h5>
                    <a href="{{ route('quote.index') }}" class="btn btn-light btn-sm">View All Quotes</a>
                </div>
            </div>
        </div>
        <!-- Quotes Sent -->
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Quotes Sent</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $sentQuotes }}</h5>
                    <a href="{{ route('quote.index') }}" class="btn btn-light btn-sm">View Sent Quotes</a>
                </div>
            </div>
        </div>
        <!-- Total Customers -->
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Total Customers</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalCustomers }}</h5>
                    <a href="{{ route('customer.index') }}" class="btn btn-light btn-sm">View Customers</a>
                </div>
            </div>
        </div>
        <!-- Total Vehicles -->
        <div class="col-md-3">
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
            <h2>Recent Quotes</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Contract Length</th>
                        <th>Monthly Payment</th>
                        <th>Created At</th>
                        <th>Actions</th>
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
                        <td>
                            <a href="{{ route('quote.view', ['id' => $quote->id]) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('quote.review', ['id' => $quote->id]) }}" class="btn btn-sm btn-warning">Review</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('quote.index') }}" class="btn btn-primary">View All Quotes</a>
        </div>
    </div>

    <!-- Recent Customers Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Recent Customers</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Business Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
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
                        <td>
                            <a href="{{ route('customer.show', ['id' => $customer->id]) }}" class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('customer.index') }}" class="btn btn-secondary">View All Customers</a>
        </div>
    </div>

    <!-- Recent Vehicles Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Recent Vehicles</h2>
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
                        <th>Actions</th>
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
                        <td>
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
@endsection
