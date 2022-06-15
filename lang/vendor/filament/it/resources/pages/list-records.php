<?php

return [

    'breadcrumb' => 'Lista',

    'actions' => [

        'create' => [
            'label' => 'Aggiungi :label',

            'modal' => [

                'heading' => 'Aggiungi :label',

                'actions' => [

                    'create' => [
                        'label' => 'Aggiungi',
                    ],

                    'create_and_create_another' => [
                        'label' => 'Aggiungi & aggiungi un altro',
                    ],

                ],

            ],

            'messages' => [
                'created' => 'Creato',
            ],
        ],

    ],

    'table' => [

        'actions' => [
            'delete' => [

                'label' => 'Elimina',

                'modal' => [
                    'heading' => 'Elimina :label',
                ],

                'messages' => [
                    'deleted' => 'Eliminato',
                ],

            ],
            'edit' => [
                'label' => 'Modifica',

                'modal' => [

                    'heading' => 'Modifica :label',

                    'actions' => [

                        'save' => [
                            'label' => 'Salva',
                        ],

                    ],

                ],

                'messages' => [
                    'saved' => 'Salvato',
                ],
            ],

            'view' => [
                'label' => 'Visualizza',

                'modal' => [

                    'heading' => 'Visualizza :label',

                    'actions' => [

                        'close' => [
                            'label' => 'Chiudi',
                        ],

                    ],

                ],
            ],

        ],

        'bulk_actions' => [

            'delete' => [
                'label' => 'Elimina selezionati',
                'messages' => [
                    'deleted' => 'Eliminato',
                ],
            ],

        ],

    ],

];
