@php
    $activeClass = 'text-blue-600 bg-gray-100 dark:bg-gray-800 dark:text-blue-500 active';
    $defaultClass = 'text-gray-400 dark:text-gray-500';
@endphp
<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Checkout') }}
    </h2>
</x-slot>

<div class="py-12"
    x-data="{
        step : @entangle('step')
    }"
>
    <div class="mx-auto md:flex max-w-7xl sm:px-6 lg:px-8">

        <div class="w-full overflow-hidden bg-white shadow-xl md:w-2/3 sm:rounded-lg">

            <div>
                <x-jet-validation-errors class="mb-4" />

                @if($errors->has('email'))
                    <a href="{{ route('login') }}">{{ __('Already registered?') }}</a>
                @endif

            {{-- <ul class="flex flex-wrap w-full space-x-8 text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
                <li class="cursor-default">
                    <span class="inline-block p-4 rounded-t-lg {{ $step=='shipping' ? $activeClass : $defaultClass }}"
                    >Shipping Info</span>
                </li>
                <li class="cursor-default">
                    <span class="inline-block p-4 rounded-t-lg  {{ $step=='billing' ? $activeClass : $defaultClass }}"
                    >Billing Info</span>
                </li>
                <li class="cursor-default">
                    <span class="inline-block p-4 rounded-t-lg  {{ $step=='payment' ? $activeClass : $defaultClass }}"
                    >Payment</span>
                </li>
            </ul> --}}
                
            {{-- <form action="{{ route('stripe.checkout') }}" method="POST"> --}}
            @if(!$addresses_confirmed)
            <form action="" method="POST">
            @csrf
                <div class="px-6 py-12"
                >
                    <div class="@auth hidden @endauth relative z-0 w-full mb-6 group">
                        <input type="text" name="email" id="email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" 
                            wire:model="email" placeholder=" "/>
                        <label for="email" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                        >{{ __('Email') }}</label>
                    </div>
                    <div class="grid xl:grid-cols-2 xl:gap-6">
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="full_name" id="full_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" 
                                wire:model="full_name" placeholder=" "  />
                            <label for="full_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Full Name') }}</label>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="company" id="company" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                wire:model="company" placeholder=" "/>
                            <label for="company" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Company') }}</label>
                        </div>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="text" name="address" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            wire:model="address" placeholder=" "  />
                        <label for="address" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                        >{{ __('Address') }}</label>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="text" name="address2" id="address2" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            wire:model="address2" placeholder=" " />
                        <label for="address2" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                        >{{ __('Address2') }}</label>
                    </div>
                    <div class="grid xl:grid-cols-2 xl:gap-6">
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="city" id="city" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" 
                                wire:model="city" placeholder=" " />
                            <label for="city" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('City') }}</label>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="province" id="province" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                wire:model="province" placeholder=" " />
                            <label for="province" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Province') }}</label>
                        </div>
                    </div>
                    <div class="grid xl:grid-cols-2 xl:gap-6">
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="country_region" id="country_region" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" 
                                wire:model="country_region" placeholder=" " />
                            <label for="country_region" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Country/Region') }}</label>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="postal_code" id="postal_code" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                wire:model="postal_code" placeholder=" " />
                            <label for="postal_code" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Postal Code') }}</label>
                        </div>
                    </div>

                    <div class="relative z-0 w-full mb-6 group">  
                        <textarea id="message" name="message" rows="4" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" 
                            wire:model="message" placeholder=" "></textarea>
                        <label for="message" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Message') }}</label>
                    </div>

                    <div class="mt-6 md:flex md:justify-between">
                        <div class="flex items-center mb-6">
                            <input id="same_address" name="same_address" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                wire:model="same_address"  value=""
                            >
                            <label for="same_address" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Use as billing address</label>
                        </div>

                        @auth
                            <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
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
                            <input type="text" name="billing_full_name" id="billing_full_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" 
                                wire:model="billing_full_name" placeholder=" "  />
                            <label for="billing_full_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Full Name') }}</label>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="billing_company" id="billing_company" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                wire:model="billing_company" placeholder=" "/>
                            <label for="billing_company" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Company') }}</label>
                        </div>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="text" name="billing_address" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            wire:model="billing_address" placeholder=" "  />
                        <label for="billing_address" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                        >{{ __('Address') }}</label>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="text" name="billing_address2" id="billing_address2" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            wire:model="billing_address2" placeholder=" " />
                        <label for="billing_address2" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                        >{{ __('Address2') }}</label>
                    </div>
                    <div class="grid xl:grid-cols-2 xl:gap-6">
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="billing_city" id="billing_city" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" 
                                wire:model="billing_city" placeholder=" " />
                            <label for="billing_city" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('City') }}</label>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="billing_province" id="billing_province" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                wire:model="billing_province" placeholder=" " />
                            <label for="billing_province" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Province') }}</label>
                        </div>
                    </div>
                    <div class="grid xl:grid-cols-2 xl:gap-6">
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="billing_country_region" id="billing_country_region" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" 
                                wire:model="billing_country_region" placeholder=" " />
                            <label for="billing_country_region" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Country/Region') }}</label>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input type="text" name="billing_postal_code" id="billing_postal_code" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                wire:model="billing_postal_code" placeholder=" " />
                            <label for="billing_postal_code" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >{{ __('Postal Code') }}</label>
                        </div>
                    </div>
                    
                </div>
                @endif
                
            </form>
            @else
                <div class="px-6 py-12">
                        <div class="border-2 border-black">
                            {{ __('Shipping Address') }}
                        </div>
                        <div class="border-2 border-black">
                            {!! $addressShipping->label !!}
                        </div>
                   
                        <div class="border-2 border-black">
                            {{ __('Billing Address') }}
                        </div>
                        <div class="border-2 border-black">
                            {!! $addressBilling->label !!}
                        </div>

                        <div class="mx-auto mt-5">
                            <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            wire:click.prevent="$set('addresses_confirmed',false)"
                            >{{ __('Modify') }}</button>
                        </div>
                </div>
                
            @endif
            
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
                            - {{ $coupon->label }} = {{ $discountedSubtotal }}
                        </span>
                        @endif
                        <span wire:loading>
                            ...
                        </span>
                    </span>
                    <span>{{ __('Tax') }}: {{ $tax }}
                    </span>
                    <span>{{ __('Total') }}: 
                        <span wire:loading.remove>
                            {{ $total }}
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
                    </div>
                </div>
                
                {{-- <x-jet-button type="submit">
                    {{ __('Pay with Stripe') }}
                </x-jet-button> --}}
            </div>

            <div class="flex justify-center">
                @if($addresses_confirmed)
                    <livewire:stripe.checkout :total="$total"/>
                @else
                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        wire:click.prevent='confirmAddresses'
                    >{{ __('Confirm Addresses') }}</button>
                @endif
            </div>
        </div>
    
    </div>
</div>