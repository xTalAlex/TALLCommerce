<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ $product->name }}
    </h2>
</x-slot>
<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="py-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <div class="md:flex">
                <div class="flex flex-col md:w-1/2" wire:key='{{$product->id}}'
                    x-data="{
                        curImage : '{{ $product->image }}',
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
                    <img class="object-contain mx-auto border h-96 w-96" 
                        :src="curImage"
                        x-transition.duration.500ms
                        x-show = "show "
                    />

                    @if($product->gallery->count() > 1)
                        <div class="inline-flex mx-auto mt-6 space-x-2">
                        @foreach ($product->gallery as $image )
                            <div class="border">
                                <img class="object-cover w-24 h-24" src="{{ $image }}" 
                                    @click="changeImage('{{ $image }}')"
                                />
                            </div>
                        @endforeach
                        </div>
                    @endif
                </div>

                <div class="px-5 pb-5 mx-auto space-y-4 md:w-1/2">
                    <h2 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                        {{ $product->name }}
                    </h2>
                    <p>{{ $product->short_description ?? 'Short Description here' }}</p>
                    <p>{{ $product->description ?? 'Description here' }}</p>
                    <div>
                        @if($product->discount)
                            <span class="text-2xl text-gray-900 line-through dark:text-white">{{$product->original_price}}€</span>
                        @endif
                            <span class="text-xl font-bold text-gray-900 dark:text-white">{{ $product->selling_price }}€</span>
                    </div>

                    <div class="inline-flex space-x-4">

                        @if($product->quantity)
                        <form  wire:submit.prevent="addToCart" >
                        @csrf
                            <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                {{ __('Add to cart') }}
                            </button>
                            @if($product->quantity && $product->quantity < config('custom.stock_threshold'))
                                {{ __('Low Stock') }}
                            @endif
                        </form>
                        @else
                            <button disabled class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                {{ __('Out of Stock') }}
                            </button>
                        @endif

                        <form wire:submit.prevent="addToWishlist">
                        @csrf
                            <button class="p-2 text-xs font-medium text-center text-white bg-red-700 rounded-full hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>

                        </form>
                    </div>

                    @if($product->defaultVariant || $product->variants()->count())
                    <div class="flex flex-row space-x-4">
                        <a href="{{ route('product.show', $product->defaultVariant? $product->defaultVariant : $product) }}" class="px-1 border rounded-lg">
                            <img class="w-8 h-8" src="{{ $product->defaultVariant ? $product->defaultVariant->image : $product->image }}"/>
                        </a>
                        @foreach($product->defaultVariant? $product->defaultVariant->variants : $product->variants as $variant)
                        <a href="{{ route('product.show', $variant) }}" class="px-1 border rounded-lg">
                            <img class="w-8 h-8" src="{{ $variant->image}}"/>
                        </a>
                        @endforeach
                    </div>
                    @endif

                    <div>
                    @foreach($product->attributeValues as $attributeValue)
                        <div>{{ $attributeValue->attribute->name}} : {{ $attributeValue->value}}</div>
                    @endforeach
                    </div>

                    @if($product->defaultVariant || $product->has('variants'))
                    <div>
                        @foreach($attributes as $attribute)
                            <div>
                                {{ $attribute }}: 
                                <select wire:model="selection.{{$attribute}}">
                                    @foreach ( $attributeSet->where('attribute.name',$attribute) as $attributeValue )
                                        <option value="{{$attributeValue->id}}">{{$attributeValue->value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                    @endif

                </div>
            </div>

            <section class="text-gray-600 body-font">
                <div class="container flex flex-wrap px-5 py-24 mx-auto">
                    <div class="flex flex-wrap w-full -m-4">
                        @foreach($product->reviews as $review)
                        <div class="w-full p-4 md:w-1/2 lg:w-1/3">
                            <div class="flex flex-col p-8 border-2 border-gray-200 border-opacity-50 rounded-lg sm:flex-row">
                            <div class="inline-flex items-center justify-center flex-shrink-0 w-16 h-16 mb-4 text-indigo-500 bg-indigo-100 rounded-full sm:mr-8 sm:mb-0">
                                <img src="{{ $review->user->profile_photo_url }}" class="rounded-full" />
                            </div>
                            <div class="flex-grow">
                                <h2 class="mb-3 text-lg font-medium text-gray-900 title-font">{{ $review->user->name }}</h2>
                                <p class="text-base leading-relaxed">{{ $review->description }}</p>
                                <p class="inline-flex items-center mt-3 text-indigo-500">{{ $review->vote }}
                                </p>
                                @can('delete', $review)
                                <livewire:review.destroy-form :review='$review'/>
                                @endcan
                            </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            @can('create',[App\Models\Review::class,$product])
            <section class="relative text-gray-600 body-font">
                <div class="container px-5 py-12 mx-auto">
                    <div class="flex flex-col w-full mb-12 text-center">
                        <h1 class="mb-4 text-2xl font-medium text-gray-900 sm:text-3xl title-font">{{__('Review')}}</h1>
                    </div>
                    <div class="mx-auto lg:w-1/2 md:w-2/3">
                        <form action="{{ route('review.store', $product) }}" method="POST" >
                        @csrf
                        <div class="flex flex-wrap -m-2">
                            <div class="w-1/2 p-2">
                                <div class="relative">
                                    <label for="vote" class="text-sm leading-7 text-gray-600">{{ __('Vote') }}</label>
                                    <input type="number" id="vote" min="0" max="5" name="vote" class="w-full px-3 py-1 text-base leading-8 text-gray-700 transition-colors duration-200 ease-in-out bg-gray-100 bg-opacity-50 border border-gray-300 rounded outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200">
                                </div>
                            </div>
                            <div class="w-full p-2">
                                <div class="relative">
                                    <textarea id="message" name="description" class="w-full h-32 px-3 py-1 text-base leading-6 text-gray-700 transition-colors duration-200 ease-in-out bg-gray-100 bg-opacity-50 border border-gray-300 rounded outline-none resize-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"></textarea>
                                </div>
                            </div>
                            <div class="w-full p-2">
                                <button type="submit" class="flex px-8 py-2 mx-auto text-lg text-white bg-indigo-500 border-0 rounded focus:outline-none hover:bg-indigo-600">{{ __('Submit') }}</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </section>
            @endcan

        <section class="overflow-hidden text-gray-600 body-font">
        <div class="container px-5 py-24 mx-auto">
            <div class="flex flex-wrap mx-auto lg:w-4/5">
            <img alt="ecommerce" class="object-cover object-center w-full h-64 rounded lg:w-1/2 lg:h-auto" src="https://dummyimage.com/400x400">
            <div class="w-full mt-6 lg:w-1/2 lg:pl-10 lg:py-6 lg:mt-0">
                <h2 class="text-sm tracking-widest text-gray-500 title-font">BRAND NAME</h2>
                <h1 class="mb-1 text-3xl font-medium text-gray-900 title-font">The Catcher in the Rye</h1>
                <div class="flex mb-4">
                <span class="flex items-center">
                    <svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-indigo-500" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                    </svg>
                    <svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-indigo-500" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                    </svg>
                    <svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-indigo-500" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                    </svg>
                    <svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-indigo-500" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                    </svg>
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-indigo-500" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                    </svg>
                    <span class="ml-3 text-gray-600">4 Reviews</span>
                </span>
                <span class="flex py-2 pl-3 ml-3 border-l-2 border-gray-200 space-x-2s">
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
                </span>
                </div>
                <p class="leading-relaxed">Fam locavore kickstarter distillery. Mixtape chillwave tumeric sriracha taximy chia microdosing tilde DIY. XOXO fam indxgo juiceramps cornhole raw denim forage brooklyn. Everyday carry +1 seitan poutine tumeric. Gastropub blue bottle austin listicle pour-over, neutra jean shorts keytar banjo tattooed umami cardigan.</p>
                <div class="flex items-center pb-5 mt-6 mb-5 border-b-2 border-gray-100">
                <div class="flex">
                    <span class="mr-3">Color</span>
                    <button class="w-6 h-6 border-2 border-gray-300 rounded-full focus:outline-none"></button>
                    <button class="w-6 h-6 ml-1 bg-gray-700 border-2 border-gray-300 rounded-full focus:outline-none"></button>
                    <button class="w-6 h-6 ml-1 bg-indigo-500 border-2 border-gray-300 rounded-full focus:outline-none"></button>
                </div>
                <div class="flex items-center ml-6">
                    <span class="mr-3">Size</span>
                    <div class="relative">
                    <select class="py-2 pl-3 pr-10 text-base border border-gray-300 rounded appearance-none focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                        <option>SM</option>
                        <option>M</option>
                        <option>L</option>
                        <option>XL</option>
                    </select>
                    <span class="absolute top-0 right-0 flex items-center justify-center w-10 h-full text-center text-gray-600 pointer-events-none">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4" viewBox="0 0 24 24">
                        <path d="M6 9l6 6 6-6"></path>
                        </svg>
                    </span>
                    </div>
                </div>
                </div>
                <div class="flex">
                <span class="text-2xl font-medium text-gray-900 title-font">$58.00</span>
                <button class="flex px-6 py-2 ml-auto text-white bg-indigo-500 border-0 rounded focus:outline-none hover:bg-indigo-600">Button</button>
                <button class="inline-flex items-center justify-center w-10 h-10 p-0 ml-4 text-gray-500 bg-gray-200 border-0 rounded-full">
                    <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"></path>
                    </svg>
                </button>
                </div>
            </div>
            </div>
        </div>
        </section>

        </div>
    </div>
</div>
