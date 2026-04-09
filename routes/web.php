<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SimulatorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PriceChartController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Simulator routes
    Route::get('/simulator/form', [SimulatorController::class, 'form'])->name('simulator.form');
    Route::post('/simulator/analyze', [SimulatorController::class, 'analyze'])->name('simulator.analyze');
    Route::get('/simulator/history', [SimulatorController::class, 'history'])->name('simulator.history');
    Route::get('/simulator/result/{simulation}', [SimulatorController::class, 'result'])->name('simulator.result');
    Route::get('/simulator/pdf/{simulation}', [SimulatorController::class, 'pdf'])->name('simulator.pdf');
    Route::post('/simulator/pdf-multiple', [SimulatorController::class, 'pdfMultiple'])->name('simulator.pdf.multiple');

    // Customer routes
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::patch('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'delete'])->name('customers.delete');
    Route::post('/customers/{customer}/contracts', [CustomerController::class, 'storeContract'])->name('customers.contracts.store');
    Route::post('/customers/{customer}/shipments', [CustomerController::class, 'addShipment'])->name('customers.shipments.add');

    // Price Chart routes
    Route::get('/price-chart', [PriceChartController::class, 'index'])->name('price_chart.index');
});

require __DIR__ . '/auth.php';
