<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DealController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home route - redirects to dashboard if authenticated
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard/Home
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Quote routes
    Route::get('/quote/create', [QuoteController::class, 'create'])->name('quote.create');
    Route::post('/quote', [QuoteController::class, 'store'])->name('quote.store');
    Route::get('/quote/review/{id}', [QuoteController::class, 'review'])->name('quote.review');
    Route::post('/quote/send/{id}', [QuoteController::class, 'send'])->name('quote.send');
    Route::post('/quote/status/{id}', [QuoteController::class, 'updateStatus'])->name('quote.updateStatus');
    Route::get('/quotes', [QuoteController::class, 'index'])->name('quote.index');
    Route::get('/quotes/{id}/edit', [QuoteController::class, 'edit'])->name('quote.edit');
    Route::put('/quotes/{id}', [QuoteController::class, 'update'])->name('quote.update');
    Route::post('/quotes/{id}/duplicate', [QuoteController::class, 'duplicate'])->name('quote.duplicate');

    // Order routes
    Route::get('/order/create-from-quote/{quoteId}', [OrderController::class, 'createFromQuote'])->name('order.createFromQuote');
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/{id}', [OrderController::class, 'view'])->name('order.view');
    Route::get('/order/{id}/edit', [OrderController::class, 'edit'])->name('order.edit');
    Route::post('/order/{id}/send', [OrderController::class, 'send'])->name('order.send');
    Route::get('/order/view/{token}', [OrderController::class, 'viewByToken'])->name('order.viewByToken');
    Route::put('/order/{id}', [OrderController::class, 'update'])->name('order.update');
    Route::post('/order/{id}/confirm', [OrderController::class, 'confirm'])->name('order.confirm');
    Route::get('/order/review/{id}', [OrderController::class, 'review'])->name('order.review');
    Route::post('/order/status/{id}', [OrderController::class, 'updateStatus'])->name('order.updateStatus');
    Route::post('/order/{id}/duplicate', [OrderController::class, 'duplicate'])->name('order.duplicate');

    // Customer routes
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/{id}', [CustomerController::class, 'show'])->name('customer.show');
    Route::get('/customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('/customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');

    // Vehicle routes
    Route::get('/vehicle/create', [VehicleController::class, 'create'])->name('vehicle.create');
    Route::post('/vehicle', [VehicleController::class, 'store'])->name('vehicle.store');
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicle.index');
    Route::get('/vehicle/{id}', [VehicleController::class, 'show'])->name('vehicle.show');
    Route::get('/vehicle/{id}/edit', [VehicleController::class, 'edit'])->name('vehicle.edit');
    Route::put('/vehicle/{id}', [VehicleController::class, 'update'])->name('vehicle.update');
    Route::delete('/vehicle/{id}', [VehicleController::class, 'destroy'])->name('vehicle.destroy');

    // Deal routes
    Route::get('/deals', [DealController::class, 'index'])->name('deal.index');
    Route::get('/deal/create', [DealController::class, 'create'])->name('deal.create');
    Route::post('/deal', [DealController::class, 'store'])->name('deal.store');
    Route::get('/deal/{id}', [DealController::class, 'show'])->name('deal.show');
    Route::get('/deal/{id}/edit', [DealController::class, 'edit'])->name('deal.edit');
    Route::put('/deal/{id}', [DealController::class, 'update'])->name('deal.update');
    Route::post('/deal/{id}/status', [DealController::class, 'updateStatus'])->name('deal.updateStatus');
});

// Public quote view route (accessible without auth)
Route::get('/quote/view/{token}', [QuoteController::class, 'view'])->name('quote.view');
Route::get('/quote/confirm/{id}', [QuoteController::class, 'confirm'])->name('quote.confirm');
Route::get('/order/token/{token}', [OrderController::class, 'viewByToken'])->name('order.viewByToken');
Route::get('/order/confirm/{id}', [OrderController::class, 'confirm'])->name('order.confirm');

// Email preview routes - FOR DEVELOPMENT ONLY
if (app()->environment('local')) {
    Route::get('/email/preview/quote', function () {
        // Create sample data that matches what your real email would receive
        $customer = new App\Models\Customer();
        $customer->name = "Sample Customer";
        
        $vehicle = new App\Models\Vehicle();
        $vehicle->make = "Sample Make";
        $vehicle->model = "Sample Model";
        $vehicle->specification = "Sample Specification";
        
        $quote = new App\Models\Quote();
        $quote->vehicle = $vehicle;
        $quote->finance_type = "Business Contract Hire";
        $quote->monthly_payment = 299.99;
        $quote->created_at = now();
        
        $quoteUrl = "https://example.com/quote/123";
        
        // Return the email template with sample data
        return view('emails.quote', [
            'customer' => $customer,
            'quote' => $quote,
            'quoteUrl' => $quoteUrl
        ]);
    });
}

// Authentication routes
require __DIR__.'/auth.php';
