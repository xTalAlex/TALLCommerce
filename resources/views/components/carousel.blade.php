<div  
    x-data="{
        slider : null,
        nSlides : {{ $products->count() }},
        currentIdx : 0,
        timeout : null,
        mouseOver : false,
        visible : false,
    }"
    x-init="
        slider = new window.KeenSlider(
            '#slider',
            {
                loop: true,
                created: () => {
                    visible = true;
                },
            },
            [
                slider => {
                    function clearNextTimeout() {
                        clearTimeout(timeout)
                    }
                    function nextTimeout() {
                        clearTimeout(timeout)
                        if (!mouseOver)
                            timeout = setTimeout(() => {
                                slider.next()
                            }, 3000)
                    }
                    slider.on('created', () => {
                        slider.container.addEventListener('mouseover', () => {
                            mouseOver = true
                            clearNextTimeout()
                        })
                        slider.container.addEventListener('mouseout', () => {
                            mouseOver = false
                            nextTimeout()
                        })
                        nextTimeout()
                    })
                    slider.on('dragStarted', clearNextTimeout)
                    slider.on('animationEnded', nextTimeout)
                    slider.on('updated', nextTimeout)
                    slider.on('slideChanged', () => {
                        currentIdx = slider.track.absToRel(slider.track.details.abs);
                    })
                },
            ]
        )
    "
>
    <div class="relative">
        <div class="keen-slider h-96 xl:h-[28rem] 2xl:h-[32rem]"
            id="slider"
            x-show = "visible"
            x-cloak
        >
            @foreach($products as $product)
            <div class="relative w-full h-full bg-white keen-slider__slide">
                <img class="object-cover w-full h-full" src="{{$product->image}}"/>
                <span class="absolute z-30 flex items-center justify-center w-full h-20 text-2xl font-semibold text-white -translate-x-1/2 -translate-y-1/2 bg-black bg-opacity-50 top-1/2 left-1/2 sm:text-3xl">
                    {{ $product->name }} 
                </span>    
            </div>
            @endforeach
            <div x-on:click="slider.prev()" class="absolute flex items-center justify-center w-8 h-8 -translate-y-1/2 cursor-pointer left-1 fill-white top-1/2">
                <x-icons.chevron-left></x-icons.chevron-left>
            </div>
            <div x-on:click="slider.next()" class="absolute flex items-center justify-center w-8 h-8 -translate-y-1/2 cursor-pointer right-1 fill-white top-1/2">
                <x-icons.chevron-right></x-icons.chevron-right>
            </div>
        </div>
        <div class="flex justify-center py-2">
            @foreach($products as $product)
                <div class="w-3 h-3 p-1 mx-1 border-none rounded-full cursor-pointer"
                    x-bind:class="currentIdx == {{ $loop->index }} ? 'bg-black' : 'bg-gray-400 '"
                    x-on:click="slider.moveToIdx({{ $loop->index }})" 
                ></div>
            @endforeach
        </div>
    </div>

</div>