<?php

namespace App\View\Components;

use App\Models\Province;
use Illuminate\View\Component;

class ProvinceSelect extends Component
{

    public $active;
    public $provinces;
    

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($active = false)
    {
        $this->active = $active;
        $this->provinces = Province::query()->when($active, fn($query) => $query->active() )->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.province-select');
    }
}
