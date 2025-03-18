

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
<div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Dashboard</h1>
        <form action="<?php echo e(route('dashboard')); ?>" method="GET" class="d-flex ms-3">
            <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="<?php echo e(request('search')); ?>">
            <button type="submit" class="btn btn-sm btn-primary">Search</button>
        </form>
    </div>
    
    <?php if(isset($searchResults) && $searchResults !== null): ?>
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h2 class="mb-0 h5">Search Results</h2>
            </div>
            <div class="card-body">
                <?php if($searchResults->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Business Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $searchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($customer->name); ?></td>
                                        <td><?php echo e($customer->business_name); ?></td>
                                        <td><?php echo e($customer->email); ?></td>
                                        <td><?php echo e($customer->phone); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('customer.show', ['id' => $customer->id])); ?>" class="btn btn-sm btn-info">View</a>
                                            <a href="<?php echo e(route('quote.create')); ?>?customer_id=<?php echo e($customer->id); ?>" class="btn btn-sm btn-primary">New Quote</a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No customers found matching your search criteria.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Summary Cards - First Row -->
    <div class="row mb-4">
        <!-- Total Quotes -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">
                    <i class="bi bi-file-text me-2"></i>Total Quotes
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($totalQuotes); ?></h5>
                    <a href="<?php echo e(route('quote.index')); ?>" class="btn btn-light btn-sm">View All Quotes</a>
                </div>
            </div>
        </div>
        <!-- Total Orders -->
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">
                    <i class="bi bi-cart me-2"></i>Total Orders
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($totalOrders); ?></h5>
                    <a href="<?php echo e(route('order.index')); ?>" class="btn btn-light btn-sm">View All Orders</a>
                </div>
            </div>
        </div>
        <!-- Confirmed Orders -->
        <div class="col-md-4">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-header">
                    <i class="bi bi-check-circle me-2"></i>Confirmed Orders
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($confirmedOrders); ?></h5>
                    <a href="<?php echo e(route('order.index')); ?>" class="btn btn-light btn-sm">View Confirmed</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards - Second Row -->
    <div class="row mb-4">
        <!-- Quotes Sent -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">
                    <i class="bi bi-envelope me-2"></i>Quotes Sent
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($sentQuotes); ?></h5>
                    <a href="<?php echo e(route('quote.index')); ?>" class="btn btn-light btn-sm">View Sent Quotes</a>
                </div>
            </div>
        </div>
        <!-- Total Customers -->
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">
                    <i class="bi bi-people me-2"></i>Total Customers
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($totalCustomers); ?></h5>
                    <a href="<?php echo e(route('customer.index')); ?>" class="btn btn-light btn-sm">View Customers</a>
                </div>
            </div>
        </div>
        <!-- Total Vehicles -->
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">
                    <i class="bi bi-truck me-2"></i>Total Vehicles
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($totalVehicles); ?></h5>
                    <a href="<?php echo e(route('vehicle.index')); ?>" class="btn btn-light btn-sm">View Vehicles</a>
                </div>
            </div>
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
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $customerInteractions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($interaction->created_at->format('d/m/Y H:i')); ?></td>
                                    <td><?php echo e($interaction->quote->customer->name); ?></td>
                                    <td><?php echo e($interaction->quote->vehicle->make); ?> <?php echo e($interaction->quote->vehicle->model); ?></td>
                                    <td>
                                        <?php switch($interaction->event_type):
                                            case ('view'): ?>
                                                <span class="badge bg-info">Viewed Quote</span>
                                                <?php break; ?>
                                            <?php case ('confirm'): ?>
                                                <span class="badge bg-success">Confirmed Order</span>
                                                <?php break; ?>
                                            <?php default: ?>
                                                <span class="badge bg-secondary"><?php echo e(ucfirst($interaction->event_type)); ?></span>
                                        <?php endswitch; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No customer interactions found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Quotes Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0 h5">Recent Quotes</h2>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Contract Length</th>
                                <th>Monthly Payment</th>
                                <th>Created At</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $recentQuotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($quote->id); ?></td>
                                <td><?php echo e($quote->customer->name); ?></td>
                                <td><?php echo e($quote->vehicle->make); ?> <?php echo e($quote->vehicle->model); ?></td>
                                <td><?php echo e($quote->contract_length); ?> months</td>
                                <td><?php echo e($quote->monthly_payment); ?></td>
                                <td><?php echo e($quote->created_at->format('Y-m-d')); ?></td>
                                <td class="text-right">
                                    <a href="<?php echo e(route('quote.view', ['token' => $quote->token])); ?>" class="btn btn-sm btn-info" target="_blank">View</a>
                                    <a href="<?php echo e(route('quote.review', ['id' => $quote->id])); ?>" class="btn btn-sm btn-warning">Review</a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <a href="<?php echo e(route('quote.index')); ?>" class="btn btn-primary">View All Quotes</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h2 class="mb-0 h5">Recent Orders</h2>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Finance Type</th>
                                <th>Monthly Payment</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>000<?php echo e($order->id); ?></td>
                                <td><?php echo e($order->customer->name ?? 'N/A'); ?></td>
                                <td><?php echo e($order->vehicle->make ?? ''); ?> <?php echo e($order->vehicle->model ?? ''); ?></td>
                                <td><?php echo e($order->finance_type); ?></td>
                                <td>£<?php echo e(number_format($order->monthly_payment, 2)); ?></td>
                                <td><?php echo e($order->status); ?></td>
                                <td><?php echo e($order->created_at->format('d/m/Y')); ?></td>
                                <td class="text-right">
                                    <a href="<?php echo e(route('order.view', $order->id)); ?>" class="btn btn-sm btn-info" target="_blank">View</a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <a href="<?php echo e(route('order.index')); ?>" class="btn btn-info">View All Orders</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Customers Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h2 class="mb-0 h5">Recent Customers</h2>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Business Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $recentCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($customer->id); ?></td>
                                <td><?php echo e($customer->name); ?></td>
                                <td><?php echo e($customer->business_name); ?></td>
                                <td><?php echo e($customer->email); ?></td>
                                <td><?php echo e($customer->phone); ?></td>
                                <td class="text-right">
                                    <a href="<?php echo e(route('customer.show', ['id' => $customer->id])); ?>" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <a href="<?php echo e(route('customer.index')); ?>" class="btn btn-warning">View All Customers</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Vehicles Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h2 class="mb-0 h5">Recent Vehicles</h2>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Make &amp; Model</th>
                                <th>Specification</th>
                                <th>Transmission</th>
                                <th>Fuel Type</th>
                                <th>Registration Status</th>
                                <th>Colour</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $recentVehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($vehicle->id); ?></td>
                                <td><?php echo e($vehicle->make); ?> <?php echo e($vehicle->model); ?></td>
                                <td><?php echo e($vehicle->specification); ?></td>
                                <td><?php echo e($vehicle->transmission); ?></td>
                                <td><?php echo e($vehicle->fuel_type); ?></td>
                                <td><?php echo e($vehicle->registration_status); ?></td>
                                <td><?php echo e($vehicle->colour); ?></td>
                                <td class="text-right">
                                    <a href="<?php echo e(route('vehicle.show', ['id' => $vehicle->id])); ?>" class="btn btn-sm btn-info" target="_blank">View</a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <a href="<?php echo e(route('vehicle.index')); ?>" class="btn btn-danger">View All Vehicles</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Jonny\Desktop\production\quoting\laravel\resources\views/home.blade.php ENDPATH**/ ?>