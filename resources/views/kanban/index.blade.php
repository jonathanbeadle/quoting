@php use App\Models\Deal; @endphp

@extends('layouts.app')

@section('title', 'Deal Pipeline')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Deal Pipeline</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('deal.create') }}" class="btn btn-primary">Create New Deal</a>
            <a href="{{ route('deal.index') }}" class="btn btn-outline-secondary">List View</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div id="kanban-error" class="alert alert-danger alert-dismissible fade show d-none">
        <span id="error-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="kanban-board">
        <div class="row flex-nowrap overflow-auto pb-3">
            @foreach(Deal::getStatuses() as $status)
                <div class="col-md-3 col-sm-4">
                    <div class="kanban-column card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center kanban-column-header {{ str_replace(' ', '-', strtolower($status)) }}-header">
                            <h5 class="mb-0">{{ $status }}</h5>
                            <span class="badge bg-light text-dark rounded-pill">{{ count($dealsByStatus[$status]) }}</span>
                        </div>
                        <div class="card-body p-2" data-status="{{ $status }}" id="column-{{ str_replace(' ', '-', strtolower($status)) }}">
                            @foreach($dealsByStatus[$status] as $deal)
                                <div class="kanban-item card mb-2" data-deal-id="{{ $deal->id }}" id="deal-{{ $deal->id }}">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 deal-title">{{ $deal->title ?? 'Deal #' . $deal->id }}</h6>
                                            <a href="{{ route('deal.show', ['id' => $deal->id]) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                        </div>
                                        <p class="small mb-1 text-truncate">
                                            <i class="bi bi-person"></i> {{ $deal->customer->name }}
                                        </p>
                                        @if($deal->quotes->count() > 0)
                                            <p class="small mb-1">
                                                <i class="bi bi-file-text"></i> {{ $deal->quotes->count() }} quote(s)
                                            </p>
                                        @endif
                                        @if($deal->orders->count() > 0)
                                            <p class="small mb-1">
                                                <i class="bi bi-cart"></i> {{ $deal->orders->count() }} order(s)
                                            </p>
                                        @endif
                                        <p class="small text-muted mb-0">
                                            <i class="bi bi-calendar"></i> {{ $deal->updated_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .kanban-board {
        min-height: calc(100vh - 200px);
    }
    .kanban-column {
        min-height: calc(100vh - 200px);
    }
    .kanban-column-header {
        padding: 0.75rem;
    }
    .kanban-item {
        cursor: grab;
        transition: box-shadow 0.2s;
        border-left: 4px solid #dee2e6;
    }
    .kanban-item:hover {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .kanban-item:active {
        cursor: grabbing;
    }
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
    .dragging {
        opacity: 0.6;
        border: 2px dashed #0d6efd;
    }
    .drag-over {
        background-color: rgba(13, 110, 253, 0.1);
    }
    .placeholder {
        border: 2px dashed #ced4da;
        height: 100px;
        margin-bottom: 0.5rem;
    }
</style>
@endsection

@section('scripts')
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
    });
    
    // Add event listeners to each column
    columns.forEach(column => {
        column.addEventListener('dragover', handleDragOver);
        column.addEventListener('dragenter', handleDragEnter);
        column.addEventListener('dragleave', handleDragLeave);
        column.addEventListener('drop', handleDrop);
    });
    
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
        e.dataTransfer.dropEffect = 'move';
        return false;
    }
    
    // Drag enter event handler
    function handleDragEnter(e) {
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
        fetch('/kanban/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                dealId: dealId,
                newStatus: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Move the element to the new column
                columnElement.appendChild(dealElement);
                dealElement.style.display = 'block';
                
                // Update the counts
                updateColumnCounts();
            } else {
                // Show error and revert the drag operation
                showError(data.message);
                dealElement.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while updating the deal status.');
            dealElement.style.display = 'block';
        });
    }
    
    // Function to update column counts
    function updateColumnCounts() {
        columns.forEach(column => {
            const count = column.querySelectorAll('.kanban-item').length;
            const status = column.dataset.status;
            const countBadge = document.querySelector(`.kanban-column-header.${status.toLowerCase().replace(' ', '-')}-header .badge`);
            if (countBadge) {
                countBadge.textContent = count;
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
});
</script>
@endsection