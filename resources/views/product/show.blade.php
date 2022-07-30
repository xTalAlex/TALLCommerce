<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ $product->name }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
       
        <section class="overflow-hidden text-gray-600 body-font">
            <div class="container px-5 py-12 mx-auto">
                <div class="flex flex-wrap mx-auto">

                    {{-- Left Side --}}
                    <div class="flex flex-col mx-auto md:w-1/2" wire:key='{{$product->id}}'
                        x-data="{
                                curImage : '{{ $this->gallery[0]}}',
                                show : true,
                                transitionTotalTime : 500,
                                changeImage(src){
                                    if(this.curImage != src){
                                        this.show = false;                               
                                        setTimeout(() => { 
                                            this.curImage = src; 
                                            this.show = true} ,
                                            this.transitionTotalTime
                                        );
                                    }
                                }
                            }"
                    >

                        <div class="h-64 w-full lg:w-1/2 lg:h-96 rounded mx-auto">
                            <a :href="curImage">
                            <img alt="{{ $product->name }}" class="m-auto cursor-zoom-in hover:scale-150 transition-all ease-in object-contain object-center h-full max-h-full"
                                :src="curImage"
                                x-transition.duration.500ms
                                x-show = "show ">
                            </a>
                        </div>
                        @if(count($this->gallery) > 2)
                            <div class="inline-flex mx-auto mt-12 space-x-2">
                            @foreach ($this->gallery as $image )
                                <div class="border cursor-pointer"
                                    @click="changeImage('{{ $image }}')"
                                >
                                    <img class="object-contain w-24 h-24" src="{{ $image }}"     
                                    />
                                </div>
                            @endforeach
                            </div>
                        @endif

                    </div>
                    {{-- End Left Side --}}

                    {{-- Right Side --}}
                    <div class="w-full mt-12 lg:w-1/2 lg:pl-10 lg:py-6 lg:mt-0">
                        
                        @if($product->brand)
                        <h2 class="text-sm tracking-widest text-gray-500 title-font">{{ $product->brand->name}}</h2>
                        @endif

                        <h1 class="mb-1 text-3xl font-medium text-gray-900 title-font">{{ $product->name }}</h1>

                        {{-- Title Section --}}
                        <div class="flex mb-4">
                            @if($product->reviews->count())
                            <span class="flex items-center">
                                @for ($i = 1; $i <= $this->avg_rating; $i++) 
                                    <x-icons.star/>  
                                @endfor
                                @for ($i = 5; $i > $this->avg_rating; $i--) 
                                    <x-icons.star-empty/>  
                                @endfor
                                <span class="ml-3 text-gray-600">{{$product->reviews->count()}} {{ __('Reviews')}}</span>
                            </span>
                            @endif
                            {{-- <span class="flex py-2 pl-3 ml-3 border-l-2 border-gray-200 space-x-2s">
                                <a class="text-gray-500">
                                <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                    <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                                </svg>
                                </a>
                                <a class="text-gray-500">
                                <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                    <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                                </svg>
                                </a>
                                <a class="text-gray-500">
                                <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                    <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                                </svg>
                                </a>
                            </span> --}}
                        </div>
                        {{-- End Title Section --}}
                    
                        <p class="leading-relaxed">
                            {!! $this->description !!}
                        </p>

                        {{-- Details Section --}}
                        <div class="flex items-center pb-5 mt-6 mb-5 border-b-2 border-gray-200">
                            
                            @if( $this->shouldSelectVariantByImage() )
                                <div class="flex flex-row space-x-4">
                                    @if($product->defaultVariant? $product->defaultVariant->hasImage() : $product->hasImage())
                                    <a href="{{ route('product.show', $product->defaultVariant? $product->defaultVariant : $product) }}" class="px-1 border rounded-lg">
                                        <img class="object-contain w-8 h-8 mx-auto" src="{{ $product->defaultVariant ? $product->defaultVariant->image : $product->image }}"/>
                                    </a>
                                    @endif
                                    @foreach($product->defaultVariant? $product->defaultVariant->variants : $product->variants as $variant)
                                    @if($variant->hasImage())
                                    <a href="{{ route('product.show', $variant) }}" class="px-1 border rounded-lg">
                                        <img class="w-8 h-8" src="{{ $variant->image}}"/>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            @endif
                            
                            @if( $this->shouldSelectVariantByAttribute() )
                                @foreach($attributes as $id=>$name)
                                    @if($product->attributeValues->pluck('attribute_id')->contains($id))
                                    <div class="flex items-center ml-6">
                                        <span class="mr-3 capitalize">{{$name}}</span>
                                        <div class="relative">
                                            <select class="py-2 pl-3 pr-10 text-base border border-gray-300 rounded appearance-none focus:outline-none focus:ring-2 focus:ring-primary-200 focus:border-primary-500"
                                                wire:model="selection.{{$id}}"
                                            >
                                                @foreach ( $variantsAttributeValues->where('attribute.id',$id) as $attributeValue )
                                                    <option 
                                                        class="@if(!$this->variantExists($id, $attributeValue->id)) text-gray-400 @endif"
                                                        value="{{$attributeValue->id}}">{{$attributeValue->value}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                    {{-- <div class="flex ml-6">
                                        <span class="mr-3 capitalize">{{ $name }}</span>
                                        @foreach ( $variantsAttributeValues->where('attribute.id',$id) as $attributeValue )
                                            <button class="w-6 h-6 ml-1 bg-{{ $attributeValue->value }}-700 border-2 border-gray-300 rounded-full focus:outline-none"></button>
                                        @endforeach
                                    </div> --}}

                                @endforeach
                            @endif
                            
                        </div>
                        {{-- End Details Section --}}

                        <div class="flex">
                            @if($product->discount)
                            <span class="mr-2 text-2xl font-medium text-gray-600 line-through title-font">{{$product->original_price}}€</span>
                            @endif
                            <span class="text-2xl font-medium text-gray-900 title-font">{{$product->selling_price}}€</span>
                            @if($product->quantity && $product->quantity < config('custom.stock_threshold'))
                                <span class="p-2 ml-4 text-red-500 rounded">{{ __('Low Stock') }}</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center pb-5 mt-6 mb-5 border-b-2 border-gray-100">
                            

                            <div class="flex">
                                @if($product->quantity)
                                    <button class="flex px-6 py-2 ml-auto text-white bg-primary-500 border-0 rounded focus:outline-none hover:bg-primary-600"
                                        wire:click="addToCart"
                                    >{{ __('Add to cart') }}<x-icons.cart class="ml-1" /></button>
                                @else
                                    <button disabled class="flex px-6 py-2 ml-auto text-white bg-gray-500 border-0 rounded focus:outline-none"
                                    >{{ __('Out of Stock') }}</button>
                                @endif

                                @if(!$this->wishlistContains($product))
                                <button class="inline-flex items-center justify-center w-10 h-10 p-0 ml-4 text-gray-500 bg-gray-200 border-0 rounded-full"
                                    wire:click="addToWishlist"
                                >
                                    <x-icons.heart filled="false"/>
                                </button>
                                @else
                                <button class="inline-flex items-center justify-center w-10 h-10 p-0 ml-4 text-gray-500 bg-gray-200 border-0 rounded-full"
                                    wire:click="removeFromWishlist"
                                >
                                    <x-icons.heart filled="true"/>
                                </button>
                                @endif
                            </div>
                        </div>
                        
                    </div>
                    {{-- End Right Side --}}
                    
                </div>
            </div>
        </section>

        <livewire:product.reviews :product='$product'/>

        </div>
    </div>
</div>