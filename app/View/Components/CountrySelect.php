<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CountrySelect extends Component
{
    public $countries;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($active = false)
    {
        $this->countries = ['Italia'];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.country-select');
    }
}
