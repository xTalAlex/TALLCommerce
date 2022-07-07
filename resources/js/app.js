import './bootstrap';

import algoliasearch from 'algoliasearch/lite';
import instantsearch from 'instantsearch.js';
import { autocomplete , getAlgoliaResults } from '@algolia/autocomplete-js';
import { searchBox, hits, pagination, sortBy, breadcrumb, stats, voiceSearch, panel, refinementList, clearRefinements, currentRefinements } from 'instantsearch.js/es/widgets';

window.algoliasearch = algoliasearch;
window.instantsearch = instantsearch;
window.autocomplete = autocomplete;
window.getAlgoliaResults = getAlgoliaResults;
window.searchBox = searchBox;
window.hits = hits;
window.pagination = pagination;
window.sortBy = sortBy;
window.breadcrumb = breadcrumb;
window.stats = stats;
window.voiceSearch = voiceSearch;
window.panel = panel;
window.refinementList = refinementList;
window.clearRefinements = clearRefinements;
window.currentRefinements = currentRefinements;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();