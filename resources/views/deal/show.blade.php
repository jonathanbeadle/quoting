@php use App\Models\Deal; @endphp

@extends('layouts.app')

@section('title', 'Deal Details')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Deal Information</h5>
            <div>
                <a href="{{ route('quote.create') }}?customer_id={{ $deal->customer_id }}&deal_id={{ $deal->id }}" class="btn btn-primary btn-sm">Create Quote</a>
                <a href="{{ route('deal.edit', ['id' => $deal->id]) }}" class="btn btn-warning btn-sm">Edit Deal</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0">Deal Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="fw-bold">Deal ID:</label>
                                <p class="mb-0">{{ $deal->id }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Title:</label>
                                <p class="mb-0">{{ $deal->title ?? 'No title' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Status:</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $deal->status === Deal::STATUS_CLOSED ? 'secondary' : 'primary' }}">
                                        {{ $deal->status }}
                                    </span>
                                </p>
                                
                                <!-- Status Update Form -->
                                <form action="{{ route('deal.updateStatus', $deal->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <div class="input-group">
                                        <select name="status" class="form-select form-select-sm">
                                            @foreach(\App\Models\Deal::getStatuses() as $status)
                                                @if($deal->canTransitionTo($status) || $status === $deal->status)
                                                    <option value="{{ $status }}" {{ $deal->status === $status ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
                                    </div>
                                </form>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Created:</label>
                                <p class="mb-0">{{ $deal->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0">Customer Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="fw-bold">Name:</label>
                                <p class="mb-0">
                                    <a href="{{ route('customer.show', ['id' => $deal->customer->id]) }}">
                                        {{ $deal->customer->name }}
                                    </a>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Business Name:</label>
                                <p class="mb-0">{{ $deal->customer->business_name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Email:</label>
                                <p class="mb-0">
                                    <a href="mailto:{{ $deal->customer->email }}">{{ $deal->customer->email }}</a>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Phone:</label>
                                <p class="mb-0">
                                    <a href="tel:{{ $deal->customer->phone }}">{{ $deal->customer->phone }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($deal->notes)
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0">Notes</h6>
                        </div>
                        <div class="card-body">
                            {{ $deal->notes }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Quotes Section -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Quotes in this Deal</h5>
        </div>
        <div class="card-body">
            @if($deal->quotes->isEmpty())
                <p class="text-muted">No quotes found in this deal.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vehicle</th>
                                <th>Finance Type</th>
                                <th>Monthly Payment</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deal->quotes as $quote)
                            <tr>
                                <td>{{ $quote->id }}</td>
                                <td>{{ $quote->vehicle->make }} {{ $quote->vehicle->model }}</td>
                                <td>{{ $quote->finance_type }}</td>
                                <td>£{{ number_format($quote->monthly_payment, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $quote->status === 'confirmed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($quote->status) }}
                                    </span>
                                </td>
                                <td>{{ $quote->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('quote.review', ['id' => $quote->id]) }}" class="btn btn-sm btn-info">Review</a>
                                    <a href="{{ route('quote.view', ['token' => $quote->token]) }}" class="btn btn-sm btn-secondary" target="_blank">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Orders Section -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Orders in this Deal</h5>
        </div>
        <div class="card-body">
            @if($deal->orders->isEmpty())
                <p class="text-muted">No orders found in this deal.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vehicle</th>
                                <th>Finance Type</th>
                                <th>Monthly Payment</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deal->orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->vehicle->make }} {{ $order->vehicle->model }}</td>
                                <td>{{ $order->finance_type }}</td>
                                <td>£{{ number_format($order->monthly_payment, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'confirmed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('order.review', ['id' => $order->id]) }}" class="btn btn-sm btn-info">Review</a>
                                    <a href="{{ route('order.view', ['id' => $order->id]) }}" class="btn btn-sm btn-secondary" target="_blank">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection