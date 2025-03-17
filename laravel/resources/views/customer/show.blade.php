@php use App\Models\Deal; @endphp

@extends('layouts.app')

@section('title', $customer->name)

@section('content')
<div class="container">
    <!-- Success/Error Alert Container -->
    <div id="alertContainer"></div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Customer Information Card -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Customer Information</h5>
            <div>
                <a href="{{ route('quote.create') }}?customer_id={{ $customer->id }}" class="btn btn-primary btn-sm">Quote Customer</a>
                <button class="btn btn-warning btn-sm" id="editCustomerBtn" onclick="toggleEditMode()">Edit Details</button>
                <button class="btn btn-success btn-sm d-none" id="saveCustomerBtn" onclick="saveCustomerDetails()">Save Changes</button>
                <button class="btn btn-secondary btn-sm d-none" id="cancelEditBtn" onclick="cancelEdit()">Cancel</button>
                <button class="btn btn-danger btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteConfirmModal" 
                        data-customer-id="{{ $customer->id }}" 
                        data-customer-name="{{ $customer->name }}">
                    Delete Customer
                </button>
            </div>
        </div>
        <div class="card-body">
            <form id="customerForm" onsubmit="return false;">
                @csrf
                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0">Business Details</h6>
                            </div>
                            <div class="card-body p-1">
                                <div class="row py-1">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Customer ID</label>
                                    <div class="col-8">
                                        <div class="form-control form-control-sm bg-light">{{ $customer->id }}</div>
                                    </div>
                                </div>
                                <div class="row py-1" data-field="business_name">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Business Name</label>
                                    <div class="col-8">
                                        <span class="customer-info form-control form-control-sm bg-light">{{ $customer->business_name }}</span>
                                        <input type="text" class="form-control form-control-sm customer-edit d-none" name="business_name" value="{{ $customer->business_name }}" required>
                                    </div>
                                </div>
                                <div class="row py-1">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Created</label>
                                    <div class="col-8">
                                        <div class="form-control form-control-sm bg-light">{{ $customer->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                                @if($recentInteractions->isNotEmpty())
                                <div class="row py-1">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Last Interaction</label>
                                    <div class="col-8">
                                        <div class="form-control form-control-sm bg-light">{{ $recentInteractions->first()->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0">Contact Details</h6>
                            </div>
                            <div class="card-body p-1">
                                <div class="row py-1" data-field="name">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Name</label>
                                    <div class="col-8">
                                        <span class="customer-info form-control form-control-sm bg-light">{{ $customer->name }}</span>
                                        <input type="text" class="form-control form-control-sm customer-edit d-none" name="name" value="{{ $customer->name }}" required>
                                    </div>
                                </div>
                                <div class="row py-1" data-field="email">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Email</label>
                                    <div class="col-8">
                                        <span class="customer-info form-control form-control-sm bg-light">
                                            <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                                        </span>
                                        <input type="email" class="form-control form-control-sm customer-edit d-none" name="email" value="{{ $customer->email }}" required>
                                    </div>
                                </div>
                                <div class="row py-1" data-field="phone">
                                    <label class="col-4 col-form-label col-form-label-sm fw-bold">Phone</label>
                                    <div class="col-8">
                                        <span class="customer-info form-control form-control-sm bg-light">
                                            <a href="tel:{{ $customer->phone }}">{{ $customer->phone }}</a>
                                        </span>
                                        <input type="text" class="form-control form-control-sm customer-edit d-none" name="phone" value="{{ $customer->phone }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Deals Section -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Deals for {{ $customer->name }}</h5>
        </div>
        <div class="card-body">
            @if($customer->deals->isEmpty())
                <p class="text-muted">No deals found for this customer.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Quotes</th>
                                <th>Orders</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->deals as $deal)
                            <tr>
                                <td>{{ $deal->id }}</td>
                                <td>{{ $deal->title ?? 'No title' }}</td>
                                <td>
                                    <span class="badge bg-{{ $deal->status === Deal::STATUS_CLOSED ? 'secondary' : 'primary' }}">
                                        {{ $deal->status }}
                                    </span>
                                </td>
                                <td>{{ $deal->quotes->count() }}</td>
                                <td>{{ $deal->orders->count() }}</td>
                                <td>{{ $deal->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('deal.show', ['id' => $deal->id]) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('deal.edit', ['id' => $deal->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Quotes Section -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Quotes for {{ $customer->name }}</h5>
        </div>
        <div class="card-body">
            @if($customer->quotes->isEmpty())
                <p class="text-muted">No quotes found for this customer.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vehicle</th>
                                <th>Finance Type</th>
                                <th>Monthly Payment</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->quotes as $quote)
                            <tr>
                                <td>{{ $quote->id }}</td>
                                <td>{{ $quote->vehicle->make }} {{ $quote->vehicle->model }}</td>
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

    <!-- Orders Section -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Orders for {{ $customer->name }}</h5>
        </div>
        <div class="card-body">
            @if($customer->orders->isEmpty())
                <p class="text-muted">No orders found for this customer.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vehicle</th>
                                <th>Finance Type</th>
                                <th>Monthly Payment</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->vehicle->make }} {{ $order->vehicle->model }}</td>
                                <td>{{ $order->finance_type }}</td>
                                <td>£{{ number_format($order->monthly_payment, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'confirmed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('order.viewByToken', ['token' => $order->token]) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('order.review', ['id' => $order->id]) }}" class="btn btn-sm btn-warning">Review</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Customer Interactions Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h2 class="mb-0 h5">Latest Customer Interactions</h2>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date/Time</th>
                                <th>Quote</th>
                                <th>Action</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInteractions as $interaction)
                                <tr>
                                    <td>{{ $interaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('quote.review', ['id' => $interaction->quote->id]) }}">
                                            Quote #{{ $interaction->quote->id }}
                                        </a>
                                    </td>
                                    <td>
                                        @switch($interaction->event_type)
                                            @case('view')
                                                <span class="badge bg-info">Viewed Quote</span>
                                                @break
                                            @case('sent')
                                                <span class="badge bg-primary">Email Sent</span>
                                                @break
                                            @case('resent')
                                                <span class="badge bg-warning">Email Resent</span>
                                                @break
                                            @case('confirm')
                                                <span class="badge bg-success">Confirmed</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($interaction->event_type) }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $interaction->ip_address }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No interactions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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
                <p>Are you sure you want to delete the customer: <strong id="customerNameToDelete"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
                @if(!$customer->quotes->isEmpty())
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> This customer has associated quotes. You cannot delete a customer with existing quotes.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCustomerForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" {{ !$customer->quotes->isEmpty() ? 'disabled' : '' }}>Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Existing delete modal code
        const deleteModal = document.getElementById('deleteConfirmModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const customerId = button.getAttribute('data-customer-id');
                const customerName = button.getAttribute('data-customer-name');
                const customerNameElement = deleteModal.querySelector('#customerNameToDelete');
                customerNameElement.textContent = customerName;
                const deleteForm = deleteModal.querySelector('#deleteCustomerForm');
                deleteForm.action = '/customer/' + customerId;
            });
        }
        
        // Auto-activate edit mode if edit parameter is present in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('edit') === 'true') {
            toggleEditMode();
        }
    });

    // Original values for cancel functionality
    let originalValues = {};

    function toggleEditMode() {
        const infoElements = document.querySelectorAll('.customer-info');
        const editElements = document.querySelectorAll('.customer-edit');
        const editBtn = document.getElementById('editCustomerBtn');
        const saveBtn = document.getElementById('saveCustomerBtn');
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
        const editElements = document.querySelectorAll('.customer-edit');
        editElements.forEach(input => {
            input.value = originalValues[input.name];
        });
        toggleEditMode();
    }

    function saveCustomerDetails() {
        const form = document.getElementById('customerForm');
        const formData = new FormData(form);
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Add method spoofing for PUT request
        formData.append('_method', 'PUT');

        fetch(`/customer/{{ $customer->id }}`, {
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
                    throw new Error(data.message || JSON.stringify(data.errors) || 'Error updating customer');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Safely update displayed values
                const updateField = (fieldName, value, linkType = null) => {
                    const container = document.querySelector(`[data-field="${fieldName}"]`);
                    if (container) {
                        const infoSpan = container.querySelector('.customer-info');
                        if (infoSpan) {
                            if (linkType) {
                                const link = infoSpan.querySelector('a');
                                if (link) {
                                    link.textContent = value;
                                    link.href = `${linkType}:${value}`;
                                }
                            } else {
                                infoSpan.textContent = value;
                            }
                        }
                    }
                };

                updateField('name', formData.get('name'));
                updateField('business_name', formData.get('business_name'));
                updateField('email', formData.get('email'), 'mailto');
                updateField('phone', formData.get('phone'), 'tel');

                // Remove any existing alerts
                const existingAlerts = document.querySelectorAll('.alert');
                existingAlerts.forEach(alert => alert.remove());

                // Create and insert success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show';
                alert.innerHTML = `
                    ${data.message || 'Customer details updated successfully!'}
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
                ${error.message || 'Error updating customer details. Please try again.'}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Insert alert into the alertContainer
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.appendChild(alert);
        });
    }
</script>
@endsection
