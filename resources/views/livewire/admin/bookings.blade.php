<div>

    <script>
        // Confirm popup
        window.addEventListener('swal:confirm', event => {
            const detail = event.detail[0];
            const buttonColor = detail.color === 'green' ? '#28a745' : '#dc3545';

            Swal.fire({
                title: detail.title,
                text: detail.text,
                icon: detail.icon,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                confirmButtonColor: buttonColor,
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call(detail.method, detail.id);
                }
            });
        });

        // Success popup
        window.addEventListener('swal:success', event => {
            const detail = event.detail[0];
            const buttonColor = detail.color === 'green' ? '#28a745' : '#dc3545';

            Swal.fire({
                title: detail.title,
                text: detail.text,
                icon: detail.icon,
                confirmButtonText: 'OK',
                confirmButtonColor: buttonColor
            });
        });
    </script>

    <h2 class="text-2xl font-bold mb-4 text-gray-900">Recent Bookings</h2>
    <div class="bg-white shadow rounded-lg p-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                    </th>
                    {{-- <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th> --}}
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                @forelse($recentBookings ?? [] as $booking)
                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer"
                        wire:click.stop="showDetails({{ $booking->id }})">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $booking->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $booking->room->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $booking->date }}</td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                        </td>
                        <td class="px-4 py-3 text-gray-900 font-semibold">
                            ฿{{ number_format($booking->total_price, 2) }}
                        </td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $booking->status === 'approved'
                                ? 'bg-green-100 text-green-700'
                                : ($booking->status === 'canceled'
                                    ? 'bg-red-100 text-red-700'
                                    : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        {{-- <td class="px-4 py-3">
                            <button wire:click="showDetails({{ $booking->id }})"
                                class="px-3 py-1 text-sm font-medium text-white bg-gray-800 rounded hover:bg-gray-900 transition">
                                Details
                            </button>
                        </td> --}}
                        <td class="px-4 py-3">
                            @if ($booking->status === 'pending')
                                <div class="flex space-x-2">
                                    <button wire:click.stop="confirmApprove({{ $booking->id }})"
                                        class="text-green-600 hover:text-green-800 transition p-1 rounded-full hover:bg-green-100">
                                        <i class="fa-solid fa-square-check"></i>
                                    </button>
                                    <button wire:click.stop="confirmCancel({{ $booking->id }})"
                                        class="text-red-600 hover:text-red-800 transition p-1 rounded-full hover:bg-red-100">
                                        <i class="fa-solid fa-square-xmark"></i>
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-calendar-xmark text-4xl text-gray-300 mb-2"></i>
                                <p class="text-lg">No bookings found</p>
                                <p class="text-sm text-gray-400">Bookings will appear here once created</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Modal --}}
        @if ($showModal && $selectedBooking)
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                wire:click="closeModal">
                <div class="bg-white rounded shadow-lg w-1/2 p-6 relative">
                    <button wire:click="closeModal"
                        class="absolute top-2 right-4 text-black hover:text-red-500 ">&times;</button>

                    <h2 class="text-xl font-bold mb-4">Booking Details</h2>

                    <table class="w-full border border-gray-200 rounded mb-6">
                        <tbody>
                            <tr class="border-b">
                                <td class="font-semibold p-2 w-1/3">Name</td>
                                <td class="p-2">{{ $selectedBooking->user->name ?? $selectedBooking->name }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-2">Email</td>
                                <td class="p-2">{{ $selectedBooking->user->email ?? $selectedBooking->email }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-2">Phone</td>
                                <td class="p-2">{{ $selectedBooking->phone }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-2">Room</td>
                                <td class="p-2">{{ $selectedBooking->room->name ?? '-' }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-2">Band Name</td>
                                <td class="p-2">{{ $selectedBooking->band_name ?? '-' }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-2">Members</td>
                                <td class="p-2">{{ $selectedBooking->members }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-2">Date</td>
                                <td class="p-2">{{ $selectedBooking->date }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-2">Time</td>
                                <td class="p-2">
                                    {{ \Carbon\Carbon::parse($selectedBooking->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($selectedBooking->end_time)->format('H:i') }} </td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-2">Status</td>
                                <td class="p-2">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium
                    {{ $selectedBooking->status === 'approved' ? 'bg-green-100 text-green-700' : ($selectedBooking->status === 'canceled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($selectedBooking->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-2">Request</td>
                                <td class="p-2">{{ $selectedBooking->additional_request ?? '-' }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-2">Addition Instruments</td>
                                <td class="p-2">
                                    Bass Guitar x1, Cable x2, Microphone x2
                                </td>
                            </tr>
                            <tr>
                                <td class="font-semibold p-2">Created At</td>
                                <td class="p-2">{{ $selectedBooking->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Action buttons --}}
                    <div class="flex justify-end gap-4">
                        @if ($selectedBooking->status === 'pending')
                            <button wire:click="confirmCancel({{ $selectedBooking->id }})"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 flex items-center gap-2">
                                <i class="fa-solid fa-square-xmark"></i> Cancel
                            </button>

                            <button wire:click="confirmApprove({{ $selectedBooking->id }})"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center gap-2">
                                <i class="fa-solid fa-square-check"></i> Approve
                            </button>
                        @else
                            <span class="text-gray-500 italic">No actions available</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
