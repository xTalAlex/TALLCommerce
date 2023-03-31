<?php

namespace App\Http\Livewire\Profile;

use App\Models\Address;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UpdateDefaultAddressForm extends Component
{
    public $user;
    public $address;

    protected $rules = [
        'address.full_name' => 'required',
        'address.address' => 'required',   
        'address.city' => 'required',
        'address.province' => 'required|size:2',
        'address.country_region' => 'required',
        'address.postal_code' => 'required|min:5|max:5',
    ];

    protected $listeners = ['defaultAddressDeleted' => 'defaultAddressDeleted'];


    public function mount()
    {
        $this->user = Auth::user();
        $this->address = Auth::user()->defaultAddress ?? new Address();
    }

    public function updateAddress()
    {
        $this->validate();
        
        $this->user->defaultAddress()->updateOrCreate([
            'user_id' => $this->user->id,
        ],[
            'full_name' => $this->address->full_name,
            'address' => $this->address->address,
            'city' => $this->address->city,
            'province' => $this->address->province,
            'country_region' => $this->address->country_region,
            'postal_code' => $this->address->postal_code,
            'default' => true,
        ]);

        $this->emit('saved');
    }

    public function defaultAddressDeleted()
    {
        $this->address = new Address();
    }

    public function render()
    {
        return view('profile.update-default-address-form'); 
    }
}
