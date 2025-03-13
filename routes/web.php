<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
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
    Route::get('/quote/confirm/{id}', [QuoteController::class, 'confirm'])->name('quote.confirm');
    Route::post('/quote/status/{id}', [QuoteController::class, 'updateStatus'])->name('quote.updateStatus');
    Route::get('/quotes', [QuoteController::class, 'index'])->name('quote.index');

    // Order routes
    Route::get('/order/create-from-quote/{quoteId}', [OrderController::class, 'createFromQuote'])->name('order.createFromQuote');
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/{id}', [OrderController::class, 'view'])->name('order.view');
    Route::get('/order/{id}/edit', [OrderController::class, 'edit'])->name('order.edit');
    Route::put('/order/{id}', [OrderController::class, 'update'])->name('order.update');
    Route::post('/order/{id}/confirm', [OrderController::class, 'confirm'])->name('order.confirm');

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
});

// Public quote view route (accessible without auth)
Route::get('/quote/view/{token}', [QuoteController::class, 'view'])->name('quote.view');
Route::get('/order/token/{token}', [OrderController::class, 'viewByToken'])->name('order.viewByToken');

// Authentication routes
require __DIR__.'/auth.php';
