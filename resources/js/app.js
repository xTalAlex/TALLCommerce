import './bootstrap';

import algoliasearch from 'algoliasearch/lite';

window.algoliasearch = algoliasearch;

import './algolia-autocomplete';

import './algolia-instant-search';

import { loadScript } from "@paypal/paypal-js";

window.paypalLoadScript = loadScript;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();