<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Instrument;
use App\Models\Booking;
use App\Models\User;
use App\Models\Room;

class Instruments extends Component
{
    public $totalBookings;
    public $totalUsers;
    public $pendingBookings;
    public $totalRooms;

    public $showModal = false;
    public $instrumentId;

    public $instruments;
    public $name;
    public $stock;
    public $price;
    public $isEditMode = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'stock' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalBookings = Booking::count();
        $this->totalUsers = User::count();
        $this->pendingBookings = Booking::where('status', 'pending')->count();
        $this->totalRooms = Room::count();
        $this->instruments = Instrument::all();
    }

    // ----------------- ADD -----------------
    public function create()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditMode) {
            return $this->confirmEdit();
        }

        Instrument::create([
            'name' => $this->name,
            'stock' => $this->stock,
            'price' => $this->price,
        ]);

        $this->loadStats();
        $this->closeModal();

        $this->dispatch('swal:success', [
            'title' => 'Added!',
            'text' => 'Instrument added successfully.',
            'icon' => 'success',
            'color' => 'green'
        ]);
    }

    // ----------------- EDIT -----------------
    public function openEdit($id)
    {
        $instrument = Instrument::findOrFail($id);

        $this->instrumentId = $instrument->id;
        $this->name = $instrument->name;
        $this->stock = $instrument->stock;
        $this->price = $instrument->price;

        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function confirmEdit()
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => "You are about to update the instrument: $this->name",
            'icon' => 'warning',
            'method' => 'edit',
            'color' => 'green',
        ]);
    }

    #[On('edit')]
    public function edit()
    {
        try {
            $this->validate();

            $instrument = Instrument::findOrFail($this->instrumentId);
            $instrument->update([
                'name' => $this->name,
                'stock' => $this->stock,
                'price' => $this->price,
            ]);

            $this->loadStats();
            $this->closeModal();

            $this->dispatch('swal:success', [
                'title' => 'Updated!',
                'text' => 'Instrument updated successfully.',
                'icon' => 'success',
                'color' => 'green'
        ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'An error occurred while updating the instrument.',
                'icon' => 'error',
                'color' => 'red'
            ]);
        }
    }

    // ----------------- DELETE -----------------
    public function confirmDelete($id)
    {
        $instrument = Instrument::find($id);
        if (!$instrument) {
            $this->dispatch('swal:success', [
                'title' => 'Error!',
                'text' => 'Instrument not found!',
                'icon' => 'error',
                'color' => 'red'
            ]);
            return;
        }

        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => "You are about to delete the instrument: $this->name",
            'icon' => 'warning',
            'color' => 'red',
            'method' => 'delete',
            'id' => $instrument->id
        ]);
    }

    #[On('delete')]
    public function delete($data)
    {
        $instrument = Instrument::findOrFail($data['id']);
        $instrument->delete();

        $this->loadStats();

        $this->dispatch('swal:success', [
            'title' => 'Deleted!',
            'text' => 'Instrument deleted successfully.',
            'icon' => 'success',
            'color' => 'green'
        ]);
    }

    // ----------------- UTILS -----------------
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->instrumentId = null;
        $this->name = '';
        $this->stock = '';
        $this->price = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.instruments');
    }
}