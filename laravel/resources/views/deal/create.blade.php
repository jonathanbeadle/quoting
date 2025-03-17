@extends('layouts.app')

@section('title', 'Create New Deal')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Create New Deal</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('deal.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Select Customer*</label>
                            <div class="input-group">
                                <select name="customer_id" id="customerSelect" class="form-select" required>
                                    <option value="">Select a customer...</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">
                                            {{ $customer->name }} ({{ $customer->business_name }})
                                        </option>
                                    @endforeach
                                </select>
                                <a href="{{ route('customer.create') }}" class="btn btn-outline-secondary">New Customer</a>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Deal Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Optional title for this deal">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Optional notes about this deal">{{ old('notes') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('deal.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Deal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection