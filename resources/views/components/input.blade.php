@props([
    'type' => 'text',
    'label' => null,
    'placeholder' => null,
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
  <input type="{{ $type }}" class="w-full input input-bordered {{ $id && $errors->has($id) ? 'input-error' : ''}}" 
    {{ $attributes->except('class') }} 
    {{ $disabled ? 'disabled' : '' }}
    {!! $id ? 'id="'.$id.'"' : '' !!}
    {!! $placeholder ? 'placeholder="'.$placeholder.'"' : '' !!}
  />
  @if($id)
    @error($id)
    <label class="label">
      <span class="label-text-alt text-error">{{ $message }}</span>
      {{-- <span class="label-text-alt">Bottom Right label</span> --}}
    </label>
    @enderror
  @endif
</div>