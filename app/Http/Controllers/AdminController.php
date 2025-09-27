<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Room;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalBookings = Booking::count();
        $totalUsers = User::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $totalRooms = Room::count();

        $recentBookings = Booking::with('user', 'room')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBookings',
            'totalUsers',
            'pendingBookings',
            'totalRooms',
            'recentBookings'
        ));
    }
    public function bookings()
    {
        $totalBookings = Booking::count();
        $totalUsers = User::count();
        $totalRooms = Room::count();

        $bookings = Booking::with('user', 'room')->orderBy('created_at', 'desc')->get();
        return view('admin.bookings', compact(
            'totalBookings',
            'totalRooms',
            'bookings'
        ));
    }
}
