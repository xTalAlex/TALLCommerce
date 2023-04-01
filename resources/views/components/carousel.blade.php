<div class="swiper h-96 xl:h-[28rem] 2xl:h-[32rem]"
    x-data="{
        slider : null,
    }"
    x-init="
        slider = new Swiper('.swiper', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    "
>
    <div class="swiper-wrapper"
        id="slider"
        x-show="slider"
        x-cloak
    >
        @foreach($items as $item)
        <div class="relative w-full h-full bg-white swiper-slide">
            <a class="w-full h-full"
                href="{{ route('product.index', ['category' => $item->slug] ) }}"
            >
                <img class="object-cover w-full h-full" src="{{$item->hero}}"/>
                <span class="absolute z-30 flex items-center justify-center w-full h-20 text-2xl font-semibold text-white -translate-x-1/2 -translate-y-1/2 bg-gray-900 bg-opacity-50 top-1/2 left-1/2 sm:text-3xl">
                    {{ $item->name }}   
                </span>
            </a>
        </div>
        @endforeach
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>