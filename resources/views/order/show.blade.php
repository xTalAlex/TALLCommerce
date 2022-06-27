<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Order').' #'.$order->id}} 
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">

                <h3 class="flex items-center mt-4 mb-1 ml-4 text-lg font-semibold text-gray-900 dark:text-white"
                >{{ __('Order ID') }}: #{{ $order->id }} 
                </h3>
                
                <div class="flex items-center justify-between ml-4">
                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500"
                    >{{ __('Created on') }} {{ $order->created_at }}</time>

                    @if($order->canBeInvoiced())
                    <a href="#" class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-200 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"></path></svg>
                    {{ __('Invoice') }}</a>
                    @endif
                    @if($order->canBeDeleted())
                    <a href="{{ route('order.update', $order ) }}" class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-200 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"></path></svg>
                    {{ __('Pay Now') }}</a>
                    @endif
                    @if($order->canBeDeleted())
                    <livewire:order.destroy-form :order='$order'/>
                    @endif
                </div>
                
                <div class="my-4">
                    <span class="bg-blue-100  uppercase text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ml-3"
                    >{{ $order->status->name }}</span>
                    @if($order->tracking_number)
                        <p>{{ __('Tracking Number') }}: {{ $order->tracking_number }}</p>
                    @endif
                </div>
                
                <div class="flex justify-between w-full my-6">
                    <div class="flex items-center justify-center w-full space-x-10 md:w-2/3">
                        <div><h4 class="mb-2 font-bold">{{ __('Shipping Address') }}</h4><p>{!! $order->shippingAddress()->label !!}</p></div>
                        <div><h4 class="mb-2 font-bold">{{ __('Billing Address') }}</h4><p>{!! $order->billingAddress()->label !!}</p></div>
                    </div>
                    <div class="w-full md:w-1/3">
                        <h4 class="mb-2 font-bold">{{ __('Payment Details') }}</h4>
                        <p>{{ __('Gateway') }}: {{ $order->payment_gateway }}</p>
                        <p>{{ __('Subtotal') }}: {{ $order->subtotal }}€</p>
                        <p>{{ __('Tax') }}: {{ $order->tax }}€</p>
                        @if($order->shipping_price)
                        <p>{{ __('Shipping') }}: {{$order->shippingPrice->name ?? null}} {{ $order->shipping_price }}€</p>
                        @endif
                        <p>{{ __('Total') }}: {{ $order->total }}€</p>
                        
                        @if($order->coupon)
                            <hr>
                            <p>{{ __('Coupon') }}: {{ $order->coupon->code}} </p>
                            <p>{{ __('Discount') }}: {{ $order->coupon_discount }}€</p>
                        @endif
                    </div>
                </div>
                
                <hr>
                
                <div class="flex mt-6 ml-10">
                    @foreach($order->products as $product)
                    <div class="w-full mb-4 ml-2 text-base font-normal text-gray-500 md:w-1/3 dark:text-gray-400">
                        <a href="{{ route('product.show', $product) }}">
                            {{ $product->name }}
                            <img class="object-cover w-32 h-32" src="{{$product->image}}"/>
                        </a>
                        {{ $product->pivot->quantity }}
                        x {{ $product->pivot->price }}€
                        | {{ $product->pricePerQuantity($product->pivot->quantity,$product->pivot->price) }}€
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
