@props(['id' => null, 'maxWidth' => null])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="text-left">
        <div class="text-lg font-bold">
            {{ $title }}
        </div>

        <div class="mt-4">
            {{ $content }}
        </div>
    </div>

    <x-slot name="footer">
        {{ $footer }}
    </x-slot>
</x-jet-modal>
