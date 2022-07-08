<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Shop') }}
        </h2>
    </x-slot>

    <div class="flex justify-center py-8"
        x-data
        x-init="
            searchClient = window.algoliasearch(
                '{{ config('scout.algolia.id') }}',
                '{{ config('scout.algolia.client') }}'
            );
            search = window.instantsearch({
                indexName: '{{ config('scout.prefix') }}products',
                searchClient,
            });
            search.addWidgets([
                window.searchBox({
                    container: '#searchbox',
                    placeholder: '{{ __('Search...') }}',
                    autofocus : true,
                }),

                window.voiceSearch({
                    container: '#voicesearch',
                    searchAsYouSpeak: true,
                    language: '{{ config('app.locale') }}',
                    templates: {
                        status: '',
                    },
                }),

                window.hits({
                    container: '#hits',
                    templates : {
                        empty : document.getElementById('empty').innerHTML,
                        item : document.getElementById('item').innerHTML,
                    }
                })
            ]);
            search.start();
        "
    >
        <div class="flex flex-col w-full mx-auto md:space-x-3 md:flex-row md:inline-flex max-w-7xl sm:px-6 lg:px-8">
            
            <div class="flex flex-row items-center justify-center w-full mb-2 space-x-1 md:w-64 md:mb-auto" aria-label="Sidebar">
                <span id="voicesearch"></span>
                <div id="searchbox"></div>
            </div>
            <div class="w-full overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div id="hits"></div>
            </div>
            
        </div>
        
    </div>

    @push('scripts')

        <script id="empty" type="text/html">
            <p class="mt-2 text-center">{{__('No results for') }} <strong>@{{query}}</strong></p>
        </script>

        <script id="item" type="text/html">
            <div class='flex flex-col w-full max-w-xs mx-auto'>
            <a href='@{{url}}'>
                <div class='w-full mb-6'>
                    <img class='object-cover w-auto h-48 mx-auto' src='@{{image}}'/>
                </div>
                <div class='flex flex-col'>
                    <h2 class='mb-1 font-bold'>
                        <span class='mr-1'>@{{{_highlightResult.name.value}}}</span>
                        @{{#avg_rating}}
                            @{{avg_rating}}<x-icons.star class="w-4 h-4"></x-icons.star>
                        @{{/avg_rating}}
                        
                    </h2>
                    <h3 class="mb-2 font-medium">
                        @{{short_description}}
                    </h3>
                    <div class='flex justify-between'>
                        <span>
                            @{{^has_variants}}
                                @{{stock_status}}
                            @{{/has_variants}}
                        </span>
                        <span class='font-bold'>@{{price}}€</span>
                    </div>
                </div>
            </a>
            </div>
        </script>

    @endpush

</x-app-layout>
