<x-slot name="seo">
    {!! seo($product) !!}
</x-slot>

<x-slot name="header">
    <div class="text-xl font-semibold leading-tight">
        <a class="underline" href="{{ route('product.index') }}">{{ __('Shop') }}</a> / <span class="text-gray-500">{{ $product->name }}</span>
    </div>
</x-slot>

<div class="px-6 py-8 mx-auto max-w-7xl lg:px-8">
    
    <div class="flex flex-wrap mx-auto">
            {{-- Left Side --}}
            <div class="flex flex-col mx-auto md:w-1/2 " wire:key='{{$product->id}}'
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

                <div class="w-full h-64 mx-auto overflow-hidden border-2 lg:h-96">
                    @if($product->hasImage())
                        <a :href="curImage">
                            <img class="object-contain object-center h-full max-h-full m-auto transition-all duration-300 ease-in cursor-zoom-in hover:scale-125"
                                alt="{{ $product->name }}"
                                :src="curImage"
                                x-transition.duration.500ms
                                x-show = "show ">
                        </a>
                    @else
                        <img class="object-contain object-center h-full max-h-full m-auto"
                            alt="{{ $product->name }}"
                            :src="curImage"
                            x-transition.duration.500ms
                            x-show = "show ">
                    @endif
                </div>
                @if(count($this->gallery) > 1)
                    <div class="inline-flex mt-4 space-x-2">
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
            <div class="flex flex-col justify-between w-full mt-12 space-y-2 lg:w-1/2 lg:pl-12 lg:pb-0 lg:mt-0">
                
                {{-- Title Section --}}
                <div class="space-y-2">
                    {{-- @if($product->brand)
                        <img class="inline-flex w-24 h-12" alt="{{ $product->brand->name }}" title="{{ $product->brand->name }}" 
                            src="{{ $product->brand->logo }}"/>
                    @endif --}}
                    <h1 class="text-3xl">
                        {{ $product->name }}
                    </h1>
                    <p class="text-gray-500">
                        {{ $product->short_description }}
                    </p>
                </div>
                {{-- End Title Section --}}

                {{-- Details Section --}}
                <div class="flex flex-col py-2 space-y-2">
                    
                    {{-- @if( $this->shouldSelectVariantByImage() )
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
                    @endif --}}
                    
                    @if( $this->shouldSelectVariantByAttribute() )
                        <div class="">{{ __('Avaiable variants') }}:</div>
                        @foreach($attributes as $id=>$name)
                            @if($product->attributeValues->pluck('attribute_id')->contains($id))
                                <div class="py-1">
                                    <div class="relative flex space-x-1">
                                        @foreach ( $variantsAttributeValues->where('attribute.id',$id)->sortBy('value') as $attributeValue )
                                            <label @class([
                                                'px-2 py-1 border cursor-pointer',
                                                'text-gray-500' => !$this->variantExists($id, $attributeValue->id),
                                                'border-primary-500' => $this->selection[$id] == $attributeValue->id
                                            ])>
                                                {{ $this->getAttributeValueLabel($name, $attributeValue->value) }}
                                                <input type="radio" class="hidden"
                                                    wire:model="selection.{{$id}}"
                                                    value="{{$attributeValue->id}}"
                                                />
                                            </label>
                                        @endforeach                                             
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    @if($this->tags->count())
                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                            <x-icons.tags class="mt-0.5"/>
                            @foreach($this->tags as $tag )
                                <span class="capitalize">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                {{-- End Details Section --}}
                
                {{-- Actions --}}
                <div class="space-y-2 md:pb-28">

                    @auth
                        <div class="flex py-6">
                            <div class="text-3xl font-black text-gray-900">
                                {{$product->taxed_selling_price}}€
                            </div>
                            @if($product->discount)
                                <div class="ml-1 -mt-2 text-2xl font-medium text-gray-500 line-through">
                                    {{$product->taxed_original_price}}€
                                </div>
                            @endif
                        </div>
                    @endauth

                    @guest
                        <a class="w-full" href="{{ route('product.login', $product) }}">
                            <x-secondary-button class="w-full text-base">{{ __('Login to view price') }}</x-secondary-button>
                        </a>
                    @endguest

                    <x-button class="w-full text-base" wire:click="addToCart">
                        {{ __('Add to cart') }}<x-icons.cart class="ml-1" />
                    </x-button>

                    @if(!$this->wishlistContains($product))
                        <x-button class="w-full text-base" wire:click="addToWishlist" ghost="true"
                        >
                            {{ __('Wishlist') }}<x-icons.heart class="ml-1" red="false" filled="false"/>
                        </x-button>
                    @else
                        <x-button class="w-full text-base" ghost="true"
                            wire:click="removeFromWishlist"
                        >
                            {{ __('Wishlist') }}<x-icons.heart class="ml-1" red="false" filled="true"/>
                        </x-button>
                    @endif

                </div>
                {{-- End Actions --}}
                
            </div>
            {{-- End Right Side --}}
            
    </div>

    <div class="mt-12"
        x-data="{
            tab: @entangle('tab')
        }"
    >

        <div class="flex mb-12 space-x-6 text-xl border-b">
            <div class="pb-4 transition ease-in-out border-b-2 cursor-pointer hover:border-primary-500"
                :class="{ 
                    'opacity-100 border-primary-500' : tab == 0,
                    'opacity-80' : tab != 0
                }"
                x-on:click="tab=0"
            >{{ __('Description') }}</div>
            <div class="pb-4 transition ease-in-out border-b-2 cursor-pointer hover:border-primary-500"
                :class="{ 
                    'opacity-100 border-primary-500' : tab == 1,
                    'opacity-80' : tab != 1
                }"
                x-on:click="tab=1"
            >
                {{ trans_choice('Review',2) }}
                @if($product->reviews->count())
                    <span class="ml-2 text-sm text-gray-500">{{ $product->avg_rating}}<x-icons.star class="-mt-1"/></span> 
                @endif 
            </div>
        </div>

        <div class=""
            x-show="tab==0"
            x-cloak
            x-transition.opacity
        >
            <div class="my-4 prose text-gray-500 max-w-none">
                @if($this->description)
                    {!! $this->description !!}
                @else
                    {{ __('No description') }}
                @endif
            </div>
        </div>

        <div
            x-show="tab==1"
            x-cloak
            x-transition.opacity
        >
            <livewire:product.reviews key="product-{{$product->id}}" :product='$product'/>
        </div>
    </div>

</div>

{{-- @if($product->quantity && $product->quantity < config('custom.stock_threshold'))
    <span class="p-2 ml-4 text-red-500 rounded">{{ __('Low Stock') }}</span>
@endif
@if($product->quantity)
    <x-button class="w-full py-4 text-base" wire:click="addToCart">{{ __('Add to cart') }}<x-icons.cart class="ml-1" /></x-button>
@else
    <button disabled class="flex px-6 py-2 ml-auto text-white bg-gray-500 border-0 rounded focus:outline-none"
    >{{ __('Out of Stock') }}</button>
@endif --}}
{{-- <div class="flex mb-2">
    @if($product->reviews->count())
    <span class="flex items-center">
        @for ($i = 1; $i <= $this->product->avg_rating; $i++) 
            <x-icons.star/>  
        @endfor
        @for ($i = 5; $i > $this->product->avg_rating; $i--) 
            <x-icons.star-empty/>  
        @endfor
        <span class="ml-1 text-sm text-gray-500">{{$reviews->count()}}</span>
    </span>
    @endif
</div> --}}
{{-- <select class="py-2 pl-3 pr-10 text-base border border-gray-300 rounded appearance-none focus:outline-none focus:ring-2 focus:ring-primary-200 focus:border-primary-500"
    wire:model="selection.{{$id}}"
>
    @foreach ( $variantsAttributeValues->where('attribute.id',$id)->sortBy('value') as $attributeValue )
        <option 
            class="@if(!$this->variantExists($id, $attributeValue->id)) text-gray-400 @endif"
            value="{{$attributeValue->id}}">{{$attributeValue->value}}
        </option>
    @endforeach
</select> --}}