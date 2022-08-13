<div>
    <div class="mt-1">
        <x-jet-danger-button class="rounded-[4px]" wire:click="confirmPayment" wire:loading.attr="disabled">
            {{ __('Complete Payment') }}
        </x-jet-danger-button>
    </div>

    <div class="mt-1"
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

                                    shipping: {
                                        shipping_detail : {
                                            name: {
                                                full_name: 'Hans Muller'
                                            },
                                            address: {
                                                address_line_1: 'MyStreet 12',
                                                admin_area_2: 'New York',
                                                postal_code: '10001',
                                                country_code: 'US',
                                            }
                                        }
                                    }
                                    
                                }]
                            });
                        },

                        onApprove: (data, actions) => {
                            return actions.order.capture()
                                .then(function(orderData) {
                                    if(orderData.status=='COMPLETED'){
                                        $wire.set('gateway','paypal');
                                        $wire.set('intent.id',orderData.id);
                                        $wire.submitPayment();
                                    }
                                    else{
                                        return actions.restart();
                                    }
                                });
                        },

                        onError: function (err) {
                            console.log('qualcosa non torna :'+err);
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
        <div id="paypal-buttons" class="relative z-0"></div>
    </div>

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
                            error = (await this.stripe.confirmPayment({
                                elements : this.elements,
                                confirmParams: {
                                    return_url: '{{ route('stripe.handle.checkout.response') }}',
                                },
                            })).error;
                            if(error) {
                                this.errorMessage = error.message;
                                @this.set('submitDisabled', false);
                            } else {
                                console.log('qualcosa non torna');
                            }
                        }
                    }"

                    x-init="
                        options = {
                            clientSecret: '{{ $intent['client_secret'] }}',
                            appearance : {
                                theme: 'stripe',
                                labels: 'floating',
                                variables: {
                                },
                            },
                            loader : 'always',
                        };
                        elements = stripe.elements(options);
                        paymentElement = elements.create('payment');
                        paymentElement.mount('#payment-element');
                        Livewire.on('paymentConfirmed', () =>{
                            submit();
                        });
                    "
                    wire:ignore
                >
                    <div id="payment-element">
                        <!-- Elements will create form elements here -->
                    </div>
                    <div id="error-message" x-text="errorMessage">
                        <!-- Display error message to your customers here -->
                    </div>
                </form>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingPayment')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button wire:click="submitPayment" :disabled="$submitDisabled" class="ml-3" wire:loading.attr="disabled">
                    {{ __('Confirm') }}
                </x-jet-danger-button>
            </x-slot>
        </div>
    </x-jet-dialog-modal>
    @endif
</div>