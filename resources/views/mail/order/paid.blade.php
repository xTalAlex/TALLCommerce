<x-mail::message>
{{__('Dear :name',['name' => '**'.($order->user?->name ?? $order->shipping_address_full_name).'**' ])}}

{{ __('Thank you for your order. We will notify you as soon as your products are shipped.') }}
<br>
{{ __('You can check your order status by clicking the button below or visiting "My Orders" page.') }}

<x-mail::button url="{{ route('order.show', $order) }}">
{{ __('View Order') }}
</x-mail::button>

<x-mail::panel>
{{ __('Order').' #'.$order->number }} 

<x-mail::table>
| {{__('Product')}} | {{__('Quantity')}} | {{ __('Price') }} |
| ------------- |:-------------:| --------:|
@foreach($order->products as $product)
| {{ $product->name}} | x{{ $product->pivot->quantity }} | {{ number_format($product->pivot->price * $product->pivot->quantity,2) }}€ |
@endforeach
</x-mail::table>

@if($order->coupon_discount > 0)
{{ __('Discount') }}: -{{ number_format($order->coupon_discount,2)}}€ 
<br>
@endif
{{ __('Tax') }}: {{ number_format($order->tax,2)}}€ 
<br>
{{ __('Shipping') }} {{ $order->shippingPrice->name }}: {{ number_format($order->shipping_price,2)}}€
<br>
{{ __('Total') }}: {{ number_format($order->total,2)}}€ 
</x-mail::panel>

{{ __('Regards') }},<br>
**{{ config('app.name') }}**
</x-mail::message>
