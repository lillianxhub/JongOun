<div>
    <div class="flex justify-end">
        <button wire:click="openAdd"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow font-bold">
            Add Room
        </button>
    </div>

    <!-- Add Room Modal -->
    @if ($showAddModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" wire:click="closeAdd">
            <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-xl relative mx-4" wire:click.stop>
                <!-- Close Button -->
                <button wire:click="closeAdd"
                    class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-xl transition-colors"
                    title="Close">
                    &times;
                </button>

                <!-- Modal Header -->
                <div class="mb-6">
                    <div class="flex items-center mb-2">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fa-solid fa-plus text-green-600"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Add New Room</h2>
                    </div>
                    <p class="text-gray-600 text-sm">Create a new music room with instruments and pricing</p>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="confirmAdd">
                    <div class="space-y-4">
                        <!-- Room Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="add_name">
                                Room Name <span class="text-red-500">*</span>
                            </label>
                            <div>
                                <input type="text" id="add_name" wire:model.defer="addData.name"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('addData.name') border-red-500 @enderror"
                                    placeholder="Enter room name" required>
                            </div>
                            @error('addData.name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Room Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="add_room_type_id">
                                Room Type <span class="text-red-500">*</span>
                            </label>
                            <div>
                                <select id="add_room_type_id" wire:model.defer="addData.room_type_id"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent appearance-none @error('addData.room_type_id') border-red-500 @enderror"
                                    required>
                                    <option value="">Select room type</option>
                                    @foreach ($roomTypes as $type)
                                        <option value="{{ $type->id }}" data-max="{{ $type->max_capacity }}"
                                            data-name="{{ $type->name }}">
                                            {{ $type->name }} (max {{ $type->max_capacity }} people)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('addData.room_type_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Capacity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="add_capacity">
                                Capacity <span class="text-red-500">*</span>
                            </label>
                            <div>
                                <input type="number" id="add_capacity" wire:model="addData.capacity" min="1"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('addData.capacity') border-red-500 @enderror"
                                    placeholder="Enter capacity" required>
                            </div>
                            @error('addData.capacity')
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
                                        class="flex items-center space-x-3 cursor-pointer p-3 rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-green-300 transition-all duration-200">
                                        <input type="checkbox" value="{{ $instrument }}"
                                            wire:model.defer="addData.instruments"
                                            class="rounded border-gray-300 text-green-600 focus:ring-green-500 w-4 h-4">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-700 font-medium">{{ $instrument }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('addData.instruments')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="add_price">
                                Price (฿/hour) <span class="text-red-500">*</span>
                            </label>
                            <div>
                                <input type="number" id="add_price" wire:model.defer="addData.price" step="0.01"
                                    min="0"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('addData.price') border-red-500 @enderror"
                                    placeholder="0.00" required>
                            </div>
                            @error('addData.price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-100">
                        <button type="button" wire:click="closeAdd"
                            class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 flex items-center">
                            <i class="fa-solid fa-times mr-2"></i>
                            Cancel
                        </button>
                        <button type="button" wire:click="confirmAdd"
                            class="px-6 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                            <i class="fa-solid fa-plus mr-2"></i>
                            Add Room
                        </button>
                    </div>
                </form>
            </div>
        </div>

    @endif

    <!-- Custom Styles for Enhanced Add Modal -->
    <style>
        /* Enhanced checkbox styling */
        input[type="checkbox"]:checked+div {
            background-color: #f0fdf4;
            border-color: #22c55e;
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
            color: #22c55e;
            transform: scale(1.1);
        }
    </style>
</div>
