<x-app-layout>
    <!-- หน้า Home / Hero Section -->
    <section id="home"
        class="h-fit p-5 md:p-10 flex flex-col md:flex-row items-center justify-between bg-header-gradient bg-cover bg-center relative">
        <!-- ข้อความ -->
        <div class="p-5 md:p-10 rounded-xl text-left text-white max-w-full md:max-w-3xl mb-8 md:mb-0">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 bg-text-gradient bg-clip-text text-transparent">
                Book Premium Music Studios for Professionals
            </h1>
            <p class="text-base sm:text-lg md:text-xl text-gray-200 mb-6">
                Your creative space for music production. Equipped with
                professional gear and the perfect atmosphere for all types of work, whether recording, band practice, or
                live sessions.
            </p>
            <a href="#service"
                class="inline-block px-6 md:px-8 py-3 md:py-4 rounded-full font-bold text-lg bg-btn-gradient shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                Explore Rooms
            </a>
        </div>

        <!-- รูป -->
        <div class="hidden md:flex w-1/2 justify-end">
            <img src="{{ asset('images/rooms/background_v2.jpg') }}" alt="Hero Image"
                class="w-full h-full rounded-xl object-cover shadow-lg">
        </div>
    </section>

    <!-- Rooms / Service Section -->
    <section id="service" class="py-16 md:py-20 px-4 md:px-20 bg-hero-gradient">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-12 md:mb-16 text-primary">Our Rooms</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 md:gap-12">
            @foreach ($RoomTypes as $RoomType)
                <div
                    class="bg-white rounded-2xl shadow-lg overflow-hidden transition transform hover:shadow-primary hover:-translate-y-2 flex flex-col">
                    <!-- รูป -->
                    <img src="{{ asset('images/rooms/' . $RoomType->image) }}" alt="{{ $RoomType->name }}"
                        class="w-full h-48 sm:h-56 md:h-56 object-cover">

                    <!-- รายละเอียด -->
                    <div class="p-5 md:p-6 flex flex-col flex-1 text-left bg-dark">
                        <h3 class="font-bold text-xl sm:text-2xl mb-2 text-primary">{{ $RoomType->name }}</h3>
                        <p class="mb-1 text-white">Capacity: {{ $RoomType->max_capacity }}</p>
                        <p class="mb-4 text-white flex-1 text-sm sm:text-base">{{ $RoomType->detail }}</p>
                        <a href="{{ route('booking') }}"
                            class="w-full md:w-40 mt-auto inline-block px-4 md:px-6 py-2 md:py-3 rounded-full font-semibold bg-btn-gradient text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1 text-center">
                            Book Now
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Footer -->
    <footer id="about" class="w-full bg-black border-t border-gray-700 py-12 px-6">
        <div class="mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
            <div class="text-center md:text-left md:ml-20">
                <span class="text-2xl md:text-3xl font-extrabold text-white">JongOun</span>
            </div>
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-white mb-4">About JongOun</h2>
                <p class="text-gray-300 text-sm md:text-base">
                    At JongOun, we believe that every great performance begins with the perfect rehearsal. Our platform
                    makes it easy to discover, book, and manage music rehearsal rooms that fit your schedule and style.
                    Whether you are a solo artist preparing for your next gig, a band polishing your sound, or simply
                    someone who loves making music, JongOun is here to provide a convenient and inspiring space. We are
                    committed to making the booking process simple, transparent, and accessible, so you can focus on
                    what matters most — creating music.
                </p>
            </div>
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-white mb-4">Contact</h2>
                <p class="text-gray-300 text-sm md:text-base">
                    College of Computing, Khon Kaen University<br>
                    123 Vidhayavibaj Building, Mitraphap Road, Khon Kaen 40002
                </p>
                <p class="mt-2 text-gray-300 text-sm md:text-base">Email: info@jongoun.com</p>
            </div>
        </div>
    </footer>
</x-app-layout>
