@props([
    'disabled' => false,
    'outline' => false
])

<button {{ $attributes->merge(['class' => 'btn btn-error' . ($outline ? ' btn-outline' : '') ]) }}
    {{ $disabled ? 'disabled' : '' }}
>{{ $slot }}</button>