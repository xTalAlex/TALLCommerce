<div class="navbar bg-base-100">
    <div class="navbar-start">
        <label
            tabindex="0"
            for="my-drawer"
            class="drawer-button btn btn-ghost btn-circle"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                ><path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h7"></path></svg
            >
        </label>
    </div>
    <div class="navbar-center">
        <a href="{{ route('home') }}">
            <x-application-logo />
        </a>
    </div>
    <div class="navbar-end">
        <div class="dropdown dropdown-end">
            <label
                tabindex="0"
                class="btn btn-ghost btn-circle"
            >
                <div class="indicator">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        ><path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                        ></path></svg
                    >
                    <x-cart-counter class="badge badge-sm indicator-item" />
                </div>
            </label>
            <div
                tabindex="0"
                class="mt-3 shadow card card-compact dropdown-content w-52 bg-base-100"
            >
                <div class="card-body">
                    <span class="text-lg font-bold"
                        >8 Items</span
                    >
                    <span class="text-info">Subtotal: $999</span
                    >
                    <div class="card-actions">
                        <a href="{{ route('cart.index') }}"
                            class="btn btn-primary btn-block"
                            >View cart</a
                        >
                    </div>
                </div>
            </div>
        </div>
        <a
            href="{{ route('wishlist.index') }}"
            class="btn btn-ghost btn-circle"
        >
            <div class="indicator">
                <x-icons.heart filled="false" red="false" />
                <x-wishlist-counter class="badge badge-sm indicator-item" />
            </div>
        </a>
        @auth
        <div class="dropdown dropdown-end">
            <label
                tabindex="0"
                class="btn btn-ghost btn-circle avatar"
            >
                <div class="w-10 rounded-full">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"/>
                </div>
            </label>
            <ul
                tabindex="0"
                class="p-2 mt-3 shadow menu menu-compact dropdown-content bg-base-100 rounded-box w-52"
            >
                @if(Auth::user()->canAccessFilament())
                    <li>
                        <a class="justify-between" href="{{ route('filament.pages.dashboard') }}">
                            {{ __('Admin Panel') }}
                            <span class="badge">New</span>
                        </a>
                    </li>
                @endif
                <li><a href="{{ route('profile.show') }}">{{ __('Profile') }}</a></li>
                <li><a href="{{ route('order.index') }}">{{ __('My Orders') }}</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <a href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </a>
                    </form>
                </li>
            </ul>
        </div>
        @else
        <a href="{{ route('login') }}" class="btn btn-ghost">{{ __('Login') }}</a>
        <a href="{{ route('register') }}" class="btn">{{ __('Register') }}</a>
        @endauth
    </div>
</div>