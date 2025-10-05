<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Instrument;
use App\Models\RoomType;
use Carbon\Carbon;

class BookingStepper extends Component
{
    public $step = 1;

    protected $rules = [
        'name' => 'required|string|min:2|max:255',
        'email' => 'required|email',
        'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'band_name' => 'nullable|string|max:255',
        'members' => 'required|integer|min:1',
        'selectedRoom' => 'required',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'selectedDate' => 'required|date|after_or_equal:today',
        'additional_request' => 'nullable|string|max:1000'
    ];

    protected $messages = [
        'name.required' => 'Please enter your name',
        'name.min' => 'Name must be at least 2 characters',
        'email.required' => 'Please enter your email',
        'email.email' => 'Please enter a valid email address',
        'phone.required' => 'Please enter your phone number',
        'phone.regex' => 'Please enter a valid phone number',
        'phone.min' => 'Phone number must be at least 10 digits',
        'members.required' => 'Please enter the number of members',
        'members.min' => 'Number of members must be at least 1',
        'start_time.required' => 'Please select a start time',
        'end_time.required' => 'Please select an end time',
        'end_time.after' => 'End time must be after start time',
        'selectedDate.required' => 'Please select a date',
        'selectedDate.after_or_equal' => 'Please select today or a future date'
    ];

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

    public $instruments;
    public $selectedInstruments = [];

    public $total_price = 0;

    public $currentMonth;
    public $currentYear;
    public $selectedDate = null;
    public $calendarDays = [];

    public $errorType = null;
    public $errorInstrumentId = null;

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

        $this->instruments = Instrument::all();
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
        // Validate each step before proceeding
        if ($this->step == 1 && !$this->validateStep1()) {
            return;
        }
        if ($this->step == 2 && !$this->validateStep2()) {
            return;
        }

        if ($this->step == 2) {
            $this->calculatePrice();
        }
        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    // ตรวจสอบจำนวนสมาชิกแบบ real-time
    public function updatedMembers($value)
    {
        if ($this->selectedRoom && $value > $this->selectedRoom->capacity) {
            $this->addError('members', "Number of members ({$value}) exceeds room capacity ({$this->selectedRoom->capacity})");

            // แสดง SweetAlert2
            $this->dispatch('swal:error', [
                'title' => 'Invalid Number of Members',
                'text' => "The room capacity is {$this->selectedRoom->capacity} people, but you entered {$value} members.",
                'icon' => 'error',
                'confirmButtonText' => 'I understand'
            ]);
        } else {
            $this->resetErrorBag('members');
        }
    }

    protected function validateStep1()
    {
        if (!$this->selectedDate) {
            $this->addError('date', 'Please select a date');
            return false;
        }

        $selectedDate = Carbon::parse($this->selectedDate);
        if ($selectedDate->isPast() && !$selectedDate->isToday()) {
            $this->addError('date', 'Cannot select past dates');
            return false;
        }

        return true;
    }

