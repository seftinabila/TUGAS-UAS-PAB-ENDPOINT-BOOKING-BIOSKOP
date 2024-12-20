<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\ApiBookingController;
use App\Http\Controllers\Api\ApiPaymentController;
use App\Http\Controllers\Api\ApiSeatController;
use App\Http\Controllers\Api\ApiFilmController;
Use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\AuthController;



Route::post('/payment/{bookingId}', [PaymentController::class, 'createPaymentLink']);
Route::post('/payment/notification', [PaymentController::class, 'handleNotification']);

Route::middleware('api')->prefix('v1')->group(function () {

    Route::get('/debug', function () {
        \Log::info('Debug endpoint hit!');
        return response()->json(['message' => 'Debug working']);
    });

    // Booking API
    Route::middleware(['auth:sanctum', 'user'])->post('/bookings/store', [ApiBookingController::class, 'store']);
    Route::middleware(['admin'])->get('/bookings', [ApiBookingController::class, 'index']);


    // Payment API
    Route::post('/payments/{bookingId}', [ApiPaymentController::class, 'createPaymentLink']);

    // Cek ketersediaan kursi
    Route::get('/seats/availability/{screeningId}', [ApiSeatController::class, 'checkAvailability']);

    // Jadwal film
    Route::get('/films/show', [ApiFilmController::class, 'getFilms']);

    Route::middleware(['auth:sanctum', 'user'])->get('/films/{filmId}/schedules', [ApiFilmController::class, 'getSchedules']);

    // Endpoint untuk pendaftaran pengguna
    Route::post('/register', [AuthController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

});

