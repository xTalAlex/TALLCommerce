<x-mail::message>

{!! $message !!}

<x-mail::button url="{{ route('home') }}">
{{ __('Go to website') }}
</x-mail::button>

{{ __('Regards') }},<br>
**{{ config('app.name') }}**
</x-mail::message>