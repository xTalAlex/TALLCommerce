<div>
    @if($reviews->count())
    <section class="text-gray-600 body-font">
        <div class="flex flex-col w-full text-center">
            <h1 class="mb-4 text-2xl font-medium text-gray-900 sm:text-3xl title-font">{{__('Reviews')}}</h1>
        </div>

        <div class="w-full container flex flex-wrap py-12 mx-auto">
            
            <div class="flex flex-wrap w-full">
                @foreach($reviews as $review)
                <div class="w-full p-4">
                    <div class="bg-white flex flex-col p-8 border-2 border-gray-200 border-opacity-50 rounded-lg sm:flex-row">
                    <div class="inline-flex items-center justify-center flex-shrink-0 w-16 h-16 mb-4 text-indigo-500 bg-indigo-100 rounded-full sm:mr-8 sm:mb-0">
                        <img src="{{ $review->user->profile_photo_url }}" class="rounded-full" />
                    </div>
                    <div class="flex-grow">
                        <h2 class="text-lg font-medium text-gray-900 title-font">{{ $review->user->name }}</h2>
                        <p class="text-xs text-gray-600">{{ $review->created_at->format(config('custom.datetime_format')) }}</p>
                        <p class="inline-flex items-center mt-4 text-indigo-500">
                            @for ($i = 1; $i <= $review->rating; $i++) 
                                <x-icons.star/>  
                            @endfor
                            @for ($i = 5; $i > $review->rating; $i--) 
                                <x-icons.star-empty/>  
                            @endfor
                        </p>
                        <p class="text-base leading-relaxed mt-4">{{ $review->description }}</p>
                        
                        @can('delete', $review)
                        <div class="flex justify-end ">
                            <livewire:review.destroy-form :review='$review'/>
                        </div>
                        @endcan
                    </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6 w-full">
                {{ $reviews->links() }}
            </div>

        </div>
        
    </section>
    @endif

    @can('create',[App\Models\Review::class,$product])
    <section class="relative text-gray-600 body-font">
        <div class="w-full py-12 mx-auto bg-white">
            <div class="flex flex-col w-full text-center">
                <h1 class="mb-4 text-2xl font-medium text-gray-900 sm:text-3xl title-font">
                {{__('Leave a Review')}}
                </h1>
            </div>
            <div class="mx-auto w-full md:w-1/2">
                <form action="{{ route('review.store', $product) }}" method="POST" >
                @csrf
                <div class="flex flex-wrap -m-2">
                    <div class="w-1/2 p-2">
                        <div class="relative"
                            x-data="{
                                rating : 5,
                            }"
                        >
                            <label for="rating" class="text-sm leading-7 text-gray-600">{{ __('Rating') }}</label>
                            <input type="number" id="rating" min="0" max="5" name="rating" class="hidden w-full px-3 py-1 text-base leading-8 text-gray-700 transition-colors duration-200 ease-in-out bg-gray-100 bg-opacity-50 border border-gray-300 rounded outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                                x-bind:value="rating"
                            >
                            <div>
                                <template x-for="i in rating">
                                    <x-icons.star class="cursor-pointer"
                                        x-on:click="rating = i"
                                    />
                                </template>
                                <template x-for="i in 5-rating">
                                    <x-icons.star-empty  class="cursor-pointer"
                                        x-on:click="rating = rating + i"
                                    />
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="w-full p-2">
                        <div class="relative"
                            x-data="{
                                    value : '',
                                    max_chars : 500,
                                }"
                        >
                            <textarea id="description" name="description" :maxlength="max_chars" class="w-full h-32 px-3 py-1 text-base leading-6 text-gray-700 transition-colors duration-200 ease-in-out bg-gray-100 bg-opacity-50 border border-gray-300 rounded outline-none resize-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                                x-model="value"
                            ></textarea>
                            <p class="text-xs"
                                :class="(value.length < max_chars) ? 'text-gray-600' : 'text-red-500'"
                            >
                                <span x-text="value.length" ></span> 
                                /
                                <span x-text="max_chars" ></span>
                            </p>
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
    
</div>
