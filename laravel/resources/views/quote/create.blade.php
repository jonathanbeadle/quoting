@extends('layouts.app')

@section('content')
<div class="container">
    <div id="alertContainer"></div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Create New Quote</h4>
                </div>
                <div class="card-body">
                    <form id="quoteForm" action="{{ route('quote.store') }}" method="POST">
                        @csrf
                        <!-- Customer Selection with "Add Customer" Modal -->
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Select Customer*</label>
                            <div class="input-group">
                                <select name="customer_id" id="customerSelect" class="form-select" required>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}"

                                            @if(isset($selectedCustomer) && $selectedCustomer == $customer->id) selected @endif>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-secondary" style="min-width: 80px;" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                    Add
                                </button>
                            </div>
                        </div>

                        <!-- Vehicle Selection with "Add Vehicle" Modal -->
                        <div class="mb-3">
                            <label for="vehicle_id" class="form-label">Select Vehicle*</label>
                            <div class="input-group">
                                <select name="vehicle_id" id="vehicleSelect" class="form-select" required>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}"

                                            @if(isset($selectedVehicle) && $selectedVehicle == $vehicle->id) selected @endif>
                                            {{ $vehicle->make }} {{ $vehicle->model }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-secondary" style="min-width: 80px;" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                                    Add
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contract_length" class="form-label">Contract Length* (months)</label>
                                    <select name="contract_length" class="form-select" required>
                                        <option value="24">24</option>
                                        <option value="36">36</option>
                                        <option value="48">48</option>
                                        <option value="60">60</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="annual_mileage" class="form-label">Annual Mileage*</label>
                                    <input type="number" name="annual_mileage" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="payment_profile" class="form-label">Payment Profile*</label>
                            <input type="text" name="payment_profile" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="deposit" class="form-label">Deposit*</label>
                                    <div class="input-group">
                                        <span class="input-group-text">£</span>
                                        <input type="number" step="0.01" name="deposit" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="monthly_payment" class="form-label">Monthly Payment*</label>
                                    <div class="input-group">
                                        <span class="input-group-text">£</span>
                                        <input type="number" step="0.01" name="monthly_payment" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="maintenance" class="form-label">Maintenance*</label>
                                    <select name="maintenance" class="form-select" required>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="document_fee" class="form-label">Document Fee*</label>
                                    <div class="input-group">
                                        <span class="input-group-text">£</span>
                                        <input type="number" step="0.01" name="document_fee" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Review Quote</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addCustomerForm" method="POST" action="{{ route('customer.store') }}">
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
                    <button type="button" id="addCustomerBtn" class="btn btn-primary">Add Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addVehicleForm" method="POST" action="{{ route('vehicle.store') }}">
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
                    <button type="button" id="addVehicleBtn" class="btn btn-primary">Add Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded');
    
    // Check if elements exist before adding event listeners
    const addCustomerBtn = document.getElementById('addCustomerBtn');
    const addVehicleBtn = document.getElementById('addVehicleBtn');
    
    if (addCustomerBtn) {
        console.log('Customer button found');
        addCustomerBtn.addEventListener('click', function() {
            console.log('Add Customer button clicked');
            
            var xhr = new XMLHttpRequest();
            var form = document.getElementById('addCustomerForm');
            var formData = new FormData(form);
            
            // Log form data
            console.log('Form action:', form.action);
            formData.forEach((value, key) => {
                console.log(key + ": " + value);
            });

            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');
            
            // Add event listeners for all possible XMLHttpRequest events
            xhr.onreadystatechange = function() {
                console.log('XHR state changed:', xhr.readyState);
            };
            
            xhr.onload = function() {
                console.log('XHR loaded. Status:', xhr.status);
                console.log('Response:', xhr.responseText);
                
                try {
                    if (xhr.status === 200 || xhr.status === 201) {
                        var data = JSON.parse(xhr.responseText);
                        console.log("Customer added:", data);
                        
                        // Update select dropdown
                        let select = document.getElementById('customerSelect');
                        let option = document.createElement('option');
                        option.value = data.id;
                        option.text = data.name + " (" + data.email + ")";
                        select.add(option);
                        select.value = data.id;
                        
                        // Close the modal
                        var modalEl = document.getElementById('addCustomerModal');
                        var modalInstance = bootstrap.Modal.getInstance(modalEl);
                        console.log('Modal instance:', modalInstance);
                        if (modalInstance) {
                            modalInstance.hide();
                        } else {
                            console.error('Modal instance not found');
                            // Try alternative approach
                            var bsModal = new bootstrap.Modal(modalEl);
                            bsModal.hide();
                        }
                        form.reset();
                        document.getElementById('customerError').innerText = "";
                        
                        // Show success message
                        showAlert('Customer added successfully!', 'success');
                    } else {
                        console.error('Error response status:', xhr.status);
                        console.error('Error response text:', xhr.responseText);
                        document.getElementById('customerError').innerText = "Error status: " + xhr.status + ". Please try again.";
                        
                        // Try to parse error response
                        try {
                            const errorData = JSON.parse(xhr.responseText);
                            if (errorData.errors) {
                                document.getElementById('customerError').innerText = Object.values(errorData.errors).join(" ");
                            }
                        } catch (e) {
                            console.error('Could not parse error response:', e);
                        }
                    }
                } catch (err) {
                    console.error('Error in XHR onload handler:', err);
                    document.getElementById('customerError').innerText = "Error processing response: " + err.message;
                }
            };
            
            xhr.onerror = function() {
                console.error('XHR error event triggered');
                document.getElementById('customerError').innerText = "Network error. Please try again.";
                showAlert('Network error when trying to add customer', 'danger');
            };
            
            xhr.ontimeout = function() {
                console.error('XHR timeout');
                document.getElementById('customerError').innerText = "Request timed out. Please try again.";
            };
            
            xhr.onabort = function() {
                console.error('XHR aborted');
                document.getElementById('customerError').innerText = "Request was aborted.";
            };
            
            try {
                xhr.send(formData);
                console.log('XHR request sent');
            } catch (err) {
                console.error('Error sending XHR:', err);
                document.getElementById('customerError').innerText = "Error sending request: " + err.message;
            }
        });
    } else {
        console.error('Customer button not found in DOM');
    }
    
    if (addVehicleBtn) {
        console.log('Vehicle button found');
        addVehicleBtn.addEventListener('click', function() {
            console.log('Add Vehicle button clicked');
            
            var xhr = new XMLHttpRequest();
            var form = document.getElementById('addVehicleForm');
            var formData = new FormData(form);
            
            // Log form data
            console.log('Form action:', form.action);
            formData.forEach((value, key) => {
                console.log(key + ": " + value);
            });
            
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');
            
            // Add event listeners for all possible XMLHttpRequest events
            xhr.onreadystatechange = function() {
                console.log('XHR state changed:', xhr.readyState);
            };
            
            xhr.onload = function() {
                console.log('XHR loaded. Status:', xhr.status);
                console.log('Response:', xhr.responseText);
                
                try {
                    if (xhr.status === 200 || xhr.status === 201) {
                        var data = JSON.parse(xhr.responseText);
                        console.log("Vehicle added:", data);
                        
                        // Update select dropdown
                        let select = document.getElementById('vehicleSelect');
                        let option = document.createElement('option');
                        option.value = data.id;
                        option.text = data.make + " " + data.model;
                        select.add(option);
                        select.value = data.id;
                        
                        // Close the modal
                        var modalEl = document.getElementById('addVehicleModal');
                        var modalInstance = bootstrap.Modal.getInstance(modalEl);
                        console.log('Modal instance:', modalInstance);
                        if (modalInstance) {
                            modalInstance.hide();
                        } else {
                            console.error('Modal instance not found');
                            // Try alternative approach
                            var bsModal = new bootstrap.Modal(modalEl);
                            bsModal.hide();
                        }
                        form.reset();
                        document.getElementById('vehicleError').innerText = "";
                        
                        // Show success message
                        showAlert('Vehicle added successfully!', 'success');
                    } else {
                        console.error('Error response status:', xhr.status);
                        console.error('Error response text:', xhr.responseText);
                        document.getElementById('vehicleError').innerText = "Error status: " + xhr.status + ". Please try again.";
                        
                        // Try to parse error response
                        try {
                            const errorData = JSON.parse(xhr.responseText);
                            if (errorData.errors) {
                                document.getElementById('vehicleError').innerText = Object.values(errorData.errors).join(" ");
                            }
                        } catch (e) {
                            console.error('Could not parse error response:', e);
                        }
                    }
                } catch (err) {
                    console.error('Error in XHR onload handler:', err);
                    document.getElementById('vehicleError').innerText = "Error processing response: " + err.message;
                }
            };
            
            xhr.onerror = function() {
                console.error('XHR error event triggered');
                document.getElementById('vehicleError').innerText = "Network error. Please try again.";
                showAlert('Network error when trying to add vehicle', 'danger');
            };
            
            xhr.ontimeout = function() {
                console.error('XHR timeout');
                document.getElementById('vehicleError').innerText = "Request timed out. Please try again.";
            };
            
            xhr.onabort = function() {
                console.error('XHR aborted');
                document.getElementById('vehicleError').innerText = "Request was aborted.";
            };
            
            try {
                xhr.send(formData);
                console.log('XHR request sent');
            } catch (err) {
                console.error('Error sending XHR:', err);
                document.getElementById('vehicleError').innerText = "Error sending request: " + err.message;
            }
        });
    } else {
        console.error('Vehicle button not found in DOM');
    }
    
    // Show/hide registration date field
    var vehicleRegStatus = document.getElementById('vehicle_registration_status');
    if (vehicleRegStatus) {
        vehicleRegStatus.addEventListener('change', function() {
            var regDateDiv = document.getElementById('vehicleRegistrationDateDiv');
            if (this.value === 'Pre-Registered') {
                regDateDiv.style.display = 'block';
            } else {
                regDateDiv.style.display = 'none';
            }
        });
    }
    
    // Function to show temporary alert messages
    function showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show mb-3`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Insert above the quote form
        const alertContainer = document.getElementById('alertContainer');
        if (alertContainer) {
            alertContainer.appendChild(alertDiv);
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 150);
            }, 5000);
        }
    }
});
</script>
@endsection
