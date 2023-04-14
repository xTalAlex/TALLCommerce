import './bootstrap';

import { loadScript } from "@paypal/paypal-js";

window.paypalLoadScript = loadScript;

import Trix from "trix";

window.Trix = Trix;

import { DateTime } from "luxon";

window.DateTime = DateTime;

import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';

window.Swiper = Swiper;

window.Swiper.use([Navigation, Pagination, Autoplay]);

import flatpickr from "flatpickr";
// import { Italian } from "flatpickr/dist/l10n/it.js";
// flatpickr.localize(Italian);

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
