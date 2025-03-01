<!DOCTYPE html>
<html>
<head>
    <title>Fleethub Quoting App</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS: Global styles for pages -->
    <link rel="stylesheet" href="{{ asset('css/quote.css') }}">
    <style>
        body {
            padding-top: 0px; /* reduced padding since no navbar */
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Main Container -->
    <div class="container">
        @yield('content')
    </div>
    
    <!-- Bootstrap JS Bundle CDN (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
