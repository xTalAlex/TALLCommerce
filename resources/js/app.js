import './bootstrap';

import algoliasearch from 'algoliasearch/lite';
import instantsearch from 'instantsearch.js';
import { autocomplete , getAlgoliaResults } from '@algolia/autocomplete-js';
import { createLocalStorageRecentSearchesPlugin } from '@algolia/autocomplete-plugin-recent-searches';
import { searchBox, voiceSearch, configure, infiniteHits, sortBy, breadcrumb, stats, panel, refinementList, menu, hierarchicalMenu, poweredBy, rangeInput, clearRefinements, currentRefinements } from 'instantsearch.js/es/widgets';

window.algoliasearch = algoliasearch;
window.instantsearch = instantsearch;
window.autocomplete = autocomplete;
window.getAlgoliaResults = getAlgoliaResults;
window.createLocalStorageRecentSearchesPlugin = createLocalStorageRecentSearchesPlugin;
window.searchBox = searchBox;
window.voiceSearch = voiceSearch;
window.configure = configure;
window.infiniteHits = infiniteHits;
window.sortBy = sortBy;
window.breadcrumb = breadcrumb;
window.stats = stats;
window.panel = panel;
window.refinementList = refinementList;
window.hierarchicalMenu = hierarchicalMenu;
window.clearRefinements = clearRefinements;
window.currentRefinements = currentRefinements;
window.poweredBy = poweredBy;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();