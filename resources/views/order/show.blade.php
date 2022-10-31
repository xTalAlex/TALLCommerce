<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Order').' #'.$order->number}} 
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="py-10 overflow-hidden bg-white shadow-xl sm:rounded-lg">
                
                <div class="flex flex-col px-8 md:px-12 md:flex-row md:justify-between">
                    <div>
                        <h3 class="flex items-center mt-4 mb-1 text-lg font-semibold text-gray-900 dark:text-white"
                        >{{ __('Order') }}: #{{ $order->number }} 
                        </h3>
                        <div class="my-4">
                            <span class="bg-primary-100  uppercase text-primary-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-primary-200 dark:text-primary-800"
                            >{{ $order->status->label }}</span>
                            @if($order->tracking_number)
                                <p>{{ __('Tracking Number') }}: {{ $order->tracking_number }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 mr-4 md:ml-0">
                        <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500"
                            >{{ __('Created on') }} {{ $order->created_at->format(config('custom.datetime_format')) }}</time>
                    </div>
                </div>

                <div class="flex flex-col w-full md:flex-row">
                    
                    <div class="flex flex-wrap items-start w-full md:w-2/3">

                        <div class="w-full py-6 mb-6 md:px-12 md:mb-0">
                            <div class="hidden w-full lg:flex">
                                <div class="w-full lg:w-3/6">
                                    <h4 class="mb-6 font-bold text-gray-500 font-heading">
                                        {{__('Description')}}
                                    </h4>
                                </div>
                                <div class="w-full lg:w-1/6">
                                    <h4 class="mb-6 font-bold text-gray-500 font-heading">
                                        {{__('Price')}}
                                    </h4>
                                </div>
                                <div class="w-full text-center lg:w-1/6">
                                    <h4 class="mb-6 font-bold text-gray-500 font-heading">
                                        {{__('Quantity')}}
                                    </h4>
                                </div>
                                <div class="w-full text-right lg:w-1/6">
                                    <h4 class="mb-6 font-bold text-gray-500 font-heading">
                                        {{__('Subtotal')}}
                                    </h4>
                                </div>
                            </div>
                        
                            <div class="py-6 border-b border-gray-200 lg:border-t">
                                @foreach($order->products as $product)
                                <div class="relative flex flex-wrap items-center mb-6 md:mb-3">
                                    <div class="w-full px-4 mb-6 md:w-4/6 lg:w-6/12 md:mb-0">
                                        <div class="flex flex-wrap items-center">
                                            <div class="w-full px-4 mb-3 md:w-1/3">
                                                <a href="{{ route('product.show', $product) }}">
                                                <div class="flex items-center justify-center w-full h-32 bg-gray-100 md:w-24"
                                                >
                                                    <img class="object-contain h-full" src="{{ $product->image }}" alt="{{ $product->name }}">
                                                </div>
                                                </a>
                                            </div>
                                            <div class="w-full px-12 md:w-2/3">
                                                <a href="{{ route('product.show', $product) }}">
                                                <h3 class="mb-2 text-xl font-bold font-heading text"
                                                >{{ $product->name }}</h3>
                                                </a>
                                                <p class="text-gray-500">
                                                    @foreach($product->attributeValues as $attributeValue)
                                                        {{ $attributeValue->value}}
                                                        @if(!$loop->last)
                                                            , 
                                                        @endif
                                                    @endforeach
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hidden px-4 lg:block lg:w-2/12">
                                        <p class="text-lg font-bold text-primary-500 font-heading">{{ $product->applyTax($product->pivot->price) }}€</p>
                                        {{-- <span class="text-xs text-gray-500 line-through">$33.69</span> --}}
                                    </div>
                                    <div class="w-auto px-8 text-center md:px-auto md:w-1/6 lg:w-2/12"
                                    >
                                        <p class="text-lg font-bold text-primary-500 font-heading">
                                            x{{ $product->pivot->quantity }}
                                        </p>
                                    </div>
                                    <div class="w-auto px-4 text-right md:w-1/6 lg:w-2/12">
                                        <p class="text-lg font-bold text-primary-500 font-heading">
                                            {{ $product->pricePerQuantity($product->pivot->quantity,$product->applyTax($product->pivot->price)) }}€
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="w-full px-8 mb-6 md:px-12">
                            <div class="w-full">
                                <x-price-total
                                    :heading="__('Payment Details')"
                                    :subtotal="$order->subtotal"
                                    :discounted-subtotal="number_format($order->subtotal - $order->coupon_discount,2)"
                                    :tax="$order->tax"
                                    :total="$order->total"
                                    :coupon="$order->coupon"
                                    :shipping="$order->shippingPrice"
                                    :shipping-price="$order->shipping_price"
                                >
                                </x-price-total>
                            </div>
                        </div> 
                        
                    </div>

                    <div class="w-full px-8 md:w-1/3 md:px-4">

                        <div class="w-full mb-4">
                            <div><h4 class="mb-2 font-bold">{{ __('Shipping Address') }}</h4><p>{!! $order->shippingAddress()->label !!}</p></div>
                        </div>

                        <div class="w-full mb-4">
                            <div><h4 class="mb-2 font-bold">{{ __('Billing Address') }}</h4><p>{!! $order->billingAddress()->label !!}</p></div>

                            <div class="mt-2">{{ __('Payment Method') }}: <img class="inline-block h-5" src="/img/logos/{{$order->payment_gateway}}.svg" title="{{$order->payment_gateway}}" alt="{{$order->payment_gateway}} logo" {{ $order->payment_gateway }}/></div>
                        </div>

                        <div class="w-full mb-4 md:text-center">
                            @if($order->canBeDeleted())
                            <div class="w-full">
                                <a href="{{ route('order.update', $order ) }}" class="inline-flex items-center justify-center w-full px-4 py-2 mt-4 text-sm font-medium text-white border rounded-lg border-primary-200 bg-primary-500 hover:bg-primary-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-primary-200 focus:text-primary-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
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

                    </div>
                    
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
