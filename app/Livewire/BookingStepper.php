<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;
use App\Models\Booking;
use App\Models\RoomType;
use Carbon\Carbon;

class BookingStepper extends Component
{
    public $step = 1;

    // Step 1
    public $date;

    // Step 2
    public $room_id;
    public $start_time;
    public $end_time;
    public $room_types = [];
    public $rooms = [];
    public $selectedTypeId = null;
    public $selectedType = null;
    public $selectedRoom = null;
    public $selectedTime = null;
    public $availableTimes = [];

    // Step 3
    public $name;
    public $email;
    public $phone;
    public $band_name;
    public $members;
    public $additional_request;

    public $total_price = 0;

    public $currentMonth;
    public $currentYear;
    public $selectedDate = null;
    public $calendarDays = [];

    public function mount()
    {
        $now = now();
        $this->currentMonth = $now->month;
        $this->currentYear = $now->year;
        $this->generateCalendar();

        $this->room_types = RoomType::with('rooms')->get();
        $this->rooms = Room::all();
        if (auth()->check()) {
            $this->name = auth()->user()->name;
            $this->email = auth()->user()->email;
            // Assuming phone is a field in users table
            $this->phone = auth()->user()->phone ?? '';
        }
    }

    public function generateCalendar()
    {
        $firstDayOfMonth = now()->setMonth($this->currentMonth)->setYear($this->currentYear)->startOfMonth();
        $daysInMonth = $firstDayOfMonth->daysInMonth;
        $startDayOfWeek = $firstDayOfMonth->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
        $calendar = [];
        $week = [];
        // Fill previous month's days
        $prevMonth = $firstDayOfMonth->copy()->subMonth();
        $prevMonthDays = $prevMonth->daysInMonth;
        for ($i = 1; $i < $startDayOfWeek; $i++) {
            $week[] = [
                'day' => $prevMonthDays - $startDayOfWeek + 1 + $i,
                'month' => $prevMonth->month,
                'year' => $prevMonth->year,
                'other' => true
            ];
        }
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $week[] = [
                'day' => $day,
                'month' => $this->currentMonth,
                'year' => $this->currentYear,
                'other' => false
            ];
            if (count($week) == 7) {
                $calendar[] = $week;
                $week = [];
            }
        }
        // Fill next month's days
        if (count($week)) {
            $nextMonth = $firstDayOfMonth->copy()->addMonth();
            $nextDay = 1;
            while (count($week) < 7) {
                $week[] = [
                    'day' => $nextDay++,
                    'month' => $nextMonth->month,
                    'year' => $nextMonth->year,
                    'other' => true
                ];
            }
            $calendar[] = $week;
        }
        $this->calendarDays = $calendar;
    }

    public function prevMonth()
    {
        $now = now();
        if ($this->currentYear < $now->year || ($this->currentYear == $now->year && $this->currentMonth <= $now->month)) {
            // Already at current month/year, do nothing
            return;
        }
        if ($this->currentMonth == 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        } else {
            $this->currentMonth--;
        }
        $this->generateCalendar();
    }

    public function nextMonth()
    {
        if ($this->currentMonth == 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        } else {
            $this->currentMonth++;
        }
        $this->generateCalendar();
    }

    public function selectDay($year, $month, $day)
    {
        $date = now()->setYear($year)->setMonth($month)->setDay($day)->format('Y-m-d');
        $this->selectedDate = $date;
        $this->date = $date;
        $this->nextStep();
    }

    public function nextStep()
    {
        if ($this->step == 2) $this->calculatePrice();
        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= 3) {
            $this->step = $step;
        }
    }

    public function calculatePrice()
    {
        if ($this->room_id && $this->start_time && $this->end_time) {
            $room = Room::find($this->room_id);
            $hours = (strtotime($this->end_time) - strtotime($this->start_time)) / 3600;
            $this->total_price = $room->price * max($hours, 1);
        }
    }

    public function selectRoomType($typeId)
    {
        $this->selectedTypeId = $typeId;
        $this->selectedType = RoomType::with('rooms')->find($typeId);
        // Reset room selection when type changes
        $this->selectedRoom = null;
        $this->start_time = null;
        $this->end_time = null;
        $this->availableTimes = [];
    }

    public function selectRoom($roomId)
    {
        $this->selectedRoom = Room::find($roomId);
        $this->availableTimes = $this->selectedRoom->available_times
            ? json_decode($this->selectedRoom->available_times, true)
            : [];

        // Reset time selection when room changes
        $this->start_time = null;
        $this->end_time = null;
    }

    /**
     * Check if a time slot is available for booking
     */
    public function isTimeSlotAvailable($startTime, $endTime, $roomId = null, $date = null)
    {
        $roomId = $roomId ?? $this->room_id ?? ($this->selectedRoom ? $this->selectedRoom->id : null);
        $date = $date ?? $this->date ?? $this->selectedDate;

        if (!$roomId || !$date) {
            return false;
        }

        // Check for overlapping bookings
        $conflictingBookings = Booking::where('room_id', $roomId)
            ->where('date', $date)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    // New booking starts during existing booking
                    $q->where('start_time', '<=', $startTime)
                        ->where('end_time', '>', $startTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // New booking ends during existing booking
                    $q->where('start_time', '<', $endTime)
                        ->where('end_time', '>=', $endTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // New booking completely contains existing booking
                    $q->where('start_time', '>=', $startTime)
                        ->where('end_time', '<=', $endTime);
                });
            })
            ->exists();

        return !$conflictingBookings;
    }

    /**
     * Get all booked time slots for the selected date and room
     */
    public function getBookedTimeSlots($roomId = null, $date = null)
    {
        $roomId = $roomId ?? ($this->selectedRoom ? $this->selectedRoom->id : null);
        $date = $date ?? $this->date ?? $this->selectedDate;

        if (!$roomId || !$date) {
            return collect();
        }

        return Booking::where('room_id', $roomId)
            ->where('date', $date)
            ->where('status', '!=', 'cancelled')
            ->select('start_time', 'end_time')
            ->get();
    }

    /**
     * Check if a specific time is available for start time selection
     */
    public function isStartTimeAvailable($time)
    {
        if (!$this->selectedRoom || !$this->selectedDate) {
            return true;
        }

        $bookedSlots = $this->getBookedTimeSlots();

        foreach ($bookedSlots as $slot) {
            // Check if the selected time falls within any booked slot
            if ($time >= $slot->start_time && $time < $slot->end_time) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if a specific time is available for end time selection
     */
    public function isEndTimeAvailable($time)
    {
        if (!$this->selectedRoom || !$this->selectedDate || !$this->start_time) {
            return true;
        }

        // End time must be after start time
        if ($time <= $this->start_time) {
            return false;
        }

        // Check if the time slot from start_time to this end time is available
        return $this->isTimeSlotAvailable($this->start_time, $time);
    }


    public function hasAvailableEndTime($startTime)
    {
        if (!$this->selectedRoom || !$this->selectedDate) {
            return true;
        }

        // ตรวจสอบว่ามี end time available หรือไม่
        foreach ($this->availableTimes as $endTime) {
            if ($endTime > $startTime && $this->isTimeSlotAvailable($startTime, $endTime)) {
                return true; // มี end time available อย่างน้อย 1 ช่วง
            }
        }

        return false; // ไม่มี end time available เลย
    }

    public function selectTime($type, $time)
    {
        $this->resetErrorBag();

        if ($type === 'start') {
            // Validate if start time is available
            if (!$this->isStartTimeAvailable($time)) {
                $this->addError('start_time', 'This start time is not available. Please choose another time.');
                return;
            }

            $this->start_time = $time;

            // Reset end time if it's before the new start time or no longer available
            if ($this->end_time && ($this->end_time <= $this->start_time || !$this->isEndTimeAvailable($this->end_time))) {
                $this->end_time = null;
            }
        } elseif ($type === 'end') {
            // Validate if end time is available
            if (!$this->isEndTimeAvailable($time)) {
                $this->addError('end_time', 'This time slot is not available. Please choose another end time.');
                return;
            }

            $this->end_time = $time;
        }
    }

    public function reserveTime()
    {
        $this->resetErrorBag();

        if (!$this->selectedRoom || !$this->start_time || !$this->end_time) {
            $this->addError('reservation', 'Please select a room and time.');
            return;
        }

        // Final validation of the complete time slot
        if (!$this->isTimeSlotAvailable($this->start_time, $this->end_time)) {
            $this->addError('reservation', 'The selected time slot is no longer available. Please choose different times.');
            return;
        }

        $this->room_id = $this->selectedRoom->id;
        $this->calculatePrice();
        $this->nextStep();
    }

    public function getIsFormValidProperty()
    {
        return !empty($this->name) &&
            !empty($this->email) &&
            !empty($this->phone) &&
            !$this->getErrorBag()->has('name') &&
            !$this->getErrorBag()->has('email') &&
            !$this->getErrorBag()->has('phone');
    }

    public function submitBooking()
    {
        $this->validate([
            'date' => 'required|date',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => ['required', 'regex:/^(\+66|0)[0-9]{8,9}$/'],
        ]);

        // Final validation before creating booking
        if (!$this->isTimeSlotAvailable($this->start_time, $this->end_time, $this->room_id, $this->date)) {
            $this->addError('booking', 'The selected time slot is no longer available. Please go back and select different times.');
            return;
        }

        try {
            Booking::create([
                'user_id' => auth()->id(),
                'room_id' => $this->room_id,
                'date' => $this->date,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'band_name' => $this->band_name,
                'members' => $this->members ?? 1,
                'additional_request' => $this->additional_request,
                'total_price' => $this->total_price,
                'status' => 'pending',
            ]);

            session()->flash('message', 'Booking successful!');
            return redirect()->route('home');
        } catch (\Exception $e) {
            $this->addError('booking', 'An error occurred while creating your booking. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.booking-stepper');
    }
}
