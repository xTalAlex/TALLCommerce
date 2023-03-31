<x-slot name="header">
    <h1 class="mb-4 text-3xl font-bold">
        {{ __('Cart') }}
    </h1>
</x-slot>

<div class="px-6 py-8 mx-auto max-w-7xl lg:px-8">
    <div class="mb-12 text-gray-500">
        {!! trans_choice('shopping_cart.cart.count', $count) !!}
    </div>

    @if(count($invalid_quantity_row_ids))
        <div class="px-4 py-2 my-4 text-red-500 bg-red-200"> {{ __('Some products are no more avaiable') }}</div>
    @endif

    @if($count)
    <div class="flex flex-wrap w-full pb-12">
        
        <div class="w-full xl:w-8/12 xl:pr-12">

            <div class="hidden w-full mb-6 font-bold lg:flex">
                <div class="w-full text-center lg:w-3/6">
                    <span class="">{{ __('Description') }}</span>
                </div>
                <div class="w-full text-left lg:w-1/6">
                    {{ __('Price') }}
                </div>
                <div class="w-full text-center lg:w-1/6">
                    {{ __('Quantity') }}
                </div>
                <div class="w-full text-right lg:w-1/6">
                    {{ __('Subtotal') }}
                </div>
            </div>

            <div class="w-full py-6 border-gray-200 border-y">
                @foreach( $content as $rowId=>$item )
                    <livewire:cart.item-row 
                        :item="collect($item)" 
                        invalid="{{in_array($rowId,$invalid_quantity_row_ids)}}" 
                        wire:key="{{ $rowId.now() }}"
                    />
                @endforeach
            </div>

            <div class="flex flex-wrap items-center justify-center gap-4 my-12 lg:justify-start">
                <div class="font-semibold"
                >{{ __('Apply Coupon Code') }}:</div>
                <div class="flex flex-nowrap">
                    <x-input @class([
                            "disabled:bg-gray-50 py-2 text-base font-bold placeholder-gray-500",
                            "text-danger-500" => $coupon_error
                        ]) type="text" 
                        placeholder="{{ __('Coupon Code') }}"
                        disabled="{{ $coupon!=null }}"
                        x-data="{}"
                        wire:model.lazy="coupon_code"
                        x-on:input="$event.target.value=$event.target.value.toUpperCase()"
                    ></x-input>
                    @if($coupon)
                    <x-secondary-button class="py-2 text-base" wire:click="removeCoupon"
                    ><x-icons.x/></x-secondary-button>
                    @else
                    <x-secondary-button class="py-2 text-base" wire:click="refreshTotals"
                    >{{ __('Check') }}</x-secondary-button>
                    @endif
                </div>
            </div>
        </div>

        <div class="w-full xl:w-4/12">
            <x-price-total
                :subtotal="$subtotal"
                :discounted-subtotal="$discounted_subtotal"
                :original-total="$original_total"
                :tax="$tax"
                :total="$total"
                :coupon="$coupon"
            >
                <x-slot:actions>
                    @if(!count($invalid_quantity_row_ids))
                    <form action="{{ route('order.create') }}" method="GET">
                    @csrf
                        <x-button class="w-full py-4 text-base">
                            {{ __('Checkout') }}
                        </x-button>
                    </form>
                    @endif
                    <div class="mt-6 text-center">
                        <a class="underline" href="{{ route('product.index') }}">
                            {{ __('Back to Shop') }}
                        </a>
                    </div>
                </x-slot>
            </x-price-total>
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
            @livewire('cart.destroy-form')
        </div>
    @endif --}}
</div>