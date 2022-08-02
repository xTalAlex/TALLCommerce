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
                    <ol class="relative border-l border-gray-200 dark:border-gray-700 w-full mx-auto">
                        @foreach($orders as $order)
                        <li class="mb-10 ml-6">
                            
                            <span class="absolute flex items-center justify-center w-6 h-6 rounded-full text-primary-600 dark:text-primary-400 bg-primary-200 -left-3 ring-8 ring-white dark:ring-gray-900 dark:bg-primary-900">
                                <x-icons.bag class="h-3 w-3"/>    
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
                            
                            <div class="border-t border-gray-200">
                            @foreach($order->products as $product)
                                <div class="w-1/2 px-4 mb-6 md:mb-0">
                                    <div class="flex flex-wrap items-center -mx-4">
                                        <div class="w-full px-4 mb-3 md:w-1/3">
                                            <a href="{{ route('product.show', $product) }}">
                                                <div class="flex items-center justify-center w-full h-32 bg-gray-100 md:w-24"
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
                                <div class="w-1/2 px-4 mb-6 md:mb-0">
                                    <div class="flex flex-wrap items-center -mx-4">
                                        <div class="w-full px-4 mb-3 md:w-1/3">
                                            <a href="{{ route('product.show', $product) }}">
                                            <div class="flex items-center justify-center w-full h-32 bg-gray-100 md:w-24"
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
                        
                            @if($order->canBeDeleted())
                                <a href="{{ route('order.update', $order ) }}" class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-200 focus:text-primary-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"></path></svg>
                                {{ __('Pay Now') }}</a>
                            @endif
                            @if($order->canBeDeleted())
                                <livewire:order.destroy-form :order='$order'/>
                            @endif
                            @if($order->canBeInvoiced())
                                <a href="{{ route('invoice.show', $order ) }}" class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-200 focus:text-primary-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"></path></svg>
                                {{ __('Invoice') }}</a>
                            @endif
                                <a href="{{ route('order.show', $order) }}" class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-200 focus:text-primary-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                                {{ __('Details') }}</a>
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
