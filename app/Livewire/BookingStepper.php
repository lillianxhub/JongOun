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
    // Current step in the booking stepper (1,2,3)
    public $step = 1;

    // Validation rules for common fields
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

    // Custom error messages
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

    // -----------------------------
    // Step 1 properties (date / calendar)
    // -----------------------------
    public $date;
    public $currentMonth;
    public $currentYear;
    public $selectedDate = null;
    public $calendarDays = [];

    // -----------------------------
    // Step 2 properties (room/time)
    // -----------------------------
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

    // -----------------------------
    // Step 3 properties (customer / instruments)
    // -----------------------------
    public $name;
    public $email;
    public $phone;
    public $band_name;
    public $members;
    public $additional_request;

    public $instruments;
    public $selectedInstruments = [];

    public $total_price = 0;

    public $errorType = null;
    public $errorInstrumentId = null;

    /** 
     * Component mount
     * Initialize calendar, load room types/rooms/instruments and pre-fill user data if logged in
     */
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

    /**
     * Generate calendar structure for currentMonth/currentYear
     * Produces $calendarDays as array of weeks, each week has 7 day entries
     */
    public function generateCalendar()
    {
        $firstDayOfMonth = now()->setMonth($this->currentMonth)->setYear($this->currentYear)->startOfMonth();
        $daysInMonth = $firstDayOfMonth->daysInMonth;
        $startDayOfWeek = $firstDayOfMonth->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
        $calendar = [];
        $week = [];

        // Fill previous month's days to align first week
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

        // Fill current month days
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

        // Fill remaining next month days to complete last week
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

    /**
     * Go to previous month (not allowed to go before current month/year)
     */
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

    /**
     * Go to next month
     */
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

    /**
     * Select a day from calendar and move to next step
     */
    public function selectDay($year, $month, $day)
    {
        $date = now()->setYear($year)->setMonth($month)->setDay($day)->format('Y-m-d');
        $this->selectedDate = $date;
        $this->date = $date;
        $this->nextStep();
    }

    // ---------------------------
    // Step navigation
    // ---------------------------
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

    /**
     * Real-time members validation: ensure members <= room capacity
     */
    public function updatedMembers($value)
    {
        if ($this->selectedRoom && $value > $this->selectedRoom->capacity) {
            $this->addError('members', "Number of members ({$value}) exceeds room capacity ({$this->selectedRoom->capacity})");

            // Dispatch SweetAlert2 event for client-side display
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

    /**
     * Validate step 1 (date selection)
     */
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

    /**
     * Validate step 2 (room & times)
     */
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

    /**
     * Jump to specific step (1-3)
     */
    public function goToStep($step)
    {
        if ($step >= 1 && $step <= 3) {
            $this->step = $step;
        }
    }

    /**
     * Add an instrument to selection (initial quantity = 1)
     */
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

    /**
     * Remove instrument from selection
     */
    public function removeInstrument($id)
    {
        unset($this->selectedInstruments[$id]);
        $this->calculatePrice();
    }

    /**
     * Increase instrument quantity (bounded by stock)
     */
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

    /**
     * Decrease instrument quantity (remove if reaching zero)
     */
    public function decreaseInstrument($id)
    {
        if ($this->selectedInstruments[$id] > 1) {
            $this->selectedInstruments[$id]--;
        } else {
            unset($this->selectedInstruments[$id]);
        }
        $this->calculatePrice();
    }

    /**
     * Calculate total price based on room hours and instruments
     */
    public function calculatePrice()
    {
        // Reset before calculation to avoid accumulation
        $this->total_price = 0;

        if ($this->room_id && $this->start_time && $this->end_time) {
            $room = Room::find($this->room_id);
            $hours = (strtotime($this->end_time) - strtotime($this->start_time)) / 3600;

            // Ensure at least 1 hour pricing
            $this->total_price = $room->price * max($hours, 1);
        }

        foreach ($this->selectedInstruments as $id => $quantity) {
            $instrument = $this->instruments->firstWhere('id', $id);

            if ($instrument) {
                $this->total_price += max(0, $instrument->price) * $quantity;
            }
        }
    }

    /**
     * Select a room type and reset dependent selections
     */
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

    /**
     * Select a specific room and load its available times
     */
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
     * Check if the room has any available start time (used for UI availability)
     */
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
     * Prevent overlaps with existing bookings
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
            // If selected start time falls inside any booked slot, it's not available
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

        // Validate the entire time range using isTimeSlotAvailable
        return $this->isTimeSlotAvailable($this->start_time, $time);
    }

    /**
     * Determine whether there's at least one valid end time that starts after $startTime
     * Used to quickly show if a start time has any matching end time
     */
    public function hasAvailableEndTime($startTime)
    {
        if (!$this->selectedRoom || !$this->selectedDate) {
            return true;
        }

        foreach ($this->availableTimes as $endTime) {
            if ($endTime > $startTime && $this->isTimeSlotAvailable($startTime, $endTime)) {
                return true; // found at least one valid end time
            }
        }

        return false; // none found
    }

    /**
     * Select start or end time. Validates availability and resets end_time when needed.
     */
    public function selectTime($type, $time)
    {
        $this->resetErrorBag();

        if ($type === 'start') {
            // Validate start time
            if (!$this->isStartTimeAvailable($time)) {
                $this->addError('start_time', 'This start time is not available. Please choose another time.');
                return;
            }

            $this->start_time = $time;

            // If existing end_time is now invalid (before start) or unavailable, reset it
            if ($this->end_time && ($this->end_time <= $this->start_time || !$this->isEndTimeAvailable($this->end_time))) {
                $this->end_time = null;
            }
        } elseif ($type === 'end') {
            // Validate end time
            if (!$this->isEndTimeAvailable($time)) {
                $this->addError('end_time', 'This time slot is not available. Please choose another end time.');
                return;
            }

            $this->end_time = $time;
        }
    }

    /**
     * Reserve time and proceed to next step after final validation of the slot
     */
    public function reserveTime()
    {
        $this->resetErrorBag();

        if (!$this->selectedRoom || !$this->start_time || !$this->end_time) {
            $this->addError('reservation', 'Please select a room and time.');
            return;
        }

        // Final validation: is slot still available?
        if (!$this->isTimeSlotAvailable($this->start_time, $this->end_time)) {
            $this->addError('reservation', 'The selected time slot is no longer available. Please choose different times.');
            return;
        }

        $this->room_id = $this->selectedRoom->id;
        $this->calculatePrice();
        $this->nextStep();
    }

    /**
     * Partial live validation for fields when they are updated
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'members' => 'required|integer|min:1',
        ]);
    }

    /**
     * Computed property: check if basic form fields are valid
     */
    public function getIsFormValidProperty()
    {
        return !empty($this->name) &&
            !empty($this->email) &&
            !empty($this->phone) &&
            !$this->getErrorBag()->has('name') &&
            !$this->getErrorBag()->has('email') &&
            !$this->getErrorBag()->has('phone');
    }

    /**
     * Show confirmation dialog (client-side) before submitting booking
     */
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

    /**
     * Final submission handler - called after client confirms
     * Includes re-validation and creation of Booking + instrument attachments
     */
    #[On('submitBooking')]
    public function submitBooking()
    {
        // Check members capacity first
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

        // Ensure room exists and capacity still valid
        if ($this->members > $this->selectedRoom->capacity) {
            $this->addError('members', "Number of members ({$this->members}) exceeds room capacity ({$this->selectedRoom->capacity})");
            return;
        }

        // Check conflicts again
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

        // Check instrument stock
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

        // Final availability check using robust helper
        if (!$this->isTimeSlotAvailable($this->start_time, $this->end_time, $this->room_id, $this->date)) {
            $this->addError('booking', 'The selected time slot is no longer available. Please go back and select different times.');
            return;
        }

        // Re-check capacity using room from DB
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

            // Attach instruments to booking pivot table with quantity & price
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
        } catch (\Exception $e) {
            // On error, dispatch an error alert
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to reserve this booking. Please try again.',
                'icon' => 'error'
            ]);
        }
    }

    /**
     * Remove instrument error state and recalculate price
     */
    public function removeInstrumentError($id)
    {
        unset($this->selectedInstruments[$id]);
        $this->calculatePrice();
        $this->errorType = null;
        $this->errorInstrumentId = null;
        $this->resetErrorBag('booking');
    }

    /**
     * Render the Livewire view
     */
    public function render()
    {
        return view('livewire.booking-stepper');
    }
}
