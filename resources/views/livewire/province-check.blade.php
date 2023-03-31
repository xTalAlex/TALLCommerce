<div>
    <x-input type="text" @class([
            'block mt-2 w-full'
        ]) 
        id="province" name="province" wire:model.debounce="province"
        placeholder="{{ __('Inserite la vostra provincia per sapere se effettuiamo consegne nella vostra zona.') }}"    
    />
    @if($province && $matchedProvince)
    <div class="mt-2">
        @if($matchedProvince->isActive())
            <span class="text-success-500">{{ __('Consegniamo nella provincia di :value', [ 'value' => $matchedProvince->name ]) }}</span>
        @else
            <span class="text-danger-500">{{ __('Non consegniamo nella provincia di :value', [ 'value' => $matchedProvince->name ]) }}</span>
        @endif
    </div>
    @endif
</div>
