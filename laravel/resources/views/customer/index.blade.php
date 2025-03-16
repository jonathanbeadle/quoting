@extends('layouts.app')

@section('title', 'All Customers')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>All Customers ({{ $customers->total() }})</h1>
        <div class="d-flex align-items-center">
            {{ $customers->links() }}
            <form action="{{ route('customer.index') }}" method="GET" class="d-flex ms-3">
                <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary">Search</button>
            </form>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 5%">ID</th>
                    <th style="width: 25%">Name</th>
                    <th style="width: 25%">Email</th>
                    <th style="width: 20%">Phone</th>
                    <th style="width: 25%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td class="text-nowrap">{{ $customer->name }}</td>
                    <td class="text-nowrap">{{ $customer->email }}</td>
                    <td class="text-nowrap">{{ $customer->phone }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('customer.show', ['id' => $customer->id]) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('customer.show', ['id' => $customer->id, 'edit' => 'true']) }}" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteConfirmModal" 
                                data-customer-id="{{ $customer->id }}" 
                                data-customer-name="{{ $customer->name }}">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        <a href="{{ route('customer.create') }}" class="btn btn-primary">Create New Customer</a>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCustomerForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
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
