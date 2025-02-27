@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Vehicle</h1>
    <form action="{{ route('vehicle.update', ['id' => $vehicle->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Vehicle Name:</label>
            <input type="text" name="name" class="form-control" value="{{ $vehicle->name }}" required>
        </div>
        <div class="mb-3">
            <label for="specs" class="form-label">Vehicle Specifications:</label>
            <textarea name="specs" class="form-control" required>{{ $vehicle->specs }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Update Vehicle</button>
    </form>
    <br>
    <a href="{{ route('vehicle.show', ['id' => $vehicle->id]) }}">Back to Vehicle Details</a>
</div>
@endsection
