<x-app-layout>
    <div class="container mx-auto py-10">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold">My Bookings</h1>
            <a href="{{ route('booking') }}" class="bg-black hover:bg-green-500 text-white font-semibold px-4 py-2 rounded shadow transition">
                Add Booking
            </a>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            @if($bookings->isEmpty())
            <p class="text-gray-500">You have no bookings yet.</p>
            @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Start Time</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">End Time</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td class="px-4 py-2">{{ $booking->room->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $booking->date }}</td>
                        <td class="px-4 py-2">{{ $booking->start_time }}</td>
                        <td class="px-4 py-2">{{ $booking->end_time }}</td>
                        <td class="px-4 py-2">{{ ucfirst($booking->status) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</x-app-layout>