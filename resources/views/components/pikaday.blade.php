<!--

    !!!! non funziona con Carbon e $casts a date

    x-model funziona perché é sullo stesso elemento di x-on:change
    se si separa l'input usare x-bind:value invece di x-model
-->
<input type="text"
    {{ $attributes->whereDoesntStartWith('wire:model') }}
    x-data="{
        value: @entangle($attributes->wire('model')),
        format: '{{ $attributes->get('format') ?? 'DD/MM/YYYY' }}',
    }"
    x-init="() => {
        const picker = new Pikaday({ 
            field: $refs.datepicker, 
            format: 'DD/MM/YYYY', 
            {{-- toString(date, format) {
                return DateTime.fromJSDate(date).setLocale('it').toLocaleString();
            },
            parse(dateString, format) {
                console.log(dateString);
                console.log(format);
                console.log(DateTime.fromFormat(dateString, 'YYYY-MM-DD').toFormat('DD/MM/YYYY'));
                return DateTime.fromFormat(dateString, 'YYYY-MM-DD').toFormat('DD/MM/YYYY');
            } --}}
        });
    }"
    x-ref="datepicker" 
    x-model="value"
    x-on:change="value = $event.target.value"
    placeholder="{{ $attributes->get('placeholder') ?? '00/00/0000' }}"
>