<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\User;
use App\Models\Room;
use Livewire\Component;
use Livewire\Attributes\On;

class Dashboard extends Component
{
    public $totalBookings;
    public $totalUsers;
    public $pendingBookings;
    public $totalRooms;
    public $recentBookings;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalBookings = Booking::count();
        $this->totalUsers = User::count();
        $this->pendingBookings = Booking::where('status', 'pending')->count();
        $this->totalRooms = Room::count();
        $this->recentBookings = Booking::with('user', 'room')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    // Listen for events from other components
    #[On('booking-created')]
    #[On('booking-updated')]
    #[On('booking-deleted')]
    #[On('user-created')]
    #[On('user-updated')]
    #[On('user-deleted')]
    #[On('room-created')]
    #[On('room-updated')]
    #[On('room-deleted')]
    public function refreshStats()
    {
        $this->loadStats();
    }

    // Auto refresh every 30 seconds
    public function refreshData()
    {
        $this->loadStats();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
