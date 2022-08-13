@php
    $activeClass = 'text-primary-600 bg-gray-100 dark:bg-gray-800 dark:text-primary-500 active';
    $defaultClass = 'text-gray-400 dark:text-gray-500';
@endphp
<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Checkout') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="mx-auto md:flex max-w-7xl sm:px-6 lg:px-8">

        <div class="flex w-full overflow-hidden bg-white shadow-xl sm:rounded-lg">
        
            <div class="md:w-2/3">

                <div class="px-8 pt-6 pb-12" 
                    x-data="{ 
                        selected: {{ $addresses_confirmed ? 'null' : 1 }},
                        same_address : @entangle('same_address'),
                        addresses_confirmed : @entangle('addresses_confirmed'),
                    }"
                >
                    <x-jet-validation-errors class="my-4 mb-4" />

                    @if($errors->has('email'))
                        <a href="{{ route('order.login') }}">{{ __('Already registered?') }}</a>
                    @endif

                    <form action="" method="POST"
                    >
                    @csrf
                            
                        <div class="relative border-b-2 border-gray-200">
                            <button type="button" class="w-full py-6 text-left"
                                x-bind:disabled="addresses_confirmed"
                                @click="selected !== 1 ? selected = 1 : selected = null">
                                <div class="flex items-center justify-between">
                                    <span @class([
                                            'text-lg font-semibold text-gray-900',
                                            'text-red-500' => $errors->has('shipping_address.*'),
                                        ])
                                    >
                                        {{ __('Shipping Address') }}
                                    </span>
                                    <span
                                        x-show="!addresses_confirmed"
                                    >
                                        <x-icons.plus/>
                                    </span>
                                </div>

                                
                                @if($shipping_address->label)
                                <div class="flex items-center p-2"
                                    x-show="selected != 1"
                                    x-transition:enter.delay.200ms
                                    x-cloak
                                >
                                    {!! $shipping_address->label !!}
                                </div>
                                @endif
                            </button>

                            <div class="relative overflow-hidden transition-all duration-300 max-h-0" style=""
                                x-ref="container1"
                                x-bind:style="selected == 1 ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : ''"
                            >
                                <div class="p-6">
                                <div @class([
                                    'relative z-0 w-full mb-6 group',
                                    'hidden' => Auth::check()
                                    ])>
                                    <x-input-floating label="{{ __('Email') }}" name="shipping_address_email" wire:model.lazy="shipping_address.email"/>
                                </div>
                                <div class="grid xl:grid-cols-2 xl:gap-6">
                                    <div class="relative z-0 w-full mb-6 group">
                                        <x-input-floating label="{{ __('Full Name') }}" name="shipping_address_full_name" wire:model.lazy="shipping_address.full_name"/>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <x-input-floating label="{{ __('Company') }}" name="shipping_address_company" wire:model.lazy="shipping_address.company"/>
                                    </div>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <x-input-floating label="{{ __('Address') }}" name="shipping_address_address" wire:model.lazy="shipping_address.address"/>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <x-input-floating label="{{ __('Address2') }}" name="shipping_address_address2" wire:model.lazy="shipping_address.address2"/>
                                </div>
                                <div class="grid xl:grid-cols-2 xl:gap-6">
                                    <div class="relative z-0 w-full mb-6 group">
                                        <x-input-floating label="{{ __('City') }}" name="shipping_address_city" wire:model.lazy="shipping_address.city"/>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <x-input-floating label="{{ __('Province') }}" name="shipping_address_province" wire:model.lazy="shipping_address.province"/>
                                    </div>
                                </div>
                                <div class="grid xl:grid-cols-2 xl:gap-6">
                                    <div class="relative z-0 w-full mb-6 group">
                                        <x-input-floating label="{{ __('Country/Region') }}" name="shipping_address_country_region" wire:model.lazy="shipping_address.country_region"/>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <x-input-floating label="{{ __('Postal Code') }}" name="shipping_address_postal_code" wire:model.lazy="shipping_address.postal_code"/>
                                    </div>
                                </div>

                                <div class="relative z-0 w-full mb-6 group">  
                                    <textarea id="note" name="note" rows="4" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer" 
                                        wire:model.lazy="note" placeholder=" "></textarea>
                                    <label for="note" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                        >{{ __('Note') }}</label>
                                </div>

                                <div class="mt-6 md:flex md:justify-between">
                                    <div class="flex items-center mb-6">
                                        <input id="same_address" name="same_address" type="checkbox" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            wire:model="same_address"  value=""
                                        >
                                        <label for="same_address" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Use as billing address') }}</label>
                                    </div>

                                    @auth
                                        <button type="button" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                            wire:click.prevent='updateDefaultAddress'
                                        >{{ __('Save as default') }}</button>
                                    @endauth
                                    
                                </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative border-b-2 border-gray-200">

                            <button type="button" class="w-full py-6 text-left"
                                x-bind:disabled="same_address || addresses_confirmed"
                                @click="selected !== 2 ? selected = 2 : selected = null">
                                <div class="flex items-center justify-between">
                                    <span @class([
                                            'text-lg font-semibold text-gray-900',
                                            'text-red-500' => $errors->has('billing_address.*'),
                                        ])
                                    >
                                        {{ __('Billing Address') }}
                                    </span>
                                    <span
                                        x-show="!same_address && !addresses_confirmed"
                                    >
                                        <x-icons.plus/>
                                    </span>
                                </div>
                                                                  
                                @if( ($same_address && $shipping_address->label) || $billing_address->label)  
                                <div class="flex items-center p-2"
                                    x-show="selected != 2"
                                    x-transition:enter.delay.200ms
                                    x-cloak
                                > 
                                    {!! $billing_address->label ?? $shipping_address->label !!}
                                </div>
                                @endif
                            </button>
                            
                            <div class="relative overflow-hidden transition-all duration-300 max-h-0" style=""
                                x-ref="container2"
                                x-bind:style="selected == 2 ? 'max-height: ' + $refs.container2.scrollHeight + 'px' : ''"
                            >
                                <div class="p-6">
                                <div class="grid xl:grid-cols-2 xl:gap-6">
                                    <div class="relative z-0 w-full mb-6 group">
                                        <x-input-floating label="{{ __('Full Name') }}" name="billing_address_full_name" wire:model.lazy="billing_address.full_name"/>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <x-input-floating label="{{ __('Company') }}" name="billing_address_company" wire:model.lazy="billing_address.company"/>
                                    </div>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <x-input-floating label="{{ __('Address') }}" name="billing_address_address" wire:model.lazy="billing_address.address"/>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <x-input-floating label="{{ __('Address2') }}" name="billing_address_address2" wire:model.lazy="billing_address.address2"/>
                                </div>
                                <div class="grid xl:grid-cols-2 xl:gap-6">
                                    <div class="relative z-0 w-full mb-6 group">
                                        <x-input-floating label="{{ __('City') }}" name="billing_address_city" wire:model.lazy="billing_address.city"/>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <x-input-floating label="{{ __('Province') }}" name="billing_address_province" wire:model.lazy="billing_address.province"/>
                                    </div>
                                </div>
                                <div class="grid xl:grid-cols-2 xl:gap-6">
                                    <div class="relative z-0 w-full mb-6 group">
                                        <x-input-floating label="{{ __('Country/Region') }}" name="billing_address_country_region" wire:model.lazy="billing_address.country_region"/>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <x-input-floating label="{{ __('Postal Code') }}" name="billing_address_postal_code" wire:model.lazy="billing_address.postal_code"/>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="relative border-b-2 border-gray-200">

                            <button type="button" class="w-full py-6 text-left"
                                x-bind:disabled="addresses_confirmed"
                                @click="selected !== 3 ? selected = 3 : selected = null">
                                <div class="flex items-center justify-between">
                                    <span @class([
                                            'text-lg font-semibold text-gray-900',
                                            'text-red-500' => $errors->has('selected_shipping_address.*'),
                                        ])
                                    >
                                        {{ __('Shipping') }}
                                    </span>
                                    <span
                                        x-show="!addresses_confirmed"
                                    >
                                        <x-icons.plus/>
                                    </span>
                                </div>
                                
                                <div class="flex p-2"
                                    x-show="selected != 3"
                                    x-transition:enter.delay.200ms
                                    x-cloak
                                >
                                    <div class="inline-block w-48 p-2 text-left border-4 border-transparent rounded-md shadow-md cursor-pointer"
                                    >
                                        <div class="mb-2 text-sm font-bold">{{ $shipping_price->name }}</div>
                                        <p class="">{{ $shipping_price->description }}</p>
                                        <p class="mt-2 text-right">{{ $shipping_price->price }}€</p>
                                    </div>
                                </div>
                                    
                            </button>

                            <div class="relative overflow-hidden transition-all duration-300 max-h-0" style=""
                                x-ref="container3"
                                x-bind:style="selected == 3 ? 'max-height: ' + $refs.container3.scrollHeight + 'px' : ''"
                            >
                                <div class="w-full p-6 space-x-4 space-y-2 text-center">
                                    @foreach($shipping_prices as $option)
                                    <input class="hidden" type="radio"
                                        x-ref="shipping{{$option->id}}" 
                                        wire:model="shipping_price.id" 
                                        value="{{ $option->id }}"
                                    >
                                    <div @class([
                                            'inline-block p-2 mx-auto rounded-md shadow-md cursor-pointer w-48 border-4 border-transparent text-left',
                                            'border-primary-500' => $shipping_price->id == $option->id
                                        ])
                                        x-on:click="$refs.shipping{{$option->id}}.click()"
                                    >
                                        <div class="mb-2 text-sm font-bold">{{ $option->name }}</div>
                                        <p class="">{{ $option->description }}</p>
                                        <p class="mt-2 text-right">{{ $option->price }}€</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        @if($addresses_confirmed)
                        <button type="button" class="mt-5 text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                            wire:click.prevent="$set('addresses_confirmed',false)"
                        >{{ __('Modify') }}</button>
                        @endif

                    </form>
                </div>
            </div>

            <div class="md:w-1/3">
                <div class="flex px-6 py-12"
                >
                    <div class="flex flex-col w-full">
                        <span>{{ __('Subtotal') }}: 
                            <span wire:loading.remove>
                                {{ $subtotal }}
                            </span>
                            @if($coupon)
                            <span wire:loading.remove>
                                - {{ $coupon->label }} = {{ $discounted_subtotal }}
                            </span>
                            @endif
                            <span wire:loading>
                                ...
                            </span>
                        </span>
                        <span>{{ __('Tax') }}: {{ $tax }}
                        </span>
                        @if($shipping_price)
                            <span wire:loading.remove>
                                {{ __('Shipping') }} : {{ $shipping_price->price }}€
                            </span>
                        @endif
                        <span>{{ __('Total') }}: 
                            <span wire:loading.remove>
                                {{ number_format( $total + $shipping_price->price, 2) }}
                            </span>
                            <span wire:loading>
                                ...
                            </span>
                        </span>

                        <div class="mt-5 space-x-2">
                            @if($coupon)
                                <span>{{ $coupon->code }}</span>
                                <x-jet-button type="button" wire:click="removeCoupon">
                                    X
                                </x-jet-button>
                            @endif
                            <input class="@if($this->coupon_error) text-red-500  bg-red-300 @endif" type="text" name="coupon_code" 
                                placeholder="{{ __('Coupon Code') }}"
                                wire:model.lazy="coupon_code"
                                x-on:input="$event.target.value=$event.target.value.toUpperCase()"
                            />
                            <x-jet-button type="button" wire:click="checkCoupon">
                                {{ __('Check') }}
                            </x-jet-button>
                            @if($coupon_error)
                            <label>{{$coupon_error}}</label>
                            @endif
                        </div>
                    </div>
                    
                </div>

                <div class="flex justify-center">
                    @if($addresses_confirmed)
                        <livewire:stripe.checkout :total="$total+$shipping_price->price" :key="$shipping_price->id"/>
                    @else
                        <button type="button" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                            wire:click.prevent='confirmAddresses'
                        >{{ __('Confirm Addresses') }}</button>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>