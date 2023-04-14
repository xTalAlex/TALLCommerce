@props([
    'label' => null,
    'placeholder' => null,
    'name' => null,
    'disabled' => false,
    'resize' => null,
])

<div {!! $attributes->only('class')->merge(['class' => 'form-control' ]) !!}>
  @if($label)
  <label class="label" {{ $name ? 'for="'.$name.'"' : '' }}>
    <span class="label-text">{{ $label }}</span>
    {{-- <span class="label-text-alt">Top Right label</span> --}}
  </label>
  @endif
  <textarea @class([
        "w-full textarea textarea-bordered",
        "textarea-error" => $name && $errors->has($name),
        "resize-none" => $resize == 'none'
    ])
    {{ $attributes->except('class') }} 
    {{ $disabled ? 'disabled' : '' }}
    {!! $name ? 'name="'.$name.'" id="'.$name.'"' : '' !!}
    {!! $placeholder ? 'placeholder="'.$placeholder.'"' : '' !!}
  ></textarea>
  @error($name)
  <label class="label">
    <span class="label-text-alt text-error">{{ $error }}</span>
    {{-- <span class="label-text-alt">Bottom Right label</span> --}}
  </label>
  @enderror
</div>