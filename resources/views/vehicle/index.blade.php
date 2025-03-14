@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>All Vehicles</h1>
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
    
    <table class="table table-bordered">
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
            @foreach($vehicles as $vehicle)
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
                    <a href="{{ route('vehicle.edit', ['id' => $vehicle->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Pagination Links -->
    <div class="d-flex justify-content-center mt-3">
        {{ $vehicles->links() }}
    </div>
</div>
@endsection
