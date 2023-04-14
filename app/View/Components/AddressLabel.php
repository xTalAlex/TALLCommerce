<?php

namespace App\View\Components;

use App\Models\Address;
use Illuminate\View\Component;

class AddressLabel extends Component
{
    public Address $address;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($address)
    {
        $this->address = $address;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.address-label');
    }
}
