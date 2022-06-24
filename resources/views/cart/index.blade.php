<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Cart') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                @if(count($invalid_quantity_row_ids))
                    <div class="px-4 py-2 my-4 text-red-500 bg-red-200"> {{ __('Some products are no more avaiable') }}</div>
                @endif
                <x-table title="{!! trans_choice('shopping_cart.cart.count', $count) !!}">
                
                    <x-slot:heading>
                        <x-table.th>
                            <span class="sr-only"{{__('Remove')}}</span>
                        </x-table.th>
                        <x-table.th>
                            <span class="sr-only"{{__('Image')}}</span>
                        </x-table.th>
                        <x-table.th>
                            {{ __('Name') }}
                        </x-table.th>
                        <x-table.th>
                            {{ __('Categories') }}
                        </x-table.th>
                        <x-table.th>
                            {{ __('Quantity') }}
                        </x-table.th>
                        <x-table.th>
                            {{ __('Price') }}
                        </x-table.th>
                        <x-table.th>
                            <span class="sr-only"{{__('shopping_car.move.wishlist')}}</span>
                        </x-table.th>
                    </x-slot:heading>
                    
                    @foreach( $content as $rowId => $item )
                        <livewire:cart.item-row :item="collect($item)" invalid="{{in_array($item->rowId,$invalid_quantity_row_ids)}}" wire:key="{{ $rowId.now() }}"/>
                    @endforeach
                    
                </x-table>
                
                @if($count)
                
                <div class="flex flex-col ml-5" >
                    <span>{{ __('Subtotal') }}: 
                        <span wire:loading.remove>
                            {{ $subtotal }}
                        </span>
                        <span wire:loading>
                            ...
                        </span>
                    </span>
                    <span>{{ __('Tax')}}: {{ $tax }}
                    </span>
                    <span>{{ __('Total') }}: 
                        <span wire:loading.remove>
                            {{ $total }}
                        </span>
                        <span wire:loading>
                            ...
                        </span>
                    </span>
                </div>

                <div class="flex items-center justify-between mx-5 mb-2">
                    @livewire('cart.destroy-form')

                    @if(!count($invalid_quantity_row_ids))
                        <form class="mt-5"
                            action="{{ route('order.create') }}" method="GET">
                        @csrf
                            <x-jet-button>
                                {{ __('Checkout') }}
                            </x-jet-button>
                        </form>
                    @endif
                </div>
                @endif

            </div>

            <section class="py-20 bg-gray-100">
                <div class="container px-4 mx-auto">
                    <div class="p-8 bg-white lg:p-20">
                    <h2 class="mb-20 text-5xl font-bold font-heading">Your cart</h2>
                    <div class="flex flex-wrap items-center -mx-4">
                        <div class="w-full px-4 mb-8 xl:w-8/12 xl:mb-0">
                        <div class="hidden w-full lg:flex">
                            <div class="w-full lg:w-3/6">
                            <h4 class="mb-6 font-bold text-gray-500 font-heading">Description</h4>
                            </div>
                            <div class="w-full lg:w-1/6">
                            <h4 class="mb-6 font-bold text-gray-500 font-heading">Price</h4>
                            </div>
                            <div class="w-full text-center lg:w-1/6">
                            <h4 class="mb-6 font-bold text-gray-500 font-heading">Quantity</h4>
                            </div>
                            <div class="w-full text-right lg:w-1/6">
                            <h4 class="mb-6 font-bold text-gray-500 font-heading">Subtotal</h4>
                            </div>
                        </div>
                        <div class="py-6 mb-12 border-t border-b border-gray-200">
                            <div class="flex flex-wrap items-center mb-6 -mx-4 md:mb-3">
                            <div class="w-full px-4 mb-6 md:w-4/6 lg:w-6/12 md:mb-0">
                                <div class="flex flex-wrap items-center -mx-4">
                                <div class="w-full px-4 mb-3 md:w-1/3">
                                    <div class="flex items-center justify-center w-full h-32 bg-gray-100 md:w-24">
                                    <img class="object-contain h-full" src="yofte-assets/images/waterbottle.png" alt="">
                                    </div>
                                </div>
                                <div class="w-2/3 px-4">
                                    <h3 class="mb-2 text-xl font-bold font-heading">BRILE water filter carafe</h3>
                                    <p class="text-gray-500">Maecenas 0.7 commodo sit</p>
                                </div>
                                </div>
                            </div>
                            <div class="hidden px-4 lg:block lg:w-2/12">
                                <p class="text-lg font-bold text-blue-500 font-heading">$29.89</p>
                                <span class="text-xs text-gray-500 line-through">$33.69</span>
                            </div>
                            <div class="w-auto px-4 md:w-1/6 lg:w-2/12">
                                <div class="inline-flex items-center px-4 font-semibold text-gray-500 border border-gray-200 rounded-md font-heading focus:ring-blue-300 focus:border-blue-300">
                                <button class="py-2 hover:text-gray-700">
                                    <svg width="12" height="2" viewbox="0 0 12 2" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.35"><rect x="12" width="2" height="12" transform="rotate(90 12 0)" fill="currentColor"></rect></g></svg>
                                </button>
                                <input class="w-12 px-2 py-4 m-0 text-center border-0 rounded-md md:text-right focus:ring-transparent focus:outline-none" type="number" placeholder="1">
                                <button class="py-2 hover:text-gray-700">
                                    <svg width="12" height="12" viewbox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.35"><rect x="5" width="2" height="12" fill="currentColor"></rect><rect x="12" y="5" width="2" height="12" transform="rotate(90 12 5)" fill="currentColor"></rect></g></svg>
                                </button>
                                </div>
                            </div>
                            <div class="w-auto px-4 text-right md:w-1/6 lg:w-2/12">
                                <p class="text-lg font-bold text-blue-500 font-heading">$29.89</p>
                            </div>
                            </div>
                            <div class="flex flex-wrap items-center -mx-4">
                            <div class="w-full px-4 mb-6 md:w-4/6 lg:w-6/12 md:mb-0">
                                <div class="flex flex-wrap items-center -mx-4">
                                <div class="w-full px-4 mb-3 md:w-1/3">
                                    <div class="flex items-center justify-center w-full h-32 bg-gray-100 md:w-24">
                                    <img class="object-contain h-full" src="yofte-assets/images/basketball.png" alt="">
                                    </div>
                                </div>
                                <div class="w-full px-4 md:w-2/3">
                                    <h3 class="mb-2 text-xl font-bold font-heading">Nike basketball ball</h3>
                                    <p class="text-gray-500">Lorem ipsum dolor L</p>
                                </div>
                                </div>
                            </div>
                            <div class="hidden px-4 lg:block lg:w-2/12">
                                <p class="text-lg font-bold text-blue-500 font-heading">$29.89</p>
                                <span class="text-xs text-gray-500 line-through">$33.69</span>
                            </div>
                            <div class="w-auto px-4 md:w-1/6 lg:w-2/12">
                                <div class="inline-flex items-center px-4 font-semibold text-gray-500 border border-gray-200 rounded-md font-heading focus:ring-blue-300 focus:border-blue-300">
                                <button class="py-2 hover:text-gray-700">
                                    <svg width="12" height="2" viewbox="0 0 12 2" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.35"><rect x="12" width="2" height="12" transform="rotate(90 12 0)" fill="currentColor"></rect></g></svg>
                                </button>
                                <input class="w-12 px-2 py-4 m-0 text-center border-0 rounded-md md:text-right focus:ring-transparent focus:outline-none" type="number" placeholder="1">
                                <button class="py-2 hover:text-gray-700">
                                    <svg width="12" height="12" viewbox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.35"><rect x="5" width="2" height="12" fill="currentColor"></rect><rect x="12" y="5" width="2" height="12" transform="rotate(90 12 5)" fill="currentColor"></rect></g></svg>
                                </button>
                                </div>
                            </div>
                            <div class="w-auto px-4 text-right md:w-1/6 lg:w-2/12">
                                <p class="text-lg font-bold text-blue-500 font-heading">$29.89</p>
                            </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center lg:-mb-4">
                            <span class="mb-4 mr-12 font-medium">Apply discount code:</span>
                            <input class="flex-1 px-8 py-4 mb-4 mr-6 font-bold placeholder-gray-800 border rounded-md md:flex-none sm:mr-0 md:mr-6 font-heading" type="text" placeholder="SUMMER30X">
                            <a class="flex-1 inline-block px-8 py-4 mb-4 font-bold text-center text-white uppercase bg-gray-800 rounded-md md:flex-none font-heading hover:bg-gray-700" href="#">Apply</a>
                        </div>
                        </div>
                        <div class="w-full px-4 xl:w-4/12">
                        <div class="p-6 bg-blue-300 md:p-12">
                            <h2 class="mb-6 text-4xl font-bold text-white font-heading">Cart totals</h2>
                            <div class="flex items-center justify-between pb-5 mb-8 border-b border-blue-100">
                            <span class="text-blue-50">Subtotal</span>
                            <span class="text-xl font-bold text-white font-heading">$89.67</span>
                            </div>
                            <h4 class="mb-2 text-xl font-bold text-white font-heading">Shipping</h4>
                            <div class="flex items-center justify-between mb-2">
                            <span class="text-blue-50">Next day</span>
                            <span class="text-xl font-bold text-white font-heading">$11.00</span>
                            </div>
                            <div class="flex items-center justify-between mb-10">
                            <span class="text-blue-50">Shipping to United States</span>
                            <span class="text-xl font-bold text-white font-heading">-</span>
                            </div>
                            <div class="flex items-center justify-between mb-10">
                            <span class="text-xl font-bold text-white font-heading">Order total</span>
                            <span class="text-xl font-bold text-white font-heading">$100.67</span>
                            </div>
                            <a class="block w-full py-4 font-bold text-center text-white uppercase transition duration-200 bg-orange-300 rounded-md hover:bg-orange-400 font-heading" href="#">Go to Checkout</a>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </section>

        </div>
    </div>
</div>