@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Quotes</h1>
    <table class="table table-bordered">
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
            @foreach($quotes as $quote)
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
    <a href="{{ route('quote.create') }}" class="btn btn-primary">Create New Quote</a>
</div>
@endsection
