@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm mt-5">
                <div class="card-body">
                    <!-- Logo Above Form -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Fleethub Logo" class="img-fluid" style="max-width: 180px;">
                    </div>
                    
                    <div class="mb-4 text-center text-secondary">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success mb-3">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Forgot Password Form -->
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        
                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <a class="text-decoration-none" href="{{ route('login') }}">
                                {{ __('Back to Login') }}
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                {{ __('Send Reset Link') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('hide_navbar', true)
