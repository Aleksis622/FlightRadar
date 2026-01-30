<?php
use App\Http\Controllers\MapController;
use App\Http\Controllers\FlightApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MapController::class, 'index']);
Route::get('/flights', [FlightApiController::class, 'index']);