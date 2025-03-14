@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>All Quotes</h1>
        <div class="d-flex align-items-center">
            {{ $quotes->links() }}
            <form action="{{ route('quote.index') }}" method="GET" class="d-flex ms-3">
                <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary">Search</button>
            </form>
        </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Quote ID</th>
                <th>Customer</th>
                <th>Vehicle</th>
                <th>Contract Length</th>
                <th>Monthly Payment</th>
                <th>Created At</th>
                <th>Actions</th>
                <th>Status Update</th>
                <th>Status</th>
                <th>Create Order</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotes as $quote)
            <tr>
                <td>{{ $quote->id }}</td>
                <td>{{ $quote->customer->name ?? 'N/A' }}</td>
                <td>{{ $quote->vehicle->make ?? '' }} {{ $quote->vehicle->model ?? '' }}</td>
                <td>{{ $quote->contract_length }} months</td>
                <td>{{ $quote->monthly_payment }}</td>
                <td>{{ $quote->created_at->format('d/m/Y') }}</td>
                <td>
                    <!-- View using token -->
                    <a href="{{ route('quote.view', ['token' => $quote->token]) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('quote.review', ['id' => $quote->id]) }}" class="btn btn-sm btn-warning">Review</a>
                </td>
                <td>
                    <form method="POST" action="{{ route('quote.updateStatus', $quote->id) }}">
                        @csrf
                        <select name="status" class="form-select form-select-sm" style="width:auto; display:inline-block;">
                          @if($quote->status === 'active')
                              <option value="active" selected>Active</option>
                              <option value="expired">Expire</option>
                          @elseif($quote->status === 'expired')
                              <option value="expired" selected>Expired</option>
                              <option value="active">Activate</option>
                          @elseif($quote->status === 'confirmed')
                              <option value="confirmed" selected>Confirmed</option>
                              <option value="active">Activate</option>
                          @else
                              <option value="{{ $quote->status }}" selected>{{ ucfirst($quote->status) }}</option>
                          @endif
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </form>
                </td>
                <td>{{ $quote->status }}</td>
                <td>
                    @if($quote->status === 'confirmed')
                        <a href="{{ route('order.createFromQuote', ['quoteId' => $quote->id]) }}" class="btn btn-sm btn-secondary">Create Order</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Pagination Links -->
    <div class="d-flex justify-content-center mt-3">
        {{ $quotes->links() }}
    </div>
    
    <div class="mt-3">
        <a href="{{ route('quote.create') }}" class="btn btn-primary">Create New Quote</a>
    </div>
</div>
@endsection
