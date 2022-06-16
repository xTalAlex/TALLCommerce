<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Wishlist') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

                    <h3 class="my-3 ml-3">{!! trans_choice('shopping_cart.wishlist.count', $count) !!}</h3>

                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">{{ __('Remove') }}</span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">{{ __('Image') }}</span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Name') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Categories') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ trans_choice('Avaiable',1) }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Price') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">{{ __('shopping_cart.move.cart') }}</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach( Cart::instance('wishlist')->content() as $item)
                        <tr class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700">
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                    wire:click.prevent="removeFromWishlist({{ $item->model->id }})"
                                >
                                    {{ __('Remove') }}
                                </a>
                            </td>
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                <img class="h-20" src="{{ $item->model->image }}"/>
                            </td>
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                {{ $item->model->name }}
                            </td>
                            <td class="px-6 py-4">
                                @foreach($item->model->categories as $category)
                                    {{ $category->name}}
                                    @if(!$loop->last)
                                        , 
                                    @endif
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->model->quantity ? trans_choice('Avaiable',1) : __('Out of Stock') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->model->price}}€
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($item->model->quantity)
                                <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                    wire:click.prevent="moveToCart({{$item->model}})"
                                >
                                    {{ __('shopping_cart.move.cart') }}
                                </a>
                                @else
                                <span>{{ __('Out of Stock') }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if(Cart::instance('wishlist')->count())
                    <div class="mt-10 sm:mt-0">
                        @livewire('wishlist.destroy-form')
                    </div>
                @endif

            </div>
          
        </div>
    </div>
</div>
