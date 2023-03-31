@props([
    'floating' => false,
    'label' => null,
    'name' => null,
    'disabled' => false,
])

@if($floating)
    <div {!! $attributes->only('class')->merge(['class' => 'relative z-0 w-full group border-0 px-0 my-2' ]) !!}>
        <select class="block py-2.5 px-2 w-full text-sm bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-primary-500 peer" 
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->except('class') }} 
            name="{{ $name }}"
            id="{{ $name }}"
        >
            <option value=" ">-</option>
            @foreach($provinces as $province)
                <option value="{{$province->code}}">{{ $province->name }}</option>
            @endforeach
        </select>
        @if($label)
        <label class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-500 peer-focus:scale-75 peer-focus:-translate-y-6"
            for="{{ $name }}" 
        >{{ $label }}</label>
        @endif
    </div>
@else
    <select {{ $disabled ? 'disabled' : '' }} 
        {!! $attributes->merge([
            'class' => 'w-full border-gray-300 focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 shadow-sm'
        ]) !!}
    >
        <option value="">-</option>
        @foreach($provinces as $province)
            <option selected="selected" value="{{$province->code}}">{{ $province->name }}</option>
        @endforeach
    </select>
@endif