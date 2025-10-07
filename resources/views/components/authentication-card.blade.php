<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-transparent">

    <a href="{{ route('home') }}" class="text-3xl font-bold text-white">JongOun</a>
    <div class="w-full sm:max-w-lg mt-6 px-10 py-8 bg-dark shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
