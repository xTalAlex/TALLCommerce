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

        <div class="w-full overflow-hidden bg-white shadow-xl md:w-2/3 sm:rounded-lg">

            <div>
                
                <x-jet-validation-errors class="my-4 mb-4" />

                @if($errors->has('email'))
                    <a href="{{ route('order.login') }}">{{ __('Already registered?') }}</a>
                @endif

            @if(!$addresses_confirmed)
            <form action="" method="POST">
            @csrf
                <div class="px-6 py-12"
                >
                    <div class="@auth hidden @endauth relative z-0 w-full mb-6 group">
                        <input type="text" name="email" id="email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer" 
                            wire:model="email" placeholder=" "/>
                        <label for="email" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                        >{{ __('Email') }}</label>
                    </div>
                    <div class="grid xl:grid-cols-2 xl:gap-6">
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="shipping_address_full_name" id="shipping_address_full_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer" 
                                wire:model.lazy="shipping_address.full_name" placeholder=" "  />
                            <label for="shipping_address_full_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Full Name') }}</label>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="shipping_address_company" id="shipping_address_company" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer"
                                wire:model.lazy="shipping_address.company" placeholder=" "/>
                            <label for="shipping_address_company" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Company') }}</label>
                        </div>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="text" name="shipping_address_address" id="shipping_address_address" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer"
                            wire:model.lazy="shipping_address.address" placeholder=" "  />
                        <label for="shipping_address_address" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                        >{{ __('Address') }}</label>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="text" name="shipping_address_address2" id="shipping_address_address2" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer"
                            wire:model.lazy="shipping_address.address2" placeholder=" " />
                        <label for="shipping_address_address2" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                        >{{ __('Address2') }}</label>
                    </div>
                    <div class="grid xl:grid-cols-2 xl:gap-6">
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="shipping_address_city" id="shipping_address_city" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer" 
                                wire:model.lazy="shipping_address.city" placeholder=" " />
                            <label for="shipping_address_city" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('City') }}</label>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="shipping_address_province" id="shipping_address_province" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer"
                                wire:model.lazy="shipping_address.province" placeholder=" " />
                            <label for="shipping_address_province" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Province') }}</label>
                        </div>
                    </div>
                    <div class="grid xl:grid-cols-2 xl:gap-6">
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="shipping_address_country_region" id="shipping_address_country_region" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer" 
                                wire:model.lazy="shipping_address.country_region" placeholder=" " />
                            <label for="shipping_address_country_region" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Country/Region') }}</label>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="shipping_address_postal_code" id="shipping_address_postal_code" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer"
                                wire:model.lazy="shipping_address.postal_code" placeholder=" " />
                            <label for="shipping_address_postal_code" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Postal Code') }}</label>
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

                @if(!$same_address)
                <div class="px-6 py-12"
                >
                    <div class="grid xl:grid-cols-2 xl:gap-6">
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="billing_address_full_name" id="billing_address_full_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer" 
                                wire:model.lazy="billing_address.full_name" placeholder=" "  />
                            <label for="billing_address_full_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Full Name') }}</label>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="billing_address_company" id="billing_address_company" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer"
                                wire:model.lazy="billing_address.company" placeholder=" "/>
                            <label for="billing_address_company" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Company') }}</label>
                        </div>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="text" name="billing_address_address" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer"
                            wire:model.lazy="billing_address.address" placeholder=" "  />
                        <label for="billing_address_address" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                        >{{ __('Address') }}</label>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="text" name="billing_address_address2" id="billing_address_address2" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer"
                            wire:model.lazy="billing_address.address2" placeholder=" " />
                        <label for="billing_address_address2" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                        >{{ __('Address2') }}</label>
                    </div>
                    <div class="grid xl:grid-cols-2 xl:gap-6">
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="billing_address_city" id="billing_address_city" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer" 
                                wire:model.lazy="billing_address.city" placeholder=" " />
                            <label for="billing_address_city" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('City') }}</label>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="billing_address_province" id="billing_address_province" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer"
                                wire:model.lazy="billing_address.province" placeholder=" " />
                            <label for="billing_address_province" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Province') }}</label>
                        </div>
                    </div>
                    <div class="grid xl:grid-cols-2 xl:gap-6">
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="billing_address_country_region" id="billing_address_country_region" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer" 
                                wire:model.lazy="billing_address.country_region" placeholder=" " />
                            <label for="billing_address_country_region" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Country/Region') }}</label>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="billing_address_postal_code" id="billing_address_postal_code" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-primary-500 focus:outline-none focus:ring-0 focus:border-primary-600 peer"
                                wire:model.lazy="billing_address.postal_code" placeholder=" " />
                            <label for="billing_address_postal_code" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary-600 peer-focus:dark:text-primary-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Postal Code') }}</label>
                        </div>
                    </div>
                    
                </div>
                @endif
                
            </form>
            @else
            <div class="px-6 py-12">
                    <div class="border-2 border-gray-900">
                        {{ __('Shipping Address') }}
                    </div>
                    <div class="border-2 border-gray-900">
                        {!! $shipping_address->label !!}
                    </div>
                
                    <div class="border-2 border-gray-900">
                        {{ __('Billing Address') }}
                    </div>
                    <div class="border-2 border-gray-900">
                        {!! $billing_address->label !!}
                    </div>

                    <div class="mx-auto mt-5">
                        <button type="button" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                        wire:click.prevent="$set('addresses_confirmed',false)"
                        >{{ __('Modify') }}</button>
                    </div>
            </div>
            @endif
            
            <div class="">
                @foreach($shipping_prices as $option)
                <input type="radio" id="{{$option->name}}" wire:model="shipping_price_id" value="{{ $option->id }}">
                <label for="{{$option->name}}">{{ $option->name }} {{ $option->price }}  {{ $option->description }}</label><br>
                @endforeach
            </div>

            </div>
        </div>

        <div class="w-full overflow-hidden bg-white shadow-xl md:w-1/3 sm:rounded-lg">
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