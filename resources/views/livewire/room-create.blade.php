<div>
    <div class="flex justify-end">
        <button wire:click="openAdd"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow font-bold">
            Add Room
        </button>
    </div>

    <!-- Add Room Modal -->
    @if ($showAddModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-8 rounded shadow-lg w-full max-w-xl">
                <h2 class="text-2xl font-bold mb-4">Add New Room</h2>
                <form wire:submit.prevent="addRoom">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="add_name">Room Name</label>
                        <input type="text" id="add_name" wire:model.defer="addData.name"
                            class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="add_room_type_id">Room Type</label>
                        <select id="add_room_type_id" wire:model.defer="addData.room_type_id"
                            class="w-full border rounded px-3 py-2" required>
                            <option value="">-- Select Room Type --</option>
                            @foreach ($roomTypes as $type)
                                <option value="{{ $type->id }}" data-max="{{ $type->max_capacity }}"
                                    data-name="{{ $type->name }}">{{ $type->name }} (max {{ $type->max_capacity }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="add_capacity">Capacity</label>
                        <input type="number" id="add_capacity" wire:model="addData.capacity"
                            class="w-full border rounded px-3 py-2" required min="1">
                        @error('addData.capacity')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Instruments</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach (['Lead-Guitar', 'Rhythm-Guitar', 'Drum', 'Bass', 'Microphone', 'Keyboard', 'Piano'] as $instrument)
                                <label class="flex items-center">
                                    <input type="checkbox" value="{{ $instrument }}"
                                        wire:model.defer="addData.instruments" class="mr-2"> {{ $instrument }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="add_price">Price (฿/hour)</label>
                        <input type="number" id="add_price" wire:model.defer="addData.price"
                            class="w-full border rounded px-3 py-2" step="0.01" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="closeAdd"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded shadow">Cancel</button>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded shadow">Add
                            Room</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
