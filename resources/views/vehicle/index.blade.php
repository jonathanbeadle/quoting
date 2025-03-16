@extends('layouts.app')

@section('title', 'All Vehicles')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>All Vehicles ({{ $vehicles->total() }})</h1>
        <div class="d-flex align-items-center">
            {{ $vehicles->links() }}
            <form action="{{ route('vehicle.index') }}" method="GET" class="d-flex ms-3">
                <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary">Search</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 5%">ID</th>
                    <th style="width: 15%">Make & Model</th>
                    <th style="width: 20%">Specification</th>
                    <th style="width: 10%">Transmission</th>
                    <th style="width: 10%">Fuel Type</th>
                    <th style="width: 15%">Registration Status</th>
                    <th style="width: 10%">Colour</th>
                    <th style="width: 15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->id }}</td>
                    <td class="text-nowrap">{{ $vehicle->make }} {{ $vehicle->model }}</td>
                    <td class="text-nowrap">{{ $vehicle->specification }}</td>
                    <td class="text-nowrap">{{ $vehicle->transmission }}</td>
                    <td class="text-nowrap">{{ $vehicle->fuel_type }}</td>
                    <td class="text-nowrap">{{ $vehicle->registration_status }}</td>
                    <td class="text-nowrap">{{ $vehicle->colour }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('vehicle.show', ['id' => $vehicle->id]) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('vehicle.show', ['id' => $vehicle->id, 'edit' => 'true']) }}" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        <a href="{{ route('vehicle.create') }}" class="btn btn-primary">Create New Vehicle</a>
    </div>
</div>
@endsection
