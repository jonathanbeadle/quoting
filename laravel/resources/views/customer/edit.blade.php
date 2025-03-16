@extends('layouts.app')

@section('title', 'Edit Customer: ' . $customer->name)

@section('content')
<div class="container">
    <h1>Edit Customer</h1>
    <form action="{{ route('customer.update', ['id' => $customer->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Customer Name:</label>
            <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Customer Email:</label>
            <input type="email" name="email" class="form-control" value="{{ $customer->email }}" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Customer Phone:</label>
            <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
        </div>
        <button type="submit" class="btn btn-success">Update Customer</button>
    </form>
    <br>
    <a href="{{ route('customer.show', ['id' => $customer->id]) }}">Back to Customer Details</a>
</div>
@endsection
