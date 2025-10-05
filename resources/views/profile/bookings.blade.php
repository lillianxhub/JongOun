<x-app-layout>
    <div class="container mx-auto py-10">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold">My Bookings</h1>
            <a href="{{ route('booking') }}"
               class="bg-black hover:bg-green-500 text-white font-semibold px-4 py-2 rounded shadow transition">
                Add Booking
            </a>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            @if ($bookings->isEmpty())
                <p class="text-gray-500">You have no bookings yet.</p>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Start</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">End</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-200 cursor-pointer"
                                onclick="openModal({{ $booking->id }})"
                                data-booking='@json($booking->load("room","instruments","user"))'>
                                <td class="px-4 py-2">{{ $booking->room->name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $booking->date }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $booking->status === 'approved'
                                        ? 'bg-green-100 text-green-700'
                                        : ($booking->status === 'canceled'
                                            ? 'bg-red-100 text-red-700'
                                            : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div id="bookingModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6 relative">
            <h2 class="text-2xl font-bold mb-6 text-center">Booking Details</h2>
            <div class="overflow-x-auto" id="modalContent"></div>
            <div class="mt-6 text-center">
                <button onclick="closeModal()"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-gray-700">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            const row = event.currentTarget;
            const booking = JSON.parse(row.dataset.booking);
            const content = `
                <table class="w-full text-sm border border-gray-200 rounded-lg">
                    <tbody>
                        <tr class="border-b"><td class="font-semibold p-3 w-1/3">Name</td><td class="p-3">${booking.user?.name ?? booking.name}</td></tr>
                        <tr class="border-b"><td class="font-semibold p-3">Email</td><td class="p-3">${booking.user?.email ?? '-'}</td></tr>
                        <tr class="border-b"><td class="font-semibold p-3">Phone</td><td class="p-3">${booking.phone}</td></tr>
                        <tr class="border-b"><td class="font-semibold p-3">Room</td><td class="p-3">${booking.room?.name ?? '-'}</td></tr>
                        <tr class="border-b"><td class="font-semibold p-3">Band Name</td><td class="p-3">${booking.band_name ?? '-'}</td></tr>
                        <tr class="border-b"><td class="font-semibold p-3">Members</td><td class="p-3">${booking.members}</td></tr>
                        <tr class="border-b"><td class="font-semibold p-3">Date</td><td class="p-3">${booking.date}</td></tr>
                        <tr class="border-b"><td class="font-semibold p-3">Time</td><td class="p-3">${booking.start_time} - ${booking.end_time}</td></tr>
                        <tr class="border-b"><td class="font-semibold p-3">Status</td><td class="p-3"><span class="px-2 py-1 rounded-full text-xs font-medium ${booking.status === 'approved' ? 'bg-green-100 text-green-700' : (booking.status === 'canceled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')}">${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}</span></td></tr>
                        <tr class="border-b"><td class="font-semibold p-3">Request</td><td class="p-3">${booking.additional_request ?? '-'}</td></tr>
                        <tr class="border-b"><td class="font-semibold p-3">Instruments</td><td class="p-3">${booking.instruments.length ? booking.instruments.map(i => `<span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800">${i.name} x ${i.pivot.quantity}</span>`).join(' ') : '<span class="text-gray-400 italic">None</span>'}</td></tr>
                    </tbody>
                </table>
            `;
            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('bookingModal').classList.remove('hidden');
            document.getElementById('bookingModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('bookingModal').classList.remove('flex');
            document.getElementById('bookingModal').classList.add('hidden');
            document.getElementById('modalContent').innerHTML = '';
        }
    </script>
</x-app-layout>
