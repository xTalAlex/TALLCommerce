<div class="relative flex flex-wrap items-center mb-12">

    <div class="flex flex-col w-full mb-6 sm:flex-row md:w-2/4 md:mb-0 sm:space-x-6">
        <div class="relative w-full md:w-1/2">
            <a href="{{ route('product.show', $product) }}">
                <div class="flex items-start justify-center w-full h-40 md:justify-start">
                    <img class="object-contain object-top h-full aspect-video" src="{{ $product->image }}" alt="{{ $product->name }}">
                </div>
            </a>
            <div class="absolute flex items-center justify-center w-6 h-6 p-1 leading-none text-center text-gray-500 rounded-full shadow-xl cursor-pointer hover:text-gray-900 hover:bg-secondary-100 bg-secondary-50 -top-1 -left-2"
                wire:click.prevent="removeFromWishlist({{ $product->id }})"
                title="{{ __('shopping_cart.remove.wishlist') }}"
            >
                <x-icons.trash class="w-full h-full"/>
            </div>
        </div>

        <div class="w-full mt-6 md:w-1/2 sm:mt-0">
            <a href="{{ route('product.show', $product) }}">
                <div class="font-bold"
                >{{ $product->name }}</div>
            </a>
            @if(!$product->short_description)
                <div class="text-gray-500">
                    {{ $product->short_description}}
                </div>
            @endif
            @if(count($product->attributeValues))
                <div class="my-6 text-gray-500">
                    @foreach($product->attributeValues as $attributeValue)
                        {{ $attributeValue->label}}
                        @if(!$loop->last)
                            , 
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- <div class="w-full px-4 text-center md:w-1/5">
        <div @class([
                'p-2 w-32 mx-auto text-gray-100 rounded-md focus:border-4 ',
                'bg-green-500 border-green-300' => $product->quantity && !$product->isLowStock(),
                'bg-yellow-500 border-yellow-300' => $product->quantity && $product->isLowStock(),
                'bg-gray-800 border-gray-600' => !$product->quantity
            ])
        >
            {{ $product->stock_status }}
        </div>
    </div> --}}

    <div class="flex items-center justify-center w-full px-4 md:w-1/4">
        <div class="text-lg font-black text-gray-900">
            {{$product->taxed_selling_price}}€
        </div>
        @if($product->discount)
            <div class="ml-1 -mt-2 font-medium text-gray-500 line-through">
                {{$product->taxed_original_price}}€
            </div>
        @endif
    </div>

    {{-- <div class="w-full px-4 mt-6 text-center md:w-1/5 md:mt-0">
        @if($product->quantity)
        <button class="flex px-6 py-2 ml-auto text-white border-0 rounded disabled:bg-primary-400 bg-primary-500 focus:outline-none hover:bg-primary-600"
            @disabled(!$product->quantity)
            wire:click.prevent="moveToCart({{$product}})"
        >
            {{ __('shopping_cart.move.cart') }}<x-icons.cart class="ml-1" />
        </button>
        @endif
    </div> --}}

    <div class="w-full mt-6 text-center md:w-1/4 md:mt-0">
        <x-button class="w-full text-sm" wire:click.prevent="moveToCart({{$product}})">
            {{ __('shopping_cart.move.cart') }}<x-icons.cart class="ml-1" />
        </x-button>
    </div>

</div>