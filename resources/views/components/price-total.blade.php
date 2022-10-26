<div class="px-6 py-6 md:px-4 lg:px-6 bg-primary-300 md:p-12">

    <h2 class="mb-6 text-4xl font-bold text-white font-heading">{{ isset($heading) ? $heading :  __('Total') }}</h2>

    <div class="flex items-center justify-between pb-5">
        <span class="text-primary-50">{{ __('Subtotal') }}</span>
        <span class="text-xl font-bold text-white font-heading">{{ $subtotal }}€</span>
    </div>
    @if($coupon)
    <div class="flex items-center justify-between pb-5">
        <span class="text-primary-50">{{ $coupon->label }} {{ $coupon->code ?? '' }}</span>
        <span class="text-xl font-bold text-white font-heading">-{{ number_format( $subtotal - $discountedSubtotal , 2) }}€</span>
    </div>
    <div class="flex items-center justify-between pb-5">
        <span class="text-primary-50"></span>
        <span class="text-xl font-bold text-white font-heading">{{ $discountedSubtotal }}€</span>
    </div>
    @endif
    
    @if($tax)
    <div class="flex items-center justify-between pb-5">
        <span class="text-primary-50">
            {{ __('Tax') }}
        </span>
        <span class="text-xl font-bold text-white font-heading">{{ $tax }}€</span>
    </div>
    @endif

    @if(isset($shipping))
    <div class="flex items-center justify-between pb-5">
        <span class="text-primary-50">
            {{ __('Shipping') }} : {{ $shipping->name }}
        </span>
        <span class="text-xl font-bold text-white font-heading">{{ $shippingPrice }}€</span>
    </div>
    @endif

    {{-- <h4 class="mb-2 text-xl font-bold text-white font-heading">Shipping</h4>
    <div class="flex items-center justify-between mb-2">
        <span class="text-primary-50">Next day</span>
        <span class="text-xl font-bold text-white font-heading">$11.00</span>
    </div>
    <div class="flex items-center justify-between mb-10">
        <span class="text-primary-50">Shipping to United States</span>
        <span class="text-xl font-bold text-white font-heading">-</span>
    </div> --}}

    <div class="flex items-center justify-between pt-8 mb-10 border-t border-primary-100">
        <span class="text-xl font-bold text-white font-heading">{{ __('Total') }}</span>
        <span class="text-xl font-bold text-white font-heading">{{ $total }}€</span>
    </div>

    @if(isset($actions))
        {{ $actions }}
    @endif

</div>