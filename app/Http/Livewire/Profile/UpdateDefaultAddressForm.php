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
        'address.full_name' => '',        
        'address.company' => '',        
        'address.address' => 'required',        
        'address.address2' => '',        
        'address.city' => 'required',
        'address.province' => 'required',
        'address.country_region' => 'required',
        'address.postal_code' => 'required|min:5',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->address = Auth::user()->defaultAddress ?? Address::make();
    }

    public function updateAddress()
    {
        $this->validate();
        
        $this->user->defaultAddress()->updateOrCreate([
            'user_id' => $this->user->id,
        ],[
            'full_name' => $this->address->full_name,
            'company' => $this->address->company,
            'address' => $this->address->address,
            'address2' => $this->address->address2,
            'city' => $this->address->city,
            'province' => $this->address->province,
            'country_region' => $this->address->country_region,
            'postal_code' => $this->address->postal_code,
            'default' => true,
        ]);

        $this->emit('saved');
    }

    public function render()
    {
        return view('profile.update-default-address-form'); 
    }
}
