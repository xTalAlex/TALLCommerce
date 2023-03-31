<x-app-layout>

    @if(!$featured_collection)
    <div class="w-full h-[32rem] bg-cover bg-right"
        style="background-image: url('/img/homebanner.png')"
    >
        <div class="pt-24 mx-auto lg:container lg:pt-32">
            <div class="w-full px-6 py-4 mx-auto text-center bg-white lg:text-left lg:px-2 lg:w-96 lg:p-auto bg-opacity-80 lg:bg-opacity-0 lg:ml-20 xl:ml-40">
                <div class="mx-auto max-w-max">
                    <x-jet-application-logo class="mx-auto lg:w-full max-h-28 lg:max-h-none"/>
                    {{-- <x-algolia-autocomplete class="w-full mt-6"/> --}}
                    <form method="GET" action="{{ route('product.index') }}">
                        <div class="flex items-center w-full pr-2 mt-6 transition focus-within:border-primary-500 focus-within:border-b-2">
                            <input class="flex-1 bg-transparent border-transparent peer focus:border-transparent focus:ring focus:ring-transparent"
                                type="text" name="query" placeholder="{{ __('Search...') }}" autocomplete="off"
                            />
                            <button type="submit" class="transition duration-300 opacity-50 hover:opacity-100 peer-focus:opacity-100">
                                <svg class="w-5 h-5 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="w-full h-[32rem] bg-cover bg-right"
        style="background-image: url({{$featured_collection->hero}})"
    >
        <div class="pt-24 mx-auto lg:container lg:pt-32">
            <div class="w-full px-6 py-4 mx-auto text-center bg-white lg:text-left lg:px-2 lg:w-96 lg:p-auto bg-opacity-80 lg:bg-opacity-0 lg:ml-20 xl:ml-40">
                <div class="mx-auto max-w-max">
                    <x-jet-application-logo class="mx-auto lg:w-full max-h-28 lg:max-h-none"/>
                    {{-- <x-algolia-autocomplete class="w-full mt-6"/> --}}
                    <form method="GET" action="{{ route('product.index') }}">
                        <div class="flex items-center w-full pr-2 mt-6 transition lg:bg-white lg:bg-opacity-80 focus-within:border-primary-500 focus-within:border-b-2">
                            <input class="flex-1 bg-transparent border-transparent peer focus:border-transparent focus:ring focus:ring-transparent"
                                type="text" name="query" placeholder="{{ __('Search...') }}"
                            />
                            <button type="submit" class="transition duration-300 opacity-50 hover:opacity-100 peer-focus:opacity-100">
                                <svg class="w-5 h-5 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="container grid gap-6 px-6 mx-auto my-6 md:grid-cols-3">
        @foreach($featured_categories as $category)
            <a href="{{ route('product.index', [ 'category' => $category->slug ]) }}">
                <div class="relative flex items-center justify-center h-24 overflow-hidden bg-cover cursor-pointer group">
                    <img class="absolute object-cover w-full h-full transition duration-700 ease-in-out transform group-hover:scale-150 group-hover:opacity-70" 
                        src="{{ $category->hero }}"
                    />
                    <span class="absolute px-6 py-1 bg-white bg-opacity-80">{{ $category->name }}</span>
                </div>
            </a>
        @endforeach
    </div>
    
    <div class="container mx-auto my-12">
        <h2 class="text-2xl font-bold text-center">I nostri <span class="text-accent-500">Consigli</span></h2>
    
        <div class="grid grid-cols-2 mx-6 my-12 gap-x-6 gap-y-12 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">

            @foreach ($featured_products as $product)
                <div class="last:md:hidden last:lg:flex">
                    <a class="block w-full h-full" href="{{ route('product.show', $product) }}">
                        <div class="flex flex-col items-center w-full h-full p-2">
                            <div class="relative h-48 overflow-hidden group">
                                <img @class([
                                        'object-cover h-full',
                                        'transition transform duration-200 group-hover:scale-90' => $product->hasImage(),
                                        'translate-x-0 group-hover:translate-x-full' => count($product->gallery)>1
                                    ])
                                    src="{{ $product->image }}" />
                                @if(count($product->gallery)>1)
                                    <img @class([
                                            'object-cover h-full absolute inset-0',
                                            'transition transform duration-200 group-hover:translate-x-0 -translate-x-full' => $product->hasImage()
                                        ])
                                        src="{{ $product->gallery[1] }}" />
                                @endif
                                @if($product->hasImage())
                                    <div class="absolute top-0 block w-1/2 h-full transform -skew-x-12 -inset-full z-5 bg-gradient-to-r from-transparent to-white opacity-40 group-hover:animate-shine"></div>
                                @endif
                            </div>
                            <div class="mt-1 text-base font-bold text-center">{{ $product->name }}</div>
                            <div class="text-gray-500">{{ $product->short_description }}</div>
                            <form action="{{ route('product.show', $product) }}" method="GET" class="flex-none w-full pt-12 mt-auto mb-0">
                                <x-button class="justify-center w-full">{{ __('See more') }}</x-button>
                            </form>
                        </div>
                    </a>
                </div>
                </a>
            @endforeach
        
        </div>

    </div>

    <div class="container grid w-full gap-6 px-6 mx-auto my-12 md:grid-cols-2">
        
        <div class="relative flex flex-col items-center justify-center px-6 pt-6 pb-10 lg:flex-row bg-primary-500">
            <div class="flex flex-col lg:pl-48">
                <p class="max-w-xs mx-auto text-center">
                    Ti servono 50 lasagne
                    per pranzo? 100 Sbrodoloni
                    per il primo pomeriggio?
                </p>
                <div class="flex flex-col items-center justify-center mt-2 font-semibold text-white uppercase">
                    <span>MANDA UN WHATSAPP AL</span>
                    <a class="text-3xl font-black"
                        href="https://wa.me/393662121189?text=Ciao%2C%20ho%20bisogno%20di%20questi%20prodotti%3A"
                    >366 212 11 89<img class="inline h-6 ml-2 -mt-2" src="/img/logos/whatsapp.svg" /> </a>
                    <span class="text-xl">CONSEGNE EXPRESS</span>
                </div>
            </div>

            <img class="absolute bottom-0 left-0 hidden lg:block w-72" src="/img/supporto.png"/>
            <div class="absolute hidden bottom-32 left-24 lg:block">
                <div class="relative flex items-center justify-center text-xs rounded-full w-28 h-14 bg-primary-50">
                    <p class="w-20 text-center">vi rispondiamo subito!</p>
                    <div class="absolute w-2 h-4 rounded-r-full bg-primary-50 -bottom-2 left-4"></div>
                </div>
            </div>
        </div>

        <div class="flex flex-col px-4 py-10 bg-secondary-50 xl:flex-row bg-opacity-60">
            <div class="flex flex-col mb-12 text-center xl:mb-0 xl:ml-auto xl:mr-0">
                <p class="max-w-sm mx-auto">
                    Consegniamo dalle 05.00 del mattino con nostri
                    furgoni anonimi a <span class="font-semibold">Milano e provincia</span> 
                     + nuove zone aggiunte mensilmente
                </p>
                <form action="{{ route('delivery') }}" method="GET" class="mt-6">
                    <x-button type="submit" class="px-12">Scopri dove consegniamo</x-button>
                </form>
            </div>
            <img class="h-32 mx-auto -mb-10 xl:ml-12" src="/img/furgoncino.png" />
        </div>

    </div>

    <div class="w-full bg-gray-50">
        <div class="container flex flex-col w-full mx-auto my-12 md:h-96 md:flex-row">
            <img class="object-cover object-top h-64 lg:w-full md:max-w-xs lg:max-w-none md:object-center md:h-full lg:h-auto" 
                src="/img/shop.png"
            />
            <div class="flex flex-col items-center w-full px-4 md:pr-4">
                <div class="max-w-sm pt-4 pb-10 ml-auto mr-auto md:ml-12 md:mr-0 md:pt-0 md:pb-0 md:mt-12">
                    <p class="text-3xl font-bold">Hai la partita Iva?</p>
                    <p class="mt-6">
                        Colombo Food ha studiato una serie di offerte
                        dedicate e personalizzate rivolte ai commercianti,
                        proprietari di bar, panetterie o piccole attività,
                        che hanno delle esigenze particolari.
                    </p>
                    <p class="mt-6 font-bold">
                        Perchè a volte avere la Partita Iva può essere un vantaggio!
                    </p>
                    <div class="mt-6">
                        {{-- <form action="{{ route('info') . "#special-iva" }}" method="GET"> --}}
                        <form action="{{ route('info') }}" method="GET">
                            <x-button class="px-12">Scopri i vantaggi</x-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full">
        <div class="container flex flex-col w-full mx-auto my-12 md:h-96 md:flex-row">
            <div class="flex flex-col items-center w-full">
                <div class="max-w-sm px-4 pt-4 pb-10 ml-auto mr-auto text-right md:px-0 md:pl-4 md:mr-12 md:ml-0 md:pt-0 md:pb-0 md:my-auto">
                    <p class="text-3xl font-bold text-accent-500">
                        Gestisci un piccolo Hotel
                        o affiti appartamenti
                        su AirBnB e Booking?
                    </p>
                    <p class="mt-2">
                        Coccolate i vostri ospiti con brioche, torte,
                        dolci di pasticceria appena sfornati.
                        Servite anche il pranzo o la cena?
                        Il nostro servizio vi permette di selezionare
                        tra moltissimi prodotti e ricerverli
                        quotidianamente a prezzi vantaggiosissimi!
                    </p>
                    <div class="mt-6">
                        {{-- <form action="{{ route('info') . "#special-bnb" }}" method="GET"> --}}
                        <form action="{{ route('info') }}" method="GET">
                            <x-button class="px-12">Scopri l'offerta</x-button>
                        </form>
                    </div>
                </div>
            </div>
            <img class="object-cover object-top h-64 lg:w-full md:max-w-xs lg:max-w-none md:object-center md:h-full lg:h-auto" src="/img/cornetto.png"/>
        </div>
    </div>

    <div class="w-full bg-gray-50 bg-opacity-60">
        <div class="container flex w-full px-6 py-6 mx-auto my-12">
            <img class="hidden h-24 my-auto ml-auto mr-12 md:block" 
                src="/img/logos/pane_quotidiano.png"
            />
            <div class="flex flex-col items-center justify-center md:text-center">
                <div class="">
                    <p class="text-3xl font-bold text-neutral-500">Colombo Food supporta Pane Quotidiano</p>
                    <p class="text-3xl text-neutral-400">e combatte la lotta contro lo spreco al cibo.</p>
                    <p class="mt-2">
                        Puoi dare una mano anche tu, vai su: <a class="underline" href="https://panequotidiano.eu" target="_blank">panequotidiano.eu</a>
                    </p>
                </div>
            </div>
            <img class="hidden h-24 my-auto ml-12 mr-auto sm:block" 
                src="/img/logos/pane_quotidiano.png" 
            />
        </div>
    </div>

</x-app-layout>