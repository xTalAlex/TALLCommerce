@php
    if($errors->has('shipping_address.*') || $errors->has('email') || $errors->has('phone') || $errors->has('note'))
        $shownStep = 1;
    elseif($errors->has('billing_address.*') || $errors->has('fiscal_code') || $errors->has('vat'))
        $shownStep = 2;
    elseif($errors->has('shipping_price.*'))
        $shownStep = 3;
    elseif($errors->has('coupon.*'))
        $shownStep = 4;
@endphp

<x-slot name="seo">
    {!! 
        seo(new RalphJSmit\Laravel\SEO\Support\SEOData(
                title: __('Checkout') 
            )) 
    !!}
</x-slot>

<x-slot name="header">
    <h1 class="mb-4 text-3xl font-bold">
        {{ __('Checkout') }}
    </h1>
    @guest
    <div class="mb-12 text-gray-500">
        <a class="underline transition hover:text-gray-900" href="{{ route('order.login') }}">{{ __('Already registered?') }}</a>
    </div>
    @endguest
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
                                'text-danger-500' => $errors->has('shipping_address.*') || $errors->has('email') || $errors->has('phone') || $errors->has('note')
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
                        <div>{{ $phone }}</div>
                        @if($note)
                            <div class="mt-2">"{{ $note }}"</div>
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
                        <x-input-floating @class(['hidden' => Auth::check()]) label="{{ __('Email') }}" name="email" wire:model.lazy="email"/>
                        <div class="grid gap-2 xl:grid-cols-2 xl:gap-6">
                            <div>
                                <x-input-floating label="{{ __('Full Name') . ' / ' . __('Company') }}" name="shipping_address_full_name" wire:model.lazy="shipping_address.full_name"/>
                                <x-jet-input-error class="mb-4" for="shipping_address.full_name"/>
                            </div>
                            <div>
                                <x-input-floating label="{{ __('Phone Number') }}" name="phone" wire:model.lazy="phone"/>
                                <x-jet-input-error class="mb-4" for="phone"/>
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
                            <x-textarea label="{{ __('Note') }}" resize="none" name="note" rows="4" maxlength="255" wire:model.lazy="note"></x-textarea>
                            <x-jet-input-error class="mb-4" for="note"/>
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
                                'text-danger-500' => $errors->has('billing_address.*') || $errors->has('fiscal_code') || $errors->has('vat'),
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
                            {{ __('Fiscal Code')}}: {{ $fiscal_code ? $fiscal_code : '-' }}
                        </div>
                        <div>
                            {{ __('VAT')}}: {{ $vat ? $vat : '-' }}
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
                                <x-input-floating label="{{ __('Fiscal Code') }}" name="fiscal_code" wire:model.lazy="fiscal_code"/>
                                <x-jet-input-error class="mb-4" for="fiscal_code"/>
                            </div>   
                            <div>
                                <x-input-floating label="{{ __('VAT') }}" name="vat" wire:model.lazy="vat"/>
                                <x-jet-input-error class="mb-4" for="vat"/>
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
                                    || auth()->user()->vat != $vat
                                    || auth()->user()->fiscal_code != $fiscal_code 
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
            
            <div class="relative border-b border-gray-200">
                <button class="w-full py-6"
                    x-bind:disabled="addresses_confirmed"
                    @click="selected != 3 ? selected = 3 : selected = 0">
                    <div class="flex items-center justify-between">
                        <span @class([
                                'font-semibold',
                                'text-danger-500' => $errors->has('shipping_price.*'),
                            ])
                        >
                            {{ __('Shipping') }}
                        </span>
                        <span x-show="!addresses_confirmed">
                            <span x-show="selected != 3" x-cloak><x-icons.plus /></span>
                            <span x-show="selected == 3" x-cloak><x-icons.minus /></span>
                        </span>
                    </div>
                    
                    @if($shipping_price)
                    <div class="flex py-2"
                        x-show="selected != 3"
                        x-transition:enter.delay.200ms
                        x-cloak
                    >
                        <div class="flex text-sm text-gray-500">
                            <div class="font-semibold">{{ $shipping_price->name }}</div>
                            <p class="ml-2">{{ $shipping_price->description }}</p>
                        </div>
                    </div>
                    @endif
                        
                </button>

                <div class="relative overflow-hidden transition-all duration-300 max-h-0"
                    x-ref="container3"
                    x-bind:style="selected == 3 ? 'max-height: ' + $refs.container3.scrollHeight + 'px' : ''"
                >
                    @if(!$addresses_confirmed)	
                    <div class="grid gap-2 px-2 py-6 lg:grid-cols-2 xl:grid-cols-3 place-items-center">
                        @foreach($shipping_prices as $option)
                            <input class="hidden" type="radio"
                                x-ref="shipping{{$option->id}}" 
                                wire:model="shipping_price.id" 
                                value="{{ $option->id }}"
                            >
                            <div @class([
                                    'inline-block p-4 mx-auto shadow-md cursor-pointer w-full text-left h-full flex flex-col justify-between',
                                    'ring ring-primary-500 ring-opacity-50' => $shipping_price->id == $option->id,
                                ])
                                x-on:click="$refs.shipping{{$option->id}}.click()"
                            >
                                <div class="mb-2 text-sm font-semibold">{{ $option->name }}</div>
                                @if($option->description)
                                <div class="text-sm text-gray-500">{{ $option->description }}</div>
                                @endif
                                @if($option->deliveryTimeLabel())
                                <div class="text-sm text-gray-500">{{ $option->deliveryTimeLabel() }}</div>
                                @endif
                                @if($option->min_spend)
                                <div class="text-sm text-gray-500">{{ __('Minimum spend :amount€',['amount' => $option->min_spend]) }}</div>
                                @endif
                                <div class="pt-2 mt-auto mb-0 font-black text-right">{{ $option->price }}€</div>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <div class="relative border-b border-gray-200">
                <button class="w-full py-6"
                    x-bind:disabled="addresses_confirmed"
                    @click="selected !== 4 ? selected = 4 : selected = 0">
                    <div class="flex items-center justify-between">
                        <span @class([
                                'font-semibold',
                                'text-danger-500' => $errors->has('coupon.*'),
                            ])
                        >
                            {{ __('Coupon') }}
                        </span>
                        <span x-show="!addresses_confirmed">
                            <span x-show="selected != 4" x-cloak><x-icons.plus /></span>
                            <span x-show="selected == 4" x-cloak><x-icons.minus /></span>
                        </span>
                    </div>
                    
                    @if($coupon) 
                    <div class="flex py-2"
                        x-show="selected != 4"
                        x-transition:enter.delay.200ms
                        x-cloak
                    >
                        <div class="flex text-sm text-gray-500">
                            <div class="font-semibold">{{ $coupon->code }}</div>
                            <p class="ml-2">{{ $coupon->label }}</p>
                        </div>
                    </div>
                    @endif
                        
                </button>

                <div class="relative overflow-hidden transition-all duration-300 max-h-0" style=""
                    x-ref="container4"
                    x-bind:style="selected == 4 ? 'max-height: ' + $refs.container4.scrollHeight + 'px' : ''"
                >
                    @if(!$addresses_confirmed)
                    <div class="flex justify-center py-6">
                        <div class="flex flex-nowrap">
                            <x-input @class([
                                    "disabled:bg-gray-50 font-bold placeholder-gray-500",
                                    "text-danger-500" => $coupon_error
                                ]) type="text" 
                                placeholder="{{ __('Coupon Code') }}"
                                disabled="{{ $coupon!=null }}"
                                x-data="{}"
                                wire:model.lazy="coupon_code"
                                x-on:input="$event.target.value=$event.target.value.toUpperCase()"
                            ></x-input>
                            @if($coupon)
                            <x-secondary-button wire:click="removeCoupon"
                            ><x-icons.x/></x-secondary-button>
                            @else
                            <x-secondary-button wire:click="refreshTotals"
                            >{{ __('Check') }}</x-secondary-button>
                            @endif
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
                :subtotal="$subtotal"
                :discounted-subtotal="$discounted_subtotal"
                :original-total="$original_total"
                :tax="$tax"
                :total="$total + (optional($shipping_price)->price ?? 0)"
                :coupon="$coupon"
                :shipping="$shipping_price"
                :shipping-price="optional($shipping_price)->price"
                :products="$cartContent"
            >
                <x-slot:actions>
                    @if($addresses_confirmed)
                        @if($shipping_price->min_spend <= $total)
                            <livewire:checkout :order="$order"/>
                        @else
                            <div class="w-full px-2 py-4 text-base text-center cursor-pointer bg-danger-500">
                                {{ trans_choice('shopping_cart.checkout.min_spend', null, [ 'amount' => number_format($shipping_price->min_spend,2).'€' ]) }}
                            </div>
                        @endif
                    @else
                        <x-button class="w-full py-4 text-base" wire:click.prevent='createOrder'
                        >{{ __('Confirm Order') }}</x-button>
                        @error('*')
                            <p class="w-full px-2 py-2 mt-4 text-sm text-center bg-red-50 text-danger-500">{{ __('Whoops! Something went wrong.') }}</p>
                        @enderror
                    @endif
                </x-slot>
            </x-price-total>
        </div>

    </div>
</div>