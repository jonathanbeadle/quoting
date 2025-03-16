@extends('layouts.app')

@section('title', 'Quote Expired')

@section('content')
<div class="container my-4" style="max-width:1000px;">
    <!-- Header Section -->
    <div class="row mb-4 align-items-center">
        <!-- Left Column: Logo -->
        <div class="col-md-4 text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Fleethub Logo" class="img-fluid" style="width:100%;">
        </div>
        <!-- Right Column: Message -->
        <div class="col-md-8">
            <h1 class="mb-4">Quote Expired</h1>
            <p class="lead">Your quote for <strong>{{ $quote->vehicle->make }} {{ $quote->vehicle->model }}</strong> has expired.</p>
            <p>If you'd like a new quote, please contact us to request a new one.</p>
            <p class="mb-0 text-uppercase mt-4" style="font-size: 0.85rem; letter-spacing: 0.5px;"><b>Fleethub Limited | info@fleethub.co.uk | 07983 415924</b></p>
        </div>
    </div>
</div>
@endsection
