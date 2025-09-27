<div>
    <div class="bg-white shadow rounded-lg p-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Name
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Type
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Instruments</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($rooms as $room)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $room->name }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                            {{ $room->roomType->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                            {{ $room->capacity }} {{ $room->capacity == 1 ? 'person' : 'people' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            @php
                                $instruments = is_array($room->instruments)
                                    ? $room->instruments
                                    : json_decode($room->instruments, true) ?? [];
                            @endphp
                            <div class="flex flex-wrap gap-1">
                                @foreach (array_slice($instruments, 0, 3) as $instrument)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                        {{ $instrument }}
                                    </span>
                                @endforeach
                                @if (count($instruments) > 3)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600">
                                        +{{ count($instruments) - 3 }} more
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                            ฿{{ number_format($room->price, 2) }}/hour
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button wire:click="openEdit({{ $room->id }})"
                                    class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded-full hover:bg-blue-100"
                                    title="Edit Room">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $room->id }})"
                                    class="text-red-600 hover:text-red-900 transition-colors p-1 rounded-full hover:bg-red-100"
                                    title="Delete Room">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-door-closed text-4xl text-gray-300 mb-2"></i>
                                <p class="text-lg">No rooms found</p>
                                <p class="text-sm text-gray-400">Add your first room to get started</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Edit Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            wire:click="closeEditModal">
            <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-xl relative mx-4" wire:click.stop>
                <!-- Close Button -->
                <button wire:click="closeEditModal"
                    class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-xl transition-colors"
                    title="Close">
                    &times;
                </button>

                <!-- Modal Header -->
                <div class="mb-6">
                    <div class="flex items-center mb-2">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fa-solid fa-pen-to-square text-blue-600"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Edit Room</h2>
                    </div>
                    <p class="text-gray-600 text-sm">Edit a music room with instruments and pricing</p>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="confirmUpdate">
                    <div class="space-y-4">
                        <!-- Room Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="name">
                                Room Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" wire:model="name"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                placeholder="Enter room name">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Room Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="room_type_id">
                                Room Type <span class="text-red-500">*</span>
                            </label>
                            <select id="room_type_id" wire:model="room_type_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('room_type_id') border-red-500 @enderror">
                                <option value="">Select room type</option>
                                @foreach ($roomTypes as $type)
                                    <option value="{{ $type->id }}">
                                        {{ $type->name }} (max {{ $type->max_capacity }} people)
                                    </option>
                                @endforeach
                            </select>
                            @error('room_type_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Capacity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="capacity">
                                Capacity <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="capacity" wire:model="capacity" min="1"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('capacity') border-red-500 @enderror"
                                placeholder="Enter capacity">
                            @error('capacity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        <!-- Instruments -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Instruments <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach (['Lead-Guitar', 'Rhythm-Guitar', 'Drum', 'Bass', 'Microphone', 'Keyboard', 'Piano'] as $instrument)
                                    <label
                                        class="flex items-center space-x-3 cursor-pointer p-3 rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-blue-300 transition-all duration-200">
                                        <input type="checkbox" value="{{ $instrument }}" wire:model="instruments"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">{{ $instrument }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('instruments')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="price">
                                Price (฿/hour) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="price" wire:model="price" step="0.01" min="0"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror"
                                placeholder="0.00">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 mt-8">
                        <button type="button" wire:click="closeEditModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="fa-solid fa-times mr-2"></i>
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="fa-solid fa-pen-to-square mr-2 text-white"></i>
                            Update Room
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Custom Styles for Enhanced Edit Modal -->
    <style>
        /* Enhanced checkbox styling */
        input[type="checkbox"]:checked+div {
            background-color: #f0fbfd;
            border-color: #2563eb;
        }

        /* Smooth focus transitions */
        input:focus,
        select:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
        }


        /* Icon animations */
        .fa-solid {
            transition: all 0.2s ease;
        }

        input:focus+.fa-solid,
        select:focus+.fa-solid {
            color: #2563eb;
            transform: scale(1.1);
        }
    </style>
</div>
