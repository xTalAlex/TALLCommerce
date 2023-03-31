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
        <div class="grid place-items-center">
            <div class="py-12">
                <p>
                    {{ __("Haven't found anything, yet?") }}
                </p>
                <div class="flex flex-col w-full mt-6 space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row">
                    <form class="w-full sm:w-1/2" method="GET" action="{{ route('product.index') }}">
                        <x-button class="w-full h-full">{{ __('To Shop') }}</x-button>
                    </form>
                    <form class="w-full sm:w-1/2" method="GET" action="{{  route('product.show', $randomProduct) }}">
                        <x-secondary-button class="w-full h-full">{{ __('Random Product') }}</x-secondary-button>
                    </form>
                </div>
            </div>
        </div>
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