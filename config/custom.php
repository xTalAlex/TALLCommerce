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

    'stock_threshold' => 3,
];