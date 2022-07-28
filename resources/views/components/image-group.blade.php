<div class="container flex flex-wrap px-5 py-24 mx-auto">
    <div class="mx-auto lg:w-2/3">

        @if($items->count())
        <div class="relative flex flex-wrap w-full px-10 py-32 mb-4 bg-gray-100">
            <img alt="{{ $items[0]['name']}}" class="absolute inset-0 block object-cover object-center w-full opacity-60 h-96" src="{{ $items[0]['hero'] }}">
            <div class="relative z-10 w-full text-center">
                <h2 class="mb-2 text-2xl font-medium text-gray-900 title-font">{{ $items[0]['name']}}</h2>
                <p class="leading-relaxed">{{ $items[0]['description'] }}</p>
                <a href="{{ $items[0]['url'] }}" class="inline-flex items-center mt-3 text-indigo-500">{{ __('Learn More') }}
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
        @endif
        
        @if($items->count()>1)
        <div class="flex flex-wrap -mx-2">
            <div class="w-1/2 px-2">
                <div class="relative flex flex-wrap w-full px-6 py-16 bg-gray-100 sm:py-24 sm:px-10">
                    <img alt="{{ $items[1]['name']}}" class="absolute inset-0 block object-cover object-center w-full opacity-60 h-72" src="{{ $items[1]['hero'] }}">
                    <div class="relative z-10 w-full text-center">
                    <h2 class="mb-2 text-xl font-medium text-gray-900 title-font">{{ $items[1]['name']}}</h2>
                    <p class="leading-relaxed">{{ $items[1]['description'] }}</p>
                    <a href="{{ $items[1]['url'] }}" class="inline-flex items-center mt-3 text-indigo-500">{{ __('Learn More') }}
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    </div>
                </div>
            </div>
            @if($items->count()>2)
            <div class="w-1/2 px-2">
                <div class="relative flex flex-wrap w-full px-6 py-16 bg-gray-100 sm:py-24 sm:px-10">
                    <img alt="{{ $items[2]['name']}}" class="absolute inset-0 block object-cover object-center w-full opacity-60 h-72" src="{{ $items[2]['hero']}}">
                    <div class="relative z-10 w-full text-center">
                    <h2 class="mb-2 text-xl font-medium text-gray-900 title-font">{{ $items[2]['name']}}</h2>
                    <p class="leading-relaxed">{{ $items[2]['description']}}</p>
                    <a href="{{ $items[2]['url'] }}" class="inline-flex items-center mt-3 text-indigo-500">{{ __('Learn More') }}
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif
    
    </div>
</div>