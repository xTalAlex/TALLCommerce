<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    
    @include('layouts._head')

    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')
    
            <div class="pt-16">
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>

                @include('footer')
            </div>
        </div>

        @stack('modals')

        @stack('scripts')

        <x-tawkto-widget/>
        
        @livewireScripts
    </body>
</html>
