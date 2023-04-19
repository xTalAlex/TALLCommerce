@props([
    'label' => null,
    'id' => null,
    'disabled' => false,
])

<div {!! $attributes->only('class')->merge(['class' => 'form-control' ]) !!}>
  @if($label)
  <label class="label" {{ $id ? 'for="'.$id.'"' : '' }}>
    <span class="label-text">{{ $label }}</span>
    {{-- <span class="label-text-alt">Top Right label</span> --}}
  </label>
  @endif
  <select class="w-full select select-bordered {{ $id && $errors->has($id) ? 'select-error' : ''}}"
    {{ $attributes->except('class') }} 
    {{ $disabled ? 'disabled' : '' }}
    {!! $id ? 'id="'.$id.'"' : '' !!}
  >
      {{ $slot }}
    </select>
  @if($id)
    @error($id)
    <label class="label">
      <span class="label-text-alt text-error">{{ $message }}</span>
      {{-- <span class="label-text-alt">Bottom Right label</span> --}}
    </label>
    @enderror
  @endif
</div>