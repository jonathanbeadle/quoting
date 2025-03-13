@extends('layouts.public')

@section('content')
<div class="container my-4" style="max-width:1000px;">
  <!-- Header Section with 33.33% / 66.66% split -->
  <div class="row mb-4 align-items-center">
    <!-- Left Column: Logo -->
    <div class="col-md-4 text-center">
      <img src="{{ asset('images/logo.png') }}" alt="Fleethub Logo" class="img-fluid" style="width:100%;">
    </div>
    <!-- Right Column: Order Info -->
    <div class="col-md-8 text-end">
      <h4 class="text-uppercase mb-1" style="letter-spacing: 1px;">Order ID: 000{{ $order->id }}</h4>
      <p class="mb-1" style="font-size: 0.9rem;">Created: {{ $order->created_at->format('d/m/Y') }}</p>
      <p class="mb-0 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;"><b>Fleethub Limited | info@fleethub.co.uk | 07983 415924</b></p>
    </div>
  </div>
  
  <!-- Order Details Display (Read-Only) -->
  <div class="row">
    <div class="col-md-4 left-box" style="font-size:0.85rem;">
      <!-- Customer Details Card -->
      <div class="card mb-3">
        <div class="card-header" style="background-color: rgb(54, 54, 54); color: #ffffff;">
          Customer Details
        </div>
        <div class="card-body p-2">
          <div class="d-flex justify-content-between">
            <span class="fw-bold">Name:</span>
            <span class="text-end">{{ $order->customer->name }}</span>
          </div>
          <hr class="my-1">
          <div class="d-flex justify-content-between">
            <span class="fw-bold">Business Name:</span>
            <span class="text-end">{{ $order->customer->business_name }}</span>
          </div>
          <hr class="my-1">
          <div class="d-flex justify-content-between">
            <span class="fw-bold">Email:</span>
            <span class="text-end">{{ $order->customer->email }}</span>
          </div>
        </div>
      </div>
      
      <!-- Vehicle Details Card -->
      <div class="card mb-3">
        <div class="card-header" style="background-color: #1b9e8c; color: #ffffff;">
          Vehicle Details
        </div>
        <div class="card-body p-2">
          <div class="d-flex justify-content-between">
            <span class="fw-bold">Make &amp; Model:</span>
            <span class="text-end">{{ $order->vehicle->make }} {{ $order->vehicle->model }}</span>
          </div>
          <!-- Additional vehicle details as needed... -->
        </div>
      </div>
      
      <!-- Order Details Card -->
      <div class="card mb-3">
        <div class="card-header" style="background-color: #1b9e8c; color: #ffffff;">
          Order Details
        </div>
        <div class="card-body p-2">
          <div class="d-flex justify-content-between">
            <span class="fw-bold">Finance Type:</span>
            <span class="text-end">{{ $order->finance_type }}</span>
          </div>
          <hr class="my-1">
          <div class="d-flex justify-content-between">
            <span class="fw-bold">Contract Length:</span>
            <span class="text-end">{{ $order->contract_length }} months</span>
          </div>
          <!-- Additional order details... -->
        </div>
        <div class="card-footer text-muted text-end">
          Created: {{ $order->created_at->format('d/m/Y') }}
        </div>
      </div>
    </div>
    
    <!-- Right Column: Final Order Confirmation (Read-Only) -->
    <div class="col-md-8">
      <div class="card mb-4" style="font-size: 0.9rem;">
        <div class="card-body">
          <p>
            This is your final order. Please review the details above.
          </p>
          <p>
            Once you are satisfied, click the "Confirm Order" button below to complete the process.
          </p>
        </div>
      </div>
      <div class="text-end mt-3">
        @if($order->status == 'confirmed')
          <button type="button" class="btn btn-success" disabled>Order Confirmed</button>
        @else
          <a href="{{ route('order.confirm', ['id' => $order->id]) }}" class="btn btn-success">Confirm Order</a>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
