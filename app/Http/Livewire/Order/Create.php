<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $step;

    public $email;
    public $full_name;
    public $company;
    public $address;
    public $address2;
    public $city;
    public $province;
    public $country_region;
    public $postal_code;

    public $billing_full_name;
    public $billing_company;
    public $billing_address;
    public $billing_address2;
    public $billing_city;
    public $billing_province;
    public $billing_country_region;
    public $billing_postal_code;

    public $same_address;
    public $message;

    public function mount()
    {
        $this->step = 'shipping';
        $this->email = Auth::user() ? Auth::user()->email : null;
        if(Auth::user() && Auth::user()->defaultAddress){
            $address = Auth::user()->defaultAddress;
            $this->full_name = $address->full_name;
            $this->company = $address->company;
            $this->address = $address->address;
            $this->address2 = $address->address2;
            $this->city = $address->city;
            $this->province = $address->province;
            $this->country_region = $address->country_region;
            $this->postal_code = $address->postal_code;
            $this->full_name = $address->full_name;
        }
        $this->same_address = true;
    }

    public function submitShipping()
    {
        $this->same_address ?
            $this->step = "payment" :
            $this->step = "billing";
    }

    public function submitBilling()
    {
        $this->step = "payment";
    }
   
    public function render()
    {
        return view('order.create');
    }
}
