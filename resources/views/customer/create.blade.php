@extends('layouts.app')

@section('title', 'Add New Customer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Add New Customer</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('customer.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Customer Name*</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="business_name" class="form-label">Business Name*</label>
                            <input type="text" name="business_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number*</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address*</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Add Customer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
