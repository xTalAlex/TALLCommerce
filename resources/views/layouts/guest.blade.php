<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" data-theme="emerald">
    
    @include('layouts._head')
    
    <body>
        <div class="text-base-content">
            {{ $slot }}
        </div>

        @stack('modals')

        @stack('scripts')

        @include('layouts._tawkto-widget')

        @livewireScripts
    </body>
</html>
