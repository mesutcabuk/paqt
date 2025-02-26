<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\TaxiController;

// Resident API Endpoints
Route::get('/residents', [ResidentController::class, 'index']);
Route::get('/residents/{id}', [ResidentController::class, 'show']);
Route::put('/residents/{id}', [ResidentController::class, 'update']);
Route::delete('/residents/{id}', [ResidentController::class, 'delete']);
Route::get('/residents/{id}/budget', [ResidentController::class, 'getBudget']);
Route::post('/residents/{id}/reset-budget', [ResidentController::class, 'resetBudget']);

// Ride API Endpoints
Route::post('/rides/book', [RideController::class, 'bookRide']);
Route::get('/rides/{id}', [RideController::class, 'show']);
Route::put('/rides/{id}', [RideController::class, 'update']);
Route::post('/rides/{id}/cancel', [RideController::class, 'cancelRide']);
Route::get('/rides/resident/{residentId}', [RideController::class, 'getRidesByResident']);
Route::get('/rides/taxi/{taxiId}', [RideController::class, 'getRidesByTaxi']);

// Taxi API Endpoints
Route::get('/taxis', [TaxiController::class, 'index']);
Route::get('/taxis/{company_id}/rides', [TaxiController::class, 'getRides']);
Route::get('/taxis/parcel/{parcel_id}', [TaxiController::class, 'getTaxiByParcel']);
Route::get('/taxis/postcode/{postcode}', [TaxiController::class, 'getTaxiByPostcode']);

