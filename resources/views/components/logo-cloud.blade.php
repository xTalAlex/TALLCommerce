<div {{ $attributes->merge(['class' => 'py-16 bg-white']) }}>
    <div class="container px-6 m-auto space-y-8 md:px-12 lg:px-56">
        @if($items->count())
        <div class="m-auto text-center lg:w-7/12">
            <h2 class="text-2xl font-bold text-gray-700 md:text-4xl">
                {{ __('Our Brands') }}
            </h2>
        </div>
        @endif
        <div @class([
                'grid  mx-auto',
                'grid-cols-2' => $items->count()>=2,
                'sm:grid-cols-3' => $items->count()>=3,
                'md:grid-cols-4' => $items->count()>=4,
            ])
        >
        @foreach($items as $item)
            <div class="p-4 m-auto">
                <a href="{{ $item['url'] }}">
                    <img src="{{ $item['logo'] }}" class="w-32 " alt="">
                </a>
            </div>
        @endforeach
        </div>
    </div>
</div>