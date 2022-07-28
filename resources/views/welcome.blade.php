<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="h-96 xl:h-[28rem] 2xl:h-[32rem] flex items-center justify-center w-full bg-cover bg-[url('https://random.imagecdn.app/1500/320')]">
        <x-algolia-autocomplete class="w-full mx-4 md:w-72"/>
    </div>

    @foreach($featured_products as $product)
        <livewire:product.featured
            :product="$product"
        />
    @endforeach

    <x-image-group :items="$collections"/>

    <x-carousel :items="$featured_categories"/>

    <x-logo-cloud class="my-12" :items="$brands"/>
    
</x-app-layout>
