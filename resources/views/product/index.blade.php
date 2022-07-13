<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800"
            id="breadcrumb"
        >
        </h2>
    </x-slot>

    <div class="flex justify-center py-8 mx-auto"
        x-data
        x-init="
            searchClient = window.algoliasearch(
                '{{ config('scout.algolia.id') }}',
                '{{ config('scout.algolia.client') }}'
            );
            search = window.instantsearch({
                indexName: '{{ config('scout.prefix') }}products',
                searchClient,
                initialUiState: {
                    '{{ config('scout.prefix') }}products': {
                        query: new URLSearchParams(window.location.search).get('keyword'),
                    }
                }
            });
            search.addWidgets([
                window.algoliaWidgets.searchBox({
                    container: '#searchbox',
                    placeholder: '{{ __('Search...') }}',
                    autofocus : true,
                }),

                window.algoliaWidgets.voiceSearch({
                    container: '#voicesearch',
                    searchAsYouSpeak: true,
                    language: '{{ config('app.locale') }}',
                    templates: {
                        status: '',
                    },
                }),

                window.algoliaWidgets.configure({
                    hitsPerPage: 9,
                    attributesToSnippet: ['name:5','short_description:5','description:5'],
                    snippetEllipsisText: '&hellip;'
                }),

                window.algoliaWidgets.infiniteHits({
                    container: '#infinite-hits',
                    templates : {
                        empty : document.getElementById('empty').innerHTML,
                        item : document.getElementById('item').innerHTML,   
                        showMoreText() {
                            return '{{ __('Show more') }}';
                        },
                    }
                }),

                window.algoliaWidgets.sortBy({
                    container: '#sort-by',
                    items: [
                        { label: '{{ __('general.searchbar.options.featured') }}', value: '{{ config('scout.prefix') }}products' },
                        { label: '{{ __('general.searchbar.options.recent') }}', value: '{{ config('scout.prefix') }}products_recent' },
                        { label: '{{ __('general.searchbar.options.price_asc') }}', value: '{{ config('scout.prefix') }}products_price_asc' },
                        { label: '{{ __('general.searchbar.options.price_desc') }}', value: '{{ config('scout.prefix') }}products_price_desc' },
                    ]
                }),

                window.algoliaWidgets.hierarchicalMenu({
                    container: '#hierarchical-menu',
                    attributes: [
                        'hierarchicalCategories.lvl0',
                        'hierarchicalCategories.lvl1',
                        'hierarchicalCategories.lvl2',
                        'hierarchicalCategories.lvl3',
                    ],
                    limit: 5,
                    showMore: true,
                    separator: '>',
                }),

                {{-- window.algoliaWidgets.currentRefinements({
                    container: '#current-refinements',
                    transformItems(items) {
                        return items
                            ? items
                            : items.map(item => item.attribute='');
                    },
                }), --}}

                window.algoliaWidgets.clearRefinements({
                    container: '#clear-refinements',
                    templates: {
                        resetLabel() {
                            return '{{ __('Clear filters') }}';
                        },
                    },
                }),

                window.algoliaWidgets.breadcrumb({
                    container: '#breadcrumb',
                    attributes: [
                        'hierarchicalCategories.lvl0',
                        'hierarchicalCategories.lvl1',
                        'hierarchicalCategories.lvl2',
                        'hierarchicalCategories.lvl3',
                    ],
                    templates: {
                        home() {
                            return '{{ __('Shop') }}';
                        },
                    },
                }),

                window.algoliaWidgets.stats({
                    container: '#stats',
                    templates: {
                        text(data) {
                            let count = '';

                            if (data.hasManyResults) {
                                count += `${data.nbHits} {{ __('results') }}`;
                            } else if (data.hasOneResult) {
                                count += `1 {{ __('result') }}`;
                            } else {
                                count += `0 {{ __('results') }}`;
                            }

                            return `${count} in ${data.processingTimeMS}ms`;
                        },
                    },
                }),

                window.algoliaWidgets.poweredBy({
                    container: '#powered-by',
                }),

                {{-- window.algoliaWidgets.refinementList({
                    container: '#refinement-list',
                    attribute: 'categories.name',
                    operator: 'or',
                    limit: 5,
                    showMore: true,
                    searchable: true,
                    searchablePlaceholder: '{{ __('Category') }}...',
                    searchableIsAlwaysActive: false,
                    templates: {
                        searchableNoResults() { 
                            return '{{ __('No results') }}';
                        },
                        showMoreText(data) {
                            return data.isShowingMore ? '{{ __('Hide') }}' : '{{ __('Show') }}';
                        },
                    },
                }), --}}

            ]);
            search.start();
        "
    >
        <div class="flex flex-col w-full mx-auto md:space-x-3 md:flex-row md:inline-flex max-w-7xl sm:px-6 lg:px-8">
            
            <div class="flex flex-col w-full mb-2 space-x-2 md:w-64 md:mb-auto" aria-label="Sidebar">
                <div class="flex flex-row items-center justify-center w-full mb-4 space-x-1">
                    <span id="voicesearch"></span>
                    <div id="searchbox"></div>
                </div>
                
                <div class="px-1">
                    <div class="mb-2" id="sort-by"></div>
                    <div class="mb-2" id="hierarchical-menu"></div>
                    <div class="mb-2" id="refinement-list"></div>
                    <div class="mb-2" id="current-refinements"></div>
                    <div class="mb-2" id="clear-refinements"></div>
                </div>

                <div class="flex justify-between px-1 mt-4">
                    <div id="powered-by"></div>
                    <div class="text-xs opacity-50" id="stats"></div>
                </div>
                
            </div>
            <div class="w-full overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div id="infinite-hits"></div>
            </div>
            
        </div>
        
    </div>

    @push('scripts')

        <script id="empty" type="text/html">
            <p class="mt-2 text-center">
                @{{#query}}
                {{__('No results for') }} <strong>@{{query}}
                @{{/query}}
                @{{^query}}
                {{__('No results') }}
                @{{/query}}
            </strong></p>
        </script>

        <script id="item" type="text/html">
            <div class='flex flex-col w-full max-w-xs mx-auto'>
            <a href='@{{url}}'>
                <div class='w-full mb-6'>
                    <img class='object-cover w-auto h-48 mx-auto' src='@{{image}}'/>
                </div>
                <div class='flex flex-col'>
                    <h2 class='mb-1 font-bold'>
                        <span class="mr-1">
                            @{{#helpers.highlight}}
                                { "attribute": "name" }
                            @{{/helpers.highlight}}
                        </span>
                        @{{#avg_rating}}
                            @{{avg_rating}}<x-icons.star class="w-4 h-4"></x-icons.star>
                        @{{/avg_rating}}
                        
                    </h2>
                    <h3 class="mb-2 font-medium">
                        @{{#short_description}}
                            @{{#helpers.snippet}}
                                    { "attribute": "short_description" }
                            @{{/helpers.snippet}}
                        @{{/short_description}}
                    </h3>
                    <div class='flex justify-between'>
                        <span>
                            @{{^has_variants}}
                                @{{stock_status}}
                            @{{/has_variants}}
                        </span>
                        <div class="flex flex-col">
                            @{{#discount}}
                                <span class="ml-4 text-base text-gray-900 line-through dark:text-white">
                                    @{{original_price}}€
                                </span>
                            @{{/discount}}
                            <span class='font-bold'>
                                @{{price}}€
                            </span>
                        </div>
                    </div>
                </div>
            </a>
            </div>
        </script>

    @endpush

</x-app-layout>
