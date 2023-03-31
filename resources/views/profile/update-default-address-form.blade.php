<x-jet-form-section submit="updateAddress">
    <x-slot name="title">
        {{ __('Default Address') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile default address used for shipping.') }}
        <span>
        </span>
    </x-slot>

    <x-slot name="form">

        <!-- Full Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="full_name" value="{{ __('Full Name') . ' / ' . __('Company') }}" />
            <x-input id="full_name" type="text" class="block w-full mt-1" wire:model.defer="address.full_name" autocomplete="full_name" />
            <x-jet-input-error for="address.full_name" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="address" value="{{ __('Address') }}" />
            <x-input id="address" type="text" class="block w-full mt-1" wire:model.defer="address.address" autocomplete="address"/>
            <x-jet-input-error for="address.address" class="mt-2" />
        </div>

        <!-- City -->
        <div class="col-span-6 sm:col-span-2">
            <x-jet-label for="city" value="{{ __('City') }}" />
            <x-input id="city" type="text" class="block w-full mt-1" wire:model.defer="address.city" autocomplete="city"/>
            <x-jet-input-error for="address.city" class="mt-2" />
        </div>

        <!-- Province -->
        <div class="col-span-6 sm:col-span-2">
            <x-jet-label for="province" value="{{ __('Province') }}" />
            <x-province-select id="province"  class="block w-full mt-1" wire:model.defer="address.province" autocomplete="province" active/>
            <x-jet-input-error for="address.province" class="mt-2" />
        </div>

        <!-- Postal Code -->
        <div class="col-span-6 sm:col-span-2">
            <x-jet-label for="postal_code" value="{{ __('Postal Code') }}" />
            <x-input id="postal_code" type="text" maxlength="5" class="block w-full mt-1" wire:model.defer="address.postal_code" autocomplete="postal_code" />
            <x-jet-input-error for="address.postal_code" class="mt-2" />
        </div>
        
        <!-- Country/Region -->
        <div class="col-span-6 sm:col-span-2">
            <x-jet-label for="country_region" value="{{ __('Country/Region') }}" />
            <x-country-select id="country_region" class="block w-full mt-1" wire:model.defer="address.country_region"/>
            <x-jet-input-error for="address.country_region" class="mt-2" />
        </div>
        
    </x-slot>

    <x-slot name="actions">
            <livewire:profile.delete-default-address-form :address='$address'/>

            <x-jet-action-message class="mr-3" on="saved">
                {{ __('Saved') }}
            </x-jet-action-message>

            <x-button wire:loading.attr="disabled" wire:target="photo">
                {{ __('Save') }}
            </x-button>
    </x-slot>
</x-jet-form-section>
