@extends('layouts.public')

@section('content')
<div class="container my-4" style="max-width:1000px;">
  <!-- Header Section with 33.33% / 66.66% split -->
  <div class="row mb-4 align-items-center">
    <!-- Left Column: Logo (33.33%) -->
    <div class="col-md-4 text-center">
      <img src="{{ asset('images/logo.png') }}" alt="Fleethub Logo" class="img-fluid" style="width:100%;">
    </div>
    <!-- Right Column: Quote Info (66.66%) -->
    <div class="col-md-8 text-end">
      <h4 class="text-uppercase mb-1" style="letter-spacing: 1px;">Quote ID: {{ $quote->id }}</h4>
      <p class="mb-1" style="font-size: 0.9rem;">Created At: {{ $quote->created_at->format('d/m/Y') }}</p>
      <p class="mb-0 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Your Custom Placeholder Text</p>
    </div>
  </div>
  
  <!-- Main Content Section -->
  <div class="row">
    <!-- Left Column: Details Cards (smaller font using .left-box) -->
    <div class="col-md-4 left-box" style="font-size:0.85rem;">
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
          Created At: {{ $quote->created_at->format('d/m/Y') }}
        </div>
      </div>
    </div>
    
    <!-- Right Column: Accordion for 3 Expandable Sections with Always Open Text Above -->
    <div class="col-md-8">
      <!-- Always Open Text Above Accordion with Border -->
      <div class="card mb-4" style="font-size: 0.9rem;">
        <div class="card-body">
          <p>
            Thank you for allowing Fleethub to quote on your <strong>{{ $quote->vehicle->make }} {{ $quote->vehicle->model }}</strong>.</p>
            <p>This link and quotation will expire in 28 days. Please note, prices may change at any time and are subject to availability.
          </p>
          <p>
            If you would like to order this vehicle, please read and review the information below. Once satisfied you can proceed by clicking the Confirm Order button at the bottom of this page.
          </p>
          <p>
            To explain the next stages of the process, you can view our How Leasing Works page.
          </p>
          <p>
            You can find out more about Fleethub Limited, the products we offer, the services we provide, our fees and who regulates us in our Initial Disclosure Document.
          </p>
          <p>
            If you have any further questions please feel free to contact us.
          </p>
        </div>
      </div>
      
      <div class="accordion custom-accordion" id="accordionSections">
        <!-- Section 1: About Your Quote (Closed by default) -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button collapsed custom-accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
              About Your Quote
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionSections">
            <div class="accordion-body custom-accordion-body">
              <p>
                Please be confident before proceeding that you understand each element of your quotation, a brief overview of each section is detailed below:
              </p>
              <p>
                <strong>Vehicle Description</strong> – Make and model of the vehicle chosen by you, including specifics relating to gearbox, fuel type and drivetrain.
              </p>
              <p>
                <strong>Factory Fitted Options</strong> – Optional extras over and above the standard specification of the vehicle chosen by you.
              </p>
              <p>
                <strong>Contract Term</strong> – The duration of your agreement in months commencing from the date your vehicle is delivered. All agreements are for a fixed term, fixed mileage.
              </p>
              <p>
                <strong>Contract Mileage</strong> – Annual mileage, as specified by you, is fixed for the term of the agreement. Please be realistic with your requirements to minimise excess mileage charges.
              </p>
              <p>
                <strong>Initial Rental</strong> – An advance payment typically equivalent to a number of monthly rentals (3, 6, or 9). This payment forms part of the contract and is non-refundable. The higher the initial payment, the lower your regular monthly payment. This payment is usually taken by Direct Debit approximately 14 days after delivery.
              </p>
              <p>
                <strong>Monthly Finance Rental</strong> – The fixed monthly payment for the lease of the vehicle paid to the funder.
              </p>
              <p>
                <strong>Monthly Maintenance Rental</strong> – Where applicable, the portion of the total monthly rental attributable to the service and maintenance package. This figure is indicative and will be confirmed on your finance agreement.
              </p>
              <p>
                <strong>Total Monthly Rental</strong> – The sum of the finance rental plus, where applicable, the maintenance rental. The total will be taken by Direct Debit each month.
              </p>
              <p>
                <strong>Effective Rental</strong> – Applies only to VAT-registered business users and refers to the total rental including any unrecoverable VAT.
              </p>
              <p>
                <strong>Excess Mileage Charge</strong> – Each mile traveled in excess of the contract mileage will incur a charge (indicative pence per mile).
              </p>
            </div>
          </div>
        </div>
        <!-- Section 2: Key Information -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed custom-accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              Key Information
            </button>
          </h2>
          <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionSections">
            <div class="accordion-body custom-accordion-body">
              <p>
                Contract Hire is a product for individuals or businesses that want fixed cost motoring with predictable expenses. It is an easy and cost-effective way to fund vehicles and control your larger monthly costs. Maintenance packages may also be included.
              </p>
              <p>
                <strong>Key Considerations</strong>: Fixed term and mileage, no ownership, inclusion of maintenance, termination charges, excess mileage fees, and the return condition of the vehicle.
              </p>
            </div>
          </div>
        </div>
        <!-- Section 3: Terms & Conditions -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button collapsed custom-accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
              Terms &amp; Conditions
            </button>
          </h2>
          <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionSections">
            <div class="accordion-body custom-accordion-body">
              <p>
                This quotation is not a contractual offer and may change due to vehicle prices, interest rates, or government legislation. Figures are subject to availability and credit approval.
              </p>
              <p>
                If you proceed, we will process your finance application and conduct credit checks.
              </p>
              <p>
                If you cancel, any processing fee will not be refunded.
              </p>
              <p>
                For regulated customers, canceling will incur a fee equal to one month's rental (+VAT). If delivery is booked, an additional fee of £200 may apply.
              </p>
            </div>
          </div>
        </div>
      </div>
      
      <div class="text-end mt-3">
        <a href="{{ route('quote.review', ['id' => $quote->id]) }}" class="btn btn-warning">Review Quote</a>
        <a href="{{ route('quote.index') }}" class="btn btn-secondary">Back to All Quotes</a>
      </div>
    </div>
  </div>
  
  <!-- Small Print at the Bottom -->
  <div class="row mt-4">
    <div class="col-12">
      <p class="small text-center text-muted">
        Fleethub Limited (16055957), Finchale House, Belmont Business Park, Durham, England, DH1 1TW is part of an independent broker network. Fleethub Limited is a credit broker and not a lender. Fleethub Limited will receive a commission payment from a lender when we arrange your finance.
      </p>
    </div>
  </div>
</div>
@endsection
