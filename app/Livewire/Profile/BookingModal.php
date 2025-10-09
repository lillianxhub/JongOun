<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class BookingModal extends Component
{
    public $isOpen = false;
    public $booking = null;

    // protected $listeners = ['openBookingModal'];

    #[On('openBookingModal')]
    public function openBookingModal($bookingId)
    {
        Log::info('Modal opened for booking ID: ' . $bookingId);

        try {
            $this->booking = Booking::with(['user', 'room', 'instruments'])
                ->findOrFail($bookingId);

            $this->isOpen = true;
            Log::info('Booking details loaded successfully.');
        } catch (\Exception $e) {
            Log::error('Error loading booking details: ' . $e->getMessage());
            session()->flash('error', 'Unable to load booking details.');
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->booking = null;
    }

    // public function getStatusColorClass($status)
    // {
    //     return match ($status) {
    //         'approved' => 'bg-green-100 text-green-700',
    //         'cancelled' => 'bg-red-100 text-red-700',
    //         default => 'bg-yellow-100 text-yellow-700',
    //     };
    // }

    public function render()
    {
        return view('livewire.profile.booking-modal');
    }
}
