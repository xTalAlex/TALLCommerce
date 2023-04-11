<div>
    <footer class="p-10 footer bg-base-300 text-base-content">
    <div>
        <span class="footer-title">{{ __('Sitemap') }}</span> 
        <a class="link link-hover" href="{{ route('home') }}">{{ __('Home') }}</a>
        <a class="link link-hover" href="{{ route('product.index') }}">{{ __('Buy Online') }}</a>
    </div> 
    <div>
        <span class="footer-title">{{ __('Useful Links') }}</span> 
        <a href="https://www.iubenda.com/privacy-policy/80514097" class="iubenda-white iubenda-noiframe iubenda-embed" title="Privacy Policy ">
            {{ __('Privacy Policy') }}
        </a>
        <script type="text/javascript">
            (function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src="https://cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);
        </script>
    </div> 
    <div>
        <span class="footer-title">Social</span> 
        <div class="grid grid-flow-col gap-4">
        <a><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path></svg></a>
        <a><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"></path></svg></a>
        <a><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path></svg></a>
        </div>
    </div>
    </footer>
    <footer class="p-4 footer footer-center bg-base-300 text-base-content">
    <div class="flex items-center">
        <x-jet-application-mark class="block w-24 -mt-1" /> © {{ today()->format('Y') }} - 
        Developed by 
        <a href="https://talale.it" rel="noopener noreferrer" class="ml-1 text-gray-600" target="_blank">Alessandro Talamona</a>
    </div>
    </footer>
</div>
