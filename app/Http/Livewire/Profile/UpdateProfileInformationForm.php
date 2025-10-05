<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UpdateProfileInformationForm extends Component
{
    public $state = [];

    public function mount()
    {
        $this->state = Auth::user()->only(['name','email','profile_photo_path']);
    }

    public function updateProfileInformation()
    {
        $user = Auth::user();
        $user->name = $this->state['name'];
        $user->email = $this->state['email'];
        $user->save();

        $this->dispatch('swal:success', [
            [
                'title' => 'Profile Updated!',
                'text' => 'Your profile information has been updated successfully.',
                'icon' => 'success',
                'color' => 'green',
                'redirect' => null
            ]
        ]);
    }

    public function render()
    {
        return view('livewire.profile.update-profile-information-form');
    }
}
