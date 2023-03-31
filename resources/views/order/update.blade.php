@php
    if($errors->has('shipping_address.*') || $errors->has('order.email') || $errors->has('order.phone') || $errors->has('order.note'))
        $shownStep = 1;
    elseif($errors->has('billing_address.*') || $errors->has('order.fiscal_code') || $errors->has('order.vat'))
        $shownStep = 2;
@endphp

<x-slot name="seo">
    {!! 
        seo(new RalphJSmit\Laravel\SEO\Support\SEOData(
                title: __('Order Update').' #'.$order->number 
            )) 
    !!}
</x-slot>

<x-slot name="header">
    <h1 class="mb-4 text-3xl font-bold">
        {{ __('Order Update').' #'.$order->number }} 
    </h1>
</x-slot>

<div class="mx-auto max-w-7xl">
    <div class="w-full px-6 py-8 overflow-hidden lg:px-8 md:flex">

        <div class="w-full pb-12 md:1/2 lg:w-2/3 md:pr-12 md:pb-0"
            x-data="{ 
                selected: @entangle('shownStep'),
                addresses_confirmed : @entangle('addresses_confirmed'),
            }"
            x-init="
                Livewire.on('orderCreated', () => {
                    if(selected != 4)
                        selected = 0;
                });
            "
        >
                    
            <div class="relative border-b border-gray-200">
                <button class="w-full pb-6"
                    x-bind:disabled="addresses_confirmed"
                    @click="selected !== 1 ? selected = 1 : selected = 0">
                    <div class="flex items-center justify-between">
                        <span @class([
                                'font-semibold',
                                'text-danger-500' => $errors->has('shipping_address.*') || $errors->has('order.email') || $errors->has('order.phone') || $errors->has('order.note')
                            ])
                        >
                            {{ __('Shipping Address') }}
                        </span>
                        <span x-show="!addresses_confirmed">
                            <span x-show="selected != 1" x-cloak><x-icons.plus /></span>
                            <span x-show="selected == 1" x-cloak><x-icons.minus /></span>
                        </span>
                    </div>

                    @if($shipping_address->label)
                    <div class="flex flex-col justify-center py-2 text-sm text-left text-gray-500"
                        x-show="selected != 1"
                        x-transition:enter.delay.200ms
                        x-cloak
                    >
                        {!! $shipping_address->label !!}
                        <div>{{ $order->phone }}</div>
                        @if($order->note)
                            <div class="mt-2">"{{ $order->note }}"</div>
                        @endif
                    </div>
                    @endif
                </button>

                    <div class="relative overflow-hidden transition-all duration-300 max-h-0"
                    x-ref="container1"
                    x-bind:style="selected == 1 ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : ''"
                >
                    @if(!$addresses_confirmed)
                    <div class="py-6 space-y-2">
                        <x-input-floating @class(['hidden' => Auth::check()]) label="{{ __('Email') }}" name="email" wire:model.lazy="order.email"/>
                        <div class="grid gap-2 xl:grid-cols-2 xl:gap-6">
                            <div>
                                <x-input-floating label="{{ __('Full Name') . ' / ' . __('Company') }}" name="shipping_address_full_name" wire:model.lazy="shipping_address.full_name"/>
                                <x-jet-input-error class="mb-4" for="shipping_address.full_name"/>
                            </div>
                            <div>
                                <x-input-floating label="{{ __('Phone Number') }}" name="phone" wire:model.lazy="order.phone"/>
                                <x-jet-input-error class="mb-4" for="order.phone"/>
                            </div>
                        </div>
                        <div>
                            <x-input-floating label="{{ __('Address') }}" name="shipping_address_address" wire:model.lazy="shipping_address.address"/>
                            <x-jet-input-error class="mb-4" for="shipping_address.address"/>
                        </div>
                        <div class="grid gap-2 xl:grid-cols-2 xl:gap-6">
                            <div>
                                <x-input-floating label="{{ __('City') }}" name="shipping_address_city" wire:model.lazy="shipping_address.city"/>
                                <x-jet-input-error class="mb-4" for="shipping_address.city"/>
                            </div>
                            <div>
                                <x-province-select floating label="{{ __('Province') }}" active name="shipping_address_province" wire:model.lazy="shipping_address.province"/>
                                <x-jet-input-error class="mb-4" for="shipping_address.province"/>
                            </div>
                        </div>
                        <div class="grid gap-2 xl:grid-cols-2 xl:gap-6">
                            <div>
                                <x-input-floating label="{{ __('Postal Code') }}" maxlength="5" name="shipping_address_postal_code" wire:model.lazy="shipping_address.postal_code"/>
                                <x-jet-input-error class="mb-4" for="shipping_address.postal_code"/>
                            </div>
                            <div>
                                <x-country-select floating label="{{ __('Country/Region') }}" name="shipping_address_country_region" wire:model.lazy="shipping_address.country_region"/>
                                <x-jet-input-error class="mb-4" for="shipping_address.country_region"/>
                            </div>
                        </div>

                        <div class="mt-2">
                            <x-textarea label="{{ __('Note') }}" resize="none" name="note" rows="4" maxlength="255" wire:model.lazy="order.note"></x-textarea>
                            <x-jet-input-error class="mb-4" for="order.note"/>
                        </div>

                        <div class="items-center pt-4 space-x-2 md:flex md:justify-between">
                            <div class="flex items-center mb-6 md:mb-0">
                                @if(!$shipping_address->sameAddress($billing_address))
                                    <x-secondary-button class="w-full md:w-auto"
                                        wire:click="copyAddress()"
                                    >{{ __('Use as billing address') }}</x-secondary-button>
                                @endif
                            </div>

                            @auth
                                @if( !auth()->user()->defaultAddress?->sameAddress($shipping_address) )
                                    <x-secondary-button ghost="true" class="w-full md:w-auto" wire:click.prevent='updateDefaultShippingAddress'
                                    >{{ __('Save as default') }}</x-secondary-button>
                                @endif
                            @endauth
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="relative border-b border-gray-200">
                <button class="w-full py-6"
                    x-bind:disabled="addresses_confirmed"
                    @click="selected !== 2 ? selected = 2 : selected = 0">
                    <div class="flex items-center justify-between">
                        <span @class([
                                'font-semibold',
                                'text-danger-500' => $errors->has('billing_address.*') || $errors->has('order.fiscal_code') || $errors->has('order.vat'),
                            ])
                        >
                            {{ __('Billing Address') }}
                        </span>
                        <span x-show="!addresses_confirmed">
                            <span x-show="selected != 2" x-cloak><x-icons.plus /></span>
                            <span x-show="selected == 2" x-cloak><x-icons.minus /></span>
                        </span>
                    </div>
                                                        
                    @if($billing_address->label)  
                    <div class="flex flex-col justify-center py-2 text-sm text-left text-gray-500"
                        x-show="selected != 2"
                        x-transition:enter.delay.200ms
                        x-cloak
                    > 
                        <div>
                            {{ __('Fiscal Code')}}: {{ $order->fiscal_code ? $order->fiscal_code : '-' }}
                        </div>
                        <div>
                            {{ __('VAT')}}: {{ $order->vat ? $order->vat : '-' }}
                        </div>
                        <div class="mt-2">
                            {!! $billing_address->label !!}
                        </div>
                    </div>
                    @endif
                </button>
                
                <div class="relative overflow-hidden transition-all duration-300 max-h-0"
                    x-ref="container2"
                    x-bind:style="selected == 2 ? 'max-height: ' + $refs.container2.scrollHeight + 'px' : ''"
                >
                    @if(!$addresses_confirmed)
                    <div class="py-6 space-y-2">
                        <div class="grid gap-2 xl:grid-cols-2 xl:gap-6">
                            <div>
                                <x-input-floating label="{{ __('Full Name') . ' / ' . __('Company') }}" name="billing_address_full_name" wire:model.lazy="billing_address.full_name"/>
                                <x-jet-input-error class="mb-4" for="billing_address.full_name"/>
                            </div>
                        </div>
                        <div class="grid gap-2 xl:grid-cols-2 xl:gap-6">
                            <div>
                                <x-input-floating label="{{ __('Fiscal Code') }}" name="fiscal_code" wire:model.lazy="order.fiscal_code"/>
                                <x-jet-input-error class="mb-4" for="order.fiscal_code"/>
                            </div>   
                            <div>
                                <x-input-floating label="{{ __('VAT') }}" name="vat" wire:model.lazy="order.vat"/>
                                <x-jet-input-error class="mb-4" for="order.vat"/>
                            </div>   
                        </div>
                        <div>
                            <div>
                                <x-input-floating label="{{ __('Address') }}" name="billing_address_address" wire:model.lazy="billing_address.address"/>
                                <x-jet-input-error class="mb-4" for="billing_address.address"/>
                            </div>                        </div>
                        <div class="grid gap-2 xl:grid-cols-2 xl:gap-6">
                            <div>
                                <x-input-floating label="{{ __('City') }}" name="billing_address_city" wire:model.lazy="billing_address.city"/>
                                <x-jet-input-error class="mb-4" for="billing_address.city"/>
                            </div>
                            <div>
                                <x-province-select floating label="{{ __('Province') }}" name="billing_address_province" wire:model.lazy="billing_address.province"/>
                                <x-jet-input-error class="mb-4" for="billing_address.province"/>
                            </div>
                        </div>
                        <div class="grid gap-2 xl:grid-cols-2 xl:gap-6">
                            <div>
                                <x-input-floating label="{{ __('Postal Code') }}" maxlength="5" name="billing_address_postal_code" wire:model.lazy="billing_address.postal_code"/>
                                <x-jet-input-error class="mb-4" for="billing_address.postal_code"/>
                            </div>
                            <div>
                                <x-country-select floating label="{{ __('Country/Region') }}" name="billing_address_country_region" wire:model.lazy="billing_address.country_region"/>
                                <x-jet-input-error class="mb-4" for="billing_address.country_region"/>
                            </div>
                        </div>

                        <div class="items-center pt-4 md:flex md:justify-end">
                            @auth
                                @if( !auth()->user()->defaultAddress?->sameAddress($billing_address) 
                                    || auth()->user()->vat != $order->vat
                                    || auth()->user()->fiscal_code != $order->fiscal_code 
                                    )
                                    <x-secondary-button ghost="true" class="w-full md:w-auto" wire:click.prevent='updateDefaultBillingAddress'
                                    >{{ __('Save as default') }}</x-secondary-button>
                                @endif
                            @endauth
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($addresses_confirmed)
            <x-secondary-button ghost="true" class="w-full py-4 mt-12 text-base" wire:click.prevent="$set('addresses_confirmed',false)"
            >{{ __('Edit') }}</x-secondary-button>
            @endif      
            
        </div>

        <div class="w-full md:1/2 lg:w-1/3">
            <x-price-total
                :subtotal="$order->subtotal"
                :discounted-subtotal="$order->subtotal - $order->coupon_discount"
                :original-total="$order->total + $order->coupon_discount"
                :tax="$order->tax"
                :total="$order->total"
                :coupon="$order->coupon"
                :shipping="$order->shippingPrice"
                :shipping-price="$order->shipping_price"
            >
                <x-slot:actions>
                    @if(!$addresses_confirmed && $order->canBeEdited())
                        <x-button class="w-full py-4 text-base" wire:click.prevent="updateAddresses"
                        >{{ __('Update Addresses') }}</x-button>
                    @endif
                    @if($addresses_confirmed && $order->canBePaid())
                        <livewire:checkout :order="$order" update="true"/>
                    @endif
                </x-slot>
            </x-price-total>
        </div>
        
    </div>
</div>