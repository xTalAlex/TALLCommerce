<div>
    <div class="">
        <x-button class="btn-error" wire:click="$toggle('confirmingCartDeletion')" wire:loading.attr="disabled">
            {{ __('Empty') }}
        </x-button>
    </div>

    <x-jet-dialog-modal wire:model="confirmingCartDeletion">
        <x-slot name="title">
            {{ __('Empty Cart') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to empty your cart?') }}
        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="$toggle('confirmingCartDeletion')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-button>

            <x-button class="ml-2 btn-error" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Empty') }}
            </x-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>