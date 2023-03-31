<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    
    @include('layouts._head')

    <body class="font-sans antialiased text-gray-900">
        <x-jet-banner />

        <div class="bg-white">
            @livewire('navigation-menu')
    
            <div class="flex flex-col min-h-screen pt-16">
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="">
                        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>

                @include('layouts._footer')
            </div>
        </div>

        @stack('modals')

        @stack('scripts')

        <x-tawkto-widget/>
        
        @livewireScripts
    </body>
</html>
