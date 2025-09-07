<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\RoomType;
use App\Models\Room;

class RoomCreate extends Component
{
    public $showAddModal = false;

    public $addData = [
        'name' => '',
        'capacity' => '',
        'room_type_id' => '',
        'instruments' => [],
        'available_times' => [],
        'price' => '',
    ];
    public $roomTypes = [];

    protected $rules = [
        'addData.name' => 'required|string|max:255',
        'addData.capacity' => 'required|integer|min:1',
        'addData.room_type_id' => 'required|exists:room_types,id',
        'addData.instruments' => 'required|array|min:1',
        'addData.instruments.*' => 'string|max:255',
        'addData.price' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->roomTypes = RoomType::all();
    }

    public function openAdd()
    {
        $this->addData = [
            'name' => '',
            'capacity' => '',
            'room_type_id' => '',
            'instruments' => [],
            'price' => '',
        ];
        $this->showAddModal = true;
    }

    public function closeAdd()
    {
        $this->showAddModal = false;
    }

    public function addRoom()
    {
        $this->validate();
        $roomType = RoomType::find($this->addData['room_type_id']);
        if ($this->addData['capacity'] > $roomType->max_capacity) {
            $this->addError('addData.capacity', 'Capacity exceeds max for this room type (' . $roomType->max_capacity . ')');
            return;
        }
        Room::create([
            'name' => $this->addData['name'],
            'capacity' => $this->addData['capacity'],
            'room_type_id' => $this->addData['room_type_id'],
            'instruments' => json_encode($this->addData['instruments']),
            'available_times' => json_encode(["00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"]),
            'price' => $this->addData['price'],
        ]);
        $this->showAddModal = false;
        $this->dispatch('room-updated', ['success' => true, 'message' => 'Room added successfully!']);
        $this->addData = [
            'name' => '',
            'capacity' => '',
            'room_type_id' => '',
            'instruments' => [],
            'price' => '',
        ];
    }

    public function render()
    {
        return view('livewire.room-create', [
            'roomTypes' => $this->roomTypes,
            'addData' => $this->addData,
            'showAddModal' => $this->showAddModal,
        ]);
    }
}
