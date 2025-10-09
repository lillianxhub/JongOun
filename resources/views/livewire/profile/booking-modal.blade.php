<div>

    {{-- isOpen: {{ $isOpen ? 'true' : 'false' }}, booking: {{ $booking ? 'exists' : 'null' }} --}}
    @if ($isOpen && $booking)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-2 sm:p-4"
            wire:click.self="closeModal">

            <div class="bg-hero-gradient rounded-xl shadow-xl w-full max-w-2xl">

                <!-- Header -->
                <div class="relative">
                    <button wire:click="closeModal"
                        class="absolute top-2 right-2 bg-white/30 text-white rounded-full p-2 hover:bg-black/70 transition flex items-center justify-center">
                        <i class="fas fa-xmark text-xs"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-5 space-y-2 text-white">

                    <h2 class="text-base font-bold text-primary">Booking Information</h2>
                    <!-- Booking Info -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-0.5 bg-white/10 rounded p-1.5 border border-white/10">
                            <p class="text-xs">
                                Date
                            </p>
                            <p class="font-semibold text-sm">
                                {{ $booking->date }}
                            </p>
                        </div>
                        <div class="space-y-0.5 bg-white/10 rounded p-1.5 border border-white/10">
                            <p class="text-xs">Time</p>
                            <p class="font-semibold text-sm">
                                {{ $booking->start_time }} -
                                {{ $booking->end_time }}
                            </p>
                        </div>
                        <div class="space-y-0.5 bg-white/10 rounded p-1.5 border border-white/10">
                            <p class="text-xs">Duration</p>
                            <p class="font-semibold text-sm">
                                {{ $booking->start_time && $booking->end_time ? (strtotime($booking->end_time) - strtotime($booking->start_time)) / 3600 . ' hours' : '-' }}
                            </p>
                        </div>
                        <div class="space-y-0.5 bg-white/10 rounded p-1.5 border border-white/10">
                            <p class="text-xs">Members</p>
                            <p class="font-semibold text-sm">{{ $booking->members }} people</p>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <h3 class="text-primary font-bold text-sm">Customer Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-0.5 bg-white/10 rounded p-1.5 border border-white/10">
                            <p class="text-xs font-semibold">Name</p>
                            <p class="text-sm font-semibold">{{ $booking->user?->name ?? $booking->name }}</p>
                        </div>
                        <div class="space-y-0.5 bg-white/10 rounded p-1.5 border border-white/10">
                            <p class="text-xs font-semibold">Email</p>
                            <p class="text-sm font-semibold truncate">{{ $booking->user?->email ?? '-' }}</p>
                        </div>
                        <div class="space-y-0.5 bg-white/10 rounded p-1.5 border border-white/10">
                            <p class="text-xs font-semibold">Phone</p>
                            <p class="text-sm font-semibold">{{ $booking->phone }}</p>
                        </div>
                        <div class="space-y-0.5 bg-white/10 rounded p-1.5 border border-white/10">
                            <p class="text-xs font-semibold">Band Name</p>
                            <p class="text-sm font-semibold">{{ $booking->band_name ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Equipment / Instruments -->
                    <h3 class="text-primary font-bold text-sm">Instrument</h3>
                    <div class="grid grid-cols-1 gap-2">
                        @if ($booking->instruments->count())
                            <div class="space-y-0.5 bg-white/10 rounded p-1.5 border border-white/10 text-sm">
                                @foreach ($booking->instruments as $instrument)
                                    {{ $instrument->name }} ({{ $instrument->pivot->quantity }}),
                                @endforeach
                            </div>
                        @else
                            <div
                                class="space-y-0.5 bg-white/10 rounded p-1.5 border border-white/10 text-sm italic text-gray-300">
                                None
                            </div>
                        @endif
                    </div>

                    <!-- Payment Summary -->
                    <h3 class="text-primary font-bold text-sm">Payment Summary</h3>
                    <div class="bg-primary/30 p-2 rounded text-white border border-primary">
                        <div class="flex justify-between text-xs">
                            <span>Room {{ $booking->room->name }} ({{ $booking->duration ?? '2' }} hours)</span>
                            <span>฿{{ $booking->room->price }}</span>
                        </div>
                        <div>
                            @if ($booking->instruments)

                                <hr class="my-2 border-primary">

                                <p class="font-semibold text-xs text-white">Instrument:</p>
                                @foreach ($booking->instruments as $instrument)
                                    @if ($instrument)
                                        <div class="flex justify-between ml-4 text-white text-xs">
                                            <p>
                                                - {{ $instrument->name }} ({{ $instrument->pivot->quantity }})
                                            </p>
                                            <p>
                                                {{ $instrument->price > 0 ? '฿ ' . number_format($instrument->price * $instrument->pivot->quantity, 2) : 'Free' }}
                                            </p>
                                        </div>
                                    @endif
                                @endforeach
                            @endif


                            <hr class="my-2 border-primary">
                        </div>
                        <div class="flex justify-between font-bold mt-1 text-sm text-primary">
                            <span>Total:</span>
                            <span>฿{{ $booking->total_price }}</span>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <h3 class="text-orange-400 font-bold text-sm">Additional Notes</h3>
                    <div class="space-y-0.5 bg-white/10 rounded p-1.5 border border-white/10 text-sm">
                        {{ $booking->additional_request ?? '-' }}
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>
