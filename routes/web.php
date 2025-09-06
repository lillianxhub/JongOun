<?php


use Illuminate\Support\Facades\Route;
use App\Models\Booking;
use App\Http\Controllers\AdminController;

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

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/booking', function () {
        return view('booking');
    })->name('booking');

    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->middleware('admin')
        ->name('dashboard');

    Route::get('/profile/bookings', function () {
        $bookings = Booking::with('room')->where('user_id', auth()->id())->latest()->get();
        return view('profile.bookings', compact('bookings'));
    })->name('profile.bookings');

    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');
});
