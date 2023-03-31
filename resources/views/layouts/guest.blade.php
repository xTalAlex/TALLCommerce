<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    
    @include('layouts._head')
    
    <body>
        <div class="font-sans antialiased text-gray-900">
            {{ $slot }}
        </div>

        @stack('modals')

        @stack('scripts')

        <x-tawkto-widget/>

        @livewireScripts
    </body>
</html>
