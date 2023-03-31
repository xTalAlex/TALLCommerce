{{-- <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800" id="breadcrumb">
    </h2>
</x-slot> --}}

<div class="mx-auto max-w-7xl"
    x-init="
        $wire.on('goTop', () => { 
            window.scrollTo({top:0}) 
        })
    "
>    
    <div class="flex flex-col w-full py-8 mx-auto lg:space-x-4 lg:flex-row lg:inline-flex sm:px-6 lg:px-8">

        @include('product._filters-bar')

        <div class="w-full overflow-hidden">

            @if(count($products))
            <div class="grid grid-cols-2 mx-6 my-12 gap-x-6 gap-y-12 md:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $product)
                    <a class="block w-full h-full" href="{{ route('product.show', $product) }}">
                        <div @class([
                            'flex flex-col items-center p-2 h-full w-full',
                            ])
                        >

                            <div class="relative h-48 overflow-hidden group">
                                <img @class([
                                        'object-cover h-full',
                                        'transition transform duration-200 group-hover:scale-90' => $product->hasImage(),
                                        'translate-x-0 group-hover:translate-x-full' => count($product->gallery)>1
                                    ])
                                    src="{{ $product->image }}" />
                                @if(count($product->gallery)>1)
                                    <img @class([
                                            'object-cover h-full absolute inset-0',
                                            'transition transform duration-200 group-hover:translate-x-0 -translate-x-full' => $product->hasImage()
                                        ])
                                        src="{{ $product->gallery[1] }}" />
                                @endif
                                @if($product->hasImage())
                                    <div class="absolute top-0 block w-1/2 h-full transform -skew-x-12 -inset-full z-5 bg-gradient-to-r from-transparent to-white opacity-40 group-hover:animate-shine"></div>
                                @endif
                            </div>

                            <div class="mt-1 text-base font-bold text-center">{{ $product->name }}</div>
                            <div class="text-gray-600">{{ $product->short_description }}</div>
                            
                            @auth
                            <div class="flex-none w-full pt-2 mt-auto mb-0">
                                <div class="flex justify-center mt-1">
                                    {{-- <span>
                                        @if( !$product->defaultVariant()->exists() && !$product->variants()->exists())
                                            {{ $product->stock_status}}                                     
                                        @endif
                                    </span> --}}
                                    <div class="relative w-full">
                                        <x-button class="w-full">
                                            {{ $product->taxed_price }}€
                                            @if($product->discount) 
                                                <span class="ml-1 text-white line-through text-opacity-80">
                                                    {{ $product->taxed_original_price }}€
                                                </span>
                                            @endif
                                        </x-button>
                                    </div>
                                </div>
                            </div>
                            @endauth
                            
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="flex justify-center">
                {{ $products->links() }}
            </div>
            @else
                <div class="grid h-64 place-items-center">
                    <p class="font-semibold text-gray-600">
                        @if($query)
                            {{ __('No results for') . " \"${query}\"" }}
                        @else
                            {{ __('No results') }}
                        @endif
                    </p>
                </div>
            @endif

        </div>

    </div>
</div>
