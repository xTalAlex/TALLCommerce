@props([
    'centered' => true,
])

<div class="grid place-items-center min-h-screen pt-6 sm:pt-0 bg-cover bg-right bg-[url('/public/img/homebanner.png')]">
    <div @class([
            'flex flex-col items-center justify-center w-full overflow-hidden bg-white shadow-md sm:bg-opacity-90 sm:max-w-md',
            'sm:ml-20 sm:mr-auto' => $centered == false
        ])
    >
        <div class="pt-6">
            <a href="{{ route('home') }}">
                {{ $logo }}
            </a>
        </div>

        <div class="w-full px-6 py-4 mt-8 ">
            {{ $slot }}
        </div>
    </div>
</div>
