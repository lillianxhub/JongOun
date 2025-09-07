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
                <div class="text-3xl font-bold">{{ $totalUsers ?? '-' }}</div>
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
            @livewire('admin-bookings')
        </div>

        <div class="mt-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Rooms</h2>
                @livewire('room-create')
            </div>
            @livewire('room-manager')
        </div>
</x-app-layout>
