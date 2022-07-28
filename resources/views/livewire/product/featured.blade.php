<section class="text-gray-600 lg:px-32 body-font">
  <div class="container flex flex-col items-center px-5 py-24 mx-auto md:flex-row">
    
    <div class="flex flex-col items-center mb-16 text-center lg:flex-grow md:w-1/2 lg:pr-24 md:pr-16 md:items-start md:text-left md:mb-0">
      
        <h1 class="mb-4 text-3xl font-medium text-gray-900 title-font sm:text-4xl">
            <a href="{{ route('product.show', $product) }}">
                {{ $product->name }}
            </a>
        </h1>

        <div class="flex items-center mt-2.5 mb-5">
            <x-icons.star/>
            <x-icons.star/>
            <x-icons.star/>
            <x-icons.star/>
            <x-icons.star/>
            <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ml-3"
            >5.0</span>
        </div>

        <p class="mb-8 leading-relaxed">
            {{ $product->short_description}}
        </p>

        <div class="mb-8 leading-relaxed">
            @if($product->discount)
            <span class="text-xl text-gray-900 line-through dark:text-white">{{ $product->original_price }}€</span>
            @endif
            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $product->price }}€</span>
        </div>

        <div class="flex justify-center">
            @if($product->quantity)
            <button class="inline-flex px-6 py-2 text-lg text-white bg-indigo-500 border-0 rounded focus:outline-none hover:bg-indigo-600"
                wire:click='addToCart'
            >
                {{ __('Add to cart') }}<x-icons.cart class="ml-1" />
            </button>
            @else
            <button class="inline-flex px-6 py-2 text-lg text-white bg-gray-500 border-0 rounded focus:outline-none hover:bg-gray-600"
                wire:click='addToCart'
            >
                {{ __('Out of Stock') }}
            </button>
            @endif
            <button class="inline-flex px-6 py-2 ml-4 text-lg text-gray-700 bg-gray-100 border-0 rounded focus:outline-none hover:bg-gray-200"
                wire:click='addToWishlist'
            >
                <x-icons.heart/>
            </button>
        </div>


    </div>


    <div class="w-5/6 lg:max-w-lg lg:w-full md:w-1/2">
        <a href="{{ route('product.show', $product) }}">
            <img class="object-cover object-center mx-auto rounded h-96" alt="{{ $product->name }}" src="{{ $product->image }}">
        </a>
    </div>

  </div>
</section>