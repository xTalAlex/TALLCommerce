import instantsearch from 'instantsearch.js';

window.instantsearch = instantsearch;

import { searchBox,
    voiceSearch,
    configure,
    infiniteHits,
    sortBy,
    breadcrumb,
    stats,
    panel,
    refinementList,
    menu,
    hierarchicalMenu,
    poweredBy,
    rangeInput,
    clearRefinements,
    currentRefinements } from 'instantsearch.js/es/widgets';

window.algoliaWidgets = {
    searchBox : searchBox,
    voiceSearch : voiceSearch,
    configure : configure,
    infiniteHits : infiniteHits,
    sortBy : sortBy,
    breadcrumb : breadcrumb,
    stats : stats,
    panel : panel,
    refinementList : refinementList,
    menu : menu,
    hierarchicalMenu :hierarchicalMenu,
    poweredBy : poweredBy,
    rangeInput : rangeInput,
    clearRefinements : clearRefinements,
    currentRefinements : currentRefinements
};