    <!-- Navbar -->
    <nav class="bg-black w-full shadow-md" x-data="{ mobileMenuOpen: false }">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="font-extrabold text-2xl text-white">JongOun</a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    @auth
                        @if (auth()->user()->role === 'admin' || auth()->user()->role === 'user')
                            <a href="{{ route('home') }}#service" class="text-white hover:text-primary transition">Service</a>
                            <a href="{{ route('booking') }}" class="text-white hover:text-primary transition">Booking</a>
                            <a href="{{ route('home') }}#about" class="text-white hover:text-primary transition">About</a>

                            <!-- Profile Dropdown -->
                            <div x-data="{ open: false }" class="relative inline-flex">
                                <button @click="open = !open"
                                    class="flex items-center space-x-2 bg-tranparent text-white px-4 py-2 rounded-full shadow transition">
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                        class="rounded-full h-10 w-10 object-cover">
                                    {{-- <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" --}}
                                    alt="{{ Auth::user()->name }}" class="rounded-full h-10 w-10 object-cover">
                                </button>
                                <div x-show="open" @click.away="open = false"
                                    class="absolute right-0 top-10 mt-2 w-48 bg-dark rounded-lg shadow-lg py-2 z-50">
                                    @if (auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="block px-4 py-2 text-white hover:bg-black">Dashboard</a>
                                    @endif
                                    <a href="{{ route('profile.bookings') }}"
                                        class="block px-4 py-2 text-white hover:bg-black">My Booking</a>
                                    <a href="{{ route('user.profile') }}"
                                        class="block px-4 py-2 text-white hover:bg-black">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-red-400 hover:bg-red-900">Logout</button>
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
                                    class="flex items-center space-x-2 bg-tranparent text-white px-4 py-2 rounded">
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                        class="rounded-full h-10 w-10 object-cover">
                                    {{-- <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" --}}
                                    alt="{{ Auth::user()->name }}" class="rounded-full h-10 w-10 object-cover">
                                </button>

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
                        @endif
                    @else
                        <a href="#service" class="text-white hover:text-primary transition">Service</a>
                        <a href="{{ route('booking') }}" class="text-white hover:text-primary transition">Booking</a>
                        <a href="#about" class="text-white hover:text-primary transition">About</a>
                        <a href="{{ route('login') }}" class="text-white hover:text-primary transition font-semibold">Sign
                            in</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden p-2 rounded-md text-white hover:text-primary hover:bg-gray-700 transition">
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
            x-transition:leave-end="opacity-0 transform scale-95"
            class="md:hidden bg-dark bg-opacity-95 border-t border-gray-700">
            <div class="px-4 pt-4 pb-6 space-y-2">
                @auth
                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'user')
                        <a href="{{ route('home') }}#service"
                            class="block px-3 py-2 rounded-md text-white hover:bg-primary hover:text-white transition">Service</a>
                        <a href="{{ route('booking') }}"
                            class="block px-3 py-2 rounded-md text-white hover:bg-primary hover:text-white transition">Booking</a>
                        <a href="{{ route('home') }}#about"
                            class="block px-3 py-2 rounded-md text-white hover:bg-primary hover:text-white transition">About</a>
                        <div class="border-t border-gray-700 my-2"></div>
                        <div class="px-3 py-2 text-sm font-semibold text-white">{{ Auth::user()->name }}</div>
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="block px-3 py-2 rounded-md text-white hover:bg-primary hover:text-white transition">Dashboard</a>
                        @endif
                        <a href="{{ route('profile.bookings') }}"
                            class="block px-3 py-2 rounded-md text-white hover:bg-primary hover:text-white transition">My
                            Booking</a>
                        <a href="{{ route('user.profile') }}"
                            class="block px-3 py-2 rounded-md text-white hover:bg-primary hover:text-white transition">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-3 py-2 rounded-md text-red-500 hover:bg-red-50 transition">Logout</button>
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
                    <a href="#service"
                        class="block px-3 py-2 rounded-md text-white hover:bg-primary hover:text-white transition">Service</a>
                    <a href="{{ route('booking') }}"
                        class="block px-3 py-2 rounded-md text-white hover:bg-primary hover:text-white transition">Booking</a>
                    <a href="#about"
                        class="block px-3 py-2 rounded-md text-white hover:bg-primary hover:text-white transition">About</a>
                    <a href="{{ route('login') }}"
                        class="block px-3 py-2 rounded-md text-white hover:bg-primary hover:text-white transition font-semibold">Sign
                        in</a>
                @endauth
            </div>
        </div>
    </nav>
