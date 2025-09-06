<div class="p-6 bg-white rounded shadow w-fit min-w-[600px] mx-auto">
    <!-- Stepper -->
    <div class="flex justify-center mb-8">
        @for ($i = 1; $i <= 3; $i++)
            <div class="flex flex-col items-center">
                <div 
                    @if ($i <= $step)
                        wire:click="goToStep({{ $i}})"
                    @endif
                    class="w-10 h-10 flex items-center justify-center rounded-full font-bold text-lg border-2
                    {{ $step == $i ? 'border-black' : 'border-transparent' }}
                    {{ $step >= $i ? 'cursor-pointer' : 'cursor-not-allowed' }}"
                    style="background: {{ $step >= $i ? '#22c55e' : '#e5e7eb' }}; color: {{ $step >= $i ? 'white' : '#374151' }};">
                    {{ $i }}
                </div>
                <span class="mt-2 text-xs font-semibold {{ $step == $i ? 'text-green-600' : 'text-gray-400' }}">
                    Step {{ $i }}
                </span>
            </div>
            @if($i < 3)
                <div class="w-32 h-1 mt-5 {{ $step > $i ? 'bg-green-500' : 'bg-gray-300'}}"></div>
            @endif
        @endfor
    </div>

    <!-- Step 1 -->
    @if($step == 1)
        <div class="flex flex-col items-center gap-4">
            <div class="flex items-center justify-center gap-8 mb-4">
                <button wire:click="prevMonth" class="text-2xl px-2"
                    @if($currentYear == now()->year && $currentMonth <= now()->month)
                        disabled class="opacity-70 cursor-not-allowed hover:bg-white" 
                    @else
                        class="hover:bg-gray-300" 
                    @endif>
                   <i class="fa-solid fa-arrow-left"></i> 
                </button>
                <span class="font-bold text-lg">{{ \Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}</span>
                <button wire:click="nextMonth" class="text-2xl px-2 hover:bg-gray-300"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
            <div class="w-full max-w-lg mx-auto p-2">
                <div class="grid grid-cols-7 gap-2 mb-2 text-center text-gray-500 font-semibold">
                    <div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div><div>Sun</div>
                </div>
                <div class="grid grid-cols-7 gap-2 items-center justify-center">
                    @foreach($calendarDays as $week)
                        @foreach($week as $day)
                            @if(!$day['other'])
                                <button wire:click="selectDay({{ $day['year'] }}, {{ $day['month'] }}, {{ $day['day'] }})"
                                    class="w-100 h-10 flex items-center justify-center font-semibold transition
                                        {{ $selectedDate == \Carbon\Carbon::create($day['year'], $day['month'], $day['day'])->format('Y-m-d') ? 'bg-green-500 text-white border-2 border-black' : 'bg-gray-100 text-gray-700 hover:bg-green-100' }}
                                        {{ ($day['year'] == now()->year && $day['month'] == now()->month && $day['day'] < now()->day) ? 'opacity-50 cursor-not-allowed bg-red-100 hover:bg-red-100' : '' }}">
                                    {{ $day['day'] }}
                                </button>
                            @else
                                <div class="w-100 h-10 flex items-center justify-center text-gray-400 bg-gray-50">{{ $day['day'] }}</div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>

    @elseif($step == 2)
        <h3 class="text-xl font-bold mb-4">Choose Room & Time</h3>

        {{-- Display any errors --}}
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- เลือกประเภทห้อง --}}
        <div class="grid grid-cols-[40%_60%] gap-6">
            {{-- <div class="grid grid-rows-3 gap-4 mb-6">
                @foreach($room_types as $type)
                    <button wire:click="selectRoomType({{ $type->id }})"
                        class="cursor-pointer border rounded-lg p-4 text-center shadow-sm hover:bg-green-50
                            {{ $selectedType && $selectedType->id === $type->id ? 'bg-green-100 border-green-500' : 'bg-white' }}">
                        <p class="capitalize font-semibold">{{ $type->name }}</p>
                    </button>
                @endforeach
            </div> --}}

            <div class="mb-4">
                <div class="mb-5">
                    <label for="roomType" class="block mb-2 font-semibold">Select Room Type:</label>
                    <select id="roomType" 
                            wire:model="selectedTypeId" 
                            wire:change="selectRoomType($event.target.value)"
                            class="w-full border rounded-lg p-2 shadow-sm focus:ring focus:ring-green-200">

                        <option value="">-- Select Room Type --</option>

                        @foreach($room_types as $type)
                            <option value="{{ $type->id }}">
                                {{ ucfirst($type->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            {{-- แสดงห้องตามประเภทที่เลือก --}}
            @if($selectedType)
                <div>
                    <div class="mb-2">
                        <p class="font-semibold">Select Room :</p>
                    </div>
                    @foreach($selectedType->rooms as $room)
                        <div 
                            wire:click="selectRoom({{ $room->id }})"
                            class="mb-0 p-4 border rounded cursor-pointer shadow-sm hover:bg-green-50
                                {{ $selectedRoom && $selectedRoom->id === $room->id ? 'bg-green-100 border-green-500' : 'bg-white' }}">
                            <p><strong>Room:</strong> {{ $room->name }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
            </div>
            
            @if($selectedRoom)
                <div>
                    
                <div class="mb-4 mr-6">
                    
                    <img src="/images/rooms/room1.png" alt="Room A" class="w-100 mb-5 h-75 object-cover" />
                    <div class="flex gap-3">
                        <!-- Room -->
                        <div class="inline-flex items-center border rounded-lg overflow-hidden bg-gray-100">
                            <span class="px-3">Room</span>
                            <span class="bg-gray-700 text-white px-3 py-1 font-semibold">
                                {{ $selectedRoom->name }}
                            </span>
                        </div>

                        <!-- Capacity -->
                        <div class="inline-flex items-center border rounded-lg overflow-hidden bg-gray-100">
                            <span class="px-3">Capacity</span>
                            <span class="bg-gray-700 text-white px-3 py-1 font-semibold flex items-center gap-1">
                                {{ $selectedRoom->capacity ?? '-' }}
                                <i class="fa-solid fa-user"></i>
                            </span>
                        </div>

                        <!-- Price -->
                        <div class="inline-flex items-center border rounded-lg overflow-hidden bg-gray-100">
                            <span class="px-3">Price</span>
                            <span class="bg-gray-700 text-white px-3 py-1 font-semibold">
                                {{ number_format($selectedRoom->price, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div>
                    <p><strong>Instruments :</strong>
                        @php
                            $instruments = is_array($selectedRoom->instruments) ? 
                                        $selectedRoom->instruments : 
                                        (json_decode($selectedRoom->instruments, true) ?? []);
                        @endphp
                        @foreach($instruments as $instrument)
                            <span class="">{{$instrument}},</span>
                        @endforeach
                    </p>
                </div>
                </div>
            @endif
        </div>

        @if($selectedRoom)
            <div class="grid grid-cols-2 gap-4 mt-6">
                {{-- Start Time --}}
                <div class="mb-4">
                    <label class="block mb-2 font-semibold text-center">Start Time:</label>
                    <div class="grid grid-cols-4 gap-1">
                        @foreach($availableTimes as $time)
                            @php
                                $isAvailable = $this->isStartTimeAvailable($time);
                                $isPastTime = ($selectedDate === now()->format('Y-m-d')) && ($time <= now()->format('H:i') && now()->format('i') > 0);
                                $isBooked = !$isPastTime && !$isAvailable; // If not past time but still unavailable, it's booked
                            @endphp
                            <button 
                                wire:click="selectTime('start', '{{ $time }}')"
                                @if(!$isAvailable) disabled @endif
                                class="px-3 py-1 border rounded text-sm
                                    @if($isPastTime)
                                        bg-orange-100 text-orange-600 cursor-not-allowed opacity-50
                                    @elseif($isBooked)
                                        bg-red-100 text-red-400 cursor-not-allowed opacity-50
                                    @elseif($start_time == $time)
                                        bg-green-500 text-white
                                    @else
                                        bg-white hover:bg-green-100
                                    @endif">
                                {{ $time }}
                            </button>
                        @endforeach
                    </div>
                </div>


                @if($start_time)
                    <div class="mb-4">
                        <label class="block mb-2 font-semibold text-center">End Time:</label>
                        <div class="grid grid-cols-4 gap-1">
                            @foreach($availableTimes as $time)
                                @if($time > $start_time)
                                    @php
                                        $isAvailable = $this->isEndTimeAvailable($time);
                                    @endphp
                                    <button 
                                        wire:click="selectTime('end', '{{ $time }}')"
                                        @if(!$isAvailable) disabled @endif
                                        class="px-3 py-1 border rounded text-sm
                                            @if(!$isAvailable)
                                                bg-red-100 text-red-400 cursor-not-allowed opacity-50
                                            @elseif($end_time == $time)
                                                bg-green-500 text-white
                                            @else
                                                bg-white hover:bg-green-100
                                            @endif">
                                        {{ $time }}
                                        @if(!$isAvailable)
                                            {{-- <i class="fas fa-lock text-xs ml-1"></i> --}}
                                        @endif
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>


            @if($selectedRoom && $selectedDate)
                    <div class="items-start mt-2">
                    <p class="text-xs text-red-400 mt-1">
                        <i class="fas fa-lock"></i> = Already booked
                    </p>
                    {{-- <p class="text-xs text-orange-400 mt-1 bg-orange-100">
                        <i class="fas fa-clock"></i> = Past time (today)
                    </p> --}}
                    </div>
            @endif
            {{-- Reserve Button --}}
            @if($start_time && $end_time)

                <div class="mt-4 w-full text-center">
                    <div class="mb-2 p-2 bg-gray-50 rounded">
                        <p class="text-sm"><strong>Selected:</strong> {{ $start_time }} - {{ $end_time }}</p>
                        <p class="text-sm"><strong>Duration:</strong> {{ (strtotime($end_time) - strtotime($start_time)) / 3600 }} hour(s)</p>
                    </div>
                    <button wire:click="reserveTime" class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Reserve {{ $start_time }} - {{ $end_time }} in {{ $selectedType->name }}
                    </button>
                </div>
            @endif

            {{-- Show existing bookings for this date --}}
            @if($selectedRoom && $selectedDate)
                @php
                    $bookedSlots = $this->getBookedTimeSlots();
                @endphp
                @if($bookedSlots->count() > 0)
                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded">
                        <h5 class="font-semibold text-red-800 mb-2">Already Booked Times:</h5>
                        @foreach($bookedSlots as $slot)
                            <span class="inline-block bg-red-200 text-red-800 px-2 py-1 rounded text-xs mr-2 mb-1">
                                {{ $slot->start_time }} - {{ $slot->end_time }}
                            </span>
                        @endforeach
                    </div>
                @endif
            @endif
        @endif

    @elseif($step == 3)
        {{-- Display any booking errors --}}
        <!-- Error message for booking issues -->
        @if($errors->has('booking'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <p>{{ $errors->first('booking') }}</p>
                <button wire:click="goToStep(2)" class="mt-2 px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                    Go Back to Select Times
                </button>
            </div>
        @endif

        <div class="grid grid-cols-2 gap-6">
            <div>
                <h3 class="text-xl font-bold mb-4">Your Details</h3>
                <div class="grid gap-4">
                    <!-- Name Field -->
                    <div>
                        <input type="text" 
                            wire:model.live="name" 
                            placeholder="Your Name" 
                            class="w-full mb-2 p-2 border rounded @error('name') border-red-500 @enderror" 
                            required>
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <input type="email" 
                            wire:model.live="email" 
                            placeholder="Your Email" 
                            class="w-full mb-2 p-2 border rounded @error('email') border-red-500 @enderror" 
                            required>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div>
                        <input type="tel" 
                            wire:model.live="phone" 
                            placeholder="Your Phone Number (e.g., 0812345678)" 
                            class="w-full mb-2 p-2 border rounded @error('phone') border-red-500 @enderror" 
                            required>
                        @error('phone')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <small class="text-gray-600">Format: 0812345678 or +66812345678</small>
                    </div>

                    <!-- Band Name and Members -->
                    <div class="flex gap-4">
                        <input type="text" 
                            wire:model="band_name" 
                            placeholder="Band Name (if any)" 
                            class="w-full p-2 border rounded">
                        <input type="number" 
                            wire:model="members" 
                            placeholder="Members" 
                            min="1" 
                            class="w-32 p-2 border rounded">
                    </div>

                    <!-- Additional Requests -->
                    <textarea wire:model="additional_request" 
                            rows="4" 
                            class="w-full p-2 border rounded" 
                            placeholder="Additional Requests"></textarea>

                    <!-- Submit Button -->
                    <button 
                        wire:click="submitBooking" 
                        @class([
                            'px-4 py-2 rounded text-white transition-colors duration-200',
                            'bg-green-600 hover:bg-green-700 cursor-pointer' => $this->isFormValid,
                            'bg-gray-300 cursor-not-allowed' => !$this->isFormValid
                        ])>
                        <span wire:loading.remove wire:target="submitBooking">Reserve booking</span>
                        <span wire:loading wire:target="submitBooking">Processing...</span>
                    </button>

                    <!-- Form validation status indicator -->
                    @if(!$this->isFormValid)
                        <div class="text-sm text-gray-600">
                            Please fill in all required fields correctly to continue.
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="text-xl font-bold mb-4">Booking Summary</h3>
                <div class="border p-4 rounded">
                    <p><strong>{{ $selectedType->name ?? '-' }}</strong></p>
                    <p>{{ $selectedRoom->name ?? '-' }}</p>
                    <p><strong class="fa-solid fa-user"></strong> {{ $selectedRoom->capacity ?? '-'}} people</p>
                    <p><strong class="fa-solid fa-calendar"></strong> {{ $selectedDate ? \Carbon\Carbon::parse($selectedDate)->format('F j, Y') : '-' }}</p>
                    <p><strong class="fa-solid fa-clock"></strong> {{ $start_time ?? '-' }} - {{ $end_time ?? '-' }} : {{ ($start_time && $end_time) ? (strtotime($end_time) - strtotime($start_time)) / 3600 . ' hour(s)' : '-' }} </p>
                </div>
                <div class="mt-4">
                    <p><strong>Price per hour:</strong> {{ $selectedRoom->price ? number_format($selectedRoom->price, 2) . ' ฿' : '-'}} </p>
                    <p class="text-lg"><strong>Total Price:</strong> {{ $total_price ? number_format($total_price, 2) . ' ฿' : '-' }}</p>
                </div>
            </div>
        </div>
    @endif
</div>