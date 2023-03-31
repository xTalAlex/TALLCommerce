<div>
    <div class="">
        <x-danger-button wire:click="$toggle('confirmingWishlistDeletion')" wire:loading.attr="disabled">
            {{ __('Empty') }}
        </x-danger-button>
    </div>

    <x-jet-confirmation-modal wire:model="confirmingWishlistDeletion">
        <x-slot name="title">
            {{ __('Empty Wishlist') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to empty your wishlist?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingWishlistDeletion')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Empty Wishlist') }}
            </x-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>