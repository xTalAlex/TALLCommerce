<nav class="bg-white border-gray-200 px-2 sm:px-4 py-2.5 rounded dark:bg-gray-800"
  x-data="{
    url: new URL('{{ route('product.index', [ 'category' => request()->category ]) }}'),
    updateUrl(event){
      this.url.searchParams.set('orderby',event.target.value);
      location.href = this.url.toString();
    },
  }"

  x-init="
    searchClient = window.algoliasearch(
      '{{ config('scout.algolia.id') }}',
      '{{ config('scout.algolia.client') }}'
    );
    window.autocomplete({
      detachedMediaQuery: '(min-width: 0px)',
      translations : {
        detachedCancelButtonText: '{{ __('Cancel') }}'
      },
      container: '#autocomplete',
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
                    indexName: 'products',
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
            onSubmit(e){
              console.log(e);
              window.location.href = window.location.origin;
            },
          },
        ];
      },
      navigator: {
        navigate({itemUrl}) {
          window.location.href= itemUrl;
        },
      }
    });
  "
>
  <div class="container flex flex-wrap items-end justify-between mx-auto">
    <div>
        <label for="orderby">{{ __('general.searchbar.sort_label') }}</label>
        <select name="orderby" id="orderby"
          x-on:change="updateUrl($event)"
          class="rounded-lg h-9"
        >
          <option value="default" @if(!request()->orderby || request()->orderby=="default" ) selected @endif>{{ __('general.searchbar.options.recent') }}
          </option>
          <option value="price_asc" @if(request()->orderby=="price_asc" ) selected @endif>{{ __('general.searchbar.options.price_asc') }}
          </option>
          <option value="price_desc" @if(request()->orderby=="price_desc" ) selected @endif>{{ __('general.searchbar.options.price_desc') }}
          </option> 
        </select>
    </div>
    <div id="autocomplete" >
    </div>
  </div>
</nav>