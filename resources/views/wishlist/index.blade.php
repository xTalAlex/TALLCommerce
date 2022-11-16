<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Wishlist') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            
            @guest
            <div class="relative h-auto text-white bg-opacity-50 bg-center bg-cover" 
                style="background-image: url(https://images.unsplash.com/photo-1508849789987-4e5333c12b78?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=993&q=80)"
            >
                <div class="w-full h-full px-10 py-16 bg-black bg-opacity-50">
                    <div class="md:w-1/2">
                        <p class="text-sm font-bold uppercase">{{ __('Pro Tips') }}</p>
                        <p class="text-3xl font-bold">{{ __('Register to save your wishlist') }}</p>
                        <p class="mb-10 text-2xl leading-none">{{ __('E per rimanere aggiornato su tutte le novità') }}</p>
                        <a href="{{ route('register') }}" class="px-8 py-4 text-xs font-bold text-white uppercase rounded bg-primary-800 hover:bg-gray-200 hover:text-gray-800"
                        >{{ __('Register') }}</a>
                    </div> 
                </div>    
            </div>
            @endguest  

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                <div class="px-4 mx-auto">
                    <div class="p-8 bg-white lg:p-20">

                        <h2 class="mb-4 text-4xl font-bold font-heading">
                            {{ __('Wishlist') }}
                        </h2>
                        <h4 class="mb-20 text-lg text-gray-600 font-heading">
                            {!! trans_choice('shopping_cart.wishlist.count', $count) !!}
                        </h4>

                        <div class="py-12">
                            <x-flatpickr wire:model.lazy="user.email_verified_at"/>

                            <p>{{ $user->email_verified_at }}</p>

                            <button class="p-2 ml-2 font-semibold text-white uppercase bg-green-500 rounded-md shadow-md" wire:click="save">Save</button>
                        </div>

                        @if($count)
                        <div class="flex flex-wrap items-center -mx-4">
                            <div class="w-full px-4">

                                <div class="hidden w-full lg:flex">
                                    <div class="w-full lg:w-2/5">
                                        <h4 class="mb-6 font-bold text-gray-500 font-heading">
                                            {{__('Description')}}
                                        </h4>
                                    </div>
                                    <div class="w-full text-center lg:w-1/5">
                                        <h4 class="mb-6 font-bold text-gray-500 font-heading">
                                            {{__('Avaiability')}}
                                        </h4>
                                    </div>
                                    <div class="w-full text-center lg:w-1/5">
                                        <h4 class="mb-6 font-bold text-gray-500 font-heading">
                                            {{__('Price')}}
                                        </h4>
                                    </div>
                                    <div class="w-full text-right lg:w-1/5">
                                        <h4 class="mb-6 font-bold text-gray-500 sr-only font-heading">
                                            {{ __('shopping_cart.move.cart') }}
                                        </h4>
                                    </div>
                                </div>

                                <div class="py-6 mb-12 border-t border-b border-gray-200">
                                    @foreach( $content as $rowId=>$item )
                                        <livewire:wishlist.item-row 
                                            :item="collect($item)"
                                        />
                                    @endforeach
                                </div>

                            </div>
                                                    
                        </div>
                        @endif

                    </div>
                </div>
                
                @if($count)
                    <div class="flex items-center justify-between mx-5 mb-2">
                        @livewire('wishlist.destroy-form')
                    </div>
                @endif

            </div>      
          
        </div>
    </div>
</div>
