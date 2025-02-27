@extends('layouts.public')

@section('content')
<div class="container my-4">
  <div class="row">
    <!-- Left Column: Sticky Sidebar -->
    <div class="col-md-4">
      <div class="sticky-top" style="top:0px;">
        <!-- Customer Details Card -->
        <div class="card mb-3">
          <div class="card-header bg-secondary text-white">
            Customer Details
          </div>
          <div class="card-body p-2">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Name:</span>
              <span class="text-end">{{ $quote->customer->name }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Business Name:</span>
              <span class="text-end">{{ $quote->customer->business_name }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Email:</span>
              <span class="text-end">{{ $quote->customer->email }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Phone:</span>
              <span class="text-end">{{ $quote->customer->phone }}</span>
            </div>
          </div>
        </div>
        
        <!-- Vehicle Details Card -->
        <div class="card mb-3">
          <div class="card-header bg-info text-white">
            Vehicle Details
          </div>
          <div class="card-body p-2">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Make &amp; Model:</span>
              <span class="text-end">{{ $quote->vehicle->make }} {{ $quote->vehicle->model }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Specification:</span>
              <span class="text-end">{{ $quote->vehicle->specification }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Transmission:</span>
              <span class="text-end">{{ $quote->vehicle->transmission }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Fuel Type:</span>
              <span class="text-end">{{ $quote->vehicle->fuel_type }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Reg Status:</span>
              <span class="text-end">{{ $quote->vehicle->registration_status }}</span>
            </div>
            @if($quote->vehicle->registration_status == 'Pre-Registered')
              <hr class="my-1">
              <div class="d-flex justify-content-between">
                <span class="fw-bold">Reg Date:</span>
                <span class="text-end">{{ $quote->vehicle->registration_date }}</span>
              </div>
            @endif
            <hr class="my-1">
            <div class="d-flex justify-content-between mb-0">
              <span class="fw-bold">Colour:</span>
              <span class="text-end">{{ $quote->vehicle->colour }}</span>
            </div>
          </div>
        </div>
        
        <!-- Quote Details Card -->
        <div class="card mb-3">
          <div class="card-header bg-primary text-white">
            Quote Details
          </div>
          <div class="card-body p-2">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Quote ID:</span>
              <span class="text-end">{{ $quote->id }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Finance Type:</span>
              <span class="text-end">{{ $quote->finance_type }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Contract Length:</span>
              <span class="text-end">{{ $quote->contract_length }} months</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Annual Mileage:</span>
              <span class="text-end">{{ $quote->annual_mileage }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Payment Profile:</span>
              <span class="text-end">{{ $quote->payment_profile }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Deposit:</span>
              <span class="text-end">{{ $quote->deposit }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Monthly Payment:</span>
              <span class="text-end">{{ $quote->monthly_payment }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Maintenance:</span>
              <span class="text-end">{{ $quote->maintenance ? 'Yes' : 'No' }}</span>
            </div>
            <hr class="my-1">
            <div class="d-flex justify-content-between">
              <span class="fw-bold">Document Fee:</span>
              <span class="text-end">{{ $quote->document_fee }}</span>
            </div>
          </div>
          <div class="card-footer text-muted text-end">
            Created At: {{ $quote->created_at->format('Y-m-d H:i') }}
          </div>
        </div>
      </div>
    </div>
    
    <!-- Right Column: Explanation & Review Buttons -->
    <div class="col-md-8">
      <!-- Add a top margin to align with the left sticky column -->
      <div style="margin-top:0px;">
        <div class="card mb-4">
          <div class="card-header bg-light">
            <h5 class="mb-0">
              Explanation of Your Quotation
              <button class="btn btn-link text-decoration-none float-end" type="button" data-bs-toggle="collapse" data-bs-target="#explanationContent" aria-expanded="false" aria-controls="explanationContent">
                Toggle
              </button>
            </h5>
          </div>
          <div id="explanationContent" class="collapse show">
            <div class="card-body" style="font-size: 0.9rem;">
              <p><strong>Please be confident before proceeding that you understand each element of your quotation.</strong></p>
              <p><strong>Vehicle Description</strong> – Make and model of the vehicle chosen by you, including specifics relating to gearbox, fuel type, and drivetrain.</p>
              <p><strong>Factory Fitted Options</strong> – Optional extras over and above the standard specification of the vehicle chosen by you.</p>
              <p><strong>Contract Term</strong> – The duration of your agreement in months commencing from the date your vehicle is delivered. All agreements are for a fixed term, fixed mileage.</p>
              <p><strong>Contract Mileage</strong> – Annual mileage, as specified by you, is fixed for the term of the agreement. Please be realistic with your requirements to minimize excess mileage charges.</p>
              <p><strong>Initial Rental</strong> – An advance payment typically equivalent to a number of monthly rentals (3, 6, or 9). This payment forms part of the contract and is non-refundable. A higher initial payment results in a lower regular monthly payment. This payment is usually taken by the finance company via Direct Debit approximately 14 days after delivery (depending on the funder).</p>
              <p><strong>Monthly Finance Rental</strong> – The fixed monthly payment for the lease of the vehicle paid to the funder.</p>
              <p><strong>Monthly Maintenance Rental</strong> – Where applicable, the portion of the total monthly rental attributable to the cost of the service and maintenance package. This figure is indicative and will be confirmed on your finance agreement.</p>
              <p><strong>Total Monthly Rental</strong> – The sum of the finance rental plus, where applicable, the maintenance rental. This total is taken by Direct Debit each month by the funder.</p>
              <p><strong>Effective Rental</strong> – Applies only to VAT-registered business users on business contract hire agreements and refers to the total rental including any unrecoverable VAT.</p>
              <p><strong>Excess Mileage Charge</strong> – Each mile traveled in excess of the contract mileage will incur a charge (indicative pence per mile) confirmed on your finance agreement.</p>
              <p><strong>Quotation Acceptance / Pro-Forma Order</strong> – This is a quote, not an offer. All applications are subject to credit approval. Vehicle features, CO₂, and P11D data are for guidance only and may change. Vehicle images are for illustration only. Offers are subject to change at any time, subject to finance approval and vehicle availability. This quotation is not a contractual offer and may be altered due to changes in vehicle prices, interest rates, or government legislation. Figures are subject to vehicle availability and credit approval. If you proceed, we will process your finance application and conduct credit reference searches.</p>
              <p><strong>Terms and Conditions and Cancellation Rights</strong> – If you cancel the Order, any vehicle processing fee paid will not be refunded if credit has been secured with a finance company per the Order's terms. For regulated customers (e.g., private individuals, sole traders, or partnerships with up to 3 partners), canceling your vehicle order will incur a service fee equal to one month's rental (+VAT). If vehicle delivery has been booked, there may be an additional fee of £200 for an aborted delivery.</p>
            </div>
          </div>
        </div>
      
        <div class="text-end">
          <a href="{{ route('quote.review', ['id' => $quote->id]) }}" class="btn btn-warning">Review Quote</a>
          <a href="{{ route('quote.index') }}" class="btn btn-secondary">Back to All Quotes</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
