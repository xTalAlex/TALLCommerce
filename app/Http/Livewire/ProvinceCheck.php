<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;

class ProvinceCheck extends Component
{
    public $provinces;
    public $province;
    public $matchedProvince;

    public function mount($provinces)
    {
        $this->provinces  = $provinces;
    }

    public function updatedProvince($value)
    {
        $this->matchedProvince = $this->provinces->filter(function ($item, $key) use($value) {
            return Str::startsWith(Str::lower($item->name), Str::lower($value)) || Str::lower($item->code) == Str::lower($value);
        })->first();
    }

    public function render()
    {
        return view('livewire.province-check');
    }
}
