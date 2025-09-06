<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;
use App\Models\RoomType;

class RoomManager extends Component
{
    public $rooms;
    public $roomTypes;
    public $editRoomId = null;
    public $deleteRoomId = null;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $editData = [
        'name' => '',
        'capacity' => '',
        'room_type_id' => '',
        'instruments' => [],
        'price' => '',
    ];

    protected $rules = [
        'editData.name' => 'required|string|max:255',
        'editData.capacity' => 'required|integer|min:1',
        'editData.room_type_id' => 'required|exists:room_types,id',
        'editData.instruments' => 'required|array|min:1',
        'editData.instruments.*' => 'string|max:255',
        'editData.price' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->rooms = Room::with('roomType')->get();
        $this->roomTypes = RoomType::all();
    }

    public function openEdit($id)
    {
        $room = Room::findOrFail($id);
        $this->editRoomId = $id;
        $this->editData = [
            'name' => $room->name,
            'capacity' => $room->capacity,
            'room_type_id' => $room->room_type_id,
            'instruments' => is_array($room->instruments) ? $room->instruments : (json_decode($room->instruments, true) ?? []),
            'price' => $room->price,
        ];
        $this->showEditModal = true;
    }

    public function updateRoom()
    {
        $this->validate();
        $room = Room::findOrFail($this->editRoomId);
        $roomType = RoomType::find($this->editData['room_type_id']);
        if ($this->editData['capacity'] > $roomType->max_capacity) {
            $this->addError('editData.capacity', 'Capacity exceeds max for this room type (' . $roomType->max_capacity . ')');
            return;
        }
        $room->update([
            'name' => $this->editData['name'],
            'capacity' => $this->editData['capacity'],
            'room_type_id' => $this->editData['room_type_id'],
            'instruments' => json_encode($this->editData['instruments']),
            'price' => $this->editData['price'],
        ]);
        $this->showEditModal = false;
        $this->rooms = Room::with('roomType')->get();
        $this->dispatch('room-updated', ['success' => true, 'message' => 'Room updated successfully!']);
    }

    public function openDelete($id)
    {
        $this->deleteRoomId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteRoom()
    {
        Room::findOrFail($this->deleteRoomId)->delete();
        $this->showDeleteModal = false;
        $this->rooms = Room::with('roomType')->get();
        $this->dispatch('room-updated', ['success' => true, 'message' => 'Room deleted successfully!']);
    }

    public function render()
    {
        return view('livewire.room-manager', [
            'rooms' => $this->rooms,
            'roomTypes' => $this->roomTypes,
        ]);
    }
}
