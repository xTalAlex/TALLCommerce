<button {{ $attributes->merge(['type' => 'submit', 'class' => '
            inline-flex items-center justify-center px-4 py-2 bg-neutral-500 border border-transparent font-semibold text-xs 
            text-white tracking-widest hover:bg-neutral-600 active:bg-neutral-900 focus:outline-none 
            focus:border-neutral-900 focus:ring focus:ring-neutral-400 disabled:opacity-25 transition
        ']) }}>
    {{ $slot }}
</button>