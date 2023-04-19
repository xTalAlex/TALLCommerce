<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="emerald">
    
    @include('layouts._head')

    <body>
		<div class="drawer text-base-content">
			<input id="my-drawer" type="checkbox" class="drawer-toggle" />
			<div class="flex flex-col drawer-content scroll-smooth">
                
                <x-jet-banner />

				@livewire('navigation-menu')
                
				<main class="flex-1 pt-16">
                
                    @if (isset($header))
                        <header class="">
                            <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

					{{ $slot }}
                    
				</main>

                @include('layouts._footer')
                
			</div>
			<div class="drawer-side">
				<label for="my-drawer" class="drawer-overlay"></label>
				<div class="menu w-80 bg-base-100 text-base-content">

                    @include('layouts._sidebar')
                    
				</div>
			</div>
		</div>

        @stack('modals')

        @stack('scripts')

        @include('layouts._tawkto-widget')
        
        @livewireScripts
	</body>
</html>
