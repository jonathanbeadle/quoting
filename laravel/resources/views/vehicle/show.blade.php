@extends('layouts.app')

@section('title', $vehicle->make . ' ' . $vehicle->model)

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <!-- Vehicle Information Card -->
    <div class="card mb-3">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-2">
            <h5 class="mb-0">Vehicle Information</h5>
            <div>
                <a href="{{ route('quote.create') }}?vehicle_id={{ $vehicle->id }}" class="btn btn-primary btn-sm">Quote Vehicle</a>
                <button class="btn btn-warning btn-sm" id="editVehicleBtn" onclick="toggleEditMode()">Edit Details</button>
                <button class="btn btn-success btn-sm d-none" id="saveVehicleBtn" onclick="saveVehicleDetails()">Save Changes</button>
                <button class="btn btn-secondary btn-sm d-none" id="cancelEditBtn" onclick="cancelEdit()">Cancel</button>
                <button class="btn btn-danger btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteConfirmModal" 
                        data-vehicle-id="{{ $vehicle->id }}" 
                        data-vehicle-name="{{ $vehicle->make }} {{ $vehicle->model }}">
                    Delete Vehicle
                </button>
            </div>
        </div>
        <div class="card-body p-2">
            <form id="vehicleForm" onsubmit="return false;">
                @csrf
                <div class="row g-2">
                    <!-- Basic Information -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0">Basic Details</h6>
                            </div>
                            <div class="card-body p-1">
                                <div class="row py-1">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Vehicle ID</label>
                                    <div class="col-8">
                                        <div class="form-control form-control-sm bg-light">{{ $vehicle->id }}</div>
                                    </div>
                                </div>
                                <div class="row py-1">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Make</label>
                                    <div class="col-8" data-field="make">
                                        <span class="vehicle-info form-control form-control-sm bg-light">{{ $vehicle->make }}</span>
                                        <input type="text" class="form-control form-control-sm vehicle-edit d-none" name="make" value="{{ $vehicle->make }}" required>
                                    </div>
                                </div>
                                <div class="row py-1">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Model</label>
                                    <div class="col-8" data-field="model">
                                        <span class="vehicle-info form-control form-control-sm bg-light">{{ $vehicle->model }}</span>
                                        <input type="text" class="form-control form-control-sm vehicle-edit d-none" name="model" value="{{ $vehicle->model }}" required>
                                    </div>
                                </div>
                                <div class="row py-1">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Transmission</label>
                                    <div class="col-8" data-field="transmission">
                                        <span class="vehicle-info form-control form-control-sm bg-light">{{ $vehicle->transmission }}</span>
                                        <select class="form-select form-select-sm vehicle-edit d-none" name="transmission" required>
                                            <option value="Manual" {{ $vehicle->transmission == 'Manual' ? 'selected' : '' }}>Manual</option>
                                            <option value="Automatic" {{ $vehicle->transmission == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Details -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0">Registration Details</h6>
                            </div>
                            <div class="card-body p-1">
                                <div class="row py-1">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Status</label>
                                    <div class="col-8" data-field="registration_status">
                                        <span class="vehicle-info form-control form-control-sm bg-light">{{ $vehicle->registration_status }}</span>
                                        <select class="form-select form-select-sm vehicle-edit d-none" name="registration_status" required id="registrationStatus">
                                            <option value="New" {{ $vehicle->registration_status == 'New' ? 'selected' : '' }}>New</option>
                                            <option value="Pre-Registered" {{ $vehicle->registration_status == 'Pre-Registered' ? 'selected' : '' }}>Pre-Registered</option>
                                            <option value="Used" {{ $vehicle->registration_status == 'Used' ? 'selected' : '' }}>Used</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="registrationDateRow" class="row py-1 {{ $vehicle->registration_status != 'Pre-Registered' ? 'd-none' : '' }}" data-field="registration_date">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Reg. Date</label>
                                    <div class="col-8">
                                        <span class="vehicle-info form-control form-control-sm bg-light">{{ $vehicle->registration_date }}</span>
                                        <input type="date" class="form-control form-control-sm vehicle-edit d-none" name="registration_date" value="{{ $vehicle->registration_date }}">
                                    </div>
                                </div>
                                <div class="row py-1">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Fuel Type</label>
                                    <div class="col-8" data-field="fuel_type">
                                        <span class="vehicle-info form-control form-control-sm bg-light">{{ $vehicle->fuel_type }}</span>
                                        <select class="form-select form-select-sm vehicle-edit d-none" name="fuel_type" required>
                                            <option value="Diesel" {{ $vehicle->fuel_type == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                            <option value="Petrol" {{ $vehicle->fuel_type == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                                            <option value="Hybrid" {{ $vehicle->fuel_type == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                            <option value="Electric" {{ $vehicle->fuel_type == 'Electric' ? 'selected' : '' }}>Electric</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row py-1">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Colour</label>
                                    <div class="col-8" data-field="colour">
                                        <span class="vehicle-info form-control form-control-sm bg-light">{{ $vehicle->colour }}</span>
                                        <input type="text" class="form-control form-control-sm vehicle-edit d-none" name="colour" value="{{ $vehicle->colour }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Specifications -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0">Specifications</h6>
                            </div>
                            <div class="card-body p-1">
                                <div class="row py-1">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Spec</label>
                                    <div class="col-8" data-field="specification">
                                        <span class="vehicle-info form-control form-control-sm bg-light" style="height: auto; min-height: calc(1.5em + 0.5rem + 2px);">{{ $vehicle->specification }}</span>
                                        <textarea class="form-control form-control-sm vehicle-edit d-none" name="specification" rows="2" required>{{ $vehicle->specification }}</textarea>
                                    </div>
                                </div>
                                <div class="row py-1">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Add. Options</label>
                                    <div class="col-8" data-field="additional_options">
                                        <span class="vehicle-info form-control form-control-sm bg-light" style="height: auto; min-height: calc(1.5em + 0.5rem + 2px);">{{ $vehicle->additional_options ?: 'None' }}</span>
                                        <textarea class="form-control form-control-sm vehicle-edit d-none" name="additional_options" rows="2">{{ $vehicle->additional_options }}</textarea>
                                    </div>
                                </div>
                                <div class="row py-1">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Dealer Fit</label>
                                    <div class="col-8" data-field="dealer_fit_options">
                                        <span class="vehicle-info form-control form-control-sm bg-light" style="height: auto; min-height: calc(1.5em + 0.5rem + 2px);">{{ $vehicle->dealer_fit_options ?: 'None' }}</span>
                                        <textarea class="form-control form-control-sm vehicle-edit d-none" name="dealer_fit_options" rows="2">{{ $vehicle->dealer_fit_options }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quotes Section -->
    <div class="card mb-3">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">Quotes for {{ $vehicle->make }} {{ $vehicle->model }}</h5>
        </div>
        <div class="card-body p-2">
            @if($vehicle->quotes->isEmpty())
                <p class="text-muted mb-0">No quotes found for this vehicle.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Finance Type</th>
                                <th>Monthly Payment</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicle->quotes as $quote)
                            <tr>
                                <td>{{ $quote->id }}</td>
                                <td>
                                    <a href="{{ route('customer.show', ['id' => $quote->customer->id]) }}">
                                        {{ $quote->customer->name }}
                                    </a>
                                </td>
                                <td>{{ $quote->finance_type }}</td>
                                <td>£{{ number_format($quote->monthly_payment, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $quote->status == 'confirmed' ? 'success' : ($quote->status == 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($quote->status) }}
                                    </span>
                                    @if($quote->sent)
                                        <span class="badge bg-info ms-1" title="Email sent to customer">Sent</span>
                                    @endif
                                </td>
                                <td>{{ $quote->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('quote.view', ['token' => $quote->token]) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('quote.review', ['id' => $quote->id]) }}" class="btn btn-sm btn-warning">Review</a>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the vehicle: <strong id="vehicleNameToDelete"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
                @if(!$vehicle->quotes->isEmpty())
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> This vehicle has associated quotes. You cannot delete a vehicle with existing quotes.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteVehicleForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" {{ !$vehicle->quotes->isEmpty() ? 'disabled' : '' }}>Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Original values for cancel functionality
    let originalValues = {};

    function toggleEditMode() {
        const infoElements = document.querySelectorAll('.vehicle-info');
        const editElements = document.querySelectorAll('.vehicle-edit');
        const editBtn = document.getElementById('editVehicleBtn');
        const saveBtn = document.getElementById('saveVehicleBtn');
        const cancelBtn = document.getElementById('cancelEditBtn');

        // Store original values when entering edit mode
        if (editBtn.style.display !== 'none') {
            editElements.forEach(input => {
                originalValues[input.name] = input.value;
            });
        }

        infoElements.forEach(el => el.classList.toggle('d-none'));
        editElements.forEach(el => el.classList.toggle('d-none'));
        editBtn.classList.toggle('d-none');
        saveBtn.classList.toggle('d-none');
        cancelBtn.classList.toggle('d-none');
    }

    function cancelEdit() {
        // Restore original values
        const editElements = document.querySelectorAll('.vehicle-edit');
        editElements.forEach(input => {
            input.value = originalValues[input.name];
        });
        toggleEditMode();
    }

    function saveVehicleDetails() {
        const form = document.getElementById('vehicleForm');
        const formData = new FormData(form);
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Add method spoofing for PUT request
        formData.append('_method', 'PUT');

        fetch(`/vehicle/{{ $vehicle->id }}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            
            if (!response.ok) {
                if (response.status === 419) {
                    window.location.reload();
                    throw new Error('Your session has expired. Please try again.');
                }
                return response.json().then(data => {
                    throw new Error(data.message || JSON.stringify(data.errors) || 'Error updating vehicle');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Safely update displayed values
                const updateField = (fieldName, value, inputType = 'text') => {
                    const container = document.querySelector(`[data-field="${fieldName}"]`);
                    if (container) {
                        const infoSpan = container.querySelector('.vehicle-info');
                        if (infoSpan) {
                            if (inputType === 'textarea') {
                                infoSpan.textContent = value || 'None';
                            } else {
                                infoSpan.textContent = value;
                            }
                        }
                    }
                };

                // Update all fields
                updateField('make', formData.get('make'));
                updateField('model', formData.get('model'));
                updateField('specification', formData.get('specification'), 'textarea');
                updateField('transmission', formData.get('transmission'));
                updateField('fuel_type', formData.get('fuel_type'));
                updateField('registration_status', formData.get('registration_status'));
                updateField('registration_date', formData.get('registration_date'));
                updateField('colour', formData.get('colour'));
                updateField('additional_options', formData.get('additional_options'), 'textarea');
                updateField('dealer_fit_options', formData.get('dealer_fit_options'), 'textarea');

                // Remove any existing alerts
                const existingAlerts = document.querySelectorAll('.alert');
                existingAlerts.forEach(alert => alert.remove());

                // Create success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show';
                alert.innerHTML = `
                    ${data.message || 'Vehicle details updated successfully!'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                
                // Insert alert into the alertContainer
                const alertContainer = document.getElementById('alertContainer');
                alertContainer.appendChild(alert);

                // Exit edit mode
                toggleEditMode();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Remove any existing alerts
            const existingAlerts = document.querySelectorAll('.alert');
            existingAlerts.forEach(alert => alert.remove());
            
            // Create and insert error alert
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show';
            alert.innerHTML = `
                ${error.message || 'Error updating vehicle details. Please try again.'}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Insert alert into the alertContainer
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.appendChild(alert);
        });
    }

    // Handle registration status changes
    document.getElementById('registrationStatus')?.addEventListener('change', function() {
        const regDateRow = document.getElementById('registrationDateRow');
        if (this.value === 'Pre-Registered') {
            regDateRow.classList.remove('d-none');
        } else {
            regDateRow.classList.add('d-none');
        }
    });

    // Delete modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteConfirmModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const vehicleId = button.getAttribute('data-vehicle-id');
                const vehicleName = button.getAttribute('data-vehicle-name');
                const vehicleNameElement = deleteModal.querySelector('#vehicleNameToDelete');
                vehicleNameElement.textContent = vehicleName;
                const deleteForm = deleteModal.querySelector('#deleteVehicleForm');
                deleteForm.action = '/vehicle/' + vehicleId;
            });
        }
        
        // Auto-activate edit mode if edit parameter is present in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('edit') === 'true') {
            toggleEditMode();
        }
    });
</script>
@endsection
