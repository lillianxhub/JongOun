<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Jong-Oun') }}</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-white shadow p-6 flex justify-between">
        <a href="{{ route('home') }}" class="font-bold text-xl">JongOun</a>
        <div>
            @auth
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('dashboard') }}" class="mr-4 hover:text-blue-600 transition-colors">Dashboard</a>
            <a href="{{ route('profile.show') }}" class="mr-4 hover:text-blue-600 transition-colors">Profile</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-red-500">Logout</button>
            </form>
            @elseif(auth()->user()->role === 'user')
            <a href="{{ route('profile.bookings') }}" class="mr-4 hover:text-blue-600 transition-colors">My Booking</a>
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