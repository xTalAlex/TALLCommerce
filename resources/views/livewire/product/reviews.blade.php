<div class="grid md:grid-cols-2">
    <div class="flex flex-col pb-12 my-4">
    @if($reviews->count())
    
        @foreach($reviews as $review)
        <div class="py-6 border-b last:border-none">
            <div class="flex flex-col space-y-2">

                <div class="flex">
                    @for ($i = 1; $i <= $review->rating; $i++) 
                        <x-icons.star/>  
                    @endfor
                    @for ($i = 5; $i > $review->rating; $i--) 
                        <x-icons.star-empty/>  
                    @endfor
                </div>

                @if($review->description)
                <div class="prose text-gray-600"
                    style="overflow-wrap: anywhere;"
                >
                    {!! $review->description !!}
                </div>
                @endif
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <img class="w-8 h-8 rounded-full" src="{{ $review->user->profile_photo_url }}" />
                        <div class="flex flex-col space-y-1">
                            <div class="text-sm font-semibold">{{ $review->user->name }}</div>
                            <span class="text-xs text-gray-500">{{ $review->created_at->format(config('custom.datetime_format')) }}</span>
                        </div>
                    </div>
                    
                    
                    <div>
                        @can('delete', $review)
                            <livewire:review.destroy-form :review='$review' key="delete-{{ $review->id }}"/>
                        @endcan
                    </div>
                </div>

            </div>
        </div>
        @endforeach

        <div class="w-full mt-6">
            {{ $reviews->links() }}
        </div>
    @else
        <div class="text-gray-500">
            {{ __('No reviews') }}
        </div>
    @endif
    </div>

    @can('create',[App\Models\Review::class,$product])
    <section class="relative md:pl-12 lg:pl-24">
        <div class="w-full mx-auto mb-12 md:mb-0">

            <div class="flex flex-col w-full text-center">
                <div class="text-xl font-semibold">
                    {{__('Leave a Review')}} 
                </div>
            </div>
            
            <form class="flex flex-col w-full mx-auto mt-4 space-y-2" action="{{ route('review.store', $product) }}" method="POST" >
            @csrf
                <div class="relative flex items-center space-x-1"
                    x-data="{ rating : 5 }"
                >
                    <label for="rating" class="text-sm">{{ __('Rating') }}:</label>
                    <input class="hidden" type="number" id="rating" min="0" max="5" name="rating"
                        x-bind:value="rating"
                    >
                    <div class="-mt-1">
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

                <div class="w-full" x-data="{ value : '',  max_chars : 500 }">
                    <textarea class="w-full h-32 text-sm border-gray-300 shadow-sm resize-none focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                        id="description" name="description" x-bind:maxlength="max_chars" 
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

                <div class="w-full pt-2">
                    <x-button class="w-full">{{ __('Submit') }}</x-button>
                </div>

                <x-jet-validation-errors class="my-2" />
            </form>
            
        </div>
    </section>
    @endcan
    
</div>
