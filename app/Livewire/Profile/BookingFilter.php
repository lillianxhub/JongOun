<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingFilter extends Component
{
    public $filter = 'all'; // default filter

    public function setFilter($status)
    {
        $this->filter = $status;
    }

    public function render()
    {
        $bookings = Booking::where('user_id', Auth::id());

        if ($this->filter !== 'all') {
            $bookings->where('status', $this->filter);
        }

        $bookings = $bookings->orderBy('date', 'desc')->get();

        return view('livewire.profile.booking-filter', compact('bookings'));
    }
}
