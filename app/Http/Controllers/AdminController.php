<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Room;
use App\Models\Instrument;

class AdminController extends Controller
{

   private function getStats()
   {
        return [
            'totalBookings' => Booking::count(),
            'totalUsers' => User::count(),
            'pendingBookings' => Booking::where('status', 'pending')->count(),
            'totalRooms' => Room::count(),
            'totalInstrument' => Instrument::count(),
        ];
   } 
   
   protected function triggerBookingScheduler() 
   {
        $now = now();
        
        $bookings = Booking::with('instruments')
            ->where('status', 'approved')
            ->whereRaw("CONCAT(date, ' ' , end_time) <= ?", [$now])
            ->get();

        foreach ($bookings as $booking) {
            foreach ($booking->instruments as $instrument) {
                // Increment the stock of each instrument
                $instrument->increment('stock', $instrument->pivot->quantity);
            }
            // Update the booking status to 'finished'
            $booking->update(['status' => 'finished']);
        }
   }

    public function dashboard()
    {
        $this->triggerBookingScheduler(); 

        $stats = $this->getStats();

        $recentBookings = Booking::with('user', 'room')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', array_merge($stats, [
            'recentBookings' => $recentBookings
        ]));
    }
    public function bookings()
    {
        $stats = $this->getStats();

        $bookings = Booking::with('user', 'room')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.bookings', array_merge($stats, [
            'bookings' => $bookings
        ],
        ));
    }
    
    public function instrument()
    {
        $stats = $this->getStats();

        return view('admin.instrument', $stats);
    }
}
