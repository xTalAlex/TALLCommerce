@props(['submit'])

<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6']) }}>
    <x-jet-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-jet-section-title>

    <div class="card md:col-span-2">
        <form class="card-body" wire:submit.prevent="{{ $submit }}">
            <div class="px-4 py-5 bg-transparent border-l border-opacity-50 sm:p-6">
                <div class="grid grid-cols-6 gap-6">
                    {{ $form }}
                </div>
            </div>
            @if (isset($actions))
            <div class="justify-end px-4 border-l border-opacity-50 sm:px-6 card-actions">
                {{ $actions }}
            </div>
            @endif
        </form>
    </div>
</div>
