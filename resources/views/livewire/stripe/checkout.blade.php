<div>
    <div class="mt-5">
        <x-jet-danger-button wire:click="confirmPayment" wire:loading.attr="disabled">
            {{ __('Pay with Card') }}
        </x-jet-danger-button>
    </div>

    @if($intent)
    <!-- Delete User Confirmation Modal -->
    <div wire:model="confirmingPayment">
        <x-slot name="title">
            {{ __('Stripe Checkout') }}
        </x-slot>

        <div
        >
            <div name="content">
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
                            } else {}
                        }
                    }"

                    x-init="
                        options = {
                            clientSecret: '{{ $intent['client_secret'] }}',
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
            </div>

            <div name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingPayment')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button wire:click="submitPayment" :disabled="$submitDisabled" id="submit" class="ml-3" wire:loading.attr="disabled">
                    {{ __('Submit') }}
                </x-jet-danger-button>
            </div>
        </div>
    </div>
    @endif
</div>
