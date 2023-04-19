<x-jet-form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('Update Password') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-input id="current_password" type="password" label="{{ __('Current Password') }}" class="block w-full" wire:model.defer="state.current_password" autocomplete="current-password" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-input id="password" type="password" label="{{ __('New Password') }}" class="block w-full" wire:model.defer="state.password" autocomplete="new-password" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-input id="password_confirmation" type="password" label="{{ __('Confirm Password') }}" class="block w-full" wire:model.defer="state.password_confirmation" autocomplete="new-password" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved') }}
        </x-jet-action-message>

        <x-button>
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-jet-form-section>
