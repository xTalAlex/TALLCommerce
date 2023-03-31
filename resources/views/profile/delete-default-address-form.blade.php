<div class="ml-0 mr-auto">
    <div class="">
        <x-danger-button wire:click="$toggle('confirmingAddressDeletion')" wire:loading.attr="disabled">
            {{ __('Delete') }}
        </x-danger-button>
    </div>

    <x-jet-confirmation-modal wire:model="confirmingAddressDeletion">
        <x-slot name="title">
            {{ __('Delete') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete your default address?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingAddressDeletion')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>