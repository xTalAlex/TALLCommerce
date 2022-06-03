<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Cart') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <x-table title="Cart has {{ $count }} items">
                
                    <x-slot:heading>
                        <x-table.th>
                            
                        </x-table.th>
                        <x-table.th>
                            
                        </x-table.th>
                        <x-table.th>
                            Name
                        </x-table.th>
                        <x-table.th>
                            Categories
                        </x-table.th>
                        <x-table.th>
                            Quantity
                        </x-table.th>
                        <x-table.th>
                            Price
                        </x-table.th>
                        <x-table.th>
                            <span class="sr-only">Move to Wishlist</span>
                        </x-table.th>
                    </x-slot:heading>
                    
                    @foreach( $content as $rowId => $item )
                        <livewire:cart.item-row :item="collect($item)" wire:key="{{ $rowId }}"/>
                    @endforeach
                    
                </x-table>
                
                @if($count)
                
                <div class="flex flex-col" >
                    <span>SUBTOTAL: 
                        <span wire:loading.remove>
                            {{ $subtotal }}
                        </span>
                        <span wire:loading>
                            ...
                        </span>
                    </span>
                    <span>TAX: {{ Cart::tax() }}
                    </span>
                    <span>TOTAL: 
                        <span wire:loading.remove>
                            {{ $total }}
                        </span>
                        <span wire:loading>
                            ...
                        </span>
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    @livewire('cart.destroy-form')

                    <form class="mt-5"
                        action="{{ route('order.create') }}" method="GET">
                    @csrf
                        <x-jet-button>
                            {{ __('Checkout') }}
                        </x-jet-button>
                    </form>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>