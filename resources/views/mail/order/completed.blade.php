<x-mail::message>
{{__('Dear :name',['name' => '**'.($order->user?->name ?? $order->shipping_address_full_name).'**' ])}}

{{ __('Your order :number has been delivered. Thank you for choosing us.', ['number' => '#'.$order->number]) }}

{{ __('You can buy the same products again by clicking on the "Reorder" button in the order page.') }}<br>
{{ __('You can also let us know about your experience with us by leaving a review for the products you have bought.') }}

<x-mail::button url="{{ route('order.show', $order) }}">
{{ __('View Order') }}
</x-mail::button>

<x-mail::subcopy>
{{ __('Visit "My Orders" page or click the following link to see the invoice') }}:
<span class="break-all">[{{ route('invoice.show', $order) }}]({{ route('invoice.show', $order) }})</span>
</x-mail::subcopy>

{{ __('Regards') }},<br>
**{{ config('app.name') }}**
</x-mail::message>