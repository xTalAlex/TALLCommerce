@props([
    'embed'
])

<div class="relative w-full h-full text-gray-600 body-font">

    <div class="absolute inset-0 bg-gray-300">
        <iframe style="filter: grayscale(1) contrast(1.2) opacity(0.4);"
            width="100%" height="100%" frameborder="0" marginheight="0" marginwidth="0" title="map" scrolling="no"  
            src="{!! $embed !!}"
        ></iframe>
    </div>

    {{ $slot }}

</div>