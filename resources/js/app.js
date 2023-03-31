import './bootstrap';

import algoliasearch from 'algoliasearch/lite';

window.algoliasearch = algoliasearch;

import './algolia-autocomplete';

import './algolia-instant-search';

import { loadScript } from "@paypal/paypal-js";

window.paypalLoadScript = loadScript;

import KeenSlider from 'keen-slider';

window.KeenSlider = KeenSlider;

import Trix from "trix";

window.Trix = Trix;

import { DateTime } from "luxon";

window.DateTime = DateTime;

import flatpickr from "flatpickr";
// import { Italian } from "flatpickr/dist/l10n/it.js";
// flatpickr.localize(Italian);

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
