@extends('layouts.public')

@section('title', 'Quote: ' . $quote->vehicle->make . ' ' . $quote->vehicle->model)

@section('content')
@php
    // Set dynamic Key Information based on the quote's finance type
    switch ($quote->finance_type) {
        case 'Hire Purchase':
            $keyTitle = 'Key Information - Hire Purchase';
            $keyInfo = 'Hire Purchase Key Information: [Placeholder for Hire Purchase details].';
            break;
        case 'Finance Lease':
            $keyTitle = 'Key Information - Finance Lease';
            $keyInfo = 'Finance Lease Key Information: [Placeholder for Finance Lease details].';
            break;
        case 'Business Contract Hire':
            $keyTitle = 'Key Information - Business Contract Hire';
            $keyInfo = 'Business Contract Hire Key Information: [Placeholder for Business Contract Hire details].';
            break;
        case 'Operating Lease':
            $keyTitle = 'Key Information - Operating Lease';
            $keyInfo = 'Operating Lease Key Information: [Placeholder for Operating Lease details].';
            break;
        default:
            $keyTitle = 'Key Information';
            $keyInfo = 'Default key information details.';
            break;
    }
@endphp

<div class="container my-4" style="max-width:1000px;">
  <!-- Success/Error Messages -->
  @if($quote->status == 'confirmed')
    <div class="alert alert-success text-center">
      <strong>Order Confirmed!</strong> Thank you for confirming your order for the {{ $quote->vehicle->make }} {{ $quote->vehicle->model }}. Our team have been notified and will be in touch shortly.
        </div>
  @elseif(session('success'))
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

  <!-- Header Section with 33.33% / 66.66% split -->
  <div class="row mb-4 align-items-center">
    <!-- Left Column: Logo (33.33%) -->
    <div class="col-md-4 text-center">
      <img src="{{ asset('images/logo.png') }}" alt="Fleethub Logo" class="img-fluid" style="width:100%;">
    </div>
    <!-- Right Column: Quote Info (66.66%) -->
    <div class="col-md-8 text-end">
      <h4 class="text-uppercase mb-1" style="letter-spacing: 1px;">Quote ID: 000{{ $quote->id }}</h4>
      <p class="mb-1" style="font-size: 0.9rem;">Created: {{ $quote->created_at->format('d/m/Y') }}</p>
      <p class="mb-0 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;"><b>Fleethub Limited | info@fleethub.co.uk | 07983 415924</b></p>
    </div>
  </div>
  
  <!-- Main Content Section -->
  <div class="row">
    <!-- Left Column: Details Cards (smaller font using .left-box) -->
    <div class="col-md-4 left-box" style="font-size:0.85rem;">
      <!-- Customer Details Card -->
      <div class="card mb-3">
      <div class="card-header" style="background-color:rgb(54, 54, 54); color: #ffffff;">
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
        </div>
      </div>
      
      <!-- Vehicle Details Card -->
      <div class="card mb-3">
      <div class="card-header" style="background-color: #1b9e8c; color: #ffffff;">
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
            <span class="fw-bold">Additions:</span>
            <span class="text-end">{{ $quote->vehicle->additional_options }}</span>
          </div>
          <hr class="my-1">
          <div class="d-flex justify-content-between">
            <span class="fw-bold">Dealer Options:</span>
            <span class="text-end">{{ $quote->vehicle->dealer_fit_options }}</span>
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
      <div class="card-header" style="background-color: #1b9e8c; color: #ffffff;">
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
            <span class="fw-bold">Initial Payment:</span>
            <span class="text-end">£{{ $quote->deposit }}</span>
          </div>
          <hr class="my-1">
          <div class="d-flex justify-content-between">
            <span class="fw-bold">Monthly Payment:</span>
            <span class="text-end">£{{ $quote->monthly_payment }}</span>
          </div>
          <hr class="my-1">
          <div class="d-flex justify-content-between">
            <span class="fw-bold">Maintenance:</span>
            <span class="text-end">{{ $quote->maintenance ? 'Yes' : 'No' }}</span>
          </div>
          <hr class="my-1">
          <div class="d-flex justify-content-between">
            <span class="fw-bold">Document Fee:</span>
            <span class="text-end">£{{ $quote->document_fee }}</span>
          </div>
        </div>
        <div class="card-footer text-muted text-end">
          Created: {{ $quote->created_at->format('d/m/Y') }}
        </div>
      </div>
    </div>
    
    <!-- Right Column: Accordion for 3 Expandable Sections with Always Open Text Above -->
    <div class="col-md-8">
      <!-- Always Open Text Above Accordion with Border -->
      <div class="card mb-4" style="font-size: 0.9rem;">
        <div class="card-body">
          <p>
            Thank you for allowing Fleethub to quote on your <strong>{{ $quote->vehicle->make }} {{ $quote->vehicle->model }}</strong>.
          </p>
          @if($quote->status == 'confirmed')
          <p>
            <strong class="text-success">Your order has been confirmed.</strong> A member of our team will be in touch shortly to process your order and guide you through the next steps.
          </p>
          @else
          <p>This link and quotation will expire in 28 days. Please note, prices may change at any time and are subject to availability.
          </p>
          <p>
            If you would like to order this vehicle, please read and review the information below. Once satisfied you can proceed by clicking the <b>Confirm Order</b> button at the bottom of this page.
          </p>
          @endif
          <p>
            To explain the next stages of the process, you can view our How Leasing Works page.
          </p>
          <p>
            You can find out more about Fleethub Limited, the products we offer, the services we provide, our fees and who regulates us in our Initial Disclosure Document.
          </p>
            If you have any further questions please feel free to contact us.
        </div>
      </div>
      
      <!-- Custom Styled Accordion -->
      <div class="accordion custom-accordion" id="accordionSections">
        <!-- Section 1: About Your Quote (Closed by default) -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="accordionHeadingOne">
            <button class="accordion-button collapsed custom-accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordionCollapseOne" aria-expanded="false" aria-controls="accordionCollapseOne">
              About Your Quote
            </button>
          </h2>
          <div id="accordionCollapseOne" class="accordion-collapse collapse" aria-labelledby="accordionHeadingOne" data-bs-parent="#accordionSections">
            <div class="accordion-body custom-accordion-body">
              <p>
                Please be confident before proceeding that you understand each element of your quotation, a brief overview of each section is detailed below:
              </p>
              <p>
                <strong>Vehicle Details</strong> – Make and model of the vehicle, including specifics relating to gearbox, fuel type and options.
              </p><p>
              <strong>Reg Status</strong> – This refers to the registration status of the vehicle. A pre-reg vehicle means it has been registered and assigned a registration number—even though it remains new, the warranty has already been activated. In this case, you’ll receive only balance of the warranty.
              </p>
              <p>
                <strong>Additions</strong> – Any extra features or upgrades that might be part of the quotation. While these options may not be finalised at the time of quotation, they will be confirmed in full when you place your order.
              </p><p>
                <strong>Finance Type</strong> – The method of financing your vehicle purchase. Options include Hire Purchase, Finance Lease, and Business Contract Hire. More details on these methods are available in the Key Information section below.
              </p>
              <p>
                <strong>Contract Term</strong> – The duration of your agreement in months commencing from the date your vehicle is delivered. Agreements are for a fixed term.
              </p>
              <p>
                <strong>Contract Mileage</strong> – Annual mileage, fixed for the term of the agreement. Exceeding this agreed mileage may result in additional charges and can affect the vehicle’s residual value.
              </p>
              <p>
                <strong>Deposit / Initial Payment</strong> – An upfront payment that reduces your monthly repayments. The larger your deposit, the lower your ongoing payments will be. This fee is generally collected by direct debit after delivery.
              </p>
              <p>
                <strong>Monthly Payment</strong> – The combined amount of the finance rental and, if applicable, the maintenance rental, which will be automatically debited from your account each month via Direct Debit.
              </p>
              <p>
                <strong>Payment Profile</strong> – Your payment structure typically consists of an initial deposit followed by a series of monthly payments.
              </p>
              <p>
                <strong>Maintenance</strong> – An option indicating whether scheduled maintenance is included in your quotation. Maintenance can be added to most of our finance agreements if required.
              </p>
              <p>
                <strong>Document Fee</strong> – A fee charged by finance companies to process your finance agreement. Typically collected along with the deposit after delivery, this fee is an average figure based on various funders and is subject to change.
              </p> </div>
          </div>
        </div>
        <!-- Section 2: Key Information (Dynamic based on finance type, open by default) -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="accordionHeadingTwo">
            <button class="accordion-button collapsed custom-accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordionCollapseTwo" aria-expanded="false" aria-controls="accordionCollapseTwo">
              {{ $keyTitle }}
            </button>
          </h2>
          <div id="accordionCollapseTwo" class="accordion-collapse collapse" aria-labelledby="accordionHeadingTwo" data-bs-parent="#accordionSections">
            <div class="accordion-body custom-accordion-body">
              <p>{{ $keyInfo }}</p>
            </div>
          </div>
        </div>
        <!-- Section 3: Terms & Conditions (Closed by default) -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="accordionHeadingThree">
            <button class="accordion-button collapsed custom-accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordionCollapseThree" aria-expanded="false" aria-controls="accordionCollapseThree">
              Terms &amp; Conditions
            </button>
          </h2>
          <div id="accordionCollapseThree" class="accordion-collapse collapse" aria-labelledby="accordionHeadingThree" data-bs-parent="#accordionSections">
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
                Canceling after finance approval will incur a fee equal to one month's rental (+VAT). If delivery is booked, an additional fee may apply.
            </div>
          </div>
        </div>
      </div>
      
      <!-- Confirm Order Button: Show different button based on status -->
      <div class="text-end mt-3">
        @if($quote->status == 'confirmed')
          <button type="button" class="btn btn-success" disabled>Order Confirmed</button>
        @else
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmOrderModal">
            Confirm Order
          </button>
        @endif
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

<!-- Confirm Order Modal -->
<div class="modal fade" id="confirmOrderModal" tabindex="-1" aria-labelledby="confirmOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmOrderModalLabel">Confirm Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>
          You are about to confirm that the vehicle and quotation details are correct and that you wish to proceed to the finance application.
        </p>
        <p>
          Please confirm that you have read our Terms &amp; Conditions and Privacy Policy.
        </p>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="termsCheck">
          <label class="form-check-label" for="termsCheck">I have read the Terms &amp; Conditions and Privacy Policy</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <!-- Link this button to your order confirmation route -->
        <a href="{{ route('quote.confirm', ['id' => $quote->id]) }}" id="confirmOrderBtn" class="btn btn-primary disabled">Confirm Order</a>
      </div>
    </div>
  </div>
</div>

<!-- Script to enable Confirm Order button only when checkbox is checked -->
<script>
  document.getElementById('termsCheck').addEventListener('change', function() {
    var confirmBtn = document.getElementById('confirmOrderBtn');
    if(this.checked) {
      confirmBtn.classList.remove('disabled');
    } else {
      confirmBtn.classList.add('disabled');
    }
  });
</script>

@endsection
