import './bootstrap';

import algoliasearch from 'algoliasearch/lite';
import { autocomplete , getAlgoliaResults } from '@algolia/autocomplete-js';

window.algoliasearch = algoliasearch;
window.autocomplete = autocomplete;
window.getAlgoliaResults = getAlgoliaResults;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


