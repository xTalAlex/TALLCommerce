<footer class="text-gray-900 bg-secondary-50">

    <div class="grid w-full grid-flow-row p-10 text-sm leading-5 sm:grid-flow-col place-items-start gap-y-10 gap-x-2">
        <div class="grid gap-2 place-items-start">
            <span class="mb-2 font-bold uppercase opacity-50">{{ __('Sitemap') }}</span>
            <a class="cursor-pointer hover:underline" href="{{ route('home') }}">{{ __('Home') }}</a>
            <a class="cursor-pointer hover:underline" href="{{ route('about-us') }}">{{ __('About Us') }}</a>
            <a class="cursor-pointer hover:underline" href="{{ route('delivery') }}">{{ __('Delivery') }}</a>
            <a class="cursor-pointer hover:underline" href="{{ route('product.index') }}">{{ __('Buy Online') }}</a>
            <a class="cursor-pointer hover:underline" href="{{ route('info') }}">{{ __('Info') }}</a>
        </div>
        <div class="grid gap-2 place-items-start">
            <span class="mb-2 font-bold uppercase opacity-50">{{ __('Useful Links') }}</span>
            <a class="cursor-pointer hover:underline" href="{{ route('contact-us') }}">{{ __('Contact Us') }}</a>
            <a class="cursor-pointer hover:underline" href="{{ route('contact-us') . "#work-with-us" }}">{{ __('Work with us') }}</a>
            <a href="https://www.iubenda.com/privacy-policy/80514097" class="iubenda-white iubenda-noiframe iubenda-embed" title="Privacy Policy ">
                {{ __('Privacy Policy') }}
            </a>
            <script type="text/javascript">
                (function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src="https://cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);
            </script>
            {{-- <a class="cursor-pointer hover:underline">Impostazioni Cookie</a> --}}
        </div>
        <div class="grid gap-2 place-items-start">
            <span class="mb-2 font-bold uppercase opacity-50">Social</span>
            <div class="text-gray-500">{{ __('Coming Soon...') }}</div>
            {{-- <div class="grid grid-flow-col gap-4">
                <a href="#">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5">
                        </rect>
                        <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
                    </svg>
                </a>
                <a href="#">
                    <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                    </svg>
                </a>
            </div> --}}
        </div>
    </div>

    <div class="w-full pt-4 bg-secondary-50">
        <div class="container flex flex-col items-center justify-center px-5 py-2 mx-auto sm:flex-row">

            <a class="flex items-center justify-center font-medium text-gray-900 title-font md:justify-start">
                <x-jet-application-mark class="block w-24" />
            </a>
            <p class="mt-4 text-xs text-gray-900 sm:ml-2 sm:mt-0">© 2022 —
                <a href="https://talale.it" rel="noopener noreferrer" class="ml-1 text-gray-600"
                    target="_blank">Alessandro Talamona</a>
            </p>
        </div>
    </div>

</footer>
