<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ config('app.name', 'Jong-Oun') }}</title>

    @vite(['resources/js/app.js'])
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> --}}
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-100">
    <!-- SweetAlert2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.addEventListener('swal:confirm', event => {
                const detail = event.detail[0];
                const confirmColor = detail.color === 'green' ? '#28a745' : '#dc3545';

                Swal.fire({
                    title: detail.title,
                    text: detail.text,
                    icon: detail.icon,
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: '#6c757d'
                }).then(result => {
                    if (result.isConfirmed) {
                        Livewire.dispatch(detail.method, {
                            id: detail.id
                        });
                    }
                });
            });

            window.addEventListener('swal:success', event => {
                const detail = event.detail[0];
                const confirmColor = detail.color === 'green' ? '#28a745' : '#dc3545';

                Swal.fire({
                    title: detail.title,
                    text: detail.text,
                    icon: detail.icon,
                    confirmButtonText: 'OK',
                    confirmButtonColor: confirmColor,
                }).then(() => {
                    if (detail.redirect) {
                        window.location.href = detail.redirect;
                    }
                });
            });
        });
    </script>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white fixed h-full overflow-y-auto">
            <!-- Logo/Brand -->
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-xl font-bold">JongOun Admin</h1>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-6">
                <div class="px-6 py-3">
                    <p class="text-gray-400 text-xs uppercase tracking-wider">Main Menu</p>
                </div>

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                    </svg>
                    Dashboard
                </a>

                <!-- Total Bookings -->
                <a href="{{ route('admin.bookings') }}"
                    class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.bookings') ? 'bg-gray-700 text-white border-r-4 border-blue-500' : '' }}"
                    onclick="document.getElementById('bookings').scrollIntoView({behavior: 'smooth'})">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    Total Bookings
                    <span class="ml-auto bg-blue-500 text-white text-xs rounded-full px-2 py-1"
                        id="sidebar-total-bookings">
                        {{ $totalBookings ?? 0 }}
                    </span>
                </a>

                <!-- Total Rooms -->
                <a href="#rooms"
                    class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                    onclick="document.getElementById('rooms').scrollIntoView({behavior: 'smooth'})">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    Total Rooms
                    <span class="ml-auto bg-green-500 text-white text-xs rounded-full px-2 py-1"
                        id="sidebar-total-rooms">
                        {{ $totalRooms ?? 0 }}
                    </span>
                </a>

                <!-- Divider -->
                <div class="px-6 py-3 mt-6">
                    <p class="text-gray-400 text-xs uppercase tracking-wider">Actions</p>
                </div>

                <!-- Back to Website -->
                <a href="{{ route('home') }}"
                    class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Website
                </a>

                <!-- User Profile -->
                <div class="px-6 py-3 mt-6">
                    <p class="text-gray-400 text-xs uppercase tracking-wider">Account</p>
                </div>

                <a href="{{ route('profile.show') }}"
                    class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-6 py-3 text-gray-300 hover:bg-red-700 hover:text-white transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Logout
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 ml-64">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">
                                @yield('page-title', 'Admin Dashboard')
                            </h2>
                            <p class="text-gray-600 text-sm mt-1">
                                @yield('page-description', 'Welcome to your admin dashboard')
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-600">Welcome, {{ Auth::user()->name }}</span>
                            <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts

    <script>
        // Listen for stats updates from Livewire components
        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('stats-updated', event => {
                const data = event.detail[0];

                // Update sidebar stats
                if (document.getElementById('sidebar-total-bookings')) {
                    document.getElementById('sidebar-total-bookings').textContent = data.totalBookings || 0;
                }
                if (document.getElementById('sidebar-total-rooms')) {
                    document.getElementById('sidebar-total-rooms').textContent = data.totalRooms || 0;
                }
            });
        });
    </script>
</body>

</html>
