@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Quote</h1>
    <form id="quoteForm" action="{{ route('quote.store') }}" method="POST">
        @csrf
        <!-- Customer Selection with "Add Customer" Modal -->
        <div class="mb-3">
            <label for="customer_id" class="form-label">Select Customer:</label>
            <div class="input-group">
                <select name="customer_id" id="customerSelect" class="form-select" required>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}"
                            @if(isset($selectedCustomer) && $selectedCustomer == $customer->id) selected @endif>
                            {{ $customer->name }} ({{ $customer->email }})
                        </option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    Add Customer...
                </button>
            </div>
        </div>
        <!-- Vehicle Selection with "Add Vehicle" Modal -->
        <div class="mb-3">
            <label for="vehicle_id" class="form-label">Select Vehicle:</label>
            <div class="input-group">
                <select name="vehicle_id" id="vehicleSelect" class="form-select" required>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}"
                            @if(isset($selectedVehicle) && $selectedVehicle == $vehicle->id) selected @endif>
                            {{ $vehicle->make }} {{ $vehicle->model }}
                        </option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                    Add Vehicle...
                </button>
            </div>
        </div>
        <!-- Quote Details Fields -->
        <div class="mb-3">
            <label for="finance_type" class="form-label">Finance Type*</label>
            <select name="finance_type" class="form-select" required>
                <option value="Hire Purchase">Hire Purchase</option>
                <option value="Finance Lease">Finance Lease</option>
                <option value="Operating Lease">Operating Lease</option>
                <option value="Business Contract Hire">Business Contract Hire</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="contract_length" class="form-label">Contract Length* (months)</label>
            <select name="contract_length" class="form-select" required>
                <option value="24">24</option>
                <option value="36">36</option>
                <option value="48">48</option>
                <option value="60">60</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="annual_mileage" class="form-label">Annual Mileage*</label>
            <input type="number" name="annual_mileage" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="payment_profile" class="form-label">Payment Profile*</label>
            <input type="text" name="payment_profile" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="deposit" class="form-label">Deposit*</label>
            <input type="number" step="0.01" name="deposit" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="monthly_payment" class="form-label">Monthly Payment*</label>
            <input type="number" step="0.01" name="monthly_payment" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="maintenance" class="form-label">Maintenance*</label>
            <select name="maintenance" class="form-select" required>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="document_fee" class="form-label">Document Fee*</label>
            <input type="number" step="0.01" name="document_fee" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Review Quote</button>
    </form>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Inline onsubmit attribute added here -->
      <form id="addCustomerForm" method="POST" action="{{ route('customer.store') }}" onsubmit="return submitAddCustomerForm(event);">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="customer_name" class="form-label">Customer Name*</label>
                <input type="text" name="name" id="customer_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="customer_business_name" class="form-label">Business Name*</label>
                <input type="text" name="business_name" id="customer_business_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="customer_email" class="form-label">Email Address*</label>
                <input type="email" name="email" id="customer_email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="customer_phone" class="form-label">Phone Number*</label>
                <input type="text" name="phone" id="customer_phone" class="form-control" required>
            </div>
            <div id="customerError" class="text-danger"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Customer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Inline onsubmit attribute added here -->
      <form id="addVehicleForm" method="POST" action="{{ route('vehicle.store') }}" onsubmit="return submitAddVehicleForm(event);">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addVehicleModalLabel">Add New Vehicle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="vehicle_make" class="form-label">Make*</label>
                <input type="text" name="make" id="vehicle_make" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="vehicle_model" class="form-label">Model*</label>
                <input type="text" name="model" id="vehicle_model" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="vehicle_specification" class="form-label">Specification*</label>
                <textarea name="specification" id="vehicle_specification" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="vehicle_transmission" class="form-label">Transmission*</label>
                <select name="transmission" id="vehicle_transmission" class="form-select" required>
                    <option value="Manual">Manual</option>
                    <option value="Automatic">Automatic</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="vehicle_fuel_type" class="form-label">Fuel Type*</label>
                <select name="fuel_type" id="vehicle_fuel_type" class="form-select" required>
                    <option value="Diesel">Diesel</option>
                    <option value="Petrol">Petrol</option>
                    <option value="Hybrid">Hybrid</option>
                    <option value="Electric">Electric</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="vehicle_registration_status" class="form-label">Registration Status*</label>
                <select name="registration_status" id="vehicle_registration_status" class="form-select" required>
                    <option value="New">New</option>
                    <option value="Pre-Registered">Pre-Registered</option>
                    <option value="Used">Used</option>
                </select>
            </div>
            <div class="mb-3" id="vehicleRegistrationDateDiv" style="display:none;">
                <label for="vehicle_registration_date" class="form-label">Registration Date</label>
                <input type="date" name="registration_date" id="vehicle_registration_date" class="form-control">
            </div>
            <div class="mb-3">
                <label for="vehicle_additional_options" class="form-label">Additional Options</label>
                <textarea name="additional_options" id="vehicle_additional_options" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="vehicle_dealer_fit_options" class="form-label">Dealer Fit Options</label>
                <textarea name="dealer_fit_options" id="vehicle_dealer_fit_options" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="vehicle_colour" class="form-label">Colour*</label>
                <input type="text" name="colour" id="vehicle_colour" class="form-control" required>
            </div>
            <div id="vehicleError" class="text-danger"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Vehicle</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
function submitAddCustomerForm(e) {
    e.preventDefault();
    let form = document.getElementById('addCustomerForm');
    let formData = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': formData.get('_token'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log("Customer Response:", response);
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        console.log("Customer added:", data);
        let select = document.getElementById('customerSelect');
        let option = document.createElement('option');
        option.value = data.id;
        option.text = data.name + " (" + data.email + ")";
        select.add(option);
        select.value = data.id;
        let modalEl = document.getElementById('addCustomerModal');
        let modalInstance = bootstrap.Modal.getInstance(modalEl);
        modalInstance.hide();
        form.reset();
        document.getElementById('customerError').innerText = "";
    })
    .catch(err => {
        console.error("Error adding customer:", err);
        let errorDiv = document.getElementById('customerError');
        if(err.errors){
            errorDiv.innerText = Object.values(err.errors).join(" ");
        } else {
            errorDiv.innerText = "Error adding customer.";
        }
    });
    return false; // Prevent default form submission
}

function submitAddVehicleForm(e) {
    e.preventDefault();
    let form = document.getElementById('addVehicleForm');
    let formData = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': formData.get('_token'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log("Vehicle Response:", response);
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        console.log("Vehicle added:", data);
        let select = document.getElementById('vehicleSelect');
        let option = document.createElement('option');
        option.value = data.id;
        option.text = data.make + " " + data.model;
        select.add(option);
        select.value = data.id;
        let modalEl = document.getElementById('addVehicleModal');
        let modalInstance = bootstrap.Modal.getInstance(modalEl);
        modalInstance.hide();
        form.reset();
        document.getElementById('vehicleError').innerText = "";
    })
    .catch(err => {
        console.error("Error adding vehicle:", err);
        let errorDiv = document.getElementById('vehicleError');
        if(err.errors){
            errorDiv.innerText = Object.values(err.errors).join(" ");
        } else {
            errorDiv.innerText = "Error adding vehicle.";
        }
    });
    return false; // Prevent default form submission
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('vehicle_registration_status').addEventListener('change', function() {
        var regDateDiv = document.getElementById('vehicleRegistrationDateDiv');
        if (this.value === 'Pre-Registered') {
            regDateDiv.style.display = 'block';
        } else {
            regDateDiv.style.display = 'none';
        }
    });
});
</script>
@endsection
