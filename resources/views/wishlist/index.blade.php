<x-slot name="header">
    <h1 class="mb-4 text-3xl font-bold">
        {{ __('Wishlist') }}
    </h1>
</x-slot>

<div class="px-6 py-8 mx-auto max-w-7xl lg:px-8">
    <div class="mb-12 text-gray-500">
        {!! trans_choice('shopping_cart.wishlist.count', $count) !!}
    </div>
    @if ($count)
        <div class="flex flex-wrap items-center w-full">
            <div class="hidden w-full mb-6 font-bold lg:flex">
                <div class="w-full text-center lg:w-2/4">
                    <span class="">{{ __('Description') }}</span>
                </div>
                <div class="w-full text-center lg:w-1/4">
                    {{ __('Price') }}
                </div>
                <div class="w-full text-right lg:w-1/4">
                    <span class="sr-only">
                        {{ __('shopping_cart.move.cart') }}
                    </span>
                </div>
            </div>

            <div class="w-full py-6 border-t border-gray-200">
                @foreach ($content as $rowId => $item)
                    <livewire:wishlist.item-row :item="collect($item)" key="{{ $rowId }}" />
                @endforeach
            </div>
        </div>
    @else
    <x-product-suggestion />
    @endif

    {{-- @if($count)
        <div class="flex items-center justify-between px-4 py-3 text-right bg-gray-50 sm:px-6">
            <div>
                <a href="{{ route('product.index') }}">
                    <x-button class="w-full">{{ __('To Shop') }}</x-button>
                </a>
            </div>
            @livewire('wishlist.destroy-form')
        </div>
    @endif --}}
</div>