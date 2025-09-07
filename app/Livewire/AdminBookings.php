<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;
use App\Models\User;
use App\Models\Room;

class AdminBookings extends Component
{
    public $totalBookings;
    public $totalUsers;
    public $pendingBookings;
    public $totalRooms;
    public $recentBookings;

    public $showModal = false;
    public $selectedBooking;

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
            ->get();
    }

    public function showDetails($id)
    {
        $this->selectedBooking = Booking::with('user', 'room')->findOrFail($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedBooking = null;
    }

    public function confirmApprove($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => 'You want to approve this booking?',
            'icon' => 'warning',
            'method' => 'approve',
            'id' => $id,
            'color' => 'green'
        ]);
    }

    public function confirmCancel($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => 'You want to cancel this booking?',
            'icon' => 'warning',
            'method' => 'cancel',
            'id' => $id,
            'color' => 'red'
        ]);
    }

    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'approved']);
        $this->loadStats();

        $this->dispatch('swal:success', [
            'title' => 'Approved!',
            'text' => 'Booking approved successfully',
            'icon' => 'success',
            'color' => 'green'
        ]);
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'canceled']);
        $this->loadStats();

        $this->dispatch('swal:success', [
            'title' => 'Canceled!',
            'text' => 'Booking canceled successfully',
            'icon' => 'error',
            'color' => 'red',
        ]);
    }

    public function render()
    {
        return view('livewire.admin-bookings');
    }
}
