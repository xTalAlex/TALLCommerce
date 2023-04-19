<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-application-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-input id="name" class="block w-full" type="text" label="{{ __('Name') }}" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-input id="email" class="block w-full" type="email" label="{{ __('Email') }}" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-input id="password" class="block w-full" type="password" label="{{ __('Password') }}" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-input id="password_confirmation" class="block w-full" type="password" label="{{ __('Confirm Password') }}" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="py-2 mt-4 border-t border-gray-200">
                <x-google-auth-button class="w-full" register/>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-checkbox name="terms" id="terms" class="items-start">
                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-sm text-gray-600 underline hover:text-gray-900">'.__('Terms of Service').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-sm text-gray-600 underline hover:text-gray-900">'.__('Privacy Policy').'</a>',
                        ]) !!}
                    </x-checkbox>
                </div>
            @endif

            <div class="flex items-center justify-between mt-4">
                <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
