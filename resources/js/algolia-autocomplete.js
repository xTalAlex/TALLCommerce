import { autocomplete , getAlgoliaResults } from '@algolia/autocomplete-js';

window.autocomplete = autocomplete;
window.getAlgoliaResults = getAlgoliaResults;

import { createLocalStorageRecentSearchesPlugin } from '@algolia/autocomplete-plugin-recent-searches';

window.createLocalStorageRecentSearchesPlugin = createLocalStorageRecentSearchesPlugin;