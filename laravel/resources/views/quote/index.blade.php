@extends('layouts.app')

@section('title', 'All Quotes')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>All Quotes ({{ $quotes->total() }})</h1>
        <div class="d-flex align-items-center">
            {{ $quotes->links() }}
            <form action="{{ route('quote.index') }}" method="GET" class="d-flex ms-3">
                <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary">Search</button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 5%">ID</th>
                    <th style="width: 12%">Customer</th>
                    <th style="width: 15%">Vehicle</th>
                    <th style="width: 8%">Contract</th>
                    <th style="width: 8%">Monthly</th>
                    <th style="width: 8%">Created</th>
                    <th style="width: 15%">Actions</th>
                    <th style="width: 12%">Status Update</th>
                    <th style="width: 7%">Status</th>
                    <th style="width: 10%">Order</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotes as $quote)
                <tr>
                    <td>{{ $quote->id }}</td>
                    <td class="text-nowrap">{{ $quote->customer->name ?? 'N/A' }}</td>
                    <td class="text-nowrap">{{ $quote->vehicle->make ?? '' }} {{ $quote->vehicle->model ?? '' }}</td>
                    <td class="text-nowrap">{{ $quote->contract_length }}m</td>
                    <td class="text-nowrap">£{{ number_format($quote->monthly_payment, 2) }}</td>
                    <td class="text-nowrap">{{ $quote->created_at->format('d/m/Y') }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('quote.view', ['token' => $quote->token]) }}" class="btn btn-sm btn-info" target="_blank">View</a>
                        <a href="{{ route('quote.review', ['id' => $quote->id]) }}" class="btn btn-sm btn-warning">Review</a>
                        <form action="{{ route('quote.send', ['id' => $quote->id]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success" title="Send quote to customer's email" {{ empty($quote->customer->email) ? 'disabled' : '' }}>
                                <i class="fas fa-envelope"></i> Send
                            </button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('quote.updateStatus', $quote->id) }}" class="d-flex align-items-center gap-2">
                            @csrf
                            <select name="status" class="form-select form-select-sm" style="width: 120px;">
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
                    <td class="text-nowrap">
                        {{ ucfirst($quote->status) }}
                        @if($quote->sent)
                            <span class="badge bg-success ms-1" title="Email sent to customer">Sent</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($quote->status === 'confirmed')
                            @if($quote->order)
                                <a href="{{ route('order.review', ['id' => $quote->order->id]) }}" class="btn btn-sm btn-secondary">View</a>
                            @else
                                <a href="{{ route('order.createFromQuote', ['quoteId' => $quote->id]) }}" class="btn btn-sm btn-secondary">Create</a>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        <a href="{{ route('quote.create') }}" class="btn btn-primary">Create New Quote</a>
    </div>
</div>
@endsection
