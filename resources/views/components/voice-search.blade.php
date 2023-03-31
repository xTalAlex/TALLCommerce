<div 
    {!! $attributes->merge(['class' => '']) !!}

    x-data="{
        recognition: undefined,
        listening: false,
        transcript: {
            interim: '',
            final: '',
        },
        startListening(event) {
            this.transcript.final = '';
            try{
                this.recognition.start();  
            }
            catch(e){
                //
            }
        },
    }"
    x-init="
        if (!('webkitSpeechRecognition' in window)) {
            console.log('webkitSpeechRecognition not supported');
        } 
        else {
            recognition = new webkitSpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = true;

            recognition.onstart = function() {
                Livewire.emit('start');
            };
            recognition.onresult = function() { 
                transcript.interim = '';
                for (var i = event.resultIndex; i < event.results.length; ++i) {
                    if (event.results && event.results[i].isFinal) {
                        transcript.final += event.results[i][0].transcript;
                    } else {
                        transcript.interim += event.results[i][0].transcript;
                    }
                }
                $dispatch('change', transcript);
            };
            recognition.onerror = function(event) {
                Livewire.emit('error', event);
            };
            recognition.onend = function() {
                Livewire.emit('end');
            };
        }

        Livewire.on('start', () => listening = true );
        Livewire.on('end', () => listening = false )
    "
    x-bind:value="transcript"
    x-show="recognition"
    x-cloak
    {{ $attributes }}
>
    <button class="inline-flex items-center justify-center w-full h-full px-4 py-2 text-xs font-semibold tracking-widest transition focus:outline-none focus:ring disabled:opacity-25"
        x-bind:class="listening ? 
            'bg-white border active:text-white hover:text-white hover:bg-success-500 text-success-500 border-success-500 focus:ring-opacity-50 focus:border-success-500 active:bg-success-400 focus:ring-success-400' : 
            'bg-white border active:text-white hover:text-white hover:bg-primary-500 text-primary-500 border-primary-500 focus:ring-opacity-50 focus:border-primary-500 active:bg-primary-400 focus:ring-primary-400'
        "
        x-on:click="startListening"
    >
        <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
        </svg>
    </button>
</div>