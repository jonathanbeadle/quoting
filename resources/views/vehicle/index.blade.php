@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Vehicles</h1>
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
</div>
@endsection
