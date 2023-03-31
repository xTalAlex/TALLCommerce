<button {{ $attributes->merge([
        'type' => 'button', 
        'class' => '
            inline-flex items-center justify-center px-4 py-2 bg-danger-500 border border-transparent 
            font-semibold text-xs text-white tracking-widest hover:bg-danger-600 
            focus:outline-none focus:border-danger-700 focus:ring focus:ring-danger-200 
            active:bg-danger-600 disabled:opacity-25 transition
        ']) }}>
    {{ $slot }}
</button>