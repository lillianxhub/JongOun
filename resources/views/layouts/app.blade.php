<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Jong-Oun') }}</title>
    @vite('resources/js/app.js')
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
    @livewireScripts
    <!-- Navbar -->
    <nav class="bg-white shadow p-6 flex justify-between">
        <a href="{{ route('home') }}" class="font-bold text-xl">JongOun</a>
        <div>
            @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="mr-4 hover:text-blue-600 transition-colors">Dashboard</a>
                    <a href="{{ route('profile.bookings') }}" class="mr-4 hover:text-blue-600 transition-colors">My
                        Booking</a>
                    <a href="{{ route('profile.show') }}" class="mr-4 hover:text-blue-600 transition-colors">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500">Logout</button>
                    </form>
                @elseif(auth()->user()->role === 'user')
                    <a href="{{ route('profile.bookings') }}" class="mr-4 hover:text-blue-600 transition-colors">My
                        Booking</a>
                    <a href="{{ route('profile.show') }}" class="mr-4 hover:text-blue-600 transition-colors">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500">Logout</button>
                    </form>
                @endif
            @else
                <a href="#service" class="mr-4 hover:text-blue-500 transition-colors">Service</a>
                <a href="{{ route('booking') }}" class="mr-4 hover:text-blue-500 transition-colors">Booking</a>
                <a href="#about" class="hover:text-blue-500">About</a>
                <a href="{{ route('login') }}" class="mr-4">Login</a>
                <!-- <a href="{{ route('register') }}" class="text-blue-500">Register</a> -->
            @endauth
        </div>
    </nav>

    <main class="py-6">
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>
