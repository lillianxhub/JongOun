<div>
    @if(session('success'))
    <div class="mb-4 text-green-600 font-bold">{{ session('success') }}</div>
    @endif
    <div class="bg-white shadow rounded-lg p-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Room Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Room Type</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Capacity</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Instruments</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $room)
                <tr>
                    <td class="px-4 py-2">{{ $room->name }}</td>
                    <td class="px-4 py-2">{{ $room->roomType->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $room->capacity }}</td>
                    <td class="px-4 py-2">
                        @php
                        $instruments = is_array($room->instruments) ? $room->instruments : (json_decode($room->instruments, true) ?? []);
                        @endphp
                        @foreach(array_slice($instruments, 0, 3) as $instrument)
                        <span class="mr-2">{{ $instrument }}</span>
                        @endforeach
                        @if(count($instruments) > 3)
                        @endif
                    </td>
                    <td class="px-4 py-2">{{ $room->price }}</td>
                    <td class="px-4 py-2">
                        <button wire:click="openEdit({{ $room->id }})" class="fa-solid fa-pen-to-square text-blue-500 hover:text-blue-700 mr-2"></button>

                        <button wire:click="openDelete({{ $room->id }})" class="fa-solid fa-square-minus text-red-600 hover:text-red-700 rounded"></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-2 text-center text-gray-500">No rooms found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Edit Modal -->
    <div x-data="{ show: @entangle('showEditModal') }" x-show="show" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-8 rounded shadow-lg w-full max-w-xl">
            <h2 class="text-2xl font-bold mb-4">Edit Room</h2>
            <form wire:submit.prevent="updateRoom">
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="edit_name">Room Name</label>
                    <input type="text" id="edit_name" wire:model.defer="editData.name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="edit_capacity">Capacity</label>
                    <input type="number" id="edit_capacity" wire:model.defer="editData.capacity" class="w-full border rounded px-3 py-2" required>
                    @error('editData.capacity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="edit_room_type_id">Room Type</label>
                    <select id="edit_room_type_id" wire:model.defer="editData.room_type_id" class="w-full border rounded px-3 py-2" required>
                        @foreach($roomTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }} (max {{ $type->max_capacity }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Instruments</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(['Lead-Guitar','Rhythm-Guitar','Drum','Bass','Microphone','Keyboard','Piano'] as $instrument)
                        <label class="flex items-center">
                            <input type="checkbox" value="{{ $instrument }}" wire:model.defer="editData.instruments" class="mr-2"> {{ $instrument }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="edit_price">Price (฿/hour)</label>
                    <input type="number" id="edit_price" wire:model.defer="editData.price" class="w-full border rounded px-3 py-2" step="0.01" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="show = false" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded shadow">Cancel</button>
                    <button type="submit" class="bg-black hover:bg-gray-800 text-white font-semibold px-6 py-2 rounded shadow">Update Room</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-data="{ show: @entangle('showDeleteModal') }" x-show="show" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-5 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
            <p class="mb-6">Are you sure you want to delete this room?</p>
            <div class="flex justify-end gap-2">
                <button type="button" @click="show = false" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded shadow">Cancel</button>
                <button wire:click="deleteRoom" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded shadow">Delete</button>
            </div>
        </div>
    </div>
</div>