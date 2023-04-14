@props([
    'type' => 'text',
    'label' => null,
    'placeholder' => null,
    'name' => null,
    'disabled' => false,
])

<div {!! $attributes->only('class')->merge(['class' => 'form-control' ]) !!}>
  @if($label)
  <label class="label" {{ $name ? 'for="'.$name.'"' : '' }}>
    <span class="label-text">{{ $label }}</span>
    {{-- <span class="label-text-alt">Top Right label</span> --}}
  </label>
  @endif
  <input type="{{ $type }}" class="w-full input input-bordered {{ $name && $errors->has($name) ? 'input-error' : ''}}" 
    {{ $attributes->except('class') }} 
    {{ $disabled ? 'disabled' : '' }}
    {!! $name ? 'name="'.$name.'" id="'.$name.'"' : '' !!}
    {!! $placeholder ? 'placeholder="'.$placeholder.'"' : '' !!}
  />
  @error($name)
  <label class="label">
    <span class="label-text-alt text-error">{{ $error }}</span>
    {{-- <span class="label-text-alt">Bottom Right label</span> --}}
  </label>
  @enderror
</div>