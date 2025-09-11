<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>JongOun Music Room</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <nav class="flex justify-between p-6 bg-white shadow">
        <a href="{{ route('home') }}" class="text-xl font-bold">JongOun</a>
        <div class="space-x-4">
            <a href="#service" class="hover:text-blue-500 transition-colors">Service</a>
            <a href="{{ route('booking') }}" class="hover:text-blue-500 transition-colors">Booking</a>
            <a href="#about" class=" hover:text-blue-500">About</a>
            @auth
                <a href="{{ route('profile.bookings') }}"
                    class="bg-black hover:bg-gray-800 text-white font-semibold px-4 py-2 rounded shadow transition">Profile</a>
            @else
                <a href="{{ route('login') }}"
                    class="bg-black hover:bg-gray-800 text-white font-semibold px-4 py-2 rounded shadow transition">Sign
                    In</a>
            @endauth
        </div>
    </nav>

    <!-- Sections -->
    <section id="home" class="h-screen flex items-center justify-center bg-gray-200">
        <h1 class="text-5xl font-bold">Welcome to JongOun Music Room</h1>
    </section>

    <section id="service" class="p-20 bg-white">
        <h2 class="text-3xl font-bold mb-10 text-center">Our Rooms</h2>
        <div class="flex flex-col gap-10">

            <div class="flex flex-col gap-10">

                @foreach ($RoomTypes as $index => $RoomType)
                    <div
                        class="flex flex-col md:flex-row items-center bg-gray-100 rounded-xl shadow p-6 {{ $index % 2 == 1 ? 'md:flex-row-reverse' : '' }}">

                        {{-- ข้อมูลห้อง --}}
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="font-bold text-2xl mb-2">{{ $RoomType->name }}</h3>
                            <p class="mb-1">Capacity: {{ $RoomType->max_capacity }}</p>
                            <p class="mb-4">{{ $RoomType->detail }} </p>
                            <a href="{{ route('booking') }}"
                                class="bg-black hover:bg-gray-800 text-white font-semibold px-4 py-2 rounded shadow transition">
                                Book Now
                            </a>
                        </div>

                        {{-- รูปภาพ --}}
                        <div class="flex-1 flex justify-center mt-4 md:mt-0">
                            <img src="{{ asset('images/rooms/' . $RoomType->image) }}" alt="{{ $RoomType->name }}"
                                class="w-80 h-52 object-cover rounded-lg shadow-md" />
                        </div>
                    </div>
                @endforeach

            </div>
            {{-- <!-- Room 1: Image Right -->
            @foreach ($RoomTypes as $RoomType)
                <div class="flex flex-col md:flex-row items-center bg-gray-100 rounded-xl shadow p-6">
                    <div class="flex-1 order-2 md:order-1 text-center md:text-left">
                        <h3 class="font-bold text-2xl mb-2">Room A</h3>
                        <p class="mb-1">Capacity: 5</p>
                        <p class="mb-1">Instruments: Piano, Guitar</p>
                        <p class="mb-4">Price: 200฿ / hour</p>
                        <a href="{{ route('booking') }}"
                            class="bg-black hover:bg-gray-800 text-white font-semibold px-4 py-2 rounded shadow transition">Book
                            Now</a>
                    </div>
                    <div class="flex-1 order-1 md:order-2 flex justify-center mb-4 md:mb-0">
                        <img src="/images/rooms/room1.png" alt="Room A"
                            class="w-100 h-45 object-cover rounded-lg shadow-md" />
                    </div>
                </div>
                <!-- Room 2: Image Left -->
                <div class="flex flex-col md:flex-row items-center bg-gray-100 rounded-xl shadow p-6 gap-20">
                    <div class="flex-1 flex justify-center mb-4 md:mb-0">
                        <img src="/images/rooms/room2.png" alt="Room B"
                            class="w-100 h-45 object-cover rounded-lg shadow-md" />
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="font-bold text-2xl mb-2">Room B</h3>
                        <p class="mb-1">Capacity: 3</p>
                        <p class="mb-1">Instruments: Drum, Bass</p>
                        <p class="mb-4">Price: 180฿ / hour</p>
                        <a href="{{ route('booking') }}"
                            class="bg-black hover:bg-gray-800 text-white font-semibold px-4 py-2 rounded shadow transition">Book
                            Now</a>
                    </div>
                </div>
                <!-- Room 3: Image Right -->
                <div class="flex flex-col md:flex-row items-center bg-gray-100 rounded-xl shadow p-6">
                    <div class="flex-1 order-2 md:order-1 text-center md:text-left">
                        <h3 class="font-bold text-2xl mb-2">Room C</h3>
                        <p class="mb-1">Capacity: 2</p>
                        <p class="mb-1">Instruments: Violin, Microphone</p>
                        <p class="mb-4">Price: 150฿ / hour</p>
                        <a href="{{ route('booking') }}"
                            class="bg-black hover:bg-gray-800 text-white font-semibold px-4 py-2 rounded shadow transition">Book
                            Now</a>
                    </div>
                    <div class="flex-1 order-1 md:order-2 flex justify-center mb-4 md:mb-0">
                        <img src="/images/rooms/room3.png" alt="Room C"
                            class="w-100 h-45 object-cover rounded-lg shadow-md" />
                    </div>
                </div> --}}
        </div>
    </section>


    <footer id="about" class="w-full bg-white py-12 px-4 mt-10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start gap-20">
            <div class="flex-1 flex items-center mb-8 md:mb-0 text-center">
                <span class="text-2xl font-extrabold text-gray-800">JongOun</span>
            </div>
            <div class="flex-1 mb-8 md:mb-0">
                <h2 class="text-2xl font-bold mb-4">About JongOun</h2>
                <p>At JongOun, we believe that every great performance begins with the perfect rehearsal. Our platform
                    makes it easy to discover, book, and manage music rehearsal rooms that fit your schedule and style.
                    Whether you are a solo artist preparing for your next gig, a band polishing your sound, or simply
                    someone who loves making music, JongOun is here to provide a convenient and inspiring space. We are
                    committed to making the booking process simple, transparent, and accessible, so you can focus on
                    what matters most — creating music.</p>
            </div>
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-4">Contact</h2>
                <p>College of Computing Khon Kaen University.<br>
                    123 Vidhayavibaj Building, Mitraphap road Muang District, Khon Kaen 40002</p>
                <p class="mt-2">Email: info@jongoun.com</p>
            </div>
        </div>

        <!-- Go to Top Button -->
        <button onclick="window.scrollTo({top: 0, behavior: 'smooth'});"
            class="fixed bottom-8 right-8 bg-black text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg hover:bg-gray-800 transition z-50"
            aria-label="Go to top">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </footer>

</body>

</html>
