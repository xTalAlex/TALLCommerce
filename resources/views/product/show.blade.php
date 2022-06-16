<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ $product->name }}
    </h2>
</x-slot>
<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="py-6 overflow-hidden bg-white shadow-xl md:flex sm:rounded-lg">

            <div class="flex flex-col md:w-1/2"
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
                wire:ignore
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
                    @if($product->discount())
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
            </div>

        </div>
    </div>
</div>
