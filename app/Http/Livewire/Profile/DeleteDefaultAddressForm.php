<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;

class DeleteDefaultAddressForm extends Component
{
    public $address;
    public $confirmingAddressDeletion = false;

    public function mount($address)
    {
        $this->address = $address;
    }

    public function delete()
    {
        $this->address->default = false;
        $this->address->save();
        $this->emitUp('defaultAddressDeleted');
        $this->confirmingAddressDeletion = false;
    }
    
    public function render()
    {
        return view('profile.delete-default-address-form');
    }
}
