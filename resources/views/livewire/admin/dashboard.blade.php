<div>
    <div wire:poll.30s="refreshData">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Dashboard</h1>
        </div>

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

            <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-green-500 cursor-pointer hover:bg-gray-50 transition"
                onclick="window.location='{{ route('admin.bookings') }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Total Bookings</h2>
                        <div class="text-3xl font-bold text-green-600">{{ $totalBookings ?? 0 }}</div>
                        <p class="text-xs text-gray-500 mt-1">Click to see all</p>
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

            <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-purple-500 cursor-pointer hover:bg-gray-50 transition"
                onclick="document.getElementById('rooms').scrollIntoView({behavior: 'smooth'})">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Total Rooms</h2>
                        <div class="text-3xl font-bold text-purple-600">{{ $totalRooms ?? 0 }}</div>
                        <p class="text-xs text-gray-500 mt-1">Click to manage</p>
                    </div>
                    <i class="fas fa-door-open text-purple-500 text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Recent Bookings Section -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-history mr-2 text-gray-600"></i>
                        Recent Bookings
                    </h3>
                    <a href="{{ route('admin.bookings') }}"
                        class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                        View All Bookings →
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if ($recentBookings && $recentBookings->count() > 0)
                    <div class="space-y-4">
                        @foreach ($recentBookings as $booking)
                            <div
                                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold text-sm">
                                            {{ substr($booking->user->name ?? 'N/A', 0, 2) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ $booking->user->name ?? 'Unknown User' }}</p>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-door-open mr-1"></i>
                                            {{ $booking->room->name ?? 'Unknown Room' }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $booking->created_at ? $booking->created_at->format('M d, Y H:i') : 'Unknown Date' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">
                                        <i class="fas fa-money-bill-wave mr-1"></i>
                                        ฿{{ number_format($booking->total_price ?? 0) }}
                                    </p>
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                        {{ $booking->status === 'approved' ? 'bg-green-100 text-green-700' : ($booking->status === 'canceled' ? 'bg-red-100 text-red-700' : ($booking->status === 'finished' ? 'bg-gray-100 text-gray-700' : 'bg-yellow-100 text-yellow-700')) }}">
                                        <i class="fas fa-circle mr-1 text-xs"></i>
                                        {{ ucfirst($booking->status ?? 'unknown') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No recent bookings</p>
                        <p class="text-gray-400 text-sm">Bookings will appear here when customers make reservations</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
