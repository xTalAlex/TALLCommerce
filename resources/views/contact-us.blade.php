<x-app-layout>

    <div class="w-full bg-center bg-cover h-64 lg:h-[32rem]" style="background-image: url('/img/contatti.png')"></div>

    <div class="container max-w-5xl px-6 py-12 mx-auto prose">
        <div>
            <h2 class="mb-12 text-2xl font-bold">{{ __('Contacts') }}</h2>

            <div class="flex flex-col mb-6">
                <span class="font-bold uppercase text-accent-500">Ordina Con Whatsapp</span>
                <a class="font-black text-gray-900 no-underline"
                    href="https://wa.me/393662121189?text=Ciao%2C%20ho%20bisogno%20di%20questi%20prodotti%3A"
                >+39 366 212 11 89 <img class="inline h-5 -mt-0.5 py-0 my-0 ml-1" src="/img/logos/whatsapp.svg" /> </a>
                {{-- <a class="underline" href="mailto:ordini@colombofood.it">ordini@colombofood.it</a> --}}
            </div>

            <div class="flex flex-col mb-6">
                <span class="text-accent-500">
                    <span class="font-bold uppercase">ECommerce</span> <span>(informazioni sull'utilizzo della piattaforma)</span>
                </span>
                <span class="font-black text-gray-900 uppercase">+39 347 55 68 978</span>
                <a class="underline" href="mailto:info@colombofood.it">info@colombofood.it</a>
            </div>
            
            <div class="flex flex-col mb-6">
                <span class="text-accent-500">
                    <span class="font-bold uppercase">Amministrazione</span> <span>(gestione ordini, fatture)</span>
                </span>
                <span class="font-black text-gray-900 uppercase">+39 02 3928 3559</span>
                <a class="underline" href="mailto:amministrazione@colombofood.it">amministrazione@colombofood.it</a>
            </div>

        </div>

        <div class="pt-16 -mt-16" id="work-with-us">
            <h2 class="mb-12 text-2xl font-bold text-accent-500">{{ __('Work with us') }}</h2>

            <p>
                Siamo sempre alla ricerca di talenti da inserire nella nostra squadra.<br>
                Se sei motivato, appassionato e hai una grande professionalità, siamo pronti ad offrirti un ambiente
                di lavoro stimolante e produttivo.
            </p>

            <p>
                Ti invitiamo a inviare il tuo CV e a candidarti per le posizioni aperte nel nostro team.<br>
                La tua esperienza sarà sempre valutata e apprezzata.
            </p>

            <p>
                Non vediamo l'ora di conoscerti, scrivici a: <br>
                <a class="underline" href="mailto:info@colombofood.it">info@colombofood.it</a>
            </p>
        </div>
    </div>

    <div class="mb-12">
        <x-google-map embed="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2801.0096500592686!2d9.122464315555629!3d45.40914497910034!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4786c31ed0510799%3A0xe22ec8ee1995dbdf!2sVia%20Mario%20Idiomi%2C%204A%2C%2020090%20Assago%20MI!5e0!3m2!1sit!2sit!4v1675688446243!5m2!1sit!2sit">
            
            <div class="w-full py-12 sm:py-0">
                <div class="relative z-10 w-3/4 max-w-full px-6 py-12 pb-24 ml-auto mr-auto prose bg-white sm:w-1/2 sm:mr-0">
                    <div class="mx-auto w-fit">
                        <h2 class="mb-2 text-2xl font-bold">{{ __('Dove siamo') }}</h2>
                        <div class="mt-6 font-semibold text-accent-500">ColomboFood</div>
                        <div class="">Via Mario Idiomi, 4A, 20090 Assago (Milano)</div>

                        <div class="mt-6 font-semibold text-accent-500">Dolci Follie</div>
                        <div class="">Via Marco D'Agrate, 14, 20139 Milano</div>
                    </div>
                </div>
            </div>
            
        </x-google-map>
    </div>

</x-app-layout>