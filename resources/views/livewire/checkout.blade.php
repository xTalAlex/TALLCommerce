<div class="w-full">

    <x-button class="w-full py-4 text-base" wire:click="confirmPayment" wire:loading.attr="disabled">
        {{ __('Complete Payment') }}
    </x-button>

    {{-- <div class="mt-1"
        wire:ignore
        x-data="{
            total : {{ $total }}
        }"
        x-init="
            window.paypalLoadScript({ 
                'client-id': '{{ config('services.paypal.client') }}', 
                currency: 'EUR',
                components : 'buttons,funding-eligibility',
            })
            .then((paypal) => {
                paypal
                    .Buttons({

                        fundingSource: paypal.FUNDING.PAYPAL,

                        style: {
                            layout: 'vertical',
                            color:  'gold',
                            shape:  'rect',
                            label:  'pay',
                            height: 52
                        },

                        createOrder: (data, actions) => {
                            return actions.order.create({

                                application_context : {
                                    shipping_preference : 'NO_SHIPPING',
                                },

                                purchase_units: [{
                                    amount: {
                                        value: total,
                                    },                                    
                                }]
                            });
                        },

                        onApprove: (data, actions) => {
                            return {
                                //check if products are avaiable
                                //get total
                                actions.order.capture()
                                .then(function(orderData) {
                                    if(orderData.status=='COMPLETED'){
                                        $wire.set('gateway','paypal');
                                        $wire.set('intent.id',orderData.id);
                                        $wire.storePayment();
                                    }
                                    else{
                                        return actions.restart();
                                    }
                                });
                            }
                        },

                        onError: function (err) {
                            console.log('PayPal payment error :'+err);
                        },
                        
                    })
                    .render('#paypal-buttons')
                    .catch((error) => {
                        console.error('failed to render the PayPal Buttons', error);
                    });
            })
            .catch((error) => {
                console.error('failed to load the PayPal JS SDK script', error);
            });
            Livewire.on('paymentConfirmed', () =>{
                $wire.redirectToSuccess();
            });
        "
    >
        <div id="paypal-buttons" class="relative z-0 w-full"></div>
    </div> --}}

    @if($gateway=='stripe' && $intent)
    <!-- Delete User Confirmation Modal -->
    <x-jet-dialog-modal wire:model="confirmingPayment">
        <x-slot name="title">
            {{ __('Stripe Checkout') }}
        </x-slot>

        <div
        >
            <x-slot name="content">
                <form id="payment-form" data-secret="{{ $intent['client_secret'] }}"
                    x-data="{
                        stripe : Stripe('{{ config('services.stripe.key') }}'),
                        options : null,
                        elements : null,
                        errorMessage : null,
                        async submit(){
                            res = (await this.stripe.confirmPayment({
                                elements : this.elements,
                                confirmParams: {},
                                redirect: 'if_required'
                            }));
                            if(res.error) {
                                this.errorMessage = res.error.message;
                                $wire.set('submitDisabled', false);
                            } else {
                                $wire.storePayment();
                            }
                        }
                    }"

                    x-init="
                        options = {
                            clientSecret: '{{ $intent['client_secret'] }}',
                            appearance : {
                                theme: 'flat',
                                labels: 'floating',
                                variables: {
                                    colorPrimary: '#F6787C',
                                    colorDanger: '#C83030',
                                    borderRadius: '0px',
                                },
                            },
                            loader : 'always',
                        };
                        elements = stripe.elements(options);
                        paymentElement = elements.create('payment');
                        paymentElement.mount('#payment-element');
                        Livewire.on('submitPayment', () =>{
                            submit();
                        });
                    "
                    wire:ignore
                >
                    <div id="payment-element">
                        <!-- Elements will create form elements here -->
                    </div>
                    <div class="mt-2 text-sm text-danger-500" id="error-message" x-text="errorMessage">
                        <!-- Display error message to your customers here -->
                    </div>
                </form>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingPayment')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button wire:click="attemptSubmit" :disabled="$submitDisabled" class="ml-3" wire:loading.attr="disabled">
                    {{ __('Confirm') }}
                    <x-icons.spinner @class([
                        'w-4 h-4 ml-1 text-white',
                        'hidden' => !$submitDisabled
                    ])/>
                </x-button>
            </x-slot>
        </div>
    </x-jet-dialog-modal>
    @endif
</div>