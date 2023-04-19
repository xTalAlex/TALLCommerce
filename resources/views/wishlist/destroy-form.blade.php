<div>
    <div class="">
        <x-button class="btn-error" wire:click="$toggle('confirmingWishlistDeletion')" wire:loading.attr="disabled">
            {{ __('Empty') }}
        </x-button>
    </div>

    <x-jet-dialog-modal wire:model="confirmingWishlistDeletion">
        <x-slot name="title">
            {{ __('Empty Wishlist') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to empty your wishlist?') }}
        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="$toggle('confirmingWishlistDeletion')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-button>

            <x-button class="ml-2 btn-error" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Empty Wishlist') }}
            </x-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>