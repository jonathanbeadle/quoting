<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="/">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Fleethub Logo" style="height: 40px;">
        </a>

        <!-- Hamburger -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if(auth()->guard()->check()): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('deal.*') ? 'active' : ''); ?>" href="<?php echo e(route('deal.index')); ?>">
                            <i class="bi bi-briefcase"></i> Deals
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('quote.*') ? 'active' : ''); ?>" href="<?php echo e(route('quote.index')); ?>">
                            <i class="bi bi-file-text"></i> Quotes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('order.*') ? 'active' : ''); ?>" href="<?php echo e(route('order.index')); ?>">
                            <i class="bi bi-cart"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('customer.*') ? 'active' : ''); ?>" href="<?php echo e(route('customer.index')); ?>">
                            <i class="bi bi-people"></i> Customers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('vehicle.*') ? 'active' : ''); ?>" href="<?php echo e(route('vehicle.index')); ?>">
                            <i class="bi bi-truck"></i> Vehicles
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Right Side Navigation -->
            <ul class="navbar-nav">
                <?php if(auth()->guard()->check()): ?>
                    <!-- Add New Dropdown -->
                    <li class="nav-item dropdown me-3">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="addNewDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-plus-circle"></i> Add New
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="addNewDropdown">
                             <li>
                                <a class="dropdown-item" href="<?php echo e(route('deal.create')); ?>">
                                    <i class="bi bi-briefcase"></i> New Deal
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('quote.create')); ?>">
                                    <i class="bi bi-file-text"></i> New Quote
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('customer.create')); ?>">
                                    <i class="bi bi-person-plus"></i> New Customer
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('vehicle.create')); ?>">
                                    <i class="bi bi-truck"></i> New Vehicle
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- User Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?php echo e(Auth::user()->name); ?>

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">
                                    <i class="bi bi-gear"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('login')); ?>" class="nav-link">
                            <i class="bi bi-box-arrow-in-right"></i> Log in
                        </a>
                    </li>
                    <!-- Registration link removed -->
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH C:\Users\Jonny\Desktop\production\quoting\laravel\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>