<nav x-data="{ open: false }" class="fixed z-50 w-full h-16 bg-white border-b border-white">
    <!-- Primary Navigation Menu -->
    <div class="px-6 mx-auto xl:container">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route('home') }}">
                        <x-jet-application-mark class="block w-auto h-6" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="container hidden space-x-4 lg:-my-px lg:ml-10 lg:flex">
                    <x-jet-nav-link href="{{ route('about-us') }}" :active="request()->routeIs('about-us')">
                        {{ __('About Us') }}
                    </x-jet-nav-link>
                    <x-jet-nav-link href="{{ route('delivery') }}" :active="request()->routeIs('delivery')">
                        {{ __('Delivery') }}
                    </x-jet-nav-link>
                    <x-jet-nav-link href="{{ route('contact-us') }}" :active="request()->routeIs('contact-us')">
                        {{ __('Contact Us') }}
                    </x-jet-nav-link>
                    <x-jet-nav-link accent href="{{ route('product.index') }}" :active="request()->routeIs('product.index')">
                        {{ __('Buy Online') }}
                    </x-jet-nav-link>
                </div>
            </div>

            <div class="hidden lg:flex lg:items-center lg:ml-6">
                <div class="space-x-3">
                    <x-jet-nav-link href="{{ route('cart.index') }}" :active="request()->routeIs('cart.index')">
                        <div class="relative w-6 h-6">
                            <x-icons.cart/>
                            <x-cart-counter class="absolute w-4 h-4 ml-1 text-[0.5rem] leading-4 font-medium text-center text-white rounded-full -top-1 -right-1 bg-primary-500"/>
                        </div>
                    </x-jet-nav-link>
                    <x-jet-nav-link href="{{ route('wishlist.index') }}" :active="request()->routeIs('wishlist.index')">
                        <div class="relative w-6 h-6">
                            <x-icons.heart red="false" filled="false"/>
                            <x-wishlist-counter class="absolute w-4 h-4 ml-1 text-[0.5rem] leading-4 font-medium text-center text-white rounded-full -top-1 -right-1 bg-primary-500"/>
                        </div>
                    </x-jet-nav-link>
                </div>

                <!-- Settings Dropdown -->
                <div class="relative ml-3">
                    @auth
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm transition border-2 border-transparent rounded-full focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-primary-500">
                                    <img class="object-cover rounded-full w-9 h-9" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-semibold leading-4 text-gray-500 transition bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none">
                                        {{ Auth::user()->name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">

                            @if(Auth::user()->canAccessFilament())
                                <div class="block px-4 py-2 text-xs text-gray-500">
                                    {{ __('Admin') }}
                                </div>
                                <x-dropdown-link href="{{ route('filament.pages.dashboard') }}">
                                    {{ __('Admin Panel') }}
                                </x-dropdown-link>
                            @endif

                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-500">
                                {{ __('Menu') }}
                            </div>
                            
                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('order.index') }}">
                                {{ __('My Orders') }}
                            </x-dropdown-link>

                            <div class="border-t border-secondary-50"></div>
                            
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-jet-dropdown>
                    @else
                    <x-jet-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                        {{ __('Login') }}
                    </x-jet-nav-link>
                    <x-jet-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                        {{ __('Register') }}
                    </x-jet-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center -mr-2 space-x-2 lg:hidden">
                <x-jet-nav-link class="relative" href="{{ route('cart.index') }}" :active="request()->routeIs('cart.index')">
                    <x-icons.cart/>
                    <x-cart-counter class="absolute top-0 right-0 w-4 h-4 ml-1 text-[0.5rem] leading-4 font-medium text-center text-white rounded-full bg-primary-500"/>
                </x-jet-nav-link>

                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 text-gray-500 transition hover:text-gray-900 focus:outline-none focus:text-gray-900">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div class="absolute inset-x-0 z-10 overflow-y-auto bg-white shadow-md lg:hidden"
        x-show="open"
        x-cloak
        x-transition:enter="transition transform ease-out origin-top duration-200"
        x-transition:enter-start="opacity-50"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition transform ease-in origin-top duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="max-height: calc(100vh - 4rem);"
    >
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3">
            <div class="flex items-center px-4 pb-1">
                @auth
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <a href="{{ route('profile.show') }}" class="mr-3 shrink-0">
                            <img class="object-cover rounded-full w-9 h-9" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </a>
                    @endif

                    <div>
                        <div>
                            <a href="{{ route('profile.show') }}" class="text-base font-semibold">{{ Auth::user()->name }}</a>
                        </div>
                        <div class="flex space-x-2">
                            <div>
                                <a class="text-sm font-semibold text-gray-500 underline" href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </a>
                            </div>
                            @if(Auth::user()->canAccessFilament())
                                <div>
                                    <a class="text-sm font-semibold text-gray-500 underline" href="{{ route('filament.pages.dashboard') }}">
                                        {{ __('Admin Panel') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                            
                    </div>
                @endauth
            </div>

            <div class="px-4 pt-2 pb-1 space-y-1 border-t border-secondary-50">
                @auth
                    <x-dropdown-link href="{{ route('wishlist.index') }}">
                        {{ __('Wishlist') }}
                        <x-wishlist-counter class="inline-block w-4 h-4 text-[0.5rem] leading-4 font-medium text-center text-white rounded-full bg-primary-500"/>
                    </x-dropdown-link>
                    <x-dropdown-link href="{{ route('order.index') }}">
                            {{ __('My Orders') }}
                    </x-dropdown-link>
                @endauth
                <x-dropdown-link accent="true" href="{{ route('product.index') }}">
                    {{ __('Buy Online') }}
                </x-dropdown-link>
                <div class="pt-2 pb-1 space-y-1 border-t border-secondary-50">
                    <x-dropdown-link href="{{ route('about-us') }}" :active="request()->routeIs('about-us')">
                        {{ __('About Us') }}
                    </x-dropdown-link>
                    <x-dropdown-link href="{{ route('delivery') }}" :active="request()->routeIs('delivery')">
                        {{ __('Delivery') }}
                    </x-dropdown-link>
                    <x-dropdown-link href="{{ route('contact-us') }}" :active="request()->routeIs('contact-us')">
                        {{ __('Contact Us') }}
                    </x-dropdown-link>
                </div>
                <div class="pt-2 pb-1 space-y-1 border-t border-secondary-50">
                    @auth
                        <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}"
                                            @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                        </form>
                    @else
                        <x-dropdown-link href="{{ route('login') }}">
                                {{ __('Login') }}
                        </x-dropdown-link>
                        <x-dropdown-link href="{{ route('register') }}">
                                {{ __('Register') }}
                        </x-dropdown-link>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>
