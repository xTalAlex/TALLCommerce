<div>
    <div class="">
        <x-button class="w-full text-center btn-error" wire:click="$toggle('confirmingOrderDeletion')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-button>
        {{-- <button type="submit" class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-200 focus:text-primary-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"></path></svg>
        Delete</button> --}}
    </div>

    <x-jet-confirmation-modal wire:model="confirmingOrderDeletion">
        <x-slot name="title">
            {{ __('Cancel Order') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to cancel this order?') }}
        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="$toggle('confirmingOrderDeletion')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-button>

            <x-button class="ml-2 btn-error" wire:click="deleteOrder" wire:loading.attr="disabled">
                {{ __('Confirm') }}
            </x-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>