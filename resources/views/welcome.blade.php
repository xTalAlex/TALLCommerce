<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <x-carousel :products="$carousel_products"/>

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
    
</x-app-layout>
