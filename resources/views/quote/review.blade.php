@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Review Quote</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Quote Details</h5>
            <div>
                @if($quote->sent)
                    <span class="badge bg-success">Sent to {{ $quote->customer->email }}</span>
                @else
                    <span class="badge bg-secondary">Not Sent</span>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Quote ID:</strong> {{ $quote->id }}</p>
                    <p><strong>Finance Type:</strong> {{ $quote->finance_type }}</p>
                    <p><strong>Contract Length:</strong> {{ $quote->contract_length }} months</p>
                    <p><strong>Annual Mileage:</strong> {{ $quote->annual_mileage }}</p>
                    <p><strong>Payment Profile:</strong> {{ $quote->payment_profile }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Deposit:</strong> £{{ number_format($quote->deposit, 2) }}</p>
                    <p><strong>Monthly Payment:</strong> £{{ number_format($quote->monthly_payment, 2) }}</p>
                    <p><strong>Maintenance:</strong> {{ $quote->maintenance ? 'Yes' : 'No' }}</p>
                    <p><strong>Document Fee:</strong> £{{ number_format($quote->document_fee, 2) }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-{{ $quote->status == 'active' ? 'primary' : ($quote->status == 'expired' ? 'danger' : 'success') }}">{{ ucfirst($quote->status) }}</span></p>
                    <p><strong>Valid Until:</strong> {{ $quote->expires_at ? $quote->expires_at->format('d/m/Y') : 'Not set' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Customer Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $quote->customer->name }}</p>
                    <p><strong>Business Name:</strong> {{ $quote->customer->business_name }}</p>
                    <p><strong>Email:</strong> {{ $quote->customer->email }}</p>
                    <p><strong>Phone:</strong> {{ $quote->customer->phone }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Vehicle Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Make:</strong> {{ $quote->vehicle->make }}</p>
                    <p><strong>Model:</strong> {{ $quote->vehicle->model }}</p>
                    <p><strong>Specification:</strong> {{ $quote->vehicle->specification }}</p>
                    <p><strong>Transmission:</strong> {{ $quote->vehicle->transmission }}</p>
                    <p><strong>Fuel Type:</strong> {{ $quote->vehicle->fuel_type }}</p>
                    <p><strong>Registration Status:</strong> {{ $quote->vehicle->registration_status }}</p>
                    @if($quote->vehicle->registration_status == 'Pre-Registered')
                        <p><strong>Registration Date:</strong> {{ $quote->vehicle->registration_date }}</p>
                    @endif
                    <p><strong>Additional Options:</strong> {{ $quote->vehicle->additional_options }}</p>
                    <p><strong>Dealer Fit Options:</strong> {{ $quote->vehicle->dealer_fit_options }}</p>
                    <p><strong>Colour:</strong> {{ $quote->vehicle->colour }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Quote Link and Email</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label"><strong>Public Quote Link:</strong></label>
                <div class="input-group">
                    <input type="text" class="form-control" id="quoteUrl" value="{{ route('quote.view', ['token' => $quote->token]) }}" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="copyQuoteUrl()">Copy</button>
                </div>
                <small class="text-muted">You can share this link directly with the customer or use the email option below.</small>
            </div>

            <div class="d-flex gap-2">
                <form action="{{ route('quote.send', ['id' => $quote->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success" {{ empty($quote->customer->email) ? 'disabled' : '' }}>
                        {{ $quote->sent ? 'Resend Quote Email' : 'Send Quote Email' }}
                    </button>
                </form>
                
                <a href="{{ route('quote.view', ['token' => $quote->token]) }}" class="btn btn-info" target="_blank">
                    Preview Quote
                </a>
                
                <a href="{{ route('quote.index') }}" class="btn btn-secondary">Back to All Quotes</a>
            </div>

            @if(empty($quote->customer->email))
                <div class="alert alert-warning mt-3">
                    <strong>Warning:</strong> Customer does not have a valid email address. 
                    <a href="{{ route('customer.edit', ['id' => $quote->customer->id]) }}">Add an email address</a> to send this quote.
                </div>
            @endif

            @if($quote->sent)
                <div class="alert alert-info mt-3">
                    <strong>Note:</strong> This quote was last sent to {{ $quote->customer->email }} on {{ $quote->updated_at->format('d/m/Y') }} at {{ $quote->updated_at->format('H:i') }}.
                </div>
            @endif
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
</script>
@endsection
