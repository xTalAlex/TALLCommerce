<x-app-layout>

    <div class="navbar bg-base-100">
        <div class="hidden mx-auto navbar-center lg:flex">
            <ul class="px-1 menu menu-horizontal">
                <li><a>Item 1</a></li>
                <li tabindex="0">
                    <a>
                        Parent
                        <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24">
                            <path d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" />
                        </svg>
                    </a>
                    <ul class="p-2 bg-base-100">
                        <li><a>Submenu 1</a></li>
                        <li><a>Submenu 2</a></li>
                    </ul>
                </li>
                <li><a>Item 3</a></li>
            </ul>
        </div>
    </div>

    <div class="min-h-screen hero" style="background-image: url(https://picsum.photos/2040/1560);">
        <div class="hero-overlay bg-opacity-60"></div>
        <div class="text-center hero-content text-neutral-content">
            <div class="max-w-md">
                <h1 class="mb-5 text-5xl font-bold">{{ config('app.name') }}</h1>
                <p class="mb-5">Lo shop online sviluppato con Tailwind, Alpine, Laravel e Livewire</p>
                <a href="{{ route('product.index') }}" class="btn btn-primary">Shop</a>
            </div>
        </div>
    </div>

    <section class="text-neutral body-font">
        <div class="container flex flex-wrap px-5 py-24 mx-auto">
            <div class="flex flex-wrap w-full mb-20">
                <h1 class="mb-4 text-2xl font-medium text-neutral sm:text-3xl title-font lg:w-1/3 lg:mb-0">
                    Articoli in evidenza
                </h1>
                <p class="mx-auto text-base leading-relaxed lg:pl-6 lg:w-2/3">Whatever cardigan tote bag tumblr hexagon
                    brooklyn asymmetrical gentrify, subway tile poke farm-to-table. Franzen you probably haven't heard
                    of them man bun deep jianbing selfies heirloom.</p>
            </div>
            <div class="flex flex-wrap -m-1 md:-m-2">
                <div class="flex flex-wrap w-1/2">
                    <div class="w-1/2 p-1 md:p-2">
                        <img alt="gallery" class="block object-cover object-center w-full h-full"
                            src="https://dummyimage.com/500x300">
                    </div>
                    <div class="w-1/2 p-1 md:p-2">
                        <img alt="gallery" class="block object-cover object-center w-full h-full"
                            src="https://dummyimage.com/501x301">
                    </div>
                    <div class="w-full p-1 md:p-2">
                        <img alt="gallery" class="block object-cover object-center w-full h-full"
                            src="https://dummyimage.com/600x360">
                    </div>
                </div>
                <div class="flex flex-wrap w-1/2">
                    <div class="w-full p-1 md:p-2">
                        <img alt="gallery" class="block object-cover object-center w-full h-full"
                            src="https://dummyimage.com/601x361">
                    </div>
                    <div class="w-1/2 p-1 md:p-2">
                        <img alt="gallery" class="block object-cover object-center w-full h-full"
                            src="https://dummyimage.com/502x302">
                    </div>
                    <div class="w-1/2 p-1 md:p-2">
                        <img alt="gallery" class="block object-cover object-center w-full h-full"
                            src="https://dummyimage.com/503x303">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="h-screen">
        <x-google-map embed="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11130.996258982894!2d8.889076999999999!3d45.776219!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x478686b86a3e796d%3A0x4f620eef8a7004e3!2s21040%20Vedano%20Olona%20VA!5e0!3m2!1sit!2sit!4v1681354778826!5m2!1sit!2sit" />
    </div>
</x-app-layout>
