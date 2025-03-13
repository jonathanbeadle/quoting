@extends('layouts.public')

@section('content')
<div class="container my-4" style="max-width:1000px;">
  <!-- Header Section with 33.33% / 66.66% split -->
  <div class="row mb-4 align-items-center">
    <!-- Left Column: Logo (33.33%) -->
    <div class="col-md-4 text-center">
      <img src="{{ asset('images/logo.png') }}" alt="Fleethub Logo" class="img-fluid" style="width:100%;">
    </div>
    <!-- Right Column: Order Info (66.66%) -->
    <div class="col-md-8 text-end">
      <h4 class="text-uppercase mb-1" style="letter-spacing: 1px;">Order ID: 000{{ $order->id }}</h4>
      <p class="mb-1" style="font-size: 0.9rem;">Created: {{ $order->created_at->format('d/m/Y') }}</p>
      <p class="mb-0 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;"><b>Fleethub Limited | info@fleethub.co.uk | 07983 415924</b></p>
    </div>
  </div>
  
  <!-- Main Content Section -->
  <form action="{{ route('order.update', $order->id) }}" method="POST">
    @csrf
    <div class="row">
      <!-- Left Column: Details Cards (Customer, Vehicle - read-only; Order Details - editable) -->
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
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Specification:</span>
              <span class="text-end">{{ $order->vehicle->specification }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Transmission:</span>
              <span class="text-end">{{ $order->vehicle->transmission }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Fuel Type:</span>
              <span class="text-end">{{ $order->vehicle->fuel_type }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Additions:</span>
              <span class="text-end">{{ $order->vehicle->additional_options }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Dealer Options:</span>
              <span class="text-end">{{ $order->vehicle->dealer_fit_options }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Reg Status:</span>
              <span class="text-end">{{ $order->vehicle->registration_status }}</span>
            </div>
            @if($order->vehicle->registration_status == 'Pre-Registered')
              <hr class="my-1">
              <div class="d-flex justify-content-between">
                <span class="fw-bold">Reg Date:</span>
                <span class="text-end">{{ $order->vehicle->registration_date }}</span>
              </div>
            @endif
            <hr class="my-1">
            <div class="d-flex justify-content-between mb-0">
              <span class="fw-bold">Colour:</span>
              <span class="text-end">{{ $order->vehicle->colour }}</span>
            </div>
          </div>
        </div>
        
        <!-- Order Details Card (Editable Fields) -->
        <div class="card mb-3">
          <div class="card-header" style="background-color: #1b9e8c; color: #ffffff;">
            Order Details
          </div>
          <div class="card-body p-2">
            <div class="mb-3">
              <label for="finance_type" class="fw-bold">Finance Type</label>
              <input type="text" name="finance_type" id="finance_type" class="form-control" value="{{ $order->finance_type }}" required>
            </div>
            <div class="mb-3">
              <label for="contract_length" class="fw-bold">Contract Length (months)</label>
              <input type="number" name="contract_length" id="contract_length" class="form-control" value="{{ $order->contract_length }}" required>
            </div>
            <div class="mb-3">
              <label for="annual_mileage" class="fw-bold">Annual Mileage</label>
              <input type="number" name="annual_mileage" id="annual_mileage" class="form-control" value="{{ $order->annual_mileage }}" required>
            </div>
            <div class="mb-3">
              <label for="payment_profile" class="fw-bold">Payment Profile</label>
              <input type="text" name="payment_profile" id="payment_profile" class="form-control" value="{{ $order->payment_profile }}" required>
            </div>
            <div class="mb-3">
              <label for="deposit" class="fw-bold">Deposit</label>
              <input type="number" step="0.01" name="deposit" id="deposit" class="form-control" value="{{ $order->deposit }}" required>
            </div>
            <div class="mb-3">
              <label for="monthly_payment" class="fw-bold">Monthly Payment</label>
              <input type="number" step="0.01" name="monthly_payment" id="monthly_payment" class="form-control" value="{{ $order->monthly_payment }}" required>
            </div>
            <div class="mb-3">
              <label for="maintenance" class="fw-bold">Maintenance (1 for Yes, 0 for No)</label>
              <input type="number" name="maintenance" id="maintenance" class="form-control" value="{{ $order->maintenance }}" required>
            </div>
            <div class="mb-3">
              <label for="document_fee" class="fw-bold">Document Fee</label>
              <input type="number" step="0.01" name="document_fee" id="document_fee" class="form-control" value="{{ $order->document_fee }}" required>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Right Column: Order Summary (Read-Only) -->
      <div class="col-md-8">
        <div class="card mb-4" style="font-size: 0.9rem;">
          <div class="card-body">
            <p>
              Review your order details on the left. If everything is correct, click "Update &amp; Confirm Order" below.
            </p>
          </div>
        </div>
        
        <!-- Confirm Update & Confirm Order Button -->
        <div class="text-end mt-3">
          @if($order->status == 'confirmed')
            <button type="button" class="btn btn-success" disabled>Order Confirmed</button>
          @else
            <button type="submit" class="btn btn-primary">Update &amp; Confirm Order</button>
          @endif
        </div>
      </div>
    </div>
  </div>
</form>
@endsection
