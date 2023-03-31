<x-mail::message>
{{__('Dear :name',['name' => '**'.($order->user?->name ?? $order->shipping_address_full_name).'**' ])}}

{{ __('We couldn\'t process payment for your order :number.', ['number' => '#'.$order->number]) }}
<br>
{{ __('Please, try it again as soon as possibile so your order won\'t be cancelled.') }}

<x-mail::button url="{{ route('order.show', $order) }}">
{{ __('View Order') }}
</x-mail::button>

{{ __('Regards') }},<br>
**{{ config('app.name') }}**
</x-mail::message>
