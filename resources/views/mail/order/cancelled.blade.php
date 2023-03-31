<x-mail::message>
{{__('Dear :name',['name' => '**'.($order->user?->name ?? $order->shipping_address_full_name).'**' ])}}

{{ __('Your order :number has been cancelled.', ['number' => '#'.$order->number]) }}
<br>
{{ __('If you have any question or issue, feel free to contact our support team.') }}

<x-mail::button url="{{ route('order.show', $order) }}">
{{ __('View Order') }}
</x-mail::button>

{{ __('Regards') }},<br>
**{{ config('app.name') }}**
</x-mail::message>
