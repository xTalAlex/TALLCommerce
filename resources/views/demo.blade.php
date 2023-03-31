<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Home') }}
        </h1>
    </x-slot>

    <div class="h-96 xl:h-[28rem] 2xl:h-[32rem] flex items-center justify-center w-full bg-cover bg-[url('https://random.imagecdn.app/1500/320')]">
        <x-algolia-autocomplete class="w-full mx-4 md:w-72"/>
    </div>

    <div class="mt-12">
    @foreach($featured_products as $product)
        <livewire:product.featured
            :product="$product"
        />
    @endforeach
    </div>

    <div class="mt-12">
        <x-image-group :items="$collections"/>
    </div>

    <div class="mt-12">
        <x-carousel :items="$featured_categories"/>
    </div>

    <x-logo-cloud class="mt-12" :items="$brands"/>

    <div class="mt-12"> 
        @include('contacts')
    </div>
    
</x-app-layout>
