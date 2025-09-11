<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Booking;
use App\Models\User;
use App\Models\Room;

class Bookings extends Component
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
            ->take(10)
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

    #[On('approve')]
    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'approved']);
        $this->loadStats();

        $this->dispatch('swal:success', [
            'title' => 'Approved!',
            'text' => 'Booking approved successfully',
            'icon' => 'success',
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

    #[On('cancel')]
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
        return view('livewire.admin.bookings');
    }
}
