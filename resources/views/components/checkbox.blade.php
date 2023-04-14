@props([
    'checked' => false,
    'disabled' => false,
    'size' => 'md',
])

@php
$id = $id ?? md5($attributes->wire('model'));

$size = [
    'xs' => 'checkbox-xs',
    'sm' => 'checkbox-sm',
    'md' => 'checkbox-md',
    'lg' => 'checkbox-lg',
][$size ?? 'md'];
@endphp

<input type="checkbox"  {!! $attributes->merge(['class' => 'checkbox ' . $size]) !!}
    {{ $checked ? 'checked' : '' }}
    {{ $disabled ? 'disabled' : '' }}
/>