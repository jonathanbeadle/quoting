@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Customer Details</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <p><strong>ID:</strong> {{ $customer->id }}</p>
    <p><strong>Name:</strong> {{ $customer->name }}</p>
    <p><strong>Business Name:</strong> {{ $customer->business_name }}</p>
    <p><strong>Email:</strong> {{ $customer->email }}</p>
    <p><strong>Phone:</strong> {{ $customer->phone }}</p>

    <!-- Button to start a quote for this customer -->
    <a href="{{ route('quote.create') }}?customer_id={{ $customer->id }}" class="btn btn-primary">Quote Customer</a>
    <a href="{{ route('customer.edit', ['id' => $customer->id]) }}" class="btn btn-warning">Edit Customer</a>
    <br><br>

    <h2>Quotes for {{ $customer->name }}</h2>
    @if($customer->quotes->isEmpty())
        <p>No quotes found for this customer.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vehicle</th>
                    <th>Finance Type</th>
                    <th>Contract Length</th>
                    <th>Annual Mileage</th>
                    <th>Deposit</th>
                    <th>Monthly Payment</th>
                    <th>Maintenance</th>
                    <th>Document Fee</th>
                    <th>Sent</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customer->quotes as $quote)
                <tr>
                    <td>{{ $quote->id }}</td>
                    <td>{{ $quote->vehicle->make }} {{ $quote->vehicle->model }}</td>
                    <td>{{ $quote->finance_type }}</td>
                    <td>{{ $quote->contract_length }} months</td>
                    <td>{{ $quote->annual_mileage }}</td>
                    <td>{{ $quote->deposit }}</td>
                    <td>{{ $quote->monthly_payment }}</td>
                    <td>{{ $quote->maintenance ? 'Yes' : 'No' }}</td>
                    <td>{{ $quote->document_fee }}</td>
                    <td>{{ $quote->sent ? 'Yes' : 'No' }}</td>
                    <td>{{ $quote->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('quote.view', ['id' => $quote->id]) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('quote.review', ['id' => $quote->id]) }}" class="btn btn-sm btn-warning">Review</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <a href="{{ route('customer.index') }}" class="btn btn-secondary">Back to Customers List</a>
</div>
@endsection
