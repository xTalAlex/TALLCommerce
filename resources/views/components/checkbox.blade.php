@props([
    'label' => null,
    'id' => null,
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

<div {!! $attributes->merge(['class' => 'form-control']) !!}>
    <label class="label" {{ $id ? 'for="'.$id.'"' : '' }}>
        <input type="checkbox" class="checkbox {{ $size }} {{ $id && $errors->has($id) ? 'checkbox-error' : ''}}"
            {{ $checked ? 'checked' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {!! $id ? 'id="'.$id.'"' : '' !!}
        />
        <span class="ml-2 label-text">{{ $label ?? $slot }}</span>
    </label>
</div>