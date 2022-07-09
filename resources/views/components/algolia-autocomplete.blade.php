<div {{ $attributes->merge(['class' => ''])}}
    x-data
    x-init="
    searchClient = window.algoliasearch(
      '{{ config('scout.algolia.id') }}',
      '{{ config('scout.algolia.client') }}'
    );
    recentSearchesPlugin = createLocalStorageRecentSearchesPlugin({
      key: 'RECENT_SEARCH',
      limit: 5,
      transformSource({ source, onRemove }) {
        return {
          ...source,
          templates: {
            ...source.templates,
            item(params) {
              const { item, html } = params;
              return html`<a
                class='aa-ItemLink'
                href='{{ route('product.index') }}?keyword=${item.label}'
              >
                ${source.templates.item(params).props.children}
              </a>`;
            },
          },
        };
      },
    });
    window.autocomplete({
      detachedMediaQuery: '(min-width: 0px)',
      translations : {
        detachedCancelButtonText: '{{ __('Cancel') }}'
      },
      container: '#autocomplete',
      plugins: [recentSearchesPlugin],
      openOnFocus: true,
      placeholder: '{{__('Search...')}}',
      debug:false,
      getSources({ query }) {
        return [
          {
            sourceId: 'products',
            getItemUrl({ item }) {
              return item.url;
            },
            getItems() {
              return getAlgoliaResults({
                searchClient,
                queries: [
                  {
                    indexName: '{{ config('scout.prefix') }}products',
                    query,
                    params: {
                      hitsPerPage: 5,
                      attributesToSnippet: ['name:10', 'short_description:35'],
                      snippetEllipsisText: '…',
                    },
                  },
                ],
              });
            },
            templates: {
              item({ item, components, html }) {
                return html`<div class='w-full border-b'>
                              <div class='flex flex-row w-full space-x-2'>
                                
                                <div class='items-start justify-center flex-shrink-0 h-full'>
                                  <img
                                    src='${item.image}'
                                    alt='${item.name}'
                                    class='object-cover w-10 h-10'
                                  />
                                </div>

                                <div class='flex flex-col w-full'>
                                  <div class='text-base font-bold'>
                                    ${components.Highlight({
                                      hit: item,
                                      attribute: 'name',
                                    })}
                                  </div>
                                  <div class='text-sm'>
                                    ${item.short_description !== null ? 
                                      components.Snippet({
                                        hit: item,
                                        attribute: 'short_description',
                                      }) 
                                      : ''
                                    }
                                  </div>
                                  <div class='text-base text-right'>
                                    ${item.price}€
                                  </div>
                                </div>
                                
                              </div>
                            </div>`;
              },
              noResults() {
                return '{{ __('No results') }}';
              },
            },
            onSelect({item}){
              window.location.href = item.url;
            },
          },
        ];
      },
      onSubmit({state}){
        if(state.query!=''){
          url = new URL('{{ route('product.index') }}');
          var searchParams = new URLSearchParams(url.search);
          searchParams.set('keyword', state.query);
          window.location.href = url +'/?'+ searchParams.toString();
        }
      },
      navigator: {
        navigate({itemUrl}) {
          window.location.href= itemUrl;
        },
      }
    });
  "
>
    <div id="autocomplete">
    </div>
</div>

@push('scripts')

@endpush