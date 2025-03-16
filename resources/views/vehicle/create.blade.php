@extends('layouts.app')

@section('title', 'Add New Vehicle')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Add New Vehicle</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('vehicle.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="make" class="form-label">Make*</label>
                            <input type="text" name="make" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="model" class="form-label">Model*</label>
                            <input type="text" name="model" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="specification" class="form-label">Specification*</label>
                            <textarea name="specification" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="transmission" class="form-label">Transmission*</label>
                            <select name="transmission" class="form-select" required>
                                <option value="Manual">Manual</option>
                                <option value="Automatic">Automatic</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fuel_type" class="form-label">Fuel Type*</label>
                            <select name="fuel_type" class="form-select" required>
                                <option value="Diesel">Diesel</option>
                                <option value="Petrol">Petrol</option>
                                <option value="Hybrid">Hybrid</option>
                                <option value="Electric">Electric</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="registration_status" class="form-label">Registration Status*</label>
                            <select name="registration_status" class="form-select" required id="registrationStatus">
                                <option value="New">New</option>
                                <option value="Pre-Registered">Pre-Registered</option>
                                <option value="Used">Used</option>
                            </select>
                        </div>
                        <div class="mb-3" id="registrationDateDiv" style="display:none;">
                            <label for="registration_date" class="form-label">Registration Date</label>
                            <input type="date" name="registration_date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="additional_options" class="form-label">Additional Options</label>
                            <textarea name="additional_options" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="dealer_fit_options" class="form-label">Dealer Fit Options</label>
                            <textarea name="dealer_fit_options" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="colour" class="form-label">Colour*</label>
                            <input type="text" name="colour" class="form-control" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Add Vehicle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('registrationStatus').addEventListener('change', function() {
        var regDateDiv = document.getElementById('registrationDateDiv');
        if (this.value === 'Pre-Registered') {
            regDateDiv.style.display = 'block';
        } else {
            regDateDiv.style.display = 'none';
        }
    });
</script>
@endsection
