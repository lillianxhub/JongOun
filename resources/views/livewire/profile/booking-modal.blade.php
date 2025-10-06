<div>
    @if($isOpen && $booking)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Booking Details</h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <table class="w-full text-sm border border-gray-200 rounded-lg">
                        <tbody>
                            <tr class="border-b">
                                <td class="font-semibold p-3 w-1/3">Name</td>
                                <td class="p-3">{{ $booking->user?->name ?? $booking->name }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-3">Email</td>
                                <td class="p-3">{{ $booking->user?->email ?? '-' }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-3">Phone</td>
                                <td class="p-3">{{ $booking->phone }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-3">Room</td>
                                <td class="p-3">{{ $booking->room?->name ?? '-' }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-3">Band Name</td>
                                <td class="p-3">{{ $booking->band_name ?? '-' }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-3">Members</td>
                                <td class="p-3">{{ $booking->members }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-3">Date</td>
                                <td class="p-3">{{ $booking->date }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-3">Time</td>
                                <td class="p-3">{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-3">Status</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $this->getStatusColorClass($booking->status) }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-3">Request</td>
                                <td class="p-3">{{ $booking->additional_request ?? '-' }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-3">Instruments</td>
                                <td class="p-3">
                                    @if($booking->instruments->count())
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($booking->instruments as $instrument)
                                                <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                    {{ $instrument->name }} x {{ $instrument->pivot->quantity }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">None</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>