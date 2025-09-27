<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Room;
use App\Models\RoomType;
use Livewire\Attributes\Validate;

class RoomManager extends Component
{
    public $rooms;
    public $roomTypes;
    public $editRoomId = null;
    public $deleteRoomId = null;
    public $showEditModal = false;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|integer|min:1')]
    public $capacity = '';

    #[Validate('required|exists:room_types,id')]
    public $room_type_id = '';

    #[Validate('required|array|min:1')]
    public $instruments = [];

    #[Validate('required|numeric|min:0')]
    public $price = '';

    // protected $listeners = [
    //     'updateRoom' => 'updateRoom',
    //     'deleteRoom' => 'deleteRoom'
    // ];

    public function mount()
    {
        $this->loadData();
    }

    private function loadData()
    {
        $this->rooms = Room::with('roomType')->get();
        $this->roomTypes = RoomType::all();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->capacity = '';
        $this->room_type_id = '';
        $this->instruments = [];
        $this->price = '';
        $this->editRoomId = null;
    }

    public function openEdit($id)
    {
        try {
            $room = Room::findOrFail($id);
            $this->editRoomId = $id;
            $this->name = $room->name;
            $this->capacity = $room->capacity;
            $this->room_type_id = $room->room_type_id;
            $this->instruments = is_array($room->instruments)
                ? $room->instruments
                : (json_decode($room->instruments, true) ?? []);
            $this->price = $room->price;
            $this->showEditModal = true;
        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Room not found.',
                'icon' => 'error',
            ]);
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function confirmUpdate()
    {
        // Validate before showing confirmation
        $this->validate();

        // Additional validation for room capacity
        $roomType = RoomType::find($this->room_type_id);
        if ($roomType && $this->capacity > $roomType->max_capacity) {
            $this->addError('capacity', 'Capacity exceeds maximum for this room type (' . $roomType->max_capacity . ')');
            return;
        }

        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => 'You want to update this room?',
            'icon' => 'warning',
            'method' => 'updateRoom',
            'color' => 'green'
        ]);
    }

    #[On('updateRoom')]
    public function updateRoom($id = null)
    {
        try {
            $this->validate();

            $room = Room::findOrFail($this->editRoomId);
            $roomType = RoomType::find($this->room_type_id);

            if ($this->capacity > $roomType->max_capacity) {
                $this->addError('capacity', 'Capacity exceeds maximum for this room type (' . $roomType->max_capacity . ')');
                return;
            }

            $room->update([
                'name' => $this->name,
                'capacity' => $this->capacity,
                'room_type_id' => $this->room_type_id,
                'instruments' => json_encode($this->instruments),
                'price' => $this->price,
            ]);

            $this->closeEditModal();
            $this->loadData();

            $this->dispatch('swal:success', [
                'title' => 'Updated!',
                'text' => 'Room has been updated successfully.',
                'icon' => 'success',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to update room. Please try again.',
                'icon' => 'error',
            ]);
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteRoomId = $id;
        $room = Room::findOrFail($id);
        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => "You want to delete room : {$room->name} ? This action cannot be undone.",
            'icon' => 'warning',
            'method' => 'deleteRoom',
            'color' => 'red'
        ]);
    }

    #[On('deleteRoom')]
    public function deleteRoom($id = null)
    {
        try {
            $roomId = $id ?? $this->deleteRoomId;
            Room::findOrFail($roomId)->delete();
            $this->loadData();

            $this->dispatch('swal:success', [
                'title' => 'Deleted!',
                'text' => 'Room has been deleted successfully.',
                'icon' => 'success',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to delete room. Please try again.',
                'icon' => 'error',
            ]);
        }
    }

    public function restoreRoom($id)
    {
        try {
            $room = Room::withTrashed()->findOrFail($id);
            $room->restore();

            $this->dispatch('swal:success', [
                'title' => 'Restored!',
                'text' => "Room '{$room->name}' has been restored successfully.",
                'icon' => 'success',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to restore room. Please try again.',
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.room-manager', [
            'rooms' => $this->rooms,
            'roomTypes' => $this->roomTypes,
        ]);
    }
}
