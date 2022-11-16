<div class="inline"
    x-data="{
        value: @entangle($attributes->wire('model')),
        instance: undefined,
        format: '{{ $attributes->get('format') ?? config('custom.date_format') }}',

    }"
    x-init="
        instance = flatpickr($refs.datepicker, {
            altInput: true,
            altFormat: format,
            dateFormat: 'Y-m-d',
        });
    "
    x-on:change="value = $event.target.value"
    wire:ignore
>
    <input type="text" 
        {{ $attributes->whereDoesntStartWith('wire:model') }}  
        x-ref="datepicker" 
        x-bind:value="value" 
        placeholder="{{ $attributes->get('placeholder') ?? '00/00/0000' }}"
    >
    <p x-text="value"></p>
</div>
