@extends('layouts.admin')

@section('page-title', 'Bookings Overview')
@section('page-description', 'Monitor your bookings and rooms at a glance')

@section('content')

    <!-- Main Dashboard Component (includes stats cards) -->
    {{-- <div class="mb-8">
        @livewire('admin.dashboard')
    </div> --}}

    <!-- Dashboard Sections -->
    <div class="space-y-8">
        <!-- Bookings Management -->
        <div class="bg-white rounded-lg shadow" id="bookings">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    Bookings Management
                </h3>
            </div>
            <div class="p-6">
                @if (class_exists('App\Livewire\Admin\Bookings'))
                    @livewire('admin.bookings')
                @else
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        <strong>Component not found:</strong> admin.bookings
                        <br>Expected file: <code>app/Livewire/Admin/Bookings.php</code>
                    </div>
                @endif
            </div>
        </div>

    @endsection
