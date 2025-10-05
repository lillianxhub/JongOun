<x-app-layout>
    <!-- หน้า Home -->
    <section id="home" class="h-screen flex items-center justify-center bg-cover bg-center"
        style="background-image: url('{{ asset('images/rooms/background.jpg') }}')">
        <div class="bg-black bg-opacity-50 p-10 rounded text-center text-white">
            <h1 class="text-5xl font-bold">Welcome to JongOun Music Room</h1>
            <p class="mt-4 text-lg">Your best place for rehearsal & music creativity</p>
        </div>
    </section>

    <section id="service" class="p-20 bg-white">
        <h2 class="text-3xl font-bold mb-10 text-center">Our Rooms</h2>
        <div class="flex flex-col gap-10">
            @foreach ($RoomTypes as $index => $RoomType)
                <div
                    class="flex flex-col md:flex-row items-center bg-gray-100 rounded-xl shadow p-6 {{ $index % 2 == 1 ? 'md:flex-row-reverse' : '' }}">
                    <div class="flex-1 flex justify-center mt-4 md:mt-0">
                        <img src="{{ asset('images/rooms/' . $RoomType->image) }}" alt="{{ $RoomType->name }}"
                            class="w-100 h-52 object-cover rounded-lg shadow-md">
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="font-bold text-2xl mb-2">{{ $RoomType->name }}</h3>
                        <p class="mb-1">Capacity: {{ $RoomType->max_capacity }}</p>
                        <p class="mb-4">{{ $RoomType->detail }}</p>
                        <a href="{{ route('booking') }}"
                            class="bg-black hover:bg-gray-800 text-white font-semibold px-4 py-2 rounded shadow transition">
                            Book Now
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Footer เฉพาะหน้า Home -->
    <footer id="about" class="w-full bg-gray-50 border-t border-gray-200 py-12 px-6 mt-16">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="text-center md:text-left">
                <span class="text-2xl font-extrabold text-gray-800">JongOun</span>
            </div>
            <div>
                <h2 class="text-2xl font-bold mb-4">About JongOun</h2>
                <p class="text-gray-600">At JongOun, we believe that every great performance begins with the perfect
                    rehearsal...</p>
            </div>
            <div>
                <h2 class="text-2xl font-bold mb-4">Contact</h2>
                <p class="text-gray-600">
                    College of Computing Khon Kaen University<br>
                    123 Vidhayavibaj Building, Mitraphap Road, Khon Kaen 40002
                </p>
                <p class="mt-2 text-gray-600">Email: info@jongoun.com</p>
            </div>
        </div>
    </footer>
</x-app-layout>
