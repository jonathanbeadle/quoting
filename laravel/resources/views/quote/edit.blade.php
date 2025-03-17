@extends('layouts.app')

@section('title', 'Edit Quote #' . str_pad($quote->id, 3, '0', STR_PAD_LEFT))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Edit Quote</h1>
        <div class="d-flex gap-2 align-items-center">
            @if($quote->sent)
                <span class="badge bg-success">Sent to {{ $quote->customer->email }}</span>
            @else
                <span class="badge bg-secondary">Not Sent</span>
            @endif
            
            <button type="submit" form="quoteForm" class="btn btn-sm btn-primary">Save Changes</button>
            <a href="{{ route('quote.review', ['id' => $quote->id]) }}" class="btn btn-sm btn-secondary">Cancel</a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="quoteForm" action="{{ route('quote.update', ['id' => $quote->id]) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Quote Details</h5>
                    </div>
                    <div class="card-body p-1">
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Quote ID</label>
                            <div class="col-8">
                                <div class="form-control form-control-sm bg-light">{{ str_pad($quote->id, 3, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Finance Type</label>
                            <div class="col-8">
                                <select name="finance_type" class="form-select form-select-sm" required>
                                    @foreach(['Hire Purchase', 'Finance Lease', 'Operating Lease', 'Business Contract Hire'] as $type)
                                        <option value="{{ $type }}" {{ $quote->finance_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Contract Length</label>
                            <div class="col-8">
                                <div class="input-group input-group-sm">
                                    <select name="contract_length" class="form-select form-select-sm" required>
                                        @foreach([24, 36, 48, 60] as $months)
                                            <option value="{{ $months }}" {{ $quote->contract_length == $months ? 'selected' : '' }}>{{ $months }}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-text">months</span>
                                </div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Annual Mileage</label>
                            <div class="col-8">
                                <input type="number" name="annual_mileage" class="form-control form-control-sm" value="{{ $quote->annual_mileage }}" required>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Payment Profile</label>
                            <div class="col-8">
                                <input type="text" name="payment_profile" class="form-control form-control-sm" value="{{ $quote->payment_profile }}" required>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Deposit</label>
                            <div class="col-8">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">£</span>
                                    <input type="number" step="0.01" name="deposit" class="form-control form-control-sm" value="{{ $quote->deposit }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Monthly Payment</label>
                            <div class="col-8">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">£</span>
                                    <input type="number" step="0.01" name="monthly_payment" class="form-control form-control-sm" value="{{ $quote->monthly_payment }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Document Fee</label>
                            <div class="col-8">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">£</span>
                                    <input type="number" step="0.01" name="document_fee" class="form-control form-control-sm" value="{{ $quote->document_fee }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Maintenance</label>
                            <div class="col-8">
                                <div class="form-check form-switch mt-1">
                                    <input class="form-check-input" type="checkbox" name="maintenance" value="1" {{ $quote->maintenance ? 'checked' : '' }}>
                                    <label class="form-check-label small">Include Maintenance</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Customer Details</h5>
                    </div>
                    <div class="card-body p-1">
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Name</label>
                            <div class="col-8">
                                <div class="form-control form-control-sm bg-light">{{ $quote->customer->name }}</div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Business Name</label>
                            <div class="col-8">
                                <div class="form-control form-control-sm bg-light">{{ $quote->customer->business_name }}</div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Email</label>
                            <div class="col-8">
                                <div class="form-control form-control-sm bg-light">
                                    <a href="mailto:{{ $quote->customer->email }}">{{ $quote->customer->email }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Phone</label>
                            <div class="col-8">
                                <div class="form-control form-control-sm bg-light">
                                    <a href="tel:{{ $quote->customer->phone }}">{{ $quote->customer->phone }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Vehicle Details</h5>
                    </div>
                    <div class="card-body p-1">
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Make</label>
                            <div class="col-8">
                                <div class="form-control form-control-sm bg-light">{{ $quote->vehicle->make }}</div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Model</label>
                            <div class="col-8">
                                <div class="form-control form-control-sm bg-light">{{ $quote->vehicle->model }}</div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Specification</label>
                            <div class="col-8">
                                <div class="form-control form-control-sm bg-light" style="height: auto; min-height: calc(1.5em + 0.5rem + 2px);">{{ $quote->vehicle->specification }}</div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Transmission</label>
                            <div class="col-8">
                                <div class="form-control form-control-sm bg-light">{{ $quote->vehicle->transmission }}</div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Fuel Type</label>
                            <div class="col-8">
                                <div class="form-control form-control-sm bg-light">{{ $quote->vehicle->fuel_type }}</div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Additional Options</label>
                            <div class="col-8">
                                <div class="form-control form-control-sm bg-light" style="height: auto; min-height: calc(1.5em + 0.5rem + 2px);">{{ $quote->vehicle->additional_options }}</div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Dealer Fit Options</label>
                            <div class="col-8">
                                <div class="form-control form-control-sm bg-light" style="height: auto; min-height: calc(1.5em + 0.5rem + 2px);">{{ $quote->vehicle->dealer_fit_options }}</div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <label class="col-4 col-form-label col-form-label-sm fw-bold">Colour</label>
                            <div class="col-8">
                                <div class="form-control form-control-sm bg-light">{{ $quote->vehicle->colour }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection