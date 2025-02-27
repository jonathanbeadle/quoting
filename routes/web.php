<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CustomerController;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Quote routes
Route::get('/quote/create', [QuoteController::class, 'create'])->name('quote.create');
Route::post('/quote', [QuoteController::class, 'store'])->name('quote.store');
Route::get('/quote/review/{id}', [QuoteController::class, 'review'])->name('quote.review');
Route::post('/quote/send/{id}', [QuoteController::class, 'send'])->name('quote.send');
Route::get('/quote/view/{id}', [QuoteController::class, 'view'])->name('quote.view');
Route::get('/quotes', [QuoteController::class, 'index'])->name('quote.index');

// Customer routes
Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');
Route::get('/customers', [CustomerController::class, 'index'])->name('customer.index');
Route::get('/customer/{id}', [CustomerController::class, 'show'])->name('customer.show');
Route::get('/customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
Route::put('/customer/{id}', [CustomerController::class, 'update'])->name('customer.update');

// Vehicle routes
Route::get('/vehicle/create', [VehicleController::class, 'create'])->name('vehicle.create');
Route::post('/vehicle', [VehicleController::class, 'store'])->name('vehicle.store');
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicle.index');
Route::get('/vehicle/{id}', [VehicleController::class, 'show'])->name('vehicle.show');
Route::get('/vehicle/{id}/edit', [VehicleController::class, 'edit'])->name('vehicle.edit');
Route::put('/vehicle/{id}', [VehicleController::class, 'update'])->name('vehicle.update');
