<?php

use Illuminate\Support\Facades\Route;
use App\Models\Booking;
use App\Http\Controllers\AdminController;
use App\Livewire\Admin\Bookings;
use App\Http\Controllers\IndexController;

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

// Public routes
Route::get('/', [IndexController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // User routes (accessible to all authenticated users)
    Route::get('/booking', function () {
        return view('booking');
    })->name('booking');

    Route::get('/profile/bookings', function () {
        $bookings = Booking::with('room')->where('user_id', auth()->id())->latest()->get();
        return view('profile.bookings', compact('bookings'));
    })->name('profile.bookings');

    Route::get('/profile', function () {
        return view('profile.show');
    })->name('user.profile');

    // Admin routes (only accessible to admin users)
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // You can add more admin routes here:
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
        // Route::get('/rooms', [AdminController::class, 'rooms'])->name('rooms');
        // Route::get('/users', [AdminController::class, 'users'])->name('users');
    });
});
