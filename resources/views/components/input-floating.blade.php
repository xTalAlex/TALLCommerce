@props([
    'label' => null,
    'name' => null,
    'disabled' => false,
    'placeholder' => " ",
])

<div {!! $attributes->only('class')->merge(['class' => 'relative z-0 w-full group border-0 px-0 my-2' ]) !!}>
    <input class="block py-2.5 px-2 w-full text-sm bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-primary-500 peer" 
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->except('class') }} 
        name="{{ $name }}"
        id="{{ $name }}"
        placeholder="{{ $placeholder }}"
    />
    @if($label)
    <label class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
        for="{{ $name }}" 
    >{{ $label }}</label>
    @endif
</div>