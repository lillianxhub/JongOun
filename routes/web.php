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

// Route::get('/', function () {
//     return view('index');
// })->name('home');

Route::get('/', [IndexController::class, 'index'])->name('home');

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
// routes/web.php
// Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('admin.dashboard');
//     })->name('dashboard');

//     Route::get('/bookings', [Bookings::class])->name('bookings');
    // Route::get('/users', [AdminUserController::class, 'index'])->name('users');
    // Route::get('/rooms', [AdminRoomController::class, 'index'])->name('rooms');
    // Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
    // Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings');
// });
