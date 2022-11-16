<?php

return [
    'attribute_types' => [
        'select' => 'Select',
        'radio'  => 'Radio',
        'color'  => 'Color'
    ],

    'payment_gateways' => [
        'stripe' => 'Stripe',
        'paypal' => 'PayPal',
    ],

    'reviews' => [
        'approved_by_default' => true,
    ],

    'datetime_format' => 'j/n/y H:i',
    'date_format' => 'j/n/y',
    'hour_format' => 'H:i',
    'datenoyear_format' => 'j/n',
    'monthyear_format' => 'n/y',

    'stock_threshold' => 3,

    'use_watermark' => false,
];