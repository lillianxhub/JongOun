<x-app-layout>
    <script>
        // แปลง PHP variable เป็น JSON ให้ JS ใช้
        const totalBookings = @json($totalBookings);
        const totalUsers = @json($totalUsers);
        const pendingBookings = @json($pendingBookings);
        const totalRooms = @json($totalRooms);
        const recentBookings = @json($recentBookings);

        console.log("Total Bookings:", totalBookings);
        console.log("Total Users:", totalUsers);
        console.log("Pending Bookings:", pendingBookings);
        console.log("Total Rooms:", totalRooms);
        console.log("Recent Bookings:", recentBookings);
    </script>

    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-2">Total Users</h2>
                <div class="text-3xl font-bold">{{ $totalUsers?? '-' }}</div>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-2">Total Bookings</h2>
                <div class="text-3xl font-bold">{{ $totalBookings ?? '-' }}</div>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-2">Pending Bookings</h2>
                <div class="text-3xl font-bold">{{ $pendingBookings ?? '-' }}</div>
            </div>
        </div>
        <div class="mt-10">
            <h2 class="text-2xl font-bold mb-4">Recent Bookings</h2>
            <div class="bg-white shadow rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings ?? [] as $booking)
                        <tr>
                            <td class="px-4 py-2">{{ $booking->user->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $booking->room->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $booking->date }}</td>
                            <td class="px-4 py-2">{{ ucfirst($booking->status) }}</td>
                            <td class="px-4 py-2">
                                <button wire:click="" class="fa-solid fa-square-check text-green-500 hover:text-green-700 mr-2"></button>

                                <button wire:click="" class="fa-solid fa-square-xmark text-red-600 hover:text-red-700 rounded"></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500">No bookings found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Rooms</h2>
                @livewire('room-create')
            </div>
            @livewire('room-manager')
        </div>
</x-app-layout>