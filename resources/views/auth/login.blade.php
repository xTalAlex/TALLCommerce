<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-application-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-input id="email" class="block w-full" type="email" label="{{ __('Email') }}" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-input id="password" class="block w-full" type="password" label="{{ __('Password') }}" name="password" required autocomplete="current-password" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <x-checkbox id="remember_me" name="remember">
                    <span class="text-sm text-gray-600">{{ __('Remember me') }}</span>
                </x-checkbox>

                @if (Route::has('password.request'))
                    <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="py-2 mt-4 border-t border-gray-200">
                <x-google-auth-button class="w-full"/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a href="{{ route('register') }}">
                    <x-button type="button">
                        {{ __('Register') }}
                    </x-button>
                </a>
                <x-button class="ml-2">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
