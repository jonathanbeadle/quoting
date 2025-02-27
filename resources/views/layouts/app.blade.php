<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name', 'Fleethub Quoting App') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            padding-top: 70px;
        }
        /* Full-width wrapper for navbar */
        .navbar-container {
            width: 100vw;
        }
        /* Limit inner navbar width to 1296px and center it */
        .navbar-container .container {
            max-width: 1296px;
        }
        /* Increase bottom padding on the button group to extend the hover area */
        .navbar .btn-group {
            position: relative;
            padding-bottom: 4px; /* Adjust this value as needed */
            padding-top: 4px; /* Adjust this value as needed */
        }
        /* Ensure the dropdown menu is directly below the button group without extra margin */
        .navbar .dropdown-menu {
            display: none;
            position: absolute;
            top: 80%; /* Immediately below the parent */
            left: 0;
            margin: 0;
            padding-top: 0;
        }
        /* Show the dropdown when hovering over the button group or dropdown menu itself */
        @media (hover: hover) and (pointer: fine) {
            .navbar .btn-group:hover > .dropdown-menu,
            .navbar .btn-group > .dropdown-menu:hover {
                display: block !important;
            }
        }
        .navbar .btn-group .dropdown-toggle-split:hover {
            border-top-right-radius: 0.25rem !important;
            border-bottom-right-radius: 0.25rem !important;
        }
        /* Ensure consistent layout regarding scrollbars */
        html {
            overflow-y: scroll;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar-container">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
          <a class="navbar-brand" href="{{ url('/') }}">Fleethub Quoting App</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" 
                  aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
              <!-- Quotes Split Dropdown -->
              <li class="nav-item">
                <div class="btn-group">
                  <a href="{{ route('quote.index') }}" class="nav-link">Quotes</a>
                  <button type="button" class="btn dropdown-toggle dropdown-toggle-split text-light" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('quote.create') }}">New Quote</a></li>
                  </ul>
                </div>
              </li>
              <!-- Customers Split Dropdown -->
              <li class="nav-item">
                <div class="btn-group">
                  <a href="{{ route('customer.index') }}" class="nav-link">Customers</a>
                  <button type="button" class="btn dropdown-toggle dropdown-toggle-split text-light" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('customer.create') }}">Add New Customer</a></li>
                  </ul>
                </div>
              </li>
              <!-- Vehicles Split Dropdown -->
              <li class="nav-item">
                <div class="btn-group">
                  <a href="{{ route('vehicle.index') }}" class="nav-link">Vehicles</a>
                  <button type="button" class="btn dropdown-toggle dropdown-toggle-split text-light" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('vehicle.create') }}">Add Vehicle</a></li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
    
    <!-- Main Container -->
    <div class="container">
        @yield('content')
    </div>
    
    <!-- Bootstrap JS Bundle CDN (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