    protected function validateStep2()
    {
        if (!$this->selectedRoom) {
            $this->addError('room', 'Please select a room');
            return false;
        }

        if (!$this->start_time || !$this->end_time) {
            $this->addError('time', 'Please select both start and end times');
            return false;
        }

        // Check if the selected time slot is still available
        $conflictingBooking = Booking::where('room_id', $this->selectedRoom->id)
            ->where('date', $this->selectedDate)
            ->where(function ($query) {
                $query->whereBetween('start_time', [$this->start_time, $this->end_time])
                    ->orWhereBetween('end_time', [$this->start_time, $this->end_time]);
            })
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($conflictingBooking) {
            $this->addError('time', 'This time slot is no longer available');
            return false;
        }

        return true;
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= 3) {
            $this->step = $step;
        }
    }

    public function addInstrument($id)
    {
        $instrument = $this->instruments->find($id);
        if (!$instrument) {
            return;
        }

        if ($instrument->stock <= 0) {
            $this->addError('booking', "No stock available for {$instrument->name}");
            return;
        }

        $this->selectedInstruments[$id] = 1;
        $this->calculatePrice();
    }

    public function removeInstrument($id)
    {
        unset($this->selectedInstruments[$id]);
        $this->calculatePrice();
    }

    public function increaseInstrument($id)
    {
        $instrument = $this->instruments->find($id);
        if (!$instrument) {
            return;
        }

        if ($this->selectedInstruments[$id] < $instrument->stock) {
            $this->selectedInstruments[$id]++;
        } else {
            $this->addError('booking', "No more stock available for {$instrument->name}");
        }

        $this->calculatePrice();
    }

    public function decreaseInstrument($id)
    {
        if ($this->selectedInstruments[$id] > 1) {
            $this->selectedInstruments[$id]--;
        } else {
            unset($this->selectedInstruments[$id]);
        }
        $this->calculatePrice();
    }

    public function calculatePrice()
    {
        if ($this->room_id && $this->start_time && $this->end_time) {
            $room = Room::find($this->room_id);
            $hours = (strtotime($this->end_time) - strtotime($this->start_time)) / 3600;

            $this->total_price = $room->price * max($hours, 1);
        }

        foreach ($this->selectedInstruments as $id => $quantity) {
            $instrument = $this->instruments->firstWhere('id', $id);

            if ($instrument) {
                $this->total_price += max(0, $instrument->price) * $quantity;
            }
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

    public function isRoomAvailable(Room $room)
    {
        if (!$this->selectedDate) return true;

        $availableTimes = $room->available_times ? json_decode($room->available_times, true) : [];

        foreach ($availableTimes as $startTime) {
            if ($this->hasAvailableEndTime($startTime)) {
                return true;
            }
        }

        return false;
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

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'members' => 'required|integer|min:1',
        ]);
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

    public function confirmBooking()
    {
        $this->validate([
            'date' => 'required|date',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'name' => 'required|min:2',
            'email' => 'required|email',
            'phone' => ['required', 'regex:/^(\+66|0)[0-9]{8,9}$/'],
        ]);

        $this->dispatch(
            'swal:confirm',
            [
                'title' => 'Are you sure?',
                'text' => 'You want to reserve this booking?',
                'icon' => 'warning',
                'method' => 'submitBooking',
                'color' => 'green'
            ]
        );
    }

    #[On('submitBooking')]
    public function submitBooking()
    {
        // ตรวจสอบจำนวนสมาชิกก่อน
        if ($this->selectedRoom && $this->members > $this->selectedRoom->capacity) {
            $this->addError('members', "Cannot proceed with booking: Number of members ({$this->members}) exceeds room capacity ({$this->selectedRoom->capacity})");
            return;
        }

        $this->validate([
            'date' => 'required|date|after_or_equal:today',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email',
            'phone' => ['required', 'regex:/^(\+66|0)[0-9]{8,9}$/'],
            'members' => ['required', 'integer', 'min:1'],
            'band_name' => 'nullable|string|max:255',
            'additional_request' => 'nullable|string|max:1000'
        ]);

        // ตรวจสอบจำนวนคนไม่เกินความจุของห้อง
        if ($this->members > $this->selectedRoom->capacity) {
            $this->addError('members', "Number of members ({$this->members}) exceeds room capacity ({$this->selectedRoom->capacity})");
            return;
        }

        // ตรวจสอบช่วงเวลาว่าง
        $conflictingBooking = Booking::where('room_id', $this->selectedRoom->id)
            ->where('date', $this->selectedDate)
            ->where(function ($query) {
                $query->whereBetween('start_time', [$this->start_time, $this->end_time])
                    ->orWhereBetween('end_time', [$this->start_time, $this->end_time]);
            })
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($conflictingBooking) {
            $this->errorType = 'time_conflict';
            $this->addError('booking', 'This time slot is no longer available. Please select another time.');
            return;
        }

        // ตรวจสอบ stock เครื่องดนตรี
        foreach ($this->selectedInstruments as $id => $quantity) {
            $instrument = Instrument::find($id);
            if (!$instrument) {
                $this->errorType = 'invalid_instrument';
                $this->errorInstrumentId = $id;
                $this->addError('booking', "Invalid instrument selected.");
                return;
            }
            if ($instrument->stock < $quantity) {
                $this->errorType = 'insufficient_stock';
                $this->errorInstrumentId = $id;
                $this->addError('booking', "Not enough {$instrument->name} available. Only {$instrument->stock} left.");
                return;
            }
        }

        // Final validation before creating booking
        if (!$this->isTimeSlotAvailable($this->start_time, $this->end_time, $this->room_id, $this->date)) {
            $this->addError('booking', 'The selected time slot is no longer available. Please go back and select different times.');
            return;
        }

        // ตรวจสอบจำนวนสมาชิกอีกครั้งก่อนสร้าง Booking
        $room = Room::find($this->room_id);
        if ($this->members > $room->capacity) {
            $this->addError('booking', "Cannot create booking: Number of members ({$this->members}) exceeds room capacity ({$room->capacity})");
            return;
        }

        try {
            $booking = Booking::create([
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

            // attach Instruments
            foreach ($this->selectedInstruments as $id => $quantity) {
                $instrument = Instrument::find($id);
                $booking->instruments()->attach($id, [
                    'quantity' => $quantity,
                    'price' => $instrument->price * $quantity
                ]);
            }

            session()->flash('message', 'Booking successful!');
            $this->dispatch('swal:success', [
                'title' => 'Reserved!',
                'text' => 'Booking has been reserve successfully.',
                'icon' => 'success',
                'color' => 'green',
                'redirect' => route('profile.bookings'),
            ]);

            // return redirect()->route('profile.bookings');
        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to reserve this booking. Please try again.',
                'icon' => 'error'

            ]);
        }
    }

    public function removeInstrumentError($id)
    {
        unset($this->selectedInstruments[$id]);
        $this->calculatePrice();
        $this->errorType = null;
        $this->errorInstrumentId = null;
        $this->resetErrorBag('booking');
    }

    public function render()
    {
        return view('livewire.booking-stepper');
    }
}
