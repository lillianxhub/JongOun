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
    <nav class="bg-white shadow" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="font-bold text-xl">JongOun</a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('home') }}#service" class="hover:text-blue-500 transition-colors">Service</a>
                            <a href="{{ route('booking') }}" class="hover:text-blue-500 transition-colors">Booking</a>
                            <a href="{{ route('home') }}#about" class="hover:text-blue-600 transition-colors">About</a>

                            <!-- Profile Dropdown -->
                            <div x-data="{ open: false }" class="relative inline-flex">
                                <button @click="open = !open"
                                    class="flex items-center space-x-2 bg-black text-white px-4 py-2 rounded shadow hover:bg-gray-800 transition">
                                    <span>{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                    class="absolute right-0 mt-12 w-40 bg-white border rounded-lg py-1 shadow-lg z-50">
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Dashboard</a>
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
                        @elseif(auth()->user()->role === 'user')
                            <a href="{{ route('home') }}#service" class="hover:text-blue-500 transition-colors">Service</a>
                            <a href="{{ route('booking') }}" class="hover:text-blue-500 transition-colors">Booking</a>
                            <a href="{{ route('home') }}#about" class="hover:text-blue-600 transition-colors">About</a>

                            <!-- Profile Dropdown -->
                            <div x-data="{ open: false }" class="relative inline-flex">
                                <button @click="open = !open"
                                    class="flex items-center space-x-2 bg-black text-white px-4 py-2 rounded shadow hover:bg-gray-800 transition">
                                    <span>{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                    class="absolute right-0 mt-12 w-48 bg-white border rounded-lg shadow-lg py-2 z-50">
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
                        @endif
                    @else
                        <a href="#service" class="hover:text-blue-500 transition-colors">Service</a>
                        <a href="{{ route('booking') }}" class="hover:text-blue-500 transition-colors">Booking</a>
                        <a href="#about" class="hover:text-blue-500 transition-colors">About</a>
                        <a href="{{ route('login') }}" class="hover:text-blue-500 transition-colors">Sign in</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-md hover:bg-gray-100">
                    <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                    <i class="fas fa-times text-xl" x-show="mobileMenuOpen"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95" class="md:hidden border-t">
            <div class="px-2 pt-2 pb-3 space-y-1">
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('home') }}#service"
                            class="block px-3 py-2 rounded-md hover:bg-gray-100">Service</a>
                        <a href="{{ route('booking') }}" class="block px-3 py-2 rounded-md hover:bg-gray-100">Booking</a>
                        <a href="{{ route('home') }}#about" class="block px-3 py-2 rounded-md hover:bg-gray-100">About</a>
                        <div class="border-t my-2"></div>
                        <div class="px-3 py-2 text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</div>
                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-3 py-2 rounded-md hover:bg-gray-100">Dashboard</a>
                        <a href="{{ route('profile.bookings') }}" class="block px-3 py-2 rounded-md hover:bg-gray-100">My
                            Booking</a>
                        <a href="{{ route('user.profile') }}"
                            class="block px-3 py-2 rounded-md hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-3 py-2 rounded-md text-red-500 hover:bg-red-50">Logout</button>
                        </form>
                    @elseif(auth()->user()->role === 'user')
                        <a href="{{ route('home') }}#service"
                            class="block px-3 py-2 rounded-md hover:bg-gray-100">Service</a>
                        <a href="{{ route('booking') }}" class="block px-3 py-2 rounded-md hover:bg-gray-100">Booking</a>
                        <a href="{{ route('home') }}#about"
                            class="block px-3 py-2 rounded-md hover:bg-gray-100">About</a>
                        <div class="border-t my-2"></div>
                        <div class="px-3 py-2 text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</div>
                        <a href="{{ route('profile.bookings') }}" class="block px-3 py-2 rounded-md hover:bg-gray-100">My
                            Booking</a>
                        <a href="{{ route('user.profile') }}"
                            class="block px-3 py-2 rounded-md hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-3 py-2 rounded-md text-red-500 hover:bg-red-50">Logout</button>
                        </form>
                    @endif
                @else
                    <a href="#service" class="block px-3 py-2 rounded-md hover:bg-gray-100">Service</a>
                    <a href="{{ route('booking') }}" class="block px-3 py-2 rounded-md hover:bg-gray-100">Booking</a>
                    <a href="#about" class="block px-3 py-2 rounded-md hover:bg-gray-100">About</a>
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md hover:bg-gray-100">Sign in</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>
