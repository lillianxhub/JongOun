<div>
    <div wire:poll.30s="refreshData">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <button wire:click="refreshData"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>

        <!-- Loading Indicator -->
        <div wire:loading wire:target="refreshData" class="fixed top-5 right-5 z-50">
            <div class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg">
                <i class="fas fa-spinner fa-spin"></i> Updating...
            </div>
        </div>

        {{-- testtest --}}

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Total Users</h2>
                        <div class="text-3xl font-bold text-blue-600">{{ $totalUsers ?? 0 }}</div>
                    </div>
                    <i class="fas fa-users text-blue-500 text-3xl"></i>
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Total Bookings</h2>
                        <div class="text-3xl font-bold text-green-600">{{ $totalBookings ?? 0 }}</div>
                    </div>
                    <i class="fas fa-calendar-check text-green-500 text-3xl"></i>
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Pending Bookings</h2>
                        <div class="text-3xl font-bold text-yellow-600">{{ $pendingBookings ?? 0 }}</div>
                    </div>
                    <i class="fas fa-clock text-yellow-500 text-3xl"></i>
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Total Rooms</h2>
                        <div class="text-3xl font-bold text-purple-600">{{ $totalRooms ?? 0 }}</div>
                    </div>
                    <i class="fas fa-door-open text-purple-500 text-3xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>
