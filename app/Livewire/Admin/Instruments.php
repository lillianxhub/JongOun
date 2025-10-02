<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Instrument;

class Instruments extends Component
{
    public $instruments;
    
    public function mount()
    {
        $this->instruments = Instrument::all();
    }
    
    public function loadStats()
    {
        $this->instruments = Instrument::all();
    }
    
    public function updating()
    {
        $this->loadStats();
    }
    
    public function edit($instrumentId)
    {
        try {
            $instrumentId = Instrument::findOrFail($instrumentId);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Instrument not found.');
        }
    }

    public function render()
    {
        return view('livewire.admin.instruments');
    }
}
