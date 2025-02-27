@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Vehicle Details</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <p><strong>ID:</strong> {{ $vehicle->id }}</p>
    <p><strong>Make:</strong> {{ $vehicle->make }}</p>
    <p><strong>Model:</strong> {{ $vehicle->model }}</p>
    <p><strong>Specification:</strong> {{ $vehicle->specification }}</p>
    <p><strong>Transmission:</strong> {{ $vehicle->transmission }}</p>
    <p><strong>Fuel Type:</strong> {{ $vehicle->fuel_type }}</p>
    <p><strong>Registration Status:</strong> {{ $vehicle->registration_status }}</p>
    @if($vehicle->registration_status == 'Pre-Registered')
        <p><strong>Registration Date:</strong> {{ $vehicle->registration_date }}</p>
    @endif
    <p><strong>Additional Options:</strong> {{ $vehicle->additional_options }}</p>
    <p><strong>Dealer Fit Options:</strong> {{ $vehicle->dealer_fit_options }}</p>
    <p><strong>Colour:</strong> {{ $vehicle->colour }}</p>

    <!-- Button to start a quote for this vehicle -->
    <a href="{{ route('quote.create') }}?vehicle_id={{ $vehicle->id }}" class="btn btn-primary">Quote Vehicle</a>
    <a href="{{ route('vehicle.edit', ['id' => $vehicle->id]) }}" class="btn btn-warning">Edit Vehicle</a>
    <br><br>
    <a href="{{ route('vehicle.index') }}" class="btn btn-secondary">Back to Vehicles List</a>
</div>
@endsection
