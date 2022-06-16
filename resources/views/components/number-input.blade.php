@props(['disabled' => false])

<div class="flex items-center justify-center"
    x-data="{
        inc(){
            $refs.input.value++;
            $dispatch('input',$refs.input.value);
        },
        dec(){
            $refs.input.value--;
            $dispatch('input',$refs.input.value);
        },
    }"

    {{ $attributes->whereStartsWith('wire:')}}
>
    <span class="w-4 h-full mr-2 text-center cursor-pointer" @click="dec()" >-</span>
        <input type="number" x-ref="input"
            {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-16 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm']) !!}}
        /> 
    <span class="w-4 h-full ml-2 text-center cursor-pointer" @click="inc()">+</span>
</div>