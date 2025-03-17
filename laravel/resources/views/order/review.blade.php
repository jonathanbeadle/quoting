@extends('layouts.app')

@section('title', 'Review Order #' . str_pad($order->id, 3, '0', STR_PAD_LEFT))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Review Order</h1>
        <div class="d-flex gap-2 align-items-center">
            @if($order->sent)
                <span class="badge bg-success">Sent to {{ $order->customer->email }}</span>
            @else
                <span class="badge bg-secondary">Not Sent</span>
            @endif
            
            <form action="{{ route('order.send', ['id' => $order->id]) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-success" {{ empty($order->customer->email) ? 'disabled' : '' }}>
                    {{ $order->sent ? 'Resend Order' : 'Send Order' }}
                </button>
            </form>

            <button id="editOrderBtn" class="btn btn-sm btn-warning" onclick="handleEditClick()">
                Edit Order
            </button>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Order Details</h5>
                </div>
                <div class="card-body p-1">
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Order ID</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                    @if($order->quote)
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Quote Reference</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">#{{ str_pad($order->quote->id, 3, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                    @endif
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Finance Type</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $order->finance_type }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Contract Length</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $order->contract_length }} months</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Annual Mileage</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $order->annual_mileage }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Payment Profile</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $order->payment_profile }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Deposit</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">£{{ number_format($order->deposit, 2) }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Monthly Payment</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">£{{ number_format($order->monthly_payment, 2) }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Document Fee</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">£{{ number_format($order->document_fee, 2) }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Maintenance</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $order->maintenance ? 'Yes' : 'No' }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Status</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light d-flex justify-content-between align-items-center">
                                {{ ucfirst($order->status) }}
                                <span class="badge bg-{{ $order->status == 'active' ? 'primary' : ($order->status == 'expired' ? 'danger' : ($order->status == 'confirmed' ? 'success' : 'warning')) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
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
                            <div class="form-control form-control-sm bg-light">{{ $order->customer->name }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Business Name</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $order->customer->business_name }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Email</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">
                                <a href="mailto:{{ $order->customer->email }}">{{ $order->customer->email }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Phone</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">
                                <a href="tel:{{ $order->customer->phone }}">{{ $order->customer->phone }}</a>
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
                            <div class="form-control form-control-sm bg-light">{{ $order->vehicle->make }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Model</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $order->vehicle->model }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Specification</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light" style="height: auto; min-height: calc(1.5em + 0.5rem + 2px);">{{ $order->vehicle->specification }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Transmission</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $order->vehicle->transmission }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Fuel Type</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $order->vehicle->fuel_type }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Additional Options</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light" style="height: auto; min-height: calc(1.5em + 0.5rem + 2px);">{{ $order->vehicle->additional_options }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Dealer Fit Options</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light" style="height: auto; min-height: calc(1.5em + 0.5rem + 2px);">{{ $order->vehicle->dealer_fit_options }}</div>
                        </div>
                    </div>
                    @if($order->vehicle->registration_status == 'Pre-Registered')
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Reg. Date</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $order->vehicle->registration_date }}</div>
                        </div>
                    </div>
                    @endif
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Colour</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $order->vehicle->colour }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Order Link and Email</h5>
                </div>
                <div class="card-body p-1">
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Public Order Link</label>
                        <div class="col-8">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm bg-light" id="orderUrl" value="{{ route('order.viewByToken', ['token' => $order->token]) }}" readonly>
                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyOrderUrl()">Copy</button>
                            </div>
                            <small class="text-muted">Share directly with customer or use email option below</small>
                        </div>
                    </div>

                    <div class="row py-1">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <form action="{{ route('order.send', ['id' => $order->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" {{ empty($order->customer->email) ? 'disabled' : '' }}>
                                        {{ $order->sent ? 'Resend Order' : 'Send Order' }}
                                    </button>
                                </form>

                                <a href="{{ route('order.viewByToken', ['token' => $order->token]) }}" class="btn btn-sm btn-info" target="_blank">
                                    Preview Order
                                </a>
                                
                                <a href="{{ route('order.index') }}" class="btn btn-sm btn-secondary">Back to All Orders</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(empty($order->customer->email))
                <div class="alert alert-warning mt-2 mb-0">
                    <strong>Warning:</strong> Customer does not have a valid email address. 
                    <a href="{{ route('customer.edit', ['id' => $order->customer->id]) }}">Add an email address</a> to send this order.
                </div>
            @endif

            @php
                $lastEmailSent = $order->tracking()
                    ->whereIn('event_type', ['sent', 'resent'])
                    ->orderBy('created_at', 'desc')
                    ->first();
            @endphp
            
            @if($lastEmailSent)
                <div class="alert alert-info mt-2 mb-0">
                    <strong>Note:</strong> This order was last sent to {{ $order->customer->email }} on {{ $lastEmailSent->created_at->format('d/m/Y') }} at {{ $lastEmailSent->created_at->format('H:i') }}.
                </div>
            @endif
        </div>
    </div>

    <!-- Order Activity Log -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Order Activity Log</h5>
        </div>
        <div class="card-body p-1">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date/Time</th>
                            <th>Event</th>
                            <th>IP Address</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->tracking()->orderBy('created_at', 'desc')->get() as $track)
                            <tr>
                                <td>{{ $track->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    @switch($track->event_type)
                                        @case('view')
                                            <span class="badge bg-info">Viewed</span>
                                            @break
                                        @case('confirm')
                                            <span class="badge bg-success">Confirmed</span>
                                            @break
                                        @case('sent')
                                            <span class="badge bg-primary">Email Sent</span>
                                            @break
                                        @case('resent')
                                            <span class="badge bg-warning">Email Resent</span>
                                            @break
                                        @case('duplicated')
                                            <span class="badge bg-secondary">Duplicated</span>
                                            @if(isset(json_decode($track->metadata, true)['duplicated_from']))
                                                <small class="text-muted">From Order #{{ str_pad(json_decode($track->metadata, true)['duplicated_from'], 3, '0', STR_PAD_LEFT) }}</small>
                                            @endif
                                            @break
                                        @case('updated')
                                            <span class="badge bg-primary">Updated</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $track->ip_address }}</td>
                                <td>
                                    @if($track->user_id)
                                        <span class="badge bg-secondary">Staff: {{ $track->user->name ?? 'Unknown' }}</span>
                                    @else
                                        <span class="badge bg-light text-dark">Customer</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Duplicate Order Modal -->
<div class="modal fade" id="duplicateOrderModal" tabindex="-1" aria-labelledby="duplicateOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="duplicateOrderModalLabel">Cannot Edit Sent Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>This order has already been sent to the customer and cannot be edited.</p>
                <p>Would you like to create a duplicate order that you can edit?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('order.duplicate', ['id' => $order->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Create Duplicate Order</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function copyOrderUrl() {
    var copyText = document.getElementById("orderUrl");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    
    // Show temporary "Copied!" message
    var btn = event.target;
    var originalText = btn.innerText;
    btn.innerText = "Copied!";
    setTimeout(function() {
        btn.innerText = originalText;
    }, 2000);
}

function handleEditClick() {
    // Check if the order has been sent
    const orderSent = {{ $order->sent ? 'true' : 'false' }};
    
    if (orderSent) {
        // Show the modal if the order has been sent
        var duplicateModal = new bootstrap.Modal(document.getElementById('duplicateOrderModal'));
        duplicateModal.show();
    } else {
        // Redirect to edit page if the order hasn't been sent
        window.location.href = "{{ route('order.edit', ['id' => $order->id]) }}";
    }
}
</script>
@endsection