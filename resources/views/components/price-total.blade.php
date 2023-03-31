@props([
    'theme' => 'secondary'
])

<div @class([
    'px-6 py-12 space-y-4 text-white',
    'bg-secondary-500' => $theme == 'secondary',
    'bg-primary-500' => $theme == 'primary'
])>

    <div class="mb-2 text-3xl font-bold">{{ isset($heading) ? $heading :  __('Summary') }}</div>

    @if($products)
    <div class="flex flex-col pb-6 border-b border-white">
        @foreach($products as $rowId=>$product)
            <div class="flex items-center justify-between space-y">
                <span class="text-sm">{{ $product['name'] }} x{{ $product['qty'] }}</span>
                <span class="text-lg font-bold">{{ $product['pricePerQuantity'] }}€</span>
            </div>
        @endforeach
    </div>
    @endif

    <div class="flex items-center justify-between">
        <div class="">{{ __('Total') }}<div class="inline ml-1 text-xs">({{ __('Without Tax') }})</div></div>
        <span class="text-xl font-bold">{{ number_format( $subtotal ,2) }}€</span>
    </div>

    @if($coupon && $coupon->applyBeforeTax())
    <div class="flex items-center justify-between">
        <span class="">{{ !$coupon->is_fixed_amount ? $coupon->label : '' }} {{ $coupon->code }}</span>
        <span class="text-xl font-bold">-{{ number_format( $discount() , 2) }}€</span>
    </div>
        @if($discountedSubtotal)
        <div class="flex items-center justify-between">
            <span class=""></span>
            <span class="text-xl font-bold">{{ number_format($discountedSubtotal, 2) }}€</span>
        </div>
        @endif
    @endif
    
    @if($tax)
    <div class="flex items-center justify-between">
        <span class="text-base">
            {{ __('Tax') }}
        </span>
        <span class="text-base font-bold">{{ number_format($tax, 2) }}€</span>
    </div>
    @endif

    @if($coupon && !$coupon->applyBeforeTax())
    <div class="flex items-center justify-between">
        <span class="">{{ !$coupon->is_fixed_amount ? $coupon->label : '' }} {{ $coupon->code }}</span>
        <span class="text-xl font-bold">-{{ number_format( $discount() , 2) }}€</span>
    </div>
        @if($discountedSubtotal)
        <div class="flex items-center justify-between">
            <span class=""></span>
            <span class="text-xl font-bold">{{ number_format($discountedSubtotal, 2) }}€</span>
        </div>
        @endif
    @endif

    @if(isset($shipping))
    <div class="flex items-center justify-between">
        <span class="">
            {{ __('Shipping') }} : {{ $shipping->name }}
        </span>
        <span class="text-xl font-bold">{{ number_format($shippingPrice, 2) }}€</span>
    </div>
    @endif

    <div class="flex items-center justify-between pt-8 pb-6 border-t border-white">
        <div class="text-xl font-bold">{{ __('Total') }}<div class="inline ml-1 text-xs">({{ __('With Tax') }})</div></div>
        <span class="text-xl font-bold">{{ number_format( $total, 2) }}€</span>
    </div>

    @if(isset($actions))
        <div class="pt-6">
            {{ $actions }}
        </div>
    @endif

</div>