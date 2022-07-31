<div class="relative flex flex-wrap items-center mb-6 -mx-4 md:mb-3">

    <div class="w-full px-4 mb-6 md:w-2/5 md:mb-0">
        <div class="flex flex-wrap items-center -mx-4">
            <div class="w-full px-4 mb-3 md:w-1/3">
                <a href="{{ route('product.show', $product) }}">
                <div class="flex items-center justify-center w-full h-32 bg-gray-100 md:w-24"
                >
                    <img class="object-contain h-full" src="{{ $product->image }}" alt="{{ $product->name }}">
                </div>
                </a>
            </div>
            <div class="w-full px-4 md:w-2/3">
                <a href="{{ route('product.show', $product) }}">
                <h3 class="mb-2 text-xl font-bold font-heading text"
                >{{ $product->name }}</h3>
                </a>
                <p class="text-gray-500">
                    @foreach($product->attributeValues as $attributeValue)
                        {{ $attributeValue->value}}
                        @if(!$loop->last)
                            , 
                        @endif
                    @endforeach
                </p>
            </div>
        </div>
    </div>

    <div class="w-full px-4 text-center md:w-1/5">
        <div @class([
                'p-2 w-32 mx-auto text-gray-100 rounded-md focus:border-4 ',
                'bg-green-500 border-green-300' => $product->quantity && !$product->isLowStock(),
                'bg-yellow-500 border-yellow-300' => $product->quantity && $product->isLowStock(),
                'bg-gray-800 border-gray-600' => !$product->quantity
            ])
        >
            {{ $product->stock_status }}
        </div>
    </div>

    <div class="flex items-center justify-center w-full px-4 md:w-1/5">
        <p class="text-lg font-bold text-primary-500 font-heading">{{ $product->price }}€</p>
        @if($product->discount)
        <span class="text-xs text-gray-500 line-through">{{ $product->original_price}}€</span>
        @endif
    </div>

    <div class="w-full px-4 mt-6 text-center md:w-1/5 md:mt-0">
        @if($product->quantity)
        <button class="flex px-6 py-2 ml-auto text-white border-0 rounded disabled:bg-primary-400 bg-primary-500 focus:outline-none hover:bg-primary-600"
            @disabled(!$product->quantity)
            wire:click.prevent="moveToCart({{$product}})"
        >
            {{ __('shopping_cart.move.cart') }}<x-icons.cart class="ml-1" /></button>
        </button>
        @endif
    </div>

    <a href="#" class="absolute top-0 right-0 font-medium text-primary-600 dark:text-primary-500 hover:underline"
        wire:click.prevent="removeFromWishlist({{ $product->id }})"
    >
        <x-icons.x class="w-4 h-4 text-gray-600"/>
    </a>
</div>