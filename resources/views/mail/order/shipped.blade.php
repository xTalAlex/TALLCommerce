<x-mail::message>
{{__('Dear :name',['name' => '**'.($order->user?->name ?? $order->shipping_address_full_name).'**' ])}}

{{ __('We inform you that your order :number has been shipped today.', ['number' => '#'.$order->number]) }}

{{ __('You can check your order status by clicking the button below or visiting "My Orders" page.') }}

<x-mail::button url="{{ route('order.show', $order) }}">
{{ __('View Order') }}
</x-mail::button>

{{ __('Regards') }},<br>
**{{ config('app.name') }}**
</x-mail::message>
