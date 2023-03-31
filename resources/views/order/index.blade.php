<x-app-layout>

    <x-slot name="seo">
        {!! seo($SEOData) !!}
    </x-slot>

    <x-slot name="header">
        <h1 class="mb-4 text-3xl font-bold">
            {{ __('My Orders') }}
        </h1>
    </x-slot>
    
    <div class="px-6 py-8 mx-auto mb-12 max-w-7xl lg:px-8">
        <div>
            @if($orders->count())
            <ol class="relative w-full mx-auto space-y-24 border-l border-gray-200">
                @foreach($orders as $order)
                <li class="mb-12 ml-6 md:flex">
                    
                    <div class="flex-none w-full md:w-2/3 md:pr-6">

                        <span class="absolute flex items-center justify-center w-6 h-6 text-white rounded-full ring-8 ring-white bg-primary-500 -left-3">
                            <x-icons.cube class="w-4 h-4"/>    
                        </span>

                        <div class="flex items-center mb-2 font-semibold"
                        >
                            <a class="text-lg" href="{{ route('order.show', $order) }}">
                                #{{ $order->number }} 
                            </a>
                            <span @class([
                                'bg-white uppercase px-2 py-0.5 border text-xs mx-2',
                                'text-primary-500 border-primary-500' => $order->status->color() == 'primary',
                                'text-secondary-500 border-secondary-500' => $order->status->color() == 'secondary',
                                'text-warning-500 border-warning-500' => $order->status->color() == 'warning',
                                'text-danger-500 border-danger-500' => $order->status->color() == 'danger',
                                'text-success-500 border-success-500' => $order->status->color() == 'success',
                            ])>
                                {{ $order->status->label }}
                            </span>
                        </div>

                        <time class="block mb-4 text-xs text-gray-500">
                            {{ __('Created on') }} {{ $order->created_at->format(config('custom.date_format')) }}
                        </time>

                        @if($order->avaiable_from && $order->isActive())
                        <div class="my-2">
                            <p class="text-sm">
                                {{ __('Advanced Sale Alert', [ 'date' => $order->avaiable_from->format(config('custom.date_format')) ]) }}
                            </p>
                        </div>
                        @endif

                        <div class="mb-4 text-xl font-bold"
                        >{{ __('Total') }}: {{ $order->total }}€</div>

                        <p class="mb-4 text-sm text-gray-500"
                        >{!! $order->shippingAddress()->label !!}</p>
                    
                        <div class="grid w-full gap-6 pt-4 border-t border-gray-200 sm:grid-cols-2">
                        @foreach($order->products->take(3) as $product)
                            <div class="flex w-full flex-nowrap">
                                <div class="">
                                    <a href="{{ route('product.show', $product) }}">
                                        <div class="flex items-start justify-start w-full h-20 md:justify-start">
                                            <img class="object-contain object-top h-full" src="{{ $product->image }}" alt="{{ $product->name }}">
                                        </div>
                                    </a>
                                </div>
                                <div class="flex flex-col justify-between mt-2 ml-6 space-y-2 md:mt-0">
                                    <div class="font-semibold">
                                        <a href="{{ route('product.show', $product) }}">
                                            {{ $product->name }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ __('Quantity') }}:{{$product->pivot->quantity}}</div>
                                </div>
                            </div>
                        @endforeach
                        @if($order->products->count() > 3)
                            <div class="sm:col-span-2">
                                <span class="select-none">...</span>
                            </div>
                        @endif
                        </div>
                    </div>

                    <div class="flex flex-col items-center w-full px-6 py-6 mt-12 space-y-2 bg-gray-50 md:w-1/3 md:mt-0">
                        @if($order->canBePaid())
                            <form class="w-full" action="{{ route('order.update', $order) }}" method="GET">
                                <x-button class="w-full" type="submit">{{ __('Complete Payment') }}</x-button>
                            </form>
                        @endif
                        @if($order->canBeInvoiced())
                            <form class="w-full" action="{{ route('order.reorder', $order) }}" method="GET">
                                <x-button type="submit" class="justify-center w-full"
                                >{{ __('Reorder') }}</x-button>
                            </form>
                        @endif
                        <form class="w-full" action="{{ route('order.show', $order) }}" method="GET">
                            <x-secondary-button type="submit" class="justify-center w-full"
                            s>{{ __('Details') }}</x-secondary-button>
                        </form>
                        @if($order->canBeEdited())
                            <form class="w-full" action="{{ route('order.update', $order ) }}" method="GET">
                                <x-secondary-button ghost="true" type="submit" class="justify-center w-full"
                                >{{__('Edit') }}</x-secondary-button>
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
                                <livewire:order.destroy-form class="" :order='$order'/>
                            </div>
                        @endif
                    </div>
                    
                </li>
                @endforeach
            </ol>
            @else
            <div class="grid place-items-center">
                <div class="py-12">
                    <p>
                        {{ __("Haven't found anything, yet?") }}
                    </p>
                    <div class="flex flex-col w-full mt-6 space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row">
                        <form class="w-full sm:w-1/2" method="GET" action="{{ route('product.index') }}">
                            <x-button class="w-full">{{ __('To Shop') }}</x-button>
                        </form>
                        <form class="w-full sm:w-1/2" method="GET" action="{{  route('product.show', $randomProduct) }}">
                            <x-secondary-button class="w-full">{{ __('Random Product') }}</x-secondary-button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="flex justify-center">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
