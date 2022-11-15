<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Cart') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                <div class="px-4 mx-auto">
                    <div class="p-8 bg-white lg:p-20">

                        <h2 class="mb-4 text-4xl font-bold font-heading">
                            {{ __('Cart') }}
                        </h2>
                        <h4 class="mb-20 text-lg text-gray-600 font-heading">
                            {!! trans_choice('shopping_cart.cart.count', $count) !!}
                        </h4>

                        @if(count($invalid_quantity_row_ids))
                            <div class="px-4 py-2 my-4 text-red-500 bg-red-200"> {{ __('Some products are no more avaiable') }}</div>
                        @endif

                        @if($count)
                        <div class="flex flex-wrap items-center -mx-4">
                            <div class="w-full px-4 mb-8 xl:w-8/12 xl:mb-0">

                                <div class="hidden w-full lg:flex">
                                    <div class="w-full lg:w-3/6">
                                        <h4 class="mb-6 font-bold text-gray-500 font-heading">
                                            {{__('Description')}}
                                        </h4>
                                    </div>
                                    <div class="w-full lg:w-1/6">
                                        <h4 class="mb-6 font-bold text-gray-500 font-heading">
                                            {{__('Price')}}
                                        </h4>
                                    </div>
                                    <div class="w-full text-center lg:w-1/6">
                                        <h4 class="mb-6 font-bold text-gray-500 font-heading">
                                            {{__('Quantity')}}
                                        </h4>
                                    </div>
                                    <div class="w-full text-right lg:w-1/6">
                                        <h4 class="mb-6 font-bold text-gray-500 font-heading">
                                            {{__('Subtotal')}}
                                        </h4>
                                    </div>
                                </div>

                                <div class="py-6 mb-12 border-t border-b border-gray-200">
                                    @foreach( $content as $rowId=>$item )
                                        <livewire:cart.item-row 
                                            :item="collect($item)" 
                                            invalid="{{in_array($item->rowId,$invalid_quantity_row_ids)}}" 
                                            wire:key="{{ $rowId.now() }}"
                                        />
                                    @endforeach
                                </div>

                                <div class="flex flex-wrap items-center lg:-mb-4">
                                    <span class="mb-4 mr-12 font-medium"
                                    >{{ __('Apply Coupon Code') }}:</span>
                                    <input @class([
                                            "disabled:bg-gray-100  flex-1 px-8 py-4 mb-4 mr-6 font-bold placeholder-gray-400 border rounded-md md:flex-none sm:mr-0 md:mr-6 font-heading",
                                            "text-red-500" => $coupon_error
                                        ]) type="text" 
                                        placeholder="{{ __('Coupon Code') }}"
                                        @disabled($coupon!=null)
                                        x-data
                                        wire:model.lazy="coupon_code"
                                        x-on:input="$event.target.value=$event.target.value.toUpperCase()"
                                    >
                                    @if($coupon)
                                    <div class="flex-1 inline-block px-4 py-4 mb-4 font-bold text-center text-white uppercase bg-gray-800 rounded-md cursor-pointer md:flex-none font-heading hover:bg-gray-700" 
                                        wire:click="removeCoupon"
                                    ><x-icons.x/></div>
                                    @else
                                    <div class="flex-1 inline-block px-4 py-4 mb-4 font-bold text-center text-white uppercase bg-gray-800 rounded-md cursor-pointer md:flex-none font-heading hover:bg-gray-700" 
                                        wire:click="checkCoupon('{{ $coupon_code }}')"
                                    >{{ __('Check') }}</div>
                                    @endif
                                </div>
                            </div>

                            @if($count)
                            <div class="w-full px-4 xl:w-4/12">
                                <x-price-total
                                    :subtotal="$subtotal"
                                    :discounted-subtotal="$discounted_subtotal"
                                    :tax="$tax"
                                    :total="$total"
                                    :coupon="$coupon"
                                >
                                    <x-slot:actions>
                                        @if(!count($invalid_quantity_row_ids))
                                        <form action="{{ route('order.create') }}" method="GET">
                                        @csrf
                                            <button class="block w-full py-4 font-bold text-center text-white uppercase transition duration-200 rounded-md bg-secondary-300 hover:bg-secondary-400 font-heading">
                                                {{ __('Checkout') }}
                                            </button>
                                        </form>
                                        @endif
                                    </x-slot>
                                </x-price-total>
                            </div>
                            @endif
                            
                        </div>
                        @endif

                    </div>
                </div>
                
                @if($count)
                    <div class="flex items-center justify-between mx-5 mb-2">
                        @livewire('cart.destroy-form')
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>