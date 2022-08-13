@props([
    'name' => null,
    'label' => null,
    'placeholder' => " ",
    'disabled' => false,
])

<input {!! $attributes->merge(['class' => 'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer']) !!}
    {{ $disabled ? 'disabled' : '' }}
    name="{{ $name }}"
    id="{{ $name }}"
    placeholder="{{ $placeholder }}"/>

<label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
    for="{{ $name }}" 
>{{ $label }}</label>