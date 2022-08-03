<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Orders') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">

                <div class="p-6">
                    @if($orders->count())
                    <ol class="relative w-full mx-auto border-l border-gray-200 dark:border-gray-700">
                        @foreach($orders as $order)
                        <li class="mb-10 ml-6 md:flex">
                            
                            <div class="flex-none w-full md:w-2/3">
                                <span class="absolute flex items-center justify-center w-6 h-6 rounded-full text-primary-600 dark:text-primary-400 bg-primary-200 -left-3 ring-8 ring-white dark:ring-gray-900 dark:bg-primary-900">
                                    <x-icons.bag class="w-3 h-3"/>    
                                </span>
                                <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white"
                                >{{ $order->number }} 
                                    <span class="bg-primary-100  uppercase text-primary-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-primary-200 dark:text-primary-800 ml-3"
                                    >{{ $order->status->label }}</span>
                                </h3>

                                <time class="block mb-4 text-sm font-normal leading-none text-gray-400 dark:text-gray-500"
                                    >{{ __('Created on') }} {{ $order->created_at->format(config('custom.date_format')) }}</time>

                                <p class="mb-4 text-base font-normal text-gray-900 dark:text-white"
                                >{{ __('Total') }}: {{ $order->total }}€</p>

                                <p class="mb-4 text-sm font-normal text-gray-500 dark:text-gray-400"
                                >{!! $order->shippingAddress()->label !!}</p>
                            
                                <div class="w-full pt-4 border-t border-gray-200">
                                @foreach($order->products as $product)
                                    <div class="w-full px-4 mb-6 md:mb-0">
                                        <div class="flex flex-wrap items-center -mx-4">
                                            <div class="w-full px-4 mb-3 md:w-1/3">
                                                <a href="{{ route('product.show', $product) }}">
                                                    <div class="flex items-center justify-center w-full h-24 bg-gray-100 "
                                                    >
                                                        <img class="object-contain h-full" src="{{ $product->image }}" alt="{{ $product->name }}">
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="w-full px-4 md:w-2/3">
                                                <a href="{{ route('product.show', $product) }}">
                                                    <h3 class="mb-2 text-xl font-bold font-heading text"
                                                    >{{ $product->name }}</h3>
                                                </a>
                                                <p class="text-gray-500">
                                                    {{ $product->pivot->price}}€
                                                </p>
                                                <p class="text-gray-500">
                                                    {{ __('Quantity') }} : {{ $product->pivot->quantity}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full px-4 mb-6 md:mb-0">
                                        <div class="flex flex-wrap items-center -mx-4">
                                            <div class="w-full px-4 mb-3 md:w-1/3">
                                                <a href="{{ route('product.show', $product) }}">
                                                    <div class="flex items-center justify-center w-full h-24 bg-gray-100 "
                                                    >
                                                        <img class="object-contain h-full" src="{{ $product->image }}" alt="{{ $product->name }}">
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="w-full px-4 md:w-2/3">
                                                <a href="{{ route('product.show', $product) }}">
                                                    <h3 class="mb-2 text-xl font-bold font-heading text"
                                                    >{{ $product->name }}</h3>
                                                </a>
                                                <p class="text-gray-500">
                                                    {{ $product->pivot->price}}€
                                                </p>
                                                <p class="text-gray-500">
                                                    {{ __('Quantity') }} : {{ $product->pivot->quantity}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>

                            <div class="flex flex-col items-center w-full px-5 py-4 mt-8 bg-gray-100 rounded md:mx-4 md:px-12 md:w-1/3">
                            
                                <div class="w-full">
                                    <a href="{{ route('order.show', $order) }}" class="flex items-center justify-center px-6 py-2 ml-auto text-white border-0 rounded bg-primary-500 focus:outline-none hover:bg-primary-600">
                                    {{ __('Details') }}</a>
                                </div>
                                @if($order->canBePaied())
                                <div class="w-full">
                                    <a href="{{ route('order.update', $order ) }}" class="inline-flex items-center justify-center w-full px-4 py-2 mt-4 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-200 focus:text-primary-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                                    {{ __('Pay Now') }}</a>
                                </div>
                                @endif
                                @if($order->canBeInvoiced())
                                <div class="w-full">
                                    <a href="{{ route('invoice.show', $order ) }}" class="inline-flex items-center justify-center w-full px-4 py-2 mt-4 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-200 focus:text-primary-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                                    {{ __('Invoice') }}
                                    <x-icons.document-download class="w-4 h-4"/>
                                    </a>
                                <div>
                                @endif
                                @if($order->canBeDeleted())
                                <div class="w-full">
                                    <livewire:order.destroy-form :order='$order'/>
                                </div>
                                @endif
                                
                            </div>
                            
                        </li>
                        @endforeach
                    </ol>
                    @else
                    <div>{{ __('general.no_results') }}</div>
                    @endif
                </div>

                <div class="m-2">
                    {{ $orders->links() }}
                </div>

            </div>
            
        </div>
    </div>
</x-app-layout>
