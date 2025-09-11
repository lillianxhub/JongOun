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
        <div class="container">
            @livewire('admin.dashboard')
        </div>
        <div class="mt-10">
            @livewire('admin.bookings')
        </div>

        <div class="mt-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Rooms</h2>
                @livewire('room-create')
            </div>
            @livewire('room-manager')
        </div>
</x-app-layout>
