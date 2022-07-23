<?php

return [

    'single' => [

        'label' => 'Attach',

        'modal' => [

            'heading' => 'Attach :label',

            'fields' => [

                'record_ids' => [
                    'label' => 'Records',
                ],

            ],

            'actions' => [

                'attach' => [
                    'label' => 'Attach',
                ],

                'attach_another' => [
                    'label' => 'Attach & attach another',
                ],

            ],

        ],

        'messages' => [
            'attached' => 'Attached',
        ],

    ],

    'multiple' => [

        'label' => 'Attach selected',

        'modal' => [

            'heading' => 'Attach selected :label',

            'actions' => [

                'Attach' => [
                    'label' => 'Attach',
                ],

            ],

        ],

        'messages' => [
            'Attachd' => 'Attachd',
        ],

    ],

];
