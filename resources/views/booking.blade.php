<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Booking
        </h2>
    </x-slot>

    <div class="container mx-auto mt-10">
        @livewire('booking-stepper')
    </div>
</x-app-layout>
