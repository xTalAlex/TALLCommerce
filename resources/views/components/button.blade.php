@props([
    'disabled' => false,
    'outline' => false
])

<button {{ $attributes->merge(['class' => 'btn' . ($outline ? ' btn-outline' : '') ]) }}
    {{ $disabled ? 'disabled' : '' }}
>{{ $slot }}</button>