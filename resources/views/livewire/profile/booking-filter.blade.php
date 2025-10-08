<div>
    <!-- Filter Buttons -->
    <div class="flex flex-col md:flex-row gap-4 text-white text-center p-2 bg-dark/80 rounded-lg mb-4">
        <button wire:click="setFilter('all')"
            class="w-full md:flex-1 py-2 rounded-lg 
        {{ $filter === 'all' ? 'bg-btn-gradient' : 'hover:bg-btn-gradient' }}">
            All Booking
        </button>
        <button wire:click="setFilter('pending')"
            class="w-full md:flex-1 py-2 rounded-lg 
        {{ $filter === 'pending' ? 'bg-btn-gradient' : 'hover:bg-btn-gradient' }}">
            Pending
        </button>
        <button wire:click="setFilter('approved')"
            class="w-full md:flex-1 py-2 rounded-lg 
        {{ $filter === 'approved' ? 'bg-btn-gradient' : 'hover:bg-btn-gradient' }}">
            Approve
        </button>
        <button wire:click="setFilter('cancelled')"
            class="w-full md:flex-1 py-2 rounded-lg 
        {{ $filter === 'cancelled' ? 'bg-btn-gradient' : 'hover:bg-btn-gradient' }}">
            Cancelled
        </button>
    </div>

    <!-- Booking Cards -->
    <div class="shadow rounded-lg">
        @if ($bookings->isEmpty())

            <div class="flex items-center justify-center mb-5 bg-dark rounded-xl shadow-lg h-40">
                <p class="text-white text-lg font-semibold">
                    You have no bookings yet.
                </p>
            </div>
        @else
            <div>
                @foreach ($bookings as $booking)
                    <div class="mb-5 bg-dark/70 rounded-xl overflow-hidden">
                        <div class="flex flex-col md:flex-row gap-2">

                            <img src="{{ asset('images/rooms/' . ($booking->room->roomType?->image ?? 'default.jpg')) }}"
                                alt="" class="h-fit max-h-48 object-cover">

                            <div class="p-1 flex flex-col gap-2">
                                <h1 class="text-primary font-bold text-md md:text-lg">
                                    {{ $booking->room->roomType?->name ?? '-' }} Room - Room
                                    {{ $booking->room->name }}
                                </h1>

                                <p class="text-white font-thin">
                                    <i class="far fa-calendar-days"></i>
                                    {{ $booking->date ? \Carbon\Carbon::parse($booking->date)->format('D j M, Y') : '-' }}
                                </p>

                                <p class="text-white font-thin">
                                    <i class="far fa-clock"></i>
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                                    - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                    ({{ $booking->start_time && $booking->end_time ? (strtotime($booking->end_time) - strtotime($booking->start_time)) / 3600 . ' hours' : '-' }})
                                </p>

                                <p class="text-white font-thin">
                                    <i class="far fa-user"></i>
                                    {{ $booking->members }} people
                                </p>
                            </div>

                            <div
                                class="md:ml-auto border-t md:border-t-0 md:border-l border-gray-700 p-5 flex flex-col w-full md:w-60">
                                <span
                                    class="px-4 py-2 mb-3 rounded-full text-xs font-bold uppercase text-center border
                                {{ $booking->status === 'approved'
                                    ? 'bg-green-800/50 text-green-600 border-green-600'
                                    : ($booking->status === 'cancelled'
                                        ? 'bg-red-800/50 text-red-600 border-red-600'
                                        : ($booking->status === 'pending'
                                            ? 'bg-orange-800/50 text-orange-500 border-orange-500'
                                            : 'bg-blue-800/50 text-blue-400 border-blue-400')) }}">
                                    {{ ucfirst($booking->status) }}
                                </span>

                                <p class="text-white md:text-right text-xs font-thin md:text-sm mt-2">
                                    Total Price
                                </p>

                                <p class="text-primary font-bold md:text-right text-md md:text-lg">
                                    ฿{{ $booking->total_price }}
                                </p>

                                <button
                                    class="cursor-pointer bg-btn-gradient rounded text-white text-center font-bold px-3 py-1 mt-4"
                                    @click="Livewire.dispatch('openBookingModal', { bookingId: {{ $booking->id }} })">
                                    View
                                </button>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
