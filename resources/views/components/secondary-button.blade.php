@props([
    'disabled' => false,
    'ghost' => false
])

@if($ghost)
<button {{ $attributes->merge(['type' => 'submit', 'class' => '
            inline-flex items-center justify-center px-4 py-2 bg-white border border-secondary-500 font-semibold text-xs 
            text-secondary-500 tracking-widest hover:bg-secondary-600 hover:text-white active:bg-secondary-400 focus:outline-none 
            focus:border-secondary-500 focus:ring focus:ring-secondary-400 disabled:opacity-25 transition active:text-white
        ']) }}
    {{ $disabled ? 'disabled' : '' }}    
>
    {{ $slot }}
</button>
@else
<button {{ $attributes->merge(['type' => 'submit', 'class' => '
            inline-flex items-center justify-center px-4 py-2 bg-secondary-500 border border-transparent font-semibold text-xs 
            text-white tracking-widest hover:bg-secondary-500 active:bg-secondary-600 focus:outline-none 
            focus:border-secondary-500 focus:ring focus:ring-secondary-400 disabled:opacity-25 transition
        ']) }}
    {{ $disabled ? 'disabled' : '' }}
>
    {{ $slot }}
</button>
@endif
