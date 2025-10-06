<div class="px-2 md:px-4 py-4 w-full max-w-5xl md:max-xl mx-auto">
    <!-- Stepper -->
    <div class="flex justify-center mb-6 md:mb-8 overflow-x-auto">
        <div class="flex items-center min-w-max px-4">
            @for ($i = 1; $i <= 3; $i++)
                <div class="flex items-center">
                    <div>
                        {{-- Circle --}}
                        <div @if ($i <= $step) wire:click="goToStep({{ $i }})" @endif
                            class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full text-white text-sm md:text-lg
                        {{ $step == $i ? 'bg-btn-gradient' : 'border-transparent' }}
                        {{ $step > $i ? 'cursor-pointer bg-btn-gradient-accent' : 'cursor-not-allowed bg-white/50 text-white/50' }}
                        ">
                            {{ $i }}
                        </div>
                    </div>

                    {{-- Connector --}}
                    @if ($i < 3)
                        <div class="w-16 md:w-32 h-px {{ $step > $i ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <div class="bg-dark/80 p-4 md:p-6 rounded-lg shadow-md">

        <!-- Step 1 -->
        @if ($step == 1)
            <div class="flex flex-col items-center gap-4">
                <div class="flex items-center justify-center gap-4 md:gap-8 mb-0">
                    <button wire:click="prevMonth" class="text-xl md:text-2xl px-2 "
                        {{ $currentYear == now()->year && $currentMonth <= now()->month ? 'disabled' : '' }}>
                        <i
                            class="fa-solid fa-chevron-left {{ $currentYear == now()->year && $currentMonth <= now()->month ? 'text-black' : 'text-white' }}">
                        </i>
                    </button>
                    <span class="font-bold text-base md:text-lg text-primary">
                        {{ \Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}
                    </span>
                    <button wire:click="nextMonth" class="text-white text-xl md:text-2xl px-2 hover:bg-gray-300">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
                <div class="w-full max-w-5xl mx-auto px-2 md:px-4">
                    <div
                        class="grid grid-cols-7 gap-1 md:gap-2 mb-2 text-center text-accent font-bold text-xs md:text-base">
                        <div>Mon</div>
                        <div>Tue</div>
                        <div>Wed</div>
                        <div>Thu</div>
                        <div>Fri</div>
                        <div>Sat</div>
                        <div>Sun</div>
                    </div>
                    <div class="grid grid-cols-7 gap-1 md:gap-5 items-center justify-center">
                        @foreach ($calendarDays as $week)
                            @foreach ($week as $day)
                                @php
                                    $currentDate = now()->startOfDay(); // เวลา 00:00:00
                                    $dayDate = Carbon\Carbon::create(
                                        $day['year'],
                                        $day['month'],
                                        $day['day'],
                                    )->startOfDay();

                                    $isSelected = $selectedDate == $dayDate->format('Y-m-d');

                                    // เช็คว่าปุ่มควร disable หรือไม่
                                    $isDisabled = $dayDate < $currentDate;

                                    // กำหนด class
                                    if ($isSelected) {
                                        $btnClass = 'bg-btn-gradient-accent text-white border-white border';
                                        $isDisabled = false;
                                    } elseif (!$day['other']) {
                                        // เดือนปัจจุบัน
                                        $btnClass =
                                            $dayDate < $currentDate
                                                ? 'bg-white/5 opacity-50 text-white'
                                                : 'bg-white/10 text-white hover:bg-btn-gradient hover:border-white hover:border';
                                    } else {
                                        // เดือนอื่น
                                        $btnClass =
                                            $dayDate < $currentDate
                                                ? 'bg-white/5 opacity-50 text-white'
                                                : 'bg-white/20 text-white hover:bg-btn-gradient hover:border-white hover:border';
                                    }

                                    // เพิ่มคลาสสำหรับวันที่เลือก
                                    if ($isSelected) {
                                        $btnClass = 'bg-btn-gradient-accent text-white border-white border';
                                        $isDisabled = false; // วันที่เลือกต้อง clickable
                                    }
                                @endphp

                                <button
                                    wire:click="selectDay({{ $day['year'] }}, {{ $day['month'] }}, {{ $day['day'] }})"
                                    @disabled($isDisabled)
                                    class="w-full h-12 md:h-16 flex items-center justify-center font-semibold transition text-xs md:text-base rounded {{ $btnClass }}">
                                    {{ $day['day'] }}
                                </button>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Step 2 --}}
        @elseif($step == 2)
            <h3 class="text-xl md:text-2xl text-primary font-bold mb-4">Choose Room & Time</h3>

            {{-- Display any errors --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- select room type --}}
            <div class="grid grid-cols-1 lg:grid-cols-[40%_55%] gap-4 lg:gap-6">
                <div class="mb-4">
                    <div class="mb-5 relative">
                        {{-- <label for="roomType" class="block mb-2 font-semibold text-sm md:text-base text-white">
                            Select Room Type:
                        </label> --}}
                        <select id="roomType" wire:model="selectedTypeId"
                            wire:change="selectRoomType($event.target.value)"
                            class="appearance-none w-full bg-white/15 text-white font-medium rounded-lg py-5 px-4 pr-10 shadow-sm focus:outline-none focus:ring-2 focus:ring-white">
                            <option class="bg-black" value="">-- Select Room Type --</option>
                            @foreach ($room_types as $type)
                                <option class="bg-black" value="{{ $type->id }}">
                                    {{ ucfirst($type->name) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-white">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    {{-- Show room --}}
                    @if ($selectedType)
                        <div class="mb-4">
                            <div class="relative">
                                <select id="roomSelect" wire:model="selectedRoomId"
                                    wire:change="selectRoom($event.target.value)"
                                    class="appearance-none w-full bg-white/15 text-white font-medium rounded-lg py-5 px-4 pr-10 shadow-sm focus:outline-none focus:ring-2 focus:ring-white">
                                    <option class="bg-black" value="">-- Select Room --</option>
                                    @foreach ($selectedType->rooms as $room)
                                        <option class="bg-black" value="{{ $room->id }}"
                                            @if ($selectedRoom && $selectedRoom->id === $room->id) selected @endif>
                                            {{ $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Arrow -->
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-white">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if ($selectedRoom)
                    <div>
                        <div class="mb-4">

                            <img src="{{ asset('images/rooms/' . $selectedType->image) }}"
                                alt="{{ $selectedType->name }}"
                                class="w-full mb-5 h-65 md:h-75 object-cover rounded-lg" />

                        </div>
                        <div class="p-5 text-sm md:text-base text-white bg-black rounded-lg">

                            <h3 class="text-xl text-primary font-bold">{{ $selectedType->name }} Room -
                                {{ $selectedRoom->name }}</h3>
                            <p class="text-sm text-gray-400">{{ $selectedType->detail }}</p>
                            {{-- <div class="flex flex-wrap gap-2 md:gap-3">
                                <!-- Room -->
                                <div
                                    class="inline-flex items-center border rounded-lg overflow-hidden bg-gray-100 text-xs md:text-base">
                                    <span class="px-2 md:px-3 py-1">Room</span>
                                    <span class="bg-gray-700 text-white px-2 md:px-3 py-1 font-semibold">
                                        {{ $selectedRoom->name }}
                                    </span>
                                </div>

                                <!-- Capacity -->
                                <div
                                    class="inline-flex items-center border rounded-lg overflow-hidden bg-gray-100 text-xs md:text-base">
                                    <span class="px-2 md:px-3 py-1">Capacity</span>
                                    <span
                                        class="bg-gray-700 text-white px-2 md:px-3 py-1 font-semibold flex items-center gap-1">
                                        {{ $selectedRoom->capacity ?? '-' }}
                                        <i class="fa-solid fa-user"></i>
                                    </span>
                                </div>

                                <!-- Price -->
                                <div
                                    class="inline-flex items-center border rounded-lg overflow-hidden bg-gray-100 text-xs md:text-base">
                                    <span class="px-2 md:px-3 py-1">Price</span>
                                    <span class="bg-gray-700 text-white px-2 md:px-3 py-1 font-semibold">
                                        ฿ {{ number_format($selectedRoom->price, 2) }}
                                    </span>
                                </div>
                            </div> --}}
                            <p>
                                @php
                                    $instruments = is_array($selectedRoom->instruments)
                                        ? $selectedRoom->instruments
                                        : json_decode($selectedRoom->instruments, true) ?? [];
                                @endphp
                                @foreach ($instruments as $instrument)
                                    <span>{{ $instrument }},</span>
                                @endforeach
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            @if ($selectedRoom)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    {{-- Start Time --}}
                    <div class="mb-4">
                        <label class="block mb-2 font-semibold text-center text-sm md:text-base text-white">Start
                            Time:</label>
                        <div class="grid grid-cols-3 md:grid-cols-4 gap-1">
                            @foreach ($availableTimes as $time)
                                @php
                                    $isAvailable = $this->isStartTimeAvailable($time);
                                    $hasEndTime = $this->hasAvailableEndTime($time);
                                    $isPastTime =
                                        $selectedDate === now()->format('Y-m-d') &&
                                        ($time <= now()->format('H:i') && now()->format('i') > 0);
                                    $isBooked = !$isPastTime && (!$isAvailable || !$hasEndTime);
                                @endphp
                                <button wire:click="selectTime('start', '{{ $time }}')"
                                    @if (!$isAvailable || !$hasEndTime) disabled @endif
                                    class="px-2 md:px-3 py-1 rounded text-xs md:text-sm text-white
                                    @if ($isPastTime) bg-white/20 cursor-not-allowed opacity-50
                                    @elseif($isBooked) bg-red-500 cursor-not-allowed opacity-50
                                    @elseif($start_time == $time) bg-btn-gradient-accent
                                    @else bg-white/15 hover:bg-btn-gradient @endif">
                                    {{ $time }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- End Time --}}
                    @if ($start_time)
                        <div class="mb-4">
                            <label class="block mb-2 font-semibold text-center text-sm md:text-base text-white">End
                                Time:</label>
                            <div class="grid grid-cols-3 md:grid-cols-4 gap-1">
                                @foreach ($availableTimes as $time)
                                    @if ($time > $start_time)
                                        @php
                                            $isAvailable = $this->isEndTimeAvailable($time);
                                        @endphp
                                        <button wire:click="selectTime('end', '{{ $time }}')"
                                            @if (!$isAvailable) disabled @endif
                                            class="px-2 md:px-3 py-1 rounded text-xs md:text-sm text-white
                                            @if (!$isAvailable) bg-red-500 cursor-not-allowed opacity-50
                                            @elseif($end_time == $time) bg-btn-gradient-accent
                                            @else bg-white/15 hover:bg-btn-gradient @endif">
                                            {{ $time }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                @if ($selectedRoom && $selectedDate)
                    <div class="items-start mt-2">
                        <p class="text-xs text-red-400 mt-1">
                            <i class="fas fa-lock"></i> = Already booked
                        </p>
                    </div>
                @endif

                {{-- Reserve Button --}}
                @if ($start_time && $end_time)
                    <div class="mt-4 w-full text-center">
                        <div class="mb-2 p-2 rounded text-sm text-white">
                            <p><strong>Selected :</strong> {{ $start_time }} -
                                {{ $end_time }}</p>
                            <p><strong>Duration :</strong> {{ (strtotime($end_time) - strtotime($start_time)) / 3600 }}
                                hour(s)</p>
                        </div>
                        <button wire:click="reserveTime"
                            class="w-full px-4 py-2 bg-btn-gradient text-white rounded hover:opacity-90 hover:shadow-lg hover:shadow-primary transition transform hover:-translate-y-1 text-sm md:text-base">
                            Reserve {{ $start_time }} - {{ $end_time }} in {{ $selectedType->name }}
                        </button>
                    </div>
                @endif

                {{-- Already booked slots --}}
                @if ($selectedRoom && $selectedDate)
                    @php
                        $bookedSlots = $this->getBookedTimeSlots();
                    @endphp
                    @if ($bookedSlots->count() > 0)
                        <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded">
                            <h5 class="font-semibold text-red-800 mb-2 text-sm md:text-base">Already Booked Times:</h5>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($bookedSlots as $slot)
                                    <span class="inline-block bg-red-500 text-white px-2 py-1 rounded text-xs">
                                        {{ $slot->start_time }} - {{ $slot->end_time }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            @endif

            {{-- Step 3 --}}
        @elseif($step == 3)
            {{-- Display any booking errors --}}
            @if ($errors->has('booking'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                    <p>{{ $errors->first('booking') }}</p>

                    @if ($errorType === 'time_conflict')
                        <button wire:click="goToStep(2)"
                            class="mt-2 px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                            Go Back to Select Times
                        </button>
                    @elseif($errorType === 'invalid_instrument' || ($errorType === 'insufficient_stock' && $errorInstrumentId))
                        <button wire:click="removeInstrumentError({{ $errorInstrumentId }})"
                            class="mt-2 px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                            Remove Instrument
                        </button>
                    @endif
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-6 p-2 md:p-4 box-border">
                <div class="w-full lg:w-2/5">
                    <h3 class="text-xl md:text-2xl text-primary font-bold mb-4">Your Details</h3>
                    <div class="grid">

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="text-white">Name</label>
                            <input type="text" wire:model.live="name" placeholder="Your Name"
                                class="w-full my-2 p-2 bg-white/10 border rounded-lg text-white text-sm md:text-base @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <span class="text-red-500 text-xs md:text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="text-white">Email</label>
                            <input type="email" wire:model.live="email" placeholder="Your Email"
                                class="w-full my-2 p-2 bg-white/10 border rounded-lg text-white text-sm md:text-base @error('email') border-red-500 @enderror"
                                required>
                            @error('email')
                                <span class="text-red-500 text-xs md:text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label for="phone" class="text-white">Phone</label>
                            <input type="tel" wire:model.live="phone"
                                placeholder="Your Phone Number (e.g., 0812345678)"
                                class="w-full my-2 p-2 bg-white/10 border rounded-lg text-white text-sm md:text-base @error('phone') border-red-500 @enderror"
                                required>
                            @error('phone')
                                <span class="text-red-500 text-xs md:text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Band Name and Members -->
                        <div class="flex flex-col sm:flex-row gap-2">
                            <div class="flex-1">
                                <label class="text-white">Band Name (if any)</label>
                                <input type="text" wire:model="band_name" placeholder="Enter Band Name"
                                    class="w-full my-2 p-2 bg-white/10 border rounded-lg text-white text-sm md:text-base">
                            </div>
                            <div class="relative w-full sm:w-32">
                                <label class="text-white">Members</label>
                                <input type="text" wire:model.live="members" placeholder="Members" min="1"
                                    class="w-full my-2 p-2 bg-white/10 border rounded-lg text-white text-sm md:text-base @error('members') border-red-500 @enderror">
                            </div>
                        </div>
                        @error('members')
                            <div class="text-red-500 text-xs md:text-sm flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror

                        <!-- Additional Requests -->
                        <div>
                            <label class="text-white">Additional Requests</label>
                            <textarea wire:model="additional_request" rows="4"
                                class="w-full my-2 p-2 bg-white/10 border rounded-lg text-white text-sm md:text-base"
                                placeholder="Additional Requests"></textarea>
                        </div>

                        {{-- Instrument list --}}
                        <div class="mb-5">
                            <div class="space-y-3">
                                <p class="text-white">Add Instrument</p>
                                @foreach ($instruments as $instrument)
                                    @if (!isset($selectedInstruments[$instrument->id]))
                                        <div
                                            class="flex justify-between items-center border border-gray-500 rounded-lg p-2 text-sm md:text-base bg-white/10">
                                            <div class="flex items-center gap-2 ">
                                                @if ($instrument->stock > 0)
                                                    <button wire:click="addInstrument({{ $instrument->id }})"
                                                        class="bg-green-500 hover:bg-green-300 text-white px-1 md:px-2 py-1 rounded text-xs md:text-sm">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    <span class="text-white">{{ $instrument->name }}</span>
                                                @else
                                                    <button disabled
                                                        class="text-white px-1 md:px-2 py-1 md:px-2 rounded cursor-not-allowed text-xs md:text-sm">
                                                        <i class="fas fa-xmark"></i>
                                                    </button>
                                                    <span class="text-white">{{ $instrument->name }} (Out of
                                                        stock)</span>
                                                @endif
                                            </div>
                                            <span class="text-white text-sm md:text-base">
                                                {{ $instrument->price > 0 ? '฿ ' . number_format($instrument->price, 2) : 'Free' }}
                                            </span>
                                        </div>
                                    @else
                                        <div
                                            class="flex justify-between items-center border border-gray-500 rounded-lg p-2 text-sm md:text-base bg-white/10">
                                            <div class="flex items-center gap-2">
                                                <button wire:click="removeInstrument({{ $instrument->id }})"
                                                    class="bg-red-500 hover:bg-red-300 text-white px-1 md:px-2 py-1  rounded text-xs md:text-sm">
                                                    <i class="fas fa-xmark"></i>
                                                </button>
                                                <span class="text-white">{{ $instrument->name }}</span>
                                                <span class="text-sm md:text-base text-gray-500">
                                                    {{ $instrument->price > 0 ? '฿ ' . number_format($instrument->price, 2) : 'Free' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @if ($selectedInstruments[$instrument->id] < $instrument->stock)
                                                    <div class="flex items-center gap-2">

                                                        <button wire:click="decreaseInstrument({{ $instrument->id }})"
                                                            class="text-xs md:text-sm text-white">
                                                            <i class="fas fa-minus"></i>
                                                        </button>

                                                        <span class="text-sm md:text-base text-white">
                                                            {{ $selectedInstruments[$instrument->id] }}
                                                        </span>
                                                        <button wire:click="increaseInstrument({{ $instrument->id }})"
                                                            class="text-xs md:text-sm text-white">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <button disabled
                                                        class="bg-gray-300 text-white px-2 md:px-3 py-1 rounded text-xs md:text-sm cursor-not-allowed">
                                                        <i class="fas fa-xmark"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button wire:click="confirmBooking" @disabled(!$name || !$email || !$phone) @class([
                            'px-4 py-2 rounded text-white transition-colors duration-200 text-sm md:text-base',
                            'bg-btn-gradient hover:opacity-90 hover:shadow-lg hover:shadow-primary transition transform hover:-translate-y-1 cursor-pointer' =>
                                $this->isFormValid,
                            'bg-white/10 opacity-50 cursor-default' => !$this->isFormValid,
                        ])>
                            <span wire:loading.remove wire:target="confirmBooking">Reserve booking</span>
                            <span wire:loading wire:target="confirmBooking">Processing...</span>
                        </button>

                        <!-- Form validation status indicator -->
                        @if (!$this->isFormValid)
                            <div class="mt-2 text-xs md:text-sm text-gray-600">
                                Please fill in all required fields correctly to continue.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="w-full lg:w-3/5 h-fit rounded-lg border border-gray-500 bg-black/50 p-5">
                    @if ($selectedType)
                        <div class="w-full">
                            <img class="w-full h-48 md:h-auto object-cover rounded-xl border border-gray-500"
                                src="{{ asset('images/rooms/' . $selectedType->image) }}"
                                alt="{{ $selectedType->name }}">
                        </div>
                    @endif
                    <div class="text-sm md:text-base text-white">
                        <h3 class="text-primary text-xl md:text-2xl font-bold mt-5">Booking Summary</h3>
                        {{-- Room --}}
                        <div class="flex justify-between text-sm md:text-base mt-5">
                            <p>Room Type :</p>
                            <p class="font-semibold">{{ $selectedType->name ?? '-' }} -
                                {{ $selectedRoom->name ?? '-' }}</p>
                        </div>
                        <hr class="my-2 border-gray-500">
                        {{-- Date --}}
                        <div class="flex justify-between text-sm md:text-base mt-5">
                            <p>Date :</p>
                            <p class="font-semibold">
                                {{ $selectedDate ? \Carbon\Carbon::parse($selectedDate)->format('D j M, Y') : '-' }}
                            </p>
                        </div>
                        <hr class="my-2 border-gray-500">
                        {{-- Time --}}
                        <div class="flex justify-between text-sm md:text-base mt-5">
                            <p>Time :</p>
                            <p class="font-semibold">
                                {{ $start_time ?? '-' }} - {{ $end_time ?? '-' }}
                            </p>
                        </div>
                        <hr class="my-2 border-gray-500">
                        {{-- Duration --}}
                        <div class="flex justify-between text-sm md:text-base mt-5">
                            <p>Duration :</p>
                            <p class="font-semibold">
                                {{ $start_time && $end_time ? (strtotime($end_time) - strtotime($start_time)) / 3600 . ' hour(s)' : '-' }}
                            </p>
                        </div>
                        <hr class="my-2 border-gray-500">
                        {{-- Capacity --}}
                        <div class="flex justify-between text-sm md:text-base mt-5">
                            <p>Capacity :</p>
                            <p class="font-semibold">
                                {{ $selectedRoom->capacity ?? '-' }} people</p>
                        </div>
                        <hr class="my-2 border-gray-500">
                    </div>



                    <div class="rounded-xl border border-primary bg-primary/30 p-3 md:p-4 text-sm md:text-base mt-5">
                        <h3 class="text-lg md:text-xl text-white font-bold mb-2">Price Details</h3>
                        <p class="text-sm md:text-base text-white font-semibold">
                            {{ $selectedType->name }} - {{ $selectedRoom->name }}
                        </p>
                        <div class="flex justify-between">
                            <p class="text-xs md:text-sm text-white">Price per hour</p>
                            <p class="text-xs md:text-sm text-white">
                                ฿ {{ $selectedRoom->price ? number_format($selectedRoom->price, 2) : '-' }}
                            </p>
                        </div>
                        {{-- <p class="text-xs md:text-sm text-gray-300">
                            Time in {{ $selectedRoom->name }} :
                            {{ $start_time && $end_time ? (strtotime($end_time) - strtotime($start_time)) / 3600 . ' hour(s)' : '-' }}
                        </p> --}}

                        @if ($selectedInstruments && count($selectedInstruments) > 0)

                            <hr class="my-2 border-primary">

                            <p class="font-semibold text-white">Instrument:</p>
                            @foreach ($selectedInstruments as $id => $quantity)
                                @php
                                    $instrument = $instruments->find($id);
                                @endphp
                                @if ($instrument)
                                    <div class="flex justify-between ml-4 text-white">
                                        <p>
                                            - {{ $instrument->name }} ({{ $quantity }})
                                        </p>
                                        <p>
                                            {{ $instrument->price > 0 ? '฿ ' . number_format($instrument->price * $quantity, 2) : 'Free' }}
                                        </p>
                                    </div>
                                @endif
                            @endforeach

                        @endif
                        <hr class="my-2 border-primary">

                        <div class="flex justify-between text-base md:text-lg font-bold text-primary">
                            <p>Total Price: </p>
                            <p>฿{{ $total_price ? number_format($total_price, 2) : '-' }} </p>
                        </div>
                    </div>
                    {{-- <div class="w-full">

                        <!-- Submit Button -->
                        <button wire:click="confirmBooking" @disabled(!$name || !$email || !$phone) @class([
                            'px-4 py-2 rounded text-white transition-colors duration-200 text-sm md:text-base',
                            'bg-green-600 hover:bg-green-700 cursor-pointer' => $this->isFormValid,
                            'bg-gray-300 cursor-default' => !$this->isFormValid,
                        ])>
                            <span wire:loading.remove wire:target="confirmBooking">Reserve booking</span>
                            <span wire:loading wire:target="confirmBooking">Processing...</span>
                        </button>

                    </div> --}}

                </div>
            </div>
        @endif
    </div>
</div>
