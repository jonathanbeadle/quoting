@extends('layouts.app')

@section('title', 'Order Expired')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-danger mb-4">Order Expired</h3>
                    <p class="mb-4">We're sorry, but this order has expired. Orders are valid for 28 days from creation.</p>
                    <p>The order was for a {{ $order->vehicle->make }} {{ $order->vehicle->model }}.</p>
                    <hr>
                    <p class="mb-0">Please contact us to discuss creating a new order:</p>
                    <p class="mb-4">
                        <strong>Phone:</strong> 07983 415924<br>
                        <strong>Email:</strong> <a href="mailto:info@fleethub.co.uk">info@fleethub.co.uk</a>
                    </p>
                    <div class="text-muted small">
                        <p class="mb-1">Order Reference: {{ $order->id }}</p>
                        <p class="mb-0">Expired on: {{ $order->expires_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection