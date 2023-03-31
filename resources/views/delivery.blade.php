<x-app-layout>

<div class="w-full bg-bottom bg-cover h-64 lg:h-[32rem] " style="background-image: url('/img/milano.png')"></div>

<div class="container max-w-5xl px-6 py-12 mx-auto mb-12 prose">
    <div>
        <h2 class="mb-12 text-2xl font-bold">{{ __('Dove consegniamo') }}</h2>
        <p>
            Il nostro personale specializzato consegna quotidianamente con i mezzi aziendali (anonimi)
            refrigerati su <strong>Milano e provincia</strong> inoltre serviamo diversi comuni in 
            <strong>provincia di Pavia</strong> e alcune zone del <strong>Lago di Garda</strong>.
        </p>
        <p>
            Mensilmente cerchiamo di espandere le nostre aree di consegna.
        </p>
        
        <livewire:province-check :provinces="$provinces">
        
        <div class="flex items-center justify-center px-6 pt-6 pb-10 mt-12 bg-neutral-50 bg-opacity-60 not-prose">
            <div class="flex flex-col">
                <p>Se la vostra zona non è coperta dal nostro servizio potete scrivere a</p>
                <p><a class="underline" href="mailto:info@info.it">info@colombofood.it</a> per organizzare un servizio di consegna tramite corrieri.</p>
            </div>
        </div>
    </div>

    @if($shippingPrices->count())
    <div>
        <h2 class="mb-12 text-2xl font-bold">{{ __('Tariffe e tempi di consegna') }}</h2>

        <div class="space-y-6">
        @if($shippingPrices->count())
            <table>
                <thead>
                    <tr>
                        <th class="pl-2">{{ __('Tariffa') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Tempi di consegna') }}</th>
                        <th>{{ __('Prezzo') }} </th>
                    </tr>
                </thead>
                <tbody>
                @foreach($shippingPrices as $shippingPrice)
                    <tr class="even:bg-gray-50">
                        <td class="px-2 font-semibold">
                            {{ $shippingPrice->name }}
                        </td>
                        <td class="px-2">
                            @if($shippingPrice->description)
                                <div>{{ $shippingPrice->description }}</div>
                            @endif
                            @if($shippingPrice->min_spend)
                                <div class="font-semibold">{{ __('Minimum spend :amount€',['amount' => $shippingPrice->min_spend]) }}</div>
                            @endif
                        </td>
                        <td class="px-2">
                            @if($shippingPrice->deliveryTimeLabel())
                                {{ $shippingPrice->deliveryTimeLabel() }}
                            @endif
                        </td>
                        <td class="px-2 font-semibold">
                            @if($shippingPrice->price > 0)
                            {{ priceLabel($shippingPrice->price) }}
                            @else
                                {{ __('Free') }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            
            <div class="not-prose">
                <p>Per garantire i tempi di consegna gli ordini devono essere effettuati <strong>entro le 16.30</strong>, oltre si considera il giorno successivo. </p>
                <p>Sabato e Domenica sono giorni <strong>festivi</strong>.</p>
            </div>
        @endif
        </div>
    </div>
    @endif
</div>

</x-app-layout>