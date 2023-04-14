<x-select {{ $attributes }}>
    <option value=" ">-</option>
    @foreach ($countries as $country)
        <option value="{{ $country }}">{{ $country }}</option>
    @endforeach
</x-select>
