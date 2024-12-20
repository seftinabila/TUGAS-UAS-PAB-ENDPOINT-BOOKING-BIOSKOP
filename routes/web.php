<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ScreeningController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\BookingHomeController;
use App\Http\Controllers\PaymentController;




Route::get('/', function () {
    return view('auth.login');
});
Route::get('/checkout/{bookingId}', [PaymentController::class, 'checkout'])->name('checkout');
Route::get('/payments/{bookingId}', [PaymentController::class, 'showPaymentMethods'])->name('payments.show');
Route::post('/payments/{bookingId}', [PaymentController::class, 'createPaymentLink'])->name('payments.create');
Route::get('/payments/redirect/{paymentId}', [PaymentController::class, 'redirectToMidtrans'])->name('payment.redirect');
Route::post('/payments/notification', [PaymentController::class, 'handleNotification'])->name('payments.notification');
Route::get('home', [MainController::class, 'index'])->name('home');
Route::get('/bookings/create/{filmId}', [MainController::class, 'create'])->name('booking-tiket.create');
Route::post('/bookings-tiket', [MainController::class, 'store'])->name('bookings-tiket.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Route::get('/api/users', [UserController::class, 'index']);
// Route::get('/api/films', [FilmController::class, 'index']);
// Route::post('/api/films', [FilmController::class, 'store']);
// Route::post('/api/bookings', [BookingController::class, 'store']);
Route::resource('users', UserController::class);
Route::resource('films', FilmController::class);
Route::resource('screenings', ScreeningController::class);
Route::resource('bookings', BookingController::class);


require __DIR__.'/auth.php';
