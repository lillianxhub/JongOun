@extends('layouts.admin')

@section('page-title', 'Instrument Overview')
@section('page-description', 'Monitor your instrument at a glance')

@section('content')
    <div>
        <div class="space-y-8">
            <!-- Instrument Management -->
            <div class="bg-white rounded-lg shadow" id="instrument">
                <div class="p-6">
                    @if (class_exists('App\Livewire\Admin\Instruments'))
                        @livewire('admin.instruments')
                    @else
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                            <strong>Component not found:</strong> admin.instruments
                            <br>Expected file: <code>app/Livewire/Admin/Instruments.php</code>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
