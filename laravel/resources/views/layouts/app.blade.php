<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Colors CSS -->
    <link href="{{ asset('css/custom-colors.css') }}" rel="stylesheet">
    
    <!-- Custom CSS to prevent modal content shift -->
    <style>
        html {
            overflow-y: scroll;
        }
        
        body {
            padding-right: 0 !important;
        }
        
        body.modal-open {
            overflow-y: hidden;
            padding-right: 0 !important;
        }
        
        .modal {
            padding-right: 0 !important;
        }
        
        /* Navigation active item highlight */
        .nav-item .active {
            background:rgb(243, 243, 243);
            border-radius: 4px;
        }
        
        /* Optional: Style the scrollbar to be less obtrusive */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body>
    @include('layouts.navigation')
    <main class="container py-4">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </main>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Yield scripts section for page-specific JavaScript -->
    @yield('scripts')
</body>
</html>
