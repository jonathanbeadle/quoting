@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Customer Details</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <p><strong>ID:</strong> {{ $customer->id }}</p>
    <p><strong>Name:</strong> {{ $customer->name }}</p>
    <p><strong>Business Name:</strong> {{ $customer->business_name }}</p>
    <p><strong>Email:</strong> {{ $customer->email }}</p>
    <p><strong>Phone:</strong> {{ $customer->phone }}</p>

    <!-- Action buttons -->
    <div class="mb-4">
        <a href="{{ route('quote.create') }}?customer_id={{ $customer->id }}" class="btn btn-primary">Quote Customer</a>
        <a href="{{ route('customer.edit', ['id' => $customer->id]) }}" class="btn btn-warning">Edit Customer</a>
        <button class="btn btn-danger" 
                data-bs-toggle="modal" 
                data-bs-target="#deleteConfirmModal" 
                data-customer-id="{{ $customer->id }}" 
                data-customer-name="{{ $customer->name }}">
            Delete Customer
        </button>
    </div>

    <h2>Quotes for {{ $customer->name }}</h2>
    @if($customer->quotes->isEmpty())
        <p>No quotes found for this customer.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vehicle</th>
                    <th>Finance Type</th>
                    <th>Contract Length</th>
                    <th>Annual Mileage</th>
                    <th>Deposit</th>
                    <th>Monthly Payment</th>
                    <th>Maintenance</th>
                    <th>Document Fee</th>
                    <th>Sent</th>
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
                    <td>{{ $quote->contract_length }} months</td>
                    <td>{{ $quote->annual_mileage }}</td>
                    <td>{{ $quote->deposit }}</td>
                    <td>{{ $quote->monthly_payment }}</td>
                    <td>{{ $quote->maintenance ? 'Yes' : 'No' }}</td>
                    <td>{{ $quote->document_fee }}</td>
                    <td>{{ $quote->sent ? 'Yes' : 'No' }}</td>
                    <td>{{ $quote->created_at->format('Y-m-d') }}</td>
                    <td>
                    <a href="{{ route('quote.view', ['token' => $quote->token]) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('quote.review', ['id' => $quote->id]) }}" class="btn btn-sm btn-warning">Review</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <a href="{{ route('customer.index') }}" class="btn btn-secondary">Back to Customers List</a>
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
        // Set up the delete confirmation modal
        const deleteModal = document.getElementById('deleteConfirmModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                // Button that triggered the modal
                const button = event.relatedTarget;
                
                // Extract customer info
                const customerId = button.getAttribute('data-customer-id');
                const customerName = button.getAttribute('data-customer-name');
                
                // Update the modal's content
                const customerNameElement = deleteModal.querySelector('#customerNameToDelete');
                customerNameElement.textContent = customerName;
                
                // Update form action URL
                const deleteForm = deleteModal.querySelector('#deleteCustomerForm');
                deleteForm.action = '/customer/' + customerId;
            });
        }
    });
</script>
@endsection
