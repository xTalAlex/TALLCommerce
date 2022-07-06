import './bootstrap';

import algoliasearch from 'algoliasearch/lite';
import instantsearch from 'instantsearch.js';
import { autocomplete , getAlgoliaResults } from '@algolia/autocomplete-js';
import { searchBox, hits } from 'instantsearch.js/es/widgets';

window.algoliasearch = algoliasearch;
window.instantsearch = instantsearch;
window.autocomplete = autocomplete;
window.getAlgoliaResults = getAlgoliaResults;
window.searchBox = searchBox;
window.hits = hits;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();