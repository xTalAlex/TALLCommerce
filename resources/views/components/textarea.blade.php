@props([
    'label' => null,
    'placeholder' => null,
    'id' => null,
    'disabled' => false,
    'resize' => null,
])

<div {!! $attributes->only('class')->merge(['class' => 'form-control' ]) !!}>
  @if($label)
  <label class="label" {{ $id ? 'for="'.$id.'"' : '' }}>
    <span class="label-text">{{ $label }}</span>
    {{-- <span class="label-text-alt">Top Right label</span> --}}
  </label>
  @endif
  <textarea @class([
        "w-full textarea textarea-bordered",
        "textarea-error" => $id && $errors->has($id),
        "resize-none" => $resize == 'none'
    ])
    {{ $attributes->except('class') }} 
    {{ $disabled ? 'disabled' : '' }}
    {!! $id ? 'id="'.$id.'"' : '' !!}
    {!! $placeholder ? 'placeholder="'.$placeholder.'"' : '' !!}
  ></textarea>
  @if($id)
    @error($id)
    <label class="label">
      <span class="label-text-alt text-error">{{ $message }}</span>
      {{-- <span class="label-text-alt">Bottom Right label</span> --}}
    </label>
    @enderror
  @endif
</div>