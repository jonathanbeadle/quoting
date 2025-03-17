@extends('layouts.app')

@section('title', 'Edit Deal')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">Edit Deal #{{ $deal->id }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('deal.update', ['id' => $deal->id]) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer*</label>
                            <div class="input-group">
                                <select name="customer_id" id="customerSelect" class="form-select" required>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $deal->customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->business_name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Deal Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $deal->title) }}" placeholder="Optional title for this deal">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Optional notes about this deal">{{ old('notes', $deal->notes) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status*</label>
                            <select name="status" id="status" class="form-select" required>
                                @foreach(\App\Models\Deal::getStatuses() as $status)
                                    <option value="{{ $status }}" {{ $deal->status === $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('deal.show', ['id' => $deal->id]) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Deal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection