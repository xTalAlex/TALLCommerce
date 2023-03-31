<div>
    <div class="">
        <x-danger-button wire:click="$toggle('confirmingCartDeletion')" wire:loading.attr="disabled">
            {{ __('Empty') }}
        </x-danger-button>
    </div>

    <x-jet-confirmation-modal wire:model="confirmingCartDeletion">
        <x-slot name="title">
            {{ __('Empty Cart') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to empty your cart?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingCartDeletion')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Empty') }}
            </x-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>