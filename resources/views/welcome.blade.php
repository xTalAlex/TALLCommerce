<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <x-carousel :products="$carousel_products"/>

    <div class="mt-12 h-80 flex items-center justify-center w-full bg-cover bg-[url('https://random.imagecdn.app/1500/320')]">
        <x-algolia-autocomplete class="w-full mx-4 md:w-72"/>
    </div>

    @foreach($featured_products as $product)
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex my-6">
                <div class="w-1/2">
                    <livewire:product.featured :product="$product"/>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <section class="text-gray-600 body-font">
    <div class="container flex flex-col items-center px-5 py-24 mx-auto md:flex-row">
        <div class="w-5/6 mb-10 lg:max-w-lg lg:w-full md:w-1/2 md:mb-0">
        <img class="object-cover object-center rounded" alt="hero" src="https://dummyimage.com/720x600">
        </div>
        <div class="flex flex-col items-center text-center lg:flex-grow md:w-1/2 lg:pl-24 md:pl-16 md:items-start md:text-left">
        <h1 class="mb-4 text-3xl font-medium text-gray-900 title-font sm:text-4xl">Before they sold out
            <br class="hidden lg:inline-block">readymade gluten
        </h1>
        <p class="mb-8 leading-relaxed">Copper mug try-hard pitchfork pour-over freegan heirloom neutra air plant cold-pressed tacos poke beard tote bag. Heirloom echo park mlkshk tote bag selvage hot chicken authentic tumeric truffaut hexagon try-hard chambray.</p>
        <div class="flex justify-center">
            <button class="inline-flex px-6 py-2 text-lg text-white bg-indigo-500 border-0 rounded focus:outline-none hover:bg-indigo-600">Button</button>
            <button class="inline-flex px-6 py-2 ml-4 text-lg text-gray-700 bg-gray-100 border-0 rounded focus:outline-none hover:bg-gray-200">Button</button>
        </div>
        </div>
    </div>
    </section>

    <section class="text-gray-600 body-font">
    <div class="container flex flex-wrap px-5 py-24 mx-auto">
        <div class="mx-auto lg:w-2/3">
        <div class="relative flex flex-wrap w-full px-10 py-32 mb-4 bg-gray-100">
            <img alt="gallery" class="absolute inset-0 block object-cover object-center w-full h-full opacity-25" src="https://dummyimage.com/820x340">
            <div class="relative z-10 w-full text-center">
            <h2 class="mb-2 text-2xl font-medium text-gray-900 title-font">Shooting Stars</h2>
            <p class="leading-relaxed">Skateboard +1 mustache fixie paleo lumbersexual.</p>
            <a class="inline-flex items-center mt-3 text-indigo-500">Learn More
                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                <path d="M5 12h14M12 5l7 7-7 7"></path>
                </svg>
            </a>
            </div>
        </div>
        <div class="flex flex-wrap -mx-2">
            <div class="w-1/2 px-2">
            <div class="relative flex flex-wrap w-full px-6 py-16 bg-gray-100 sm:py-24 sm:px-10">
                <img alt="gallery" class="absolute inset-0 block object-cover object-center w-full h-full opacity-25" src="https://dummyimage.com/542x460">
                <div class="relative z-10 w-full text-center">
                <h2 class="mb-2 text-xl font-medium text-gray-900 title-font">Shooting Stars</h2>
                <p class="leading-relaxed">Skateboard +1 mustache fixie paleo lumbersexual.</p>
                <a class="inline-flex items-center mt-3 text-indigo-500">Learn More
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                    </svg>
                </a>
                </div>
            </div>
            </div>
            <div class="w-1/2 px-2">
            <div class="relative flex flex-wrap w-full px-6 py-16 bg-gray-100 sm:py-24 sm:px-10">
                <img alt="gallery" class="absolute inset-0 block object-cover object-center w-full h-full opacity-25" src="https://dummyimage.com/542x420">
                <div class="relative z-10 w-full text-center">
                <h2 class="mb-2 text-xl font-medium text-gray-900 title-font">Shooting Stars</h2>
                <p class="leading-relaxed">Skateboard +1 mustache fixie paleo lumbersexual.</p>
                <a class="inline-flex items-center mt-3 text-indigo-500">Learn More
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                    </svg>
                </a>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    </section>
    
</x-app-layout>
