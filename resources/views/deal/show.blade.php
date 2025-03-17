@php use App\Models\Deal; @endphp

@extends('layouts.app')

@section('title', 'Deal Details')

@section('content')
<div class="container">
    <!-- Deal Information -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Deal Information</h5>
            <div>
                <a href="{{ route('quote.create') }}?customer_id={{ $deal->customer_id }}&deal_id={{ $deal->id }}" class="btn btn-primary btn-sm">Create Quote</a>
                <a href="{{ route('deal.edit', ['id' => $deal->id]) }}" class="btn btn-warning btn-sm">Edit Deal</a>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row g-2">
                <!-- Deal Details -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0">Deal Details</h6>
                        </div>
                        <div class="card-body p-1">
                            <div class="row py-1">
                                <label class="col-4 col-form-label col-form-label-sm fw-bold">Deal ID</label>
                                <div class="col-8">
                                    <div class="form-control form-control-sm bg-light">{{ $deal->id }}</div>
                                </div>
                            </div>
                            <div class="row py-1">
                                <label class="col-4 col-form-label col-form-label-sm fw-bold">Title</label>
                                <div class="col-8">
                                    <div class="form-control form-control-sm bg-light">{{ $deal->title ?? 'No title' }}</div>
                                </div>
                            </div>
                            <div class="row py-1">
                                <label class="col-4 col-form-label col-form-label-sm fw-bold">Status</label>
                                <div class="col-8">
                                    <div class="d-flex align-items-center gap-2">
                                        <div id="statusDisplay" class="form-control form-control-sm bg-light">
                                            {{ $deal->status }}
                                        </div>
                                        <button onclick="toggleStatusEdit()" class="btn btn-sm btn-warning" id="editStatusBtn">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <div id="statusEdit" class="d-none w-100">
                                            <form action="{{ route('deal.updateStatus', $deal->id) }}" method="POST" class="d-flex gap-2" id="statusForm">
                                                @csrf
                                                <select name="status" class="form-select form-select-sm">
                                                    @foreach(\App\Models\Deal::getStatuses() as $status)
                                                        @if($deal->canTransitionTo($status) || $status === $deal->status)
                                                            <option value="{{ $status }}" {{ $deal->status === $status ? 'selected' : '' }}>
                                                                {{ $status }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                <button type="button" onclick="toggleStatusEdit()" class="btn btn-sm btn-secondary">Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-1">
                                <label class="col-4 col-form-label col-form-label-sm fw-bold">Created</label>
                                <div class="col-8">
                                    <div class="form-control form-control-sm bg-light">{{ $deal->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                            @if($deal->notes)
                                <div class="row py-1">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Notes</label>
                                    <div class="col-8">
                                        <div class="form-control form-control-sm bg-light" style="height: auto; min-height: calc(1.5em + 0.5rem + 2px);">{{ $deal->notes }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0">Customer Information</h6>
                        </div>
                        <div class="card-body p-1">
                            <div class="row py-1">
                                <label class="col-4 col-form-label col-form-label-sm fw-bold">Name</label>
                                <div class="col-8">
                                    <div class="form-control form-control-sm bg-light">
                                        <a href="{{ route('customer.show', ['id' => $deal->customer->id]) }}">
                                            {{ $deal->customer->name }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-1">
                                <label class="col-4 col-form-label col-form-label-sm fw-bold">Business</label>
                                <div class="col-8">
                                    <div class="form-control form-control-sm bg-light">{{ $deal->customer->business_name }}</div>
                                </div>
                            </div>
                            <div class="row py-1">
                                <label class="col-4 col-form-label col-form-label-sm fw-bold">Email</label>
                                <div class="col-8">
                                    <div class="form-control form-control-sm bg-light">
                                        <a href="mailto:{{ $deal->customer->email }}">{{ $deal->customer->email }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-1">
                                <label class="col-4 col-form-label col-form-label-sm fw-bold">Phone</label>
                                <div class="col-8">
                                    <div class="form-control form-control-sm bg-light">
                                        <a href="tel:{{ $deal->customer->phone }}">{{ $deal->customer->phone }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

@section('scripts')
<script>
function toggleStatusEdit() {
    const displayElement = document.getElementById('statusDisplay');
    const editElement = document.getElementById('statusEdit');
    const editBtn = document.getElementById('editStatusBtn');
    
    if (displayElement.classList.contains('d-none')) {
        displayElement.classList.remove('d-none');
        editElement.classList.add('d-none');
        editBtn.classList.remove('d-none');
    } else {
        displayElement.classList.add('d-none');
        editElement.classList.remove('d-none');
        editBtn.classList.add('d-none');
    }
}
</script>
@endsection