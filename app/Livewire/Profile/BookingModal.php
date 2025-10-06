<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class BookingModal extends Component
{
    public $isOpen = false;
    public $booking = null;

    protected $listeners = ['openBookingModal'];

    public function openBookingModal($bookingId)
    {
        // โหลดข้อมูล booking พร้อม relationships
        $this->booking = \App\Models\Booking::with(['user', 'room', 'instruments'])
            ->findOrFail($bookingId);

        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->booking = null;
    }

    public function getStatusColorClass($status)
    {
        return match($status) {
            'approved' => 'bg-green-100 text-green-700',
            'canceled' => 'bg-red-100 text-red-700',
            default => 'bg-yellow-100 text-yellow-700',
        };
    }
    
    public function render()
    {
        return view('livewire.profile.booking-modal');
    }
}
