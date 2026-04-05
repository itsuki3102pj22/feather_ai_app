<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SimulatorController;
use App\Http\Controllers\PriceChartController;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/simulator', [SimulatorController::class, 'form']);
Route::post('/simulator/analyze', [SimulatorController::class, 'analyze']);
Route::post('/simulator/pdf', [SimulatorController::class, 'pdf']);
Route::get('/simulator/history', [SimulatorController::class, 'history']);
Route::get('/price-chart', [PriceChartController::class, 'index']);
Route::post('/price-chart/store', [PriceChartController::class, 'store']);
Route::post('/price-chart/comment', [PriceChartController::class, 'comment']);
Route::get('/customers', [CustomerController::class, 'index']);
Route::post('/customers', [CustomerController::class, 'store']);
Route::post('/customers/contract', [CustomerController::class, 'storeContract']);
Route::post('/customers/ship', [CustomerController::class, 'addShipment']);

