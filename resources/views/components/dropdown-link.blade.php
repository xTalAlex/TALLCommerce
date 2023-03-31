@props([
    'accent' => false,
])

<a {{ $attributes->merge(['class' => '
        block px-4 py-2 text-sm leading-5 
        hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition'
        . ' ' . ( $accent ? 'text-accent-500' : 'text-gray-900')    
    ]) }}>{{ $slot }}</a>