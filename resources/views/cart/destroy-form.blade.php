<div>
    <div class="mt-5">
        <x-jet-danger-button wire:click="$toggle('confirmingCartDeletion')" wire:loading.attr="disabled">
            {{ __('Empty Cart') }}
        </x-jet-danger-button>
    </div>

    <x-jet-confirmation-modal wire:model="confirmingCartDeletion">
        <x-slot name="title">
            {{ __('Empty Cart') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to empty your cart?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingCartDeletion')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Empty') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>