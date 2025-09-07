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

    <h2 class="text-2xl font-bold mb-4">Recent Bookings</h2>
    <div class="bg-white shadow rounded-lg p-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Details</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings ?? [] as $booking)
                    <tr>
                        <td class="px-4 py-2">{{ $booking->user->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $booking->room->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $booking->date }}</td>
                        <td class="px-4 py-2">
                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                        </td>
                        <td class="px-4 py-2">{{ $booking->total_price }} ฿ </td>
                        <td
                            class="px-4 py-2 
                        {{ $booking->status === 'approved' ? 'text-green-600' : ($booking->status === 'canceled' ? 'text-red-600' : 'text-yellow-500') }}">
                            {{ ucfirst($booking->status) }}
                        </td>

                        <td class="px-4 py-2">
                            <button wire:click="showDetails({{ $booking->id }})"
                                class="px-2 py-1 bg-black text-white border rounded hover:bg-white hover:text-black">Details</button>
                        </td>

                        <td class="px-4 py-2">
                            @if ($booking->status === 'pending')
                                <button wire:click="confirmApprove({{ $booking->id }})"
                                    class="fa-solid fa-square-check text-green-500 hover:text-green-700 mr-2"></button>

                                <button wire:click="confirmCancel({{ $booking->id }})"
                                    class="fa-solid fa-square-xmark text-red-600 hover:text-red-700 rounded"></button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">No bookings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Modal --}}
        @if ($showModal && $selectedBooking)
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
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
                                <td class="font-semibold p-2">Status</td>
                                <td class="p-2">
                                    <span
                                        class="px-2 py-1 rounded text-white 
                    {{ $selectedBooking->status === 'approved' ? 'bg-green-600' : ($selectedBooking->status === 'canceled' ? 'bg-red-600' : 'bg-yellow-500') }}">
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
