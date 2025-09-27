<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Booking;
use App\Models\User;
use App\Models\Room;

class Bookings extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $statusFilter = '';
    public $dateRange = '';

    public $totalBookings;
    public $totalUsers;
    public $pendingBookings;
    public $totalRooms;

    public $showModal = false;
    public $selectedBooking;

    protected $paginationTheme = 'tailwind';

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
    }

    public function updating($field, $value)
    {
        if (in_array($field, ['search', 'statusFilter', 'dateRange', 'perPage'])) {
            $this->resetPage();
        }
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingDateRange()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->dateRange = '';
        $this->resetPage();
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

    public function getBookingsProperty()
    {
        return Booking::with(['user', 'room'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('user', function ($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                        ->orWhereHas('room', function ($roomQuery) {
                            $roomQuery->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhere('band_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->dateRange, function ($query) {
                $query->whereDate('date', $this->dateRange);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.admin.bookings', [
            'recentBookings' => $this->bookings,
        ]);
    }
}
