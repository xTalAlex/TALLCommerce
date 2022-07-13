<div>
    <div class="mt-5">
        <x-jet-danger-button wire:click="confirmPayment" wire:loading.attr="disabled">
            {{ __('Complete Payment') }}
        </x-jet-danger-button>
    </div>

    <div class="mx-auto text-center"> {{ __('or')}}</div>

    <div
        x-data="{
            total : {{ $total }}
        }"
        x-init="
            window.paypalLoadScript({ 
                'client-id': '{{ config('services.paypal.client') }}', 
                currency: 'EUR' 
            })
            .then((paypal) => {
                paypal
                    .Buttons({

                        style: {
                            layout: 'vertical',
                            color:  'gold',
                            shape:  'rect',
                            label:  'pay',
                        },

                        createOrder: (data, actions) => {
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: total,
                                    }
                                }]
                            });
                        },

                        onApprove: (data, actions) => {
                            return actions.order.capture().then(function(orderData) {
                                console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                                const transaction = orderData.purchase_units[0].payments.captures[0];
                                alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
                                // Go to another URL:  actions.redirect('thank_you.html');
                            });
                        }
                        
                    })
                    .render('#paypal-buttons')
                    .catch((error) => {
                        console.error('failed to render the PayPal Buttons', error);
                    });
            })
            .catch((error) => {
                console.error('failed to load the PayPal JS SDK script', error);
            });
        "
    >
        <div id="paypal-buttons"></div>
    </div>

    @if($intent)
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