@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1 class="display-4 mb-4">Welcome to {{ config('app.name', 'Laravel') }}</h1>
            
            @guest
                <p class="lead mb-4">Please login or register to continue.</p>
                <div class="mb-4">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg mx-2">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg mx-2">Register</a>
                    @endif
                </div>
            @else
                <p class="lead mb-4">Welcome back! You can proceed to your dashboard.</p>
                <div class="mb-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Go to Dashboard</a>
                </div>
            @endguest
        </div>
    </div>
</div>
@endsection
