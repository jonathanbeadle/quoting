@extends('layouts.app')

@section('title', 'Review Quote #' . str_pad($quote->id, 3, '0', STR_PAD_LEFT))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Review Quote</h1>
        <div class="d-flex gap-2 align-items-center">
            @if($quote->sent)
                <span class="badge bg-success">Sent to {{ $quote->customer->email }}</span>
            @else
                <span class="badge bg-secondary">Not Sent</span>
            @endif

            <form action="{{ route('quote.send', ['id' => $quote->id]) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-success" {{ empty($quote->customer->email) ? 'disabled' : '' }}>
                    {{ $quote->sent ? 'Resend Quote' : 'Send Quote' }}
                </button>
            </form>
            
            <button id="editQuoteBtn" class="btn btn-sm btn-warning" onclick="handleEditClick()">
                Edit Quote
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
                    <h5 class="mb-0">Quote Details</h5>
                </div>
                <div class="card-body p-1">
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Quote ID</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $quote->id }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Finance Type</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $quote->finance_type }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Contract Length</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $quote->contract_length }} months</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Annual Mileage</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $quote->annual_mileage }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Payment Profile</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $quote->payment_profile }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Deposit</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">£{{ number_format($quote->deposit, 2) }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Monthly Payment</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">£{{ number_format($quote->monthly_payment, 2) }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Maintenance</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $quote->maintenance ? 'Yes' : 'No' }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Document Fee</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">£{{ number_format($quote->document_fee, 2) }}</div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Status</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light d-flex justify-content-between align-items-center">
                                {{ ucfirst($quote->status) }}
                                <span class="badge bg-{{ $quote->status == 'active' ? 'primary' : ($quote->status == 'expired' ? 'danger' : 'success') }}">
                                    {{ ucfirst($quote->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Valid Until</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $quote->expires_at ? $quote->expires_at->format('d/m/Y') : 'Not set' }}</div>
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
                    @if($quote->vehicle->registration_status == 'Pre-Registered')
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Reg. Date</label>
                        <div class="col-8">
                            <div class="form-control form-control-sm bg-light">{{ $quote->vehicle->registration_date }}</div>
                        </div>
                    </div>
                    @endif
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

            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Quote Link and Email</h5>
                </div>
                <div class="card-body p-1">
                    <div class="row py-1">
                        <label class="col-4 col-form-label col-form-label-sm fw-bold">Public Quote Link</label>
                        <div class="col-8">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm bg-light" id="quoteUrl" value="{{ route('quote.view', ['token' => $quote->token]) }}" readonly>
                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyQuoteUrl()">Copy</button>
                            </div>
                            <small class="text-muted">Share directly with customer or use email option below</small>
                        </div>
                    </div>

                    <div class="row py-1">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <form action="{{ route('quote.send', ['id' => $quote->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" {{ empty($quote->customer->email) ? 'disabled' : '' }}>
                                        {{ $quote->sent ? 'Resend Quote Email' : 'Send Quote Email' }}
                                    </button>
                                </form>
                                
                                <a href="{{ route('quote.view', ['token' => $quote->token]) }}" class="btn btn-sm btn-info" target="_blank">
                                    Preview Quote
                                </a>
                                
                                <a href="{{ route('quote.index') }}" class="btn btn-sm btn-secondary">Back to All Quotes</a>
                            </div>
                        </div>
                    </div>

                    @if(empty($quote->customer->email))
                        <div class="alert alert-warning mt-2 mb-0">
                            <strong>Warning:</strong> Customer does not have a valid email address. 
                            <a href="{{ route('customer.edit', ['id' => $quote->customer->id]) }}">Add an email address</a> to send this quote.
                        </div>
                    @endif

                    @if($quote->sent)
                        @php
                            $lastEmailSent = $quote->tracking()
                                ->whereIn('event_type', ['sent', 'resent'])
                                ->orderBy('created_at', 'desc')
                                ->first();
                        @endphp
                        <div class="alert alert-info mt-2 mb-0">
                            <strong>Note:</strong> This quote was last sent to {{ $quote->customer->email }} on {{ $lastEmailSent->created_at->format('d/m/Y') }} at {{ $lastEmailSent->created_at->format('H:i') }}.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quote Activity Log -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Quote Activity Log</h5>
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
                        @foreach($quote->tracking()->orderBy('created_at', 'desc')->get() as $track)
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

<!-- Duplicate Quote Modal -->
<div class="modal fade" id="duplicateQuoteModal" tabindex="-1" aria-labelledby="duplicateQuoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="duplicateQuoteModalLabel">Cannot Edit Sent Quote</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>This quote has already been sent to the customer and cannot be edited.</p>
                <p>Would you like to create a duplicate quote that you can edit?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('quote.duplicate', ['id' => $quote->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Create Duplicate Quote</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function copyQuoteUrl() {
    var copyText = document.getElementById("quoteUrl");
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
    // Check if the quote has been sent
    const quoteSent = {{ $quote->sent ? 'true' : 'false' }};
    
    if (quoteSent) {
        // Show the modal if the quote has been sent
        var duplicateModal = new bootstrap.Modal(document.getElementById('duplicateQuoteModal'));
        duplicateModal.show();
    } else {
        // Redirect to edit page if the quote hasn't been sent
        window.location.href = "{{ route('quote.edit', ['id' => $quote->id]) }}";
    }
}
</script>
@endsection
