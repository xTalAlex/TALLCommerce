<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800" id="breadcrumb">
        </h2>
    </x-slot> --}}

    <div class="flex justify-center py-8 mx-auto" x-data="instantsearch">
        <div class="flex flex-col w-full mx-auto md:space-x-10 md:flex-row md:inline-flex max-w-7xl sm:px-6 lg:px-8">

            <div class="flex flex-col w-full py-2 mb-2 space-x-2 md:w-64 md:mb-auto" aria-label="Sidebar">
                <div class="flex flex-row items-center justify-center w-full mb-4 space-x-1">
                    <span id="voicesearch"></span>
                    <div id="searchbox"></div>
                </div>

                <div class="px-4 md:px-1">
                    <div class="mb-4" id="sort-by"></div>
                    <div class="" id="hierarchical-menu"></div>
                    <div class="" id="brands-list"></div>
                    <div class="" id="collections-list"></div>
                    <div class="" id="current-refinements"></div>
                    <div class="" id="clear-refinements"></div>
                </div>

                <div class="flex justify-between px-1 mt-4">
                    <div id="powered-by"></div>
                    <div class="-mt-0.5 text-xs opacity-50" id="stats"></div>
                </div>

            </div>

            <div class="w-full overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div id="infinite-hits"></div>
            </div>

        </div>

    </div>

    <div class="mt-12 bg-white dark:bg-gray-800" 
        x-data="{
            playing: false,
            togglePlaying() {
                this.playing = !this.playing;
                if (this.playing)
                    $refs.audio.play();
                else
                    $refs.audio.pause();
            }
        }"
    >
        <div class="flex flex-col max-w-5xl px-6 pt-12 mx-auto overflow-hidden md:flex-row">

            <div>
                <h2 class="text-2xl font-semibold text-gray-900 font-display dark:text-white sm:text-3xl">
                    {{ __('Shopping Music') }}
                </h2>

                <p class="max-w-xl mt-2 text-base text-gray-400">
                    {{ __('Play some music while you search your products') }}
                </p>

                <div class="mt-6 sm:flex jusitfy-start">
                    <div
                        class="flex flex-col justify-center w-3/4 max-w-sm space-y-3 md:flex-row md:w-full md:space-x-3 md:space-y-0">
                        <button
                            class="flex-shrink-0 w-1/2 px-4 py-2 text-base font-semibold text-white uppercase rounded-lg shadow-md bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-primary-200"
                            type="submit" x-text="playing ? 'Pause' : 'Play'" x-on:click="togglePlaying">
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4 ml-auto mr-0 md:mt-0">
                <audio src="/music/fleta.mp3" x-ref="audio">
                    Your browser does not support the
                    <code>audio</code> element.
                </audio>
                <picture>
                    <source srcset="img/fleta.gif" type="image/gif" />
                    <img class="object-contain w-1/2 mx-auto maw-w-44" alt="shopping item" />
                </picture>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('instantsearch', () => ({
                    indexName: '{{ config('scout.prefix') }}products',
                    query: new URLSearchParams(window.location.search).get('q'),
                    category: '{{ $category_hierarchy_path }}',
                    brand: '{{ $brand ? $brand->name : null }}',
                    collection: '{{ $collection ? $collection->name : null }}',

                    init() {
                        var brand;
                        var collection;
                        if (this.brand) brand = [this.brand];
                        if (this.collection) collection = [this.collection];

                        searchClient = window.algoliasearch(
                            '{{ config('scout.algolia.id') }}',
                            '{{ config('scout.algolia.client') }}'
                        );

                        search = window.instantsearch({
                            indexName: this.indexName,
                            searchClient,
                            routing: true,
                            initialUiState: {
                                '{{ config('scout.prefix') }}products': {
                                    query: this.query,
                                    hierarchicalMenu: {
                                        'hierarchicalCategories.lvl0': [
                                            this.category,
                                        ],
                                    },
                                    refinementList: {
                                        'brand.name': brand,
                                        collections: collection,
                                    },
                                }
                            }
                        });

                        categoriesPanel = window.algoliaWidgets.panel({
                            templates: {
                                header: '{{ __('Categories') }}',
                            },
                            hidden(options) {
                                return options.items.length === 0;
                            },
                        })(window.algoliaWidgets.hierarchicalMenu);

                        brandsPanel = window.algoliaWidgets.panel({
                            templates: {
                                header: '{{ __('Brands') }}',
                            },
                            hidden(options) {
                                return options.items.length === 0;
                            },
                        })(window.algoliaWidgets.refinementList);

                        collectionsPanel = window.algoliaWidgets.panel({
                            templates: {
                                header: '{{ __('Collections') }}',
                            },
                            hidden(options) {
                                return options.items.length === 0;
                            },
                        })(window.algoliaWidgets.refinementList);

                        search.addWidgets([
                            window.algoliaWidgets.searchBox({
                                container: '#searchbox',
                                placeholder: '{{ __('Search...') }}',
                                autofocus: true,
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
                                attributesToSnippet: ['name:5', 'short_description:5',
                                    'description:5'
                                ],
                                snippetEllipsisText: '&hellip;'
                            }),

                            window.algoliaWidgets.infiniteHits({
                                container: '#infinite-hits',
                                templates: {
                                    empty(results, {html}) {
                                        return html`
                                            <div class="flex flex-col items-center justify-center py-12">
                                                <p class="mt-2 text-xl text-center">
                                                ${results.query && 
                                                    html`{{__('No results for') }} <strong>"${results.query}"</strong>`
                                                }
                                                ${!results.query && 
                                                    html`{{__('No results') }}`
                                                }
                                                </p>
                                                <img class="h-64" src="/img/searching.gif"/>
                                            </div>
                                        `;
                                    },
                                    item(hit, { html, components }) {
                                        return html`
                                            <div class='flex flex-col w-full max-w-xs mx-auto'>
                                                <a href='${hit.url}'>
                                                    <div class='w-full mb-6'>
                                                        <img class='object-cover w-auto h-48 mx-auto' src='${hit.image}'/>
                                                    </div>
                                                    <div class='flex flex-col'>
                                                        <h2 class='mb-1 font-bold'>
                                                            <span class="mr-1">
                                                                ${components.Highlight({ attribute: 'name', hit })}
                                                            </span>
                                                            ${ hit.avg_rating && 
                                                                html`${hit.avg_rating}<x-icons.star class="w-4 h-4"></x-icons.star>`
                                                            }
                                                            
                                                        </h2>
                                                        <h3 class="h-12 mb-2 font-medium">
                                                            ${ hit.short_description && 
                                                                html`${snippet({ attribute: 'short_description', hit })}`
                                                            }
                                                        </h3>
                                                        <div class='flex justify-between'>
                                                            <span>
                                                                ${ hit.has_variants && 
                                                                    html`${ hit.stock_status }`
                                                                }
                                                            </span>
                                                            <div class="relative flex flex-col">
                                                                ${ hit.discount>0 && 
                                                                    html`<span class="absolute ml-4 text-base text-gray-900 line-through -right-2 -top-4 dark:text-white">
                                                                            ${hit.taxed_original_price}€
                                                                        </span>`
                                                                }
                                                                <span class='font-bold'>
                                                                    ${hit.taxed_price}€
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        `;
                                    },
                                    showMoreText() {
                                        return '{{ __('Show more') }}';
                                    },
                                }
                            }),

                            window.algoliaWidgets.sortBy({
                                container: '#sort-by',
                                items: [{
                                        label: '{{ __('general.searchbar.options.featured') }}',
                                        value: '{{ config('scout.prefix') }}products'
                                    },
                                    {
                                        label: '{{ __('general.searchbar.options.recent') }}',
                                        value: '{{ config('scout.prefix') }}products_recent'
                                    },
                                    {
                                        label: '{{ __('general.searchbar.options.price_asc') }}',
                                        value: '{{ config('scout.prefix') }}products_price_asc'
                                    },
                                    {
                                        label: '{{ __('general.searchbar.options.price_desc') }}',
                                        value: '{{ config('scout.prefix') }}products_price_desc'
                                    },
                                ]
                            }),

                            categoriesPanel({
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

                            /* window.algoliaWidgets.currentRefinements({
                                container: '#current-refinements',
                                transformItems(items) {
                                    return items
                                        ? items
                                        : items.map(item => item.attribute='');
                                },
                            }), */

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

                            brandsPanel({
                                container: '#brands-list',
                                attribute: 'brand.name',
                                operator: 'or',
                                limit: 5,
                                showMore: true,
                                searchable: false,
                                searchablePlaceholder: '{{ __('Brands') }}...',
                                searchableIsAlwaysActive: false,
                                templates: {
                                    searchableNoResults() {
                                        return '{{ __('No results') }}';
                                    },
                                    showMoreText(data) {
                                        return data.isShowingMore ? '{{ __('Hide') }}' :
                                            '{{ __('Show') }}';
                                    },
                                },
                            }),

                            collectionsPanel({
                                container: '#collections-list',
                                attribute: 'collections',
                                operator: 'or',
                                limit: 5,
                                showMore: true,
                                searchable: false,
                                searchablePlaceholder: '{{ __('Collections') }}...',
                                searchableIsAlwaysActive: false,
                                templates: {
                                    searchableNoResults() {
                                        return '{{ __('No results') }}';
                                    },
                                    showMoreText(data) {
                                        return data.isShowingMore ? '{{ __('Hide') }}' :
                                            '{{ __('Show') }}';
                                    },
                                },
                            }),

                        ]);
                        search.start();
                    }
                }))
            })
        </script>
    @endpush

</x-app-layout>
