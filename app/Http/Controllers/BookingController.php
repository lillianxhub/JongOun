<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    // สำหรับ page load ปกติ
    public function booking(){
        $userId = auth()->id();
        $bookings = Booking::with('room')->where('user_id', $userId)->get();

        // ไม่เปิด modal ตอนแรก
        $selectedBooking = null;
        $showModal = false;

        return view('profile.bookings', compact('bookings', 'selectedBooking', 'showModal'));
    }
}
