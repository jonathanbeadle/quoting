@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Review Quote</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h2>Quote Details</h2>
    <p><strong>Quote ID:</strong> {{ $quote->id }}</p>
    <p><strong>Finance Type:</strong> {{ $quote->finance_type }}</p>
    <p><strong>Contract Length:</strong> {{ $quote->contract_length }} months</p>
    <p><strong>Annual Mileage:</strong> {{ $quote->annual_mileage }}</p>
    <p><strong>Payment Profile:</strong> {{ $quote->payment_profile }}</p>
    <p><strong>Deposit:</strong> {{ $quote->deposit }}</p>
    <p><strong>Monthly Payment:</strong> {{ $quote->monthly_payment }}</p>
    <p><strong>Maintenance:</strong> {{ $quote->maintenance ? 'Yes' : 'No' }}</p>
    <p><strong>Document Fee:</strong> {{ $quote->document_fee }}</p>

    <h2>Customer Details</h2>
    <p><strong>Name:</strong> {{ $quote->customer->name }}</p>
    <p><strong>Business Name:</strong> {{ $quote->customer->business_name }}</p>
    <p><strong>Email:</strong> {{ $quote->customer->email }}</p>
    <p><strong>Phone:</strong> {{ $quote->customer->phone }}</p>

    <h2>Vehicle Details</h2>
    <p><strong>Make:</strong> {{ $quote->vehicle->make }}</p>
    <p><strong>Model:</strong> {{ $quote->vehicle->model }}</p>
    <p><strong>Specification:</strong> {{ $quote->vehicle->specification }}</p>
    <p><strong>Transmission:</strong> {{ $quote->vehicle->transmission }}</p>
    <p><strong>Fuel Type:</strong> {{ $quote->vehicle->fuel_type }}</p>
    <p><strong>Registration Status:</strong> {{ $quote->vehicle->registration_status }}</p>
    @if($quote->vehicle->registration_status == 'Pre-Registered')
        <p><strong>Registration Date:</strong> {{ $quote->vehicle->registration_date }}</p>
    @endif
    <p><strong>Additional Options:</strong> {{ $quote->vehicle->additional_options }}</p>
    <p><strong>Dealer Fit Options:</strong> {{ $quote->vehicle->dealer_fit_options }}</p>
    <p><strong>Colour:</strong> {{ $quote->vehicle->colour }}</p>

    <form action="{{ route('quote.send', ['id' => $quote->id]) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">Send Quote</button>
    </form>
    <a href="{{ route('quote.index') }}" class="btn btn-secondary mt-3">Back to All Quotes</a>
</div>
@endsection
