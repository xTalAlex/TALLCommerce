@props([
    'label' => null,
    'name' => null,
    'disabled' => false,
    'placeholder' => " ",
    'resize' => null,
])
{{-- border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 --}}
<div {!! $attributes->only('class')->merge(['class' => 'relative z-0 w-full mb-6 group' ]) !!}>  
    <textarea @class([
            'block py-2.5 w-full text-sm bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-primary-500 peer',
            'resize-none' => $resize == 'none'
        ])
        id="{{ $name }}" name="{{ $name }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->except('class') }} 
        placeholder="{{ $placeholder }}"
    ></textarea>
    <label class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
        for="{{ $name }}"
    >{{ $label }}</label>
</div>
