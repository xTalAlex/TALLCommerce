@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-xs text-danger-500']) }}>{{ $message }}</p>
@enderror
