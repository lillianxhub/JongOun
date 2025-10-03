<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Jong-Oun') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-200">
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
    @livewireScripts
    <!-- Navbar -->
    <nav class="bg-white shadow p-6 flex justify-between">
        <a href="{{ route('home') }}" class="font-bold text-xl">JongOun</a>
        <div>
            @auth
                @if (auth()->user()->role === 'admin')
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}#service" class="hover:text-blue-500 transition-colors">Service</a>
                        <a href="{{ route('booking') }}" class="hover:text-blue-500 transition-colors">Booking</a>
                        <a href="{{ route('home') }}#about" class="hover:text-blue-600 transition-colors">About</a>

                        <!-- Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative inline-flex">
                            <!-- button -->
                            <button @click="open = !open"
                                class="flex items-center space-x-2 bg-black text-white px-4 py-2 rounded shadow hover:bg-gray-800 transition">

                                <span>{{ Auth::user()->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- menu -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-10 w-40 bg-white border rounded-lg py-1 shadow-lg z-50">
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Dashboard</a>
                                <a href="{{ route('profile.bookings') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Booking</a>
                                <a href="{{ route('user.profile') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-red-500 hover:bg-red-50">Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @elseif(auth()->user()->role === 'user')
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}#service" class="hover:text-blue-500 transition-colors">Service</a>
                        <a href="{{ route('booking') }}" class="hover:text-blue-500 transition-colors">Booking</a>
                        <a href="{{ route('home') }}#about" class="hover:text-blue-600 transition-colors">About</a>

                        <!-- Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative inline-flex">
                            <!-- button -->
                            <button @click="open = !open"
                                class="flex items-center space-x-2 bg-black text-white px-4 py-2 rounded shadow hover:bg-gray-800 transition">
                                <span>{{ Auth::user()->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- menu -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-10 w-48 bg-white border rounded-lg shadow-lg py-2 z-50">
                                <a href="{{ route('profile.bookings') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Booking</a>
                                <a href="{{ route('user.profile') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-red-500 hover:bg-red-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <a href="#service" class="mr-4 hover:text-blue-500 transition-colors">Service</a>
                <a href="{{ route('booking') }}" class="mr-4 hover:text-blue-500 transition-colors">Booking</a>
                <a href="#about" class="mr-4 hover:text-blue-500 transition-colors">About</a>
                <a href="{{ route('login') }}" class="mr-4 hover:text-blue-500 transition-colors">Sign in</a>
                <!-- <a href="{{ route('register') }}" class="text-blue-500">Register</a> -->
            @endauth
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>
