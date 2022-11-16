<div
    x-data = "{
        value: @entangle($attributes->wire('model'))
    }"
    x-init="
        document.addEventListener('trix-initialize', () => {
            $refs.trix.editor.loadHTML(value); 
            $watch('value', value => document.activeElement !== $refs.trix && $refs.trix.editor.loadHTML(value))
        });
    "
    x-on:trix-change="value = $event.target.value"
    wire:ignore
>
    <input id="x" type="hidden">
    <trix-editor x-ref="trix" input="x"></trix-editor>
</div>