@props([
    'filled' => "true",
    'red' => "true"
])

@php
  $class="inline w-6 h-6 stroke-2 "
    .($red == "true" ? "stroke-red-500" : "stroke-current" )
    ." "; 
  if($red == "true") $class.=(($filled == "true") ? 'fill-red-500' : 'fill-none');
  else $class.=(($filled == "true") ? 'fill-current' : 'fill-none');
@endphp

<svg xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge([
      "class" => $class
    ]) }} viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
</svg>