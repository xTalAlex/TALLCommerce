<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    @if (isset($seo))
        {{ $seo }}
    @else
        {!! seo($SEOData ?? null) !!}
    @endif

    @if (config('services.google.analytics'))
        <!-- Google tag (gtag.js) -->
        <script class="_iub_cs_activate" type="text/plain" async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google.analytics') }}"></script>
        <script class="_iub_cs_activate" type="text/plain">
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ config('services.google.analytics') }}');
        </script>
    @endif


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap">

    <!-- Styles -->
    @vite('resources/css/app.css')
    @livewireStyles

    <!-- Scripts -->
    @vite('resources/js/app.js')
    <script src="https://js.stripe.com/v3/"></script>

    @stack('styles')

    <script type="text/javascript">
        var _iub = _iub || [];
        _iub.csConfiguration = {"floatingPreferencesButtonColor":"#DECECE00","floatingPreferencesButtonDisplay":"anchored-bottom-right","perPurposeConsent":true,"siteId":3026577,"whitelabel":false,"cookiePolicyId":80514097,"lang":"it", "banner":{ "acceptButtonCaptionColor":"#FFFFFF","acceptButtonColor":"#010436","acceptButtonDisplay":true,"backgroundColor":"#FBF6EF","backgroundOverlay":true,"brandBackgroundColor":"#FBF6EF","brandTextColor":"#010436","closeButtonRejects":true,"customizeButtonCaptionColor":"#010436","customizeButtonColor":"#E1DDD7","customizeButtonDisplay":true,"explicitWithdrawal":true,"listPurposes":true,"logo":"https://www.colombofood.it/img/marchio.png","position":"bottom","theme":"autumn-neutral","textColor":"#010436" }};
    </script>
    <script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>

</head>
