<x-select {{ $attributes }}>
    <option value=" ">-</option>
    @foreach ($provinces as $province)
        <option value="{{ $province->code }}">{{ $province->name }}</option>
    @endforeach
</x-select>
