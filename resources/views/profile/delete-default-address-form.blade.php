<div class="ml-0 mr-auto">
    <div class="">
        <x-button class="btn-error" wire:click="$toggle('confirmingAddressDeletion')" wire:loading.attr="disabled">
            {{ __('Delete') }}
        </x-button>
    </div>

    <x-jet-dialog-modal wire:model="confirmingAddressDeletion">
        <x-slot name="title">
            {{ __('Delete') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete your default address?') }}
        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="$toggle('confirmingAddressDeletion')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-button>

            <x-button class="ml-2 btn-error" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>