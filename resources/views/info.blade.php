<x-app-layout>

    <div class="w-full bg-top bg-cover h-64 lg:h-[32rem] " style="background-image: url('/img/panettieri.png')"></div>

    <div class="container max-w-5xl px-6 py-12 mx-auto mb-12 prose">
        <div>
            <h2 class="mb-12 text-2xl font-bold">{{ __('Da subito, tanti vantaggi') }}</h2>

            <div>
                <ul class="pl-0 list-none">
                    <li class="pl-0 font-black odd:text-accent-500">5% DI SCONTO SUL PRIMO ORDINE <span class="font-normal">(CODICE CONAMORE5)</span></li>
                    <li class="pl-0 font-black odd:text-accent-500">NESSUN MINIMO D'ORDINE</li>
                    <li class="pl-0 font-black odd:text-accent-500">CONSEGNA STANDARD ENTRO <span class="font-normal">(ENTRO 4 GIORNI LAVORATIVI, GRATIS)</span></li>
                    <li class="pl-0 font-black odd:text-accent-500">CONSEGNA EXPRESS ENTRO <span class="font-normal">(ENTRO 2 GIORNI LAVORATIVI, 10€)</span></li>
                    <li class="pl-0 font-black odd:text-accent-500">OLTRE 400 REFERENZE A CATALAGO</li>
                    <li class="pl-0 font-black odd:text-accent-500">CONSEGNE CON MEZZI REFRIGERATI ANONIMI</li>
                    <li class="pl-0 font-black odd:text-accent-500">INIZIO CONSEGNE ALLE 05.00 DEL MATTINO</li>
                    <li class="pl-0 font-black odd:text-accent-500">RICERCA E PERSONALIZZAZIONE PRODOTTI</li>
                    <li class="pl-0 font-black odd:text-accent-500">SCONTO 10% NELLA SETTIMANA DEL VOSTRO COMPLEANNO</li>
                </ul>
            </div>
        </div>

        <div class="pt-16 -mt-16" id="special-iva">
            <h2 class="mb-12 text-2xl font-bold">{{ __('Speciale Partita iva') }}</h2>

            <p>
                Siamo all'antica ci piacciono le relazioni a lungo termine. Vi ascolteremo e faremo di tutto per
                venirvi in contro. E quando ci sarà da correre lo faremo. Con orgoglio e con una punta di vanità
                possiamo affermare che difficimente un cliente ci abbandona.
            </p>

            <div class="grid gap-6 mt-12 md:grid-cols-2 not-prose">
                <img class="object-cover w-full h-96" src="/img/cassiera.png"/>
                <img class="object-cover w-full h-96" src="/img/camerieri.png"/>
            </div>
        </div>

        <div class="pt-16 -mt-16" id="special-bnb">
            <h2 class="mb-12 text-2xl font-bold">{{ __('Per i gestori di Hotel, Air BnB e Bed and Breakfast') }}</h2>

            <p>
                Grazie ad un'ampia scelta di prodotti per la colazione e per il pranzo siamo in grado di soddisfare
                le esigenze più varie e di gestire consegne giornaliere.
            </p>

            <p>
                Inoltre abbiamo sviluppato un servizio unico di <strong>packaging personalizzato per i vostri ospiti</strong>;
                non c'è ricordo più bello di un felice risveglio al mattino. Fatevi ricordare dai vostri clienti,
                parleranno di voi e vi recensiranno al meglio!
            </p>

            <div class="grid gap-6 mt-12 md:grid-cols-2 not-prose">
                <img class="object-cover w-full h-96" src="/img/box_biscotti.png"/>
                <img class="object-cover w-full h-96" src="/img/box_colazione.png"/>
            </div>
        </div>
    </div>

</x-app-layout>