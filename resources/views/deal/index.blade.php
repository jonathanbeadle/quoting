@php use App\Models\Deal; @endphp

@extends('layouts.app')

@section('title', 'All Deals')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>All Deals ({{ $viewType === 'list' ? $deals->total() : collect($dealsByStatus)->flatten()->count() }})</h1>
        <div class="d-flex align-items-center">
            @if($viewType === 'list')
                {{ $deals->links() }}
            @endif
            <div class="d-flex me-3">
                <a href="{{ route('deal.index', ['view' => 'list']) }}" class="btn btn-sm {{ $viewType === 'list' ? 'btn-primary' : 'btn-outline-secondary' }} me-1">
                    <i class="bi bi-list-ul"></i> List
                </a>
                <a href="{{ route('deal.index', ['view' => 'kanban']) }}" class="btn btn-sm {{ $viewType === 'kanban' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="bi bi-kanban"></i> Pipeline
                </a>
            </div>
            <form action="{{ route('deal.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary">Search</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div id="kanban-error" class="alert alert-danger alert-dismissible fade show d-none">
        <span id="error-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    @if($viewType === 'list')
        <!-- List View -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th style="width: 5%">ID</th>
                        <th style="width: 20%">Customer</th>
                        <th style="width: 20%">Title</th>
                        <th style="width: 10%">Status</th>
                        <th style="width: 10%">Quotes</th>
                        <th style="width: 10%">Orders</th>
                        <th style="width: 10%">Created</th>
                        <th style="width: 15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deals as $deal)
                    <tr>
                        <td>{{ $deal->id }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('customer.show', ['id' => $deal->customer->id]) }}">
                                {{ $deal->customer->name }}
                            </a>
                        </td>
                        <td>{{ $deal->title }}</td>
                        <td>
                            <span class="badge bg-{{ $deal->status === Deal::STATUS_CLOSED ? 'secondary' : 'primary' }}">
                                {{ $deal->status }}
                            </span>
                        </td>
                        <td>{{ $deal->quotes->count() }}</td>
                        <td>{{ $deal->orders->count() }}</td>
                        <td>{{ $deal->created_at->format('d/m/Y') }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('deal.show', ['id' => $deal->id]) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('deal.edit', ['id' => $deal->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <a href="{{ route('deal.create') }}" class="btn btn-primary">Create New Deal</a>
        </div>
    @else
        <!-- Kanban View -->
        <div class="kanban-board">
            <div class="row flex-nowrap overflow-auto pb-3">
                @foreach(Deal::getStatuses() as $status)
                    <div class="col-md-3 col-sm-4" style="min-height: 600px;">
                        <div class="kanban-column card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center kanban-column-header {{ str_replace(' ', '-', strtolower($status)) }}-header">
                                <h5 class="mb-0">{{ $status }}</h5>
                                <span class="badge bg-light text-dark rounded-pill">{{ count($dealsByStatus[$status]) }}</span>
                            </div>
                            <div class="card-body p-2" data-status="{{ $status }}" id="column-{{ str_replace(' ', '-', strtolower($status)) }}">
                                @foreach($dealsByStatus[$status] as $deal)
                                    @php
                                        // Define the border color based on the deal status
                                        $borderColor = match($deal->status) {
                                            'Initial Enquiry' => '#6c757d',
                                            'Quote Sent' => '#0dcaf0',
                                            'Quote Accepted' => '#198754',
                                            'Finance Process' => '#ffc107',
                                            'Order Sent' => '#0d6efd',
                                            'Order Accepted' => '#198754',
                                            'Order Process' => '#ffc107',
                                            'Closed' => '#adb5bd',
                                            default => '#6c757d',
                                        };
                                    @endphp
                                    <div class="kanban-item card mb-2" 
                                         data-deal-id="{{ $deal->id }}"
                                         data-status="{{ $deal->status }}"
                                         id="deal-{{ $deal->id }}"
                                         style="border-left: 6px solid {{ $borderColor }}; transition: all 0.3s ease;"
                                         onmouseover="this.style.boxShadow='0 5px 15px rgba(0,0,0,0.15)'; this.style.transform='translateY(-4px)';"
                                         onmouseout="this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)'; this.style.transform='translateY(0)';">
                                        <div class="card-header p-2 d-flex justify-content-between align-items-center bg-light border-bottom">
                                            <h6 class="mb-0 deal-title fw-bold text-truncate me-1">{{ $deal->title ?? 'Deal #' . $deal->id }}</h6>
                                            <a href="{{ route('deal.show', ['id' => $deal->id]) }}" target="_blank" class="btn btn-sm btn-outline-secondary p-1">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <!-- Customer information with icon -->
                                                <div class="d-flex align-items-center flex-grow-1">
                                                    <div class="customer-icon d-flex align-items-center justify-content-center me-2" style="background-color: rgba(13,110,253,0.1); border-radius: 50%; width: 28px; height: 28px;">
                                                        <i class="bi bi-person-circle text-primary"></i>
                                                    </div>
                                                    <div class="customer-details">
                                                        <p class="small mb-0 fw-semibold text-truncate">{{ $deal->customer->name }}</p>
                                                        <p class="small text-muted mb-0 text-truncate">{{ $deal->customer->business_name }}</p>
                                                    </div>
                                                </div>
                                                <!-- Quotes count -->
                                                <div class="ms-2">
                                                    <span class="badge bg-info rounded-pill" title="{{ $deal->quotes->count() }} Quotes">
                                                        <i class="bi bi-file-text"></i> {{ $deal->quotes->count() }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Date information -->
                                            <div class="deal-dates d-flex justify-content-between border-top pt-1 mt-1">
                                                <span class="small text-muted">
                                                    <i class="bi bi-calendar-plus me-1"></i>{{ $deal->created_at->format('d M Y') }}
                                                </span>
                                                @if($deal->updated_at->gt($deal->created_at))
                                                    <span class="small text-muted">
                                                        <i class="bi bi-arrow-clockwise me-1"></i>{{ $deal->updated_at->diffForHumans(null, true) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@section('styles')
@if($viewType === 'kanban')
<style>
    /* Main container styles */
    .container-fluid {
        padding-bottom: 0;
    }
    
    /* Kanban specific sizing */
    .kanban-board {
        min-height: 600px !important;
    }
    
    .kanban-column {
        min-height: 600px !important;
        height: 600px !important;
        display: flex;
        flex-direction: column;
    }
    
    .kanban-column .card-body {
        min-height: 550px !important;
        height: calc(600px - 50px) !important;
        overflow-y: auto;
        padding: 0.75rem;
    }
    
    /* Column styling */
    .kanban-column-header {
        padding: 0.75rem;
        position: sticky;
        top: 0;
        z-index: 1;
    }
    
    /* Make sure column widths are consistent */
    .row.flex-nowrap > .col-md-3 {
        min-width: 300px;
    }
    
    /* Item styles with strong color coding */
    .kanban-item {
        cursor: grab;
        transition: all 0.3s ease;
        margin-bottom: 0.75rem;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left-width: 6px;
        border-left-style: solid;
    }
    
    .kanban-item:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        transform: translateY(-4px);
    }
    
    .kanban-item:active {
        cursor: grabbing;
    }
    
    /* Status-specific styles */
    .kanban-item[data-status="Initial Enquiry"] {
        border-left-color: #6c757d;
    }
    
    .kanban-item[data-status="Quote Sent"] {
        border-left-color: #0dcaf0;
    }
    
    .kanban-item[data-status="Quote Accepted"] {
        border-left-color: #198754;
    }
    
    .kanban-item[data-status="Finance Process"] {
        border-left-color: #ffc107;
    }
    
    .kanban-item[data-status="Order Sent"] {
        border-left-color: #0d6efd;
    }
    
    .kanban-item[data-status="Order Accepted"] {
        border-left-color: #198754;
    }
    
    .kanban-item[data-status="Order Process"] {
        border-left-color: #ffc107;
    }
    
    .kanban-item[data-status="Closed"] {
        border-left-color: #adb5bd;
    }
    
    /* Card content styling */
    .kanban-item .card-header {
        padding: 0.5rem;
        background-color: rgba(248, 249, 250, 0.7);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .kanban-item .deal-title {
        font-weight: 600;
        color: #343a40;
    }
    
    .kanban-item .customer-info {
        background-color: rgba(240, 240, 240, 0.3);
        padding: 8px;
        border-radius: 4px;
        margin-bottom: 10px;
        transition: background-color 0.2s;
    }
    
    .kanban-item:hover .customer-info {
        background-color: rgba(240, 240, 240, 0.5);
    }
    
    .kanban-item .customer-icon {
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 50%;
        width: 32px;
        height: 32px;
    }
    
    .kanban-item .deal-stats {
        margin: 10px 0;
    }
    
    .kanban-item .deal-dates {
        font-size: 0.8rem;
        border-top: 1px solid #f0f0f0;
        padding-top: 8px;
        margin-top: 8px;
    }
    
    /* Column header colors */
    .initial-enquiry-header {
        background-color: #e2e3e5; /* Light gray */
    }
    .quote-sent-header {
        background-color: #cff4fc; /* Light blue */
    }
    .quote-accepted-header {
        background-color: #d1e7dd; /* Light green */
    }
    .finance-process-header {
        background-color: #fff3cd; /* Light yellow */
    }
    .order-sent-header {
        background-color: #cfe2ff; /* Light primary */
    }
    .order-accepted-header {
        background-color: #d1e7dd; /* Light green */
    }
    .order-process-header {
        background-color: #fff3cd; /* Light yellow */
    }
    .closed-header {
        background-color: #f8f9fa; /* Light white */
    }
    
    /* Drag & drop styles */
    .dragging {
        opacity: 0.6;
        border: 2px dashed #0d6efd;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .drag-over {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .placeholder {
        border: 2px dashed #ced4da;
        height: 100px;
        margin-bottom: 0.75rem;
        border-radius: 6px;
    }
    
    /* Make notification toast prettier */
    .notification-toast {
        border-radius: 8px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        z-index: 9999;
    }
</style>
@endif
@endsection

@section('scripts')
@if($viewType === 'kanban')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    let draggingElement = null;
    let placeholderElement = null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Get all kanban items
    const kanbanItems = document.querySelectorAll('.kanban-item');
    const columns = document.querySelectorAll('.kanban-column .card-body');
    
    // Add event listeners to each kanban item
    kanbanItems.forEach(item => {
        item.addEventListener('dragstart', handleDragStart);
        item.addEventListener('dragend', handleDragEnd);
        item.setAttribute('draggable', 'true');
        
        // Add event to prevent dropping on items
        item.addEventListener('dragover', preventDragOver);
        item.addEventListener('drop', preventDrop);
    });
    
    // Add event listeners to each column
    columns.forEach(column => {
        column.addEventListener('dragover', handleDragOver);
        column.addEventListener('dragenter', handleDragEnter);
        column.addEventListener('dragleave', handleDragLeave);
        column.addEventListener('drop', handleDrop);
    });
    
    // Prevent dropping on kanban items
    function preventDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    }
    
    function preventDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    }
    
    // Drag start event handler
    function handleDragStart(e) {
        draggingElement = this;
        draggingElement.classList.add('dragging');
        
        // Create placeholder element
        placeholderElement = document.createElement('div');
        placeholderElement.className = 'placeholder';
        placeholderElement.style.height = `${this.offsetHeight}px`;
        
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.outerHTML);
        setTimeout(() => {
            this.style.display = 'none';
        }, 0);
    }
    
    // Drag end event handler
    function handleDragEnd(e) {
        if (draggingElement) {
            draggingElement.classList.remove('dragging');
            draggingElement.style.display = 'block';
            draggingElement = null;
            
            // Remove placeholder if it exists
            if (placeholderElement && placeholderElement.parentNode) {
                placeholderElement.parentNode.removeChild(placeholderElement);
            }
        }
    }
    
    // Drag over event handler
    function handleDragOver(e) {
        e.preventDefault();
        
        // Check if target is a kanban item
        if (e.target.closest('.kanban-item') && e.target !== draggingElement) {
            return false;
        }
        
        e.dataTransfer.dropEffect = 'move';
        return false;
    }
    
    // Drag enter event handler
    function handleDragEnter(e) {
        // Prevent drag enter if the target is a kanban item
        if (e.target.closest('.kanban-item') && e.target !== draggingElement) {
            return false;
        }
        
        this.classList.add('drag-over');
        
        // Insert placeholder if it exists
        if (placeholderElement && !this.contains(placeholderElement)) {
            this.appendChild(placeholderElement);
        }
    }
    
    // Drag leave event handler
    function handleDragLeave(e) {
        this.classList.remove('drag-over');
    }
    
    // Drop event handler
    function handleDrop(e) {
        e.stopPropagation();
        e.preventDefault();
        
        // Check if drop target is a kanban item
        if (e.target.closest('.kanban-item') && e.target !== draggingElement) {
            return false;
        }
        
        this.classList.remove('drag-over');
        
        // Remove placeholder if it exists
        if (placeholderElement && placeholderElement.parentNode) {
            placeholderElement.parentNode.removeChild(placeholderElement);
        }
        
        if (draggingElement) {
            // Get the column's status and the deal ID
            const newStatus = this.dataset.status;
            const dealId = draggingElement.dataset.dealId;
            
            // Send AJAX request to update status
            updateDealStatus(dealId, newStatus, draggingElement, this);
        }
        
        return false;
    }
    
    // Function to update deal status via AJAX
    function updateDealStatus(dealId, newStatus, dealElement, columnElement) {
        console.log('Updating deal', dealId, 'to status:', newStatus);
        
        // Create a form element instead of FormData for more reliable submission
        const form = document.createElement('form');
        form.method = 'POST';
        form.style.display = 'none';
        
        // Create input for CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        // Create input for status
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = newStatus;
        form.appendChild(statusInput);
        
        // Append form to body
        document.body.appendChild(form);
        
        // Create FormData from the form
        const formData = new FormData(form);
        
        // Remove the form
        document.body.removeChild(form);
        
        // Send the AJAX request
        fetch(`/deal/${dealId}/status`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Update response:', data);
            
            // Update the border color based on new status
            const borderColor = getBorderColorForStatus(newStatus);
            dealElement.style.borderLeftColor = borderColor;
            
            // Update the data-status attribute
            dealElement.dataset.status = newStatus;
            
            // Move the element to the new column
            columnElement.appendChild(dealElement);
            dealElement.style.display = 'block';
            
            // Update the counts
            updateColumnCounts();
        })
        .catch(error => {
            console.error('Error:', error);
            // Still move the card to maintain visual consistency
            columnElement.appendChild(dealElement);
            dealElement.style.display = 'block';
            updateColumnCounts();
            
            // Show error notification only for actual errors
            showNotification('error', 'Failed to update deal status. Please refresh the page.');
        });
    }
    
    // Function to get border color based on status
    function getBorderColorForStatus(status) {
        return {
            'Initial Enquiry': '#6c757d',
            'Quote Sent': '#0dcaf0',
            'Quote Accepted': '#198754',
            'Finance Process': '#ffc107',
            'Order Sent': '#0d6efd',
            'Order Accepted': '#198754',
            'Order Process': '#ffc107',
            'Closed': '#adb5bd'
        }[status] || '#6c757d';
    }
    
    // Function to update column counts
    function updateColumnCounts() {
        const columns = document.querySelectorAll('.kanban-column');
        columns.forEach(column => {
            const columnBody = column.querySelector('.card-body');
            const count = columnBody.querySelectorAll('.kanban-item').length;
            const status = columnBody.dataset.status;
            const headerBadge = column.querySelector('.badge');
            if (headerBadge) {
                headerBadge.textContent = count;
            }
        });
    }
    
    // Function to show error message
    function showError(message) {
        const errorDiv = document.getElementById('kanban-error');
        const errorMessage = document.getElementById('error-message');
        
        errorMessage.textContent = message;
        errorDiv.classList.remove('d-none');
        
        setTimeout(() => {
            errorDiv.classList.add('d-none');
        }, 5000);
    }
    
    // Function to show notification
    function showNotification(type, message) {
        // Create notification
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} notification-toast`;
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        `;
        
        // Style the notification
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.minWidth = '300px';
        notification.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
        
        // Add to body
        document.body.appendChild(notification);
        
        // Remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }
});
</script>
@endif
@endsection