<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Shop') }}
            @if($category)
                {{ " / ".$category->name }}
            @endif
        </h2>
    </x-slot>

    <div class="flex justify-center py-8">
        <div class="flex flex-col w-full mx-auto md:space-x-3 md:flex-row md:inline-flex max-w-7xl sm:px-6 lg:px-8">

            <x-sidebar></x-sidebar>
            
            <div class="w-full overflow-hidden bg-white shadow-xl sm:rounded-lg">
            
                <x-searchbar></x-searchbar>
                
                <div class="grid grid-cols-1 gap-4 p-2 mt-2 md:grid-cols-3 lg:grid-cols-4">
                    @forelse($products as $product)
                        <div class="">
                            <livewire:product.card :product="$product"/>
                        </div>
                    @empty
                        <div class="text-center">
                            No results
                        </div>
                    @endforelse 
                </div>

                <div class="m-2">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
