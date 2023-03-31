<x-app-layout>

    <x-slot name="seo">
        {!! seo($SEOData) !!}
    </x-slot>

    <x-slot name="header">
        <h1 class="mb-4 text-3xl font-bold">
            {{ __('Order').' #'.$order->number }} 
        </h1>
    </x-slot>

    <div class="mx-auto max-w-7xl">
        <div class="w-full px-6 pt-8 overflow-hidden lg:px-8">
            
            <div class="flex flex-col space-y-4 md:flex-row md:justify-between md:space-y-0">
                <div class="space-y-6">
                    <span @class([
                        'bg-white uppercase px-2 py-0.5 border',
                        'text-primary-500 border-primary-500' => $order->status->color() == 'primary',
                        'text-secondary-500 border-secondary-500' => $order->status->color() == 'secondary',
                        'text-warning-500 border-warning-500' => $order->status->color() == 'warning',
                        'text-danger-500 border-danger-500' => $order->status->color() == 'danger',
                        'text-success-500 border-success-500' => $order->status->color() == 'success',
                    ])>
                        {{ $order->status->label }}
                    </span>
                    @if($order->tracking_number)
                        <div class="">{{ __('Tracking Number') }}: {{ $order->tracking_number }}</div>
                    @elseif($order->avaiable_from)
                        <div class="">
                            {{ __('Advanced Sale Alert', 
                                [ 'date' => $order->created_at->format(config('custom.date_format')) ]
                            ) }}
                        </div>
                    @endif
                </div>

                <div class="flex justify-end">
                    <time class="block text-sm text-gray-500"
                        >{{ __('Created on') }} {{ $order->created_at->format(config('custom.datetime_format')) }}</time>
                </div>
            </div>

            <div class="w-full py-12 md:flex">
                <div class="w-full md:1/2 lg:w-2/3 md:pr-12">
                
                    <div class="relative pb-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold">
                                {{ __('Shipping Address') }}
                            </span>
                        </div>

                        <div class="flex flex-col justify-center py-2 text-sm text-left text-gray-500">
                            {!! $order->shippingAddress()->label !!}
                        </div>
                    </div>

                    <div class="relative py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold">
                                {{ __('Billing Address') }}
                            </span>
                        </div>

                        <div class="flex flex-col justify-center py-2 text-sm text-left text-gray-500"> 
                            <div>
                                {{ __('Fiscal Code')}}: {{ $order->fiscal_code ? $order->fiscal_code : '-' }}
                            </div>
                            <div>
                                {{ __('VAT')}}: {{ $order->vat ? $order->vat : '-' }}
                            </div>
                            <div class="mt-2">
                                {!! $order->billingAddress()->label !!}
                            </div>
                        </div>
                    </div>      
                </div>

                <div class="w-full md:1/2 lg:w-1/3">
                    <x-price-total
                        :heading="__('Payment Details')"
                        :subtotal="$order->subtotal"
                        :discounted-subtotal="$order->subtotal - $order->coupon_discount"
                        :original-total="$order->total + $order->coupon_discount"
                        :tax="$order->tax"
                        :total="$order->total"
                        :coupon="$order->coupon"
                        :shipping="$order->shippingPrice"
                        :shipping-price="$order->shipping_price"
                    >
                        <x-slot:actions>
                            <div class="space-y-2">
                                @if($order->canBePaid())
                                    <form action="{{ route('order.update', $order ) }}" method="GET">
                                        <x-button class="w-full py-4 text-base">{{ __('Pay Now') }}</x-button>
                                    </form>
                                @endif
                                @if($order->canBeInvoiced())
                                    <form class="w-full" action="{{ route('order.reorder', $order) }}" method="GET">
                                        <x-button type="submit" class="justify-center w-full"
                                        >{{ __('Reorder') }}</x-button>
                                    </form>
                                @endif
                                @if($order->canBeInvoiced())
                                    <form class="w-full" action="{{ route('invoice.show', $order ) }}" method="GET">
                                        <x-secondary-button ghost="true" type="submit" class="justify-center w-full"
                                        >
                                            {{ __('Invoice') }}
                                            <x-icons.document-download class="w-4 h-4"/>
                                        </x-secondary-button>
                                    </form>
                                @endif
                                @if($order->canBeDeleted())
                                    <div class="w-full">
                                        <livewire:order.destroy-form :order='$order'/>
                                    </div>
                                @endif
                            </div>
                        </x-slot>
                    </x-price-total>
                </div>
            </div>

        </div>
               
        <div class="w-full px-6 pb-8 overflow-hidden lg:px-8">     
            <div class="w-full py-6 mb-6 md:mb-0">
                <div class="hidden w-full mb-6 font-bold lg:flex">
                    <div class="w-full text-center lg:w-3/6">
                        <span class="">{{ __('Description') }}</span>
                    </div>
                    <div class="w-full text-left lg:w-1/6">
                        {{ __('Price') }}
                    </div>
                    <div class="w-full text-center lg:w-1/6">
                        {{ __('Quantity') }}
                    </div>
                    <div class="w-full text-right lg:w-1/6">
                        {{ __('Subtotal') }}
                    </div>
                </div>

                <div class="w-full py-6 border-gray-200 border-y">
                    @foreach( $order->products as $product )
                        <div class="relative flex flex-wrap items-center mb-12">

                            <div class="flex flex-col w-full mb-6 sm:flex-row md:w-6/12 lg:6/12 md:mb-0">
                                <div class="relative w-full md:w-1/2">
                                    <a href="{{ route('product.show', $product) }}">
                                        <div class="flex items-start justify-center w-full h-40 md:justify-start">
                                            <img class="object-contain object-top h-full aspect-video" src="{{ $product->image }}" alt="{{ $product->name }}">
                                        </div>
                                    </a>
                                </div>

                                <div class="w-full mt-6 md:w-1/2 sm:mt-0 sm:pl-6">
                                    <a href="{{ route('product.show', $product) }}">
                                        <div class="font-bold"
                                        >{{ $product->name }}</div>
                                        <div class="text-lg font-semibold text-gray-500 md:hidden">
                                            x{{$product->pivot->quantity}}
                                        </div>
                                    </a>
                                    @if(!$product->short_description)
                                        <div class="text-gray-500">
                                            {{ $product->short_description}}
                                        </div>
                                    @endif
                                    @if(count($product->attributeValues))
                                        <div class="my-6 text-gray-500">
                                            @foreach($product->attributeValues as $attributeValue)
                                                {{ $attributeValue->label}}
                                                @if(!$loop->last)
                                                    , 
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="items-center justify-center hidden w-full lg:px-4 lg:w-2/12 lg:flex">
                                <div class="w-full overflow-hidden text-base font-semibold text-gray-500 overflow-ellipsis">
                                    {{$product->pivot->price}}€
                                </div>
                            </div>

                            <div class="order-2 hidden w-full mt-6 text-center md:block md:order-1 md:mt-0 md:w-3/12 lg:w-2/12">
                                <div class="text-lg font-semibold text-gray-500">
                                    x{{$product->pivot->quantity}}
                                </div>
                            </div>

                            <div class="order-1 w-full px-4 mt-6 text-center md:px-0 md:pl-4 md:order-2 md:text-right md:mt-0 md:w-3/12 lg:w-2/12">
                                <div class="text-lg font-black text-gray-900">
                                    {{ $product->applyTax( $product->pricePerQuantity($product->pivot->quantity, $product->pivot->price) ) }}€
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
