<x-jet-form-section submit="updateAddress">
    <x-slot name="title">
        {{ __('Default Address') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile default address used for shipping and billing.') }}
        <span>
        </span>
    </x-slot>

    <x-slot name="form">

        <!-- Full Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="full_name" value="{{ __('Full Name') }}" />
            <x-jet-input id="full_name" type="text" class="block w-full mt-1" wire:model.defer="address.full_name" autocomplete="full_name" />
            <x-jet-input-error for="full_name" class="mt-2" />
        </div>

        <!-- Full Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="company" value="{{ __('Company') }}" />
            <x-jet-input id="company" type="text" class="block w-full mt-1" wire:model.defer="address.company" autocomplete="company" />
            <x-jet-input-error for="company" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="address" value="{{ __('Address') }}" />
            <x-jet-input id="address" type="text" class="block w-full mt-1" wire:model.defer="address.address" autocomplete="address"/>
            <x-jet-input-error for="address" class="mt-2" />
        </div>

        <!-- Address2 -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="address2" value="{{ __('Address2') }}" />
            <x-jet-input id="address2" type="text" class="block w-full mt-1" wire:model.defer="address.address2" autocomplete="address2"/>
            <x-jet-input-error for="address2" class="mt-2" />
        </div>

        <!-- City -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="city" value="{{ __('City') }}" />
            <x-jet-input id="city" type="text" class="block w-full mt-1" wire:model.defer="address.city" autocomplete="city"/>
            <x-jet-input-error for="city" class="mt-2" />
        </div>

        <!-- Province -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="province" value="{{ __('Province') }}" />
            <x-jet-input id="province" type="text" class="block w-full mt-1" wire:model.defer="address.province" autocomplete="province"/>
            <x-jet-input-error for="province" class="mt-2" />
        </div>

        <!-- Country/Region -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="country_region" value="{{ __('Country/Region') }}" />
            <x-jet-input id="country_region" type="text" class="block w-full mt-1" wire:model.defer="address.country_region" autocomplete="country_region"/>
            <x-jet-input-error for="country_region" class="mt-2" />
        </div>

        <!-- Postal Code -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="postal_code" value="{{ __('Postal Code') }}" />
            <x-jet-input id="postal_code" type="text" class="block w-full mt-1" wire:model.defer="address.postal_code" autocomplete="postal_code" />
            <x-jet-input-error for="postal_code" class="mt-2" />
        </div>
        
    </x-slot>

    <x-slot name="actions">
            <livewire:profile.delete-default-address-form :address='$address'/>

            <x-jet-action-message class="mr-3" on="saved">
                {{ __('Saved') }}
            </x-jet-action-message>

            <x-jet-button wire:loading.attr="disabled" wire:target="photo">
                {{ __('Save') }}
            </x-jet-button>
    </x-slot>
</x-jet-form-section>
