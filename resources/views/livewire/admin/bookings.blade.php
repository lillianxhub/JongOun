<div>

    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <!-- Title -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                <i class="fa-solid fa-calendar-days text-blue-500 mr-2"></i>
                Total Bookings
            </h1>
            <div class="text-sm text-gray-500 bg-gray-50 px-3 py-2 rounded-lg">
                <i class="fa-solid fa-chart-bar mr-1"></i>
                {{ $recentBookings->total() }} results found
            </div>
        </div>

        <!-- Advanced Search & Filters -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i
                        class="fa-solid fa-magnifying-glass text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                </div>
                <input type="text" wire:model.live="search" placeholder="Search by User or Room"
                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white shadow-sm hover:shadow-md" />
                @if ($search)
                    <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fa-solid fa-xmark text-gray-400 hover:text-gray-600 transition-colors"></i>
                    </button>
                @endif
            </div>

            <!-- Status Filter -->
            <div class="relative">
                <select wire:model.live="statusFilter"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white appearance-none cursor-pointer shadow-sm hover:shadow-md transition-all">
                    <option value="">🔄 All Status</option>
                    <option value="pending">⏳ Pending</option>
                    <option value="approved">✅ Approved</option>
                    <option value="canceled">❌ Canceled</option>
                    <option value="finished">⚠️ Finished</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="relative">
                <input type="date" wire:model.live="dateRange"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white shadow-sm hover:shadow-md transition-all" />
            </div>

            <!-- Per Page Selector -->
            <div class="relative">
                <select wire:model.live="perPage"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white appearance-none cursor-pointer shadow-sm hover:shadow-md transition-all">
                    <option value="5">📄 5 per page</option>
                    <option value="10">📄 10 per page</option>
                    <option value="20">📄 20 per page</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Active Filters Display -->
        @if ($search || $statusFilter || $dateRange)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm text-gray-500 font-medium">Active filters:</span>

                    @if ($search)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Search: "{{ $search }}"
                            <button wire:click="$set('search', '')" class="ml-1 hover:text-blue-600">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </span>
                    @endif

                    @if ($statusFilter)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Status: {{ ucfirst($statusFilter) }}
                            <button wire:click="$set('statusFilter', '')" class="ml-1 hover:text-green-600">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </span>
                    @endif

                    @if ($dateRange)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            Date: {{ $dateRange }}
                            <button wire:click="$set('dateRange', '')" class="ml-1 hover:text-purple-600">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </span>
                    @endif

                    <button wire:click="resetFilters" class="text-xs text-gray-500 hover:text-gray-700 underline ml-2">
                        Clear all
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Instrument</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                @forelse($recentBookings as $booking)
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
                        <td class="px-4 py-3 text-gray-600 items-center">
                            @if ($booking->instruments && $booking->instruments->count() > 0)
                                <div class="flex space-x-1">
                                    @foreach ($booking->instruments as $instrument)
                                        <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                            {{ $instrument->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 italic">None</span>
                            @endif
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
                        <td class="px-4 py-3">
                            @switch($booking->status)
                                @case('pending')
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
                                @break

                                @case('approved')
                                    <div class="flex space-x-2">
                                        <button wire:click.stop="confirmCancel({{ $booking->id }})"
                                            class="text-red-600 hover:text-red-800 transition p-1 rounded-full hover:bg-red-100">
                                            <i class="fa-solid fa-square-xmark"></i>
                                        </button>
                                    </div>
                                @break

                                @case('finished')
                                @case('canceled')
                                    <span class="text-gray-500 italic">No actions</span>
                                @break
                            @endswitch
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
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

            <!-- Enhanced Pagination -->
            @if ($recentBookings->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <!-- Results Info -->
                        <div class="flex items-center text-sm text-gray-700">
                            <span class="font-medium">Showing</span>
                            <span class="mx-1 text-blue-600">{{ $recentBookings->firstItem() ?? 0 }}</span>
                            <span>to</span>
                            <span class="mx-1 text-blue-600">{{ $recentBookings->lastItem() ?? 0 }}</span>
                            <span>of</span>
                            <span class="mx-1 text-blue-600">{{ $recentBookings->total() }}</span>
                            <span>results</span>
                        </div>

                        <!-- Pagination Controls -->
                        <nav class="flex items-center space-x-1">
                            <!-- Previous Button -->
                            <button wire:click="previousPage" @if ($recentBookings->onFirstPage()) disabled @endif
                                class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-500 transition-all">
                                <i class="fa-solid fa-chevron-left mr-1"></i>
                                Previous
                            </button>

                            <!-- Page Numbers -->
                            <div class="flex items-center space-x-1">
                                @foreach ($recentBookings->getUrlRange(max(1, $recentBookings->currentPage() - 2), min($recentBookings->lastPage(), $recentBookings->currentPage() + 2)) as $page => $url)
                                    <button wire:click="gotoPage({{ $page }})"
                                        class="px-3 py-2 text-sm font-medium rounded-lg transition-all
                                    {{ $recentBookings->currentPage() === $page
                                        ? 'bg-blue-600 text-white border border-blue-600 shadow-lg transform scale-105'
                                        : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 hover:text-blue-600 hover:border-blue-300' }}">
                                        {{ $page }}
                                    </button>
                                @endforeach
                            </div>

                            <!-- Next Button -->
                            <button wire:click="nextPage" @if ($recentBookings->currentPage() === $recentBookings->lastPage()) disabled @endif
                                class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-500 transition-all">
                                Next
                                <i class="fa-solid fa-chevron-right ml-1"></i>
                            </button>
                        </nav>
                    </div>
                </div>
            @endif
        </div>

        <!-- Modal -->
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
                                    {{ \Carbon\Carbon::parse($selectedBooking->end_time)->format('H:i') }}
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-2">Status</td>
                                <td class="p-2">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $selectedBooking->status === 'approved'
                                        ? 'bg-green-100 text-green-700'
                                        : ($selectedBooking->status === 'canceled'
                                            ? 'bg-red-100 text-red-700'
                                            : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($selectedBooking->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-2">Request</td>
                                <td class="p-2">{{ $selectedBooking->additional_request ?? '-' }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold p-2">Instruments</td>
                                <td class="p-2">
                                    @if ($selectedBooking->instruments && $selectedBooking->instruments->count() > 0)
                                        <div class="flex space-x-1">
                                            @foreach ($selectedBooking->instruments as $instrument)
                                                <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                    {{ $instrument->name }} x {{ $instrument->pivot->quantity }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">None</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="font-semibold p-2">Created At</td>
                                <td class="p-2">{{ $selectedBooking->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>

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
