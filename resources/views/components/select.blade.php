@props([
    'label' => null,
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
  <select class="w-full select select-bordered {{ $name && $errors->has($name) ? 'select-error' : ''}}"
    {{ $attributes->except('class') }} 
    {{ $disabled ? 'disabled' : '' }}
    {{ $name ? 'name="'.$name.'" id="'.$name.'"' : '' }}
  >
      {{ $slot }}
    </select>
  @error($name)
  <label class="label">
    <span class="label-text-alt text-error">{{ $error }}</span>
    {{-- <span class="label-text-alt">Bottom Right label</span> --}}
  </label>
  @enderror
</div>