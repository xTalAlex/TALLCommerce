<?php

return [

    'title' => 'Modifica :label',

    'breadcrumb' => 'Modifica',

    'actions' => [

        'delete' => [

            'label' => 'Elimina',

            'modal' => [

                'heading' => 'Elimina :label',

                'subheading' => 'Sei sicuro di volerlo fare?',

                'buttons' => [

                    'delete' => [
                        'label' => 'Elimina',
                    ],

                ],

            ],

            'messages' => [
                'deleted' => 'Eliminato',
            ],
        ],

        'view' => [
            'label' => 'Visualizza',
        ],

    ],

    'form' => [

        'actions' => [

            'cancel' => [
                'label' => 'Annulla',
            ],

            'save' => [
                'label' => 'Salva',
            ],

        ],

    ],

    'messages' => [
        'saved' => 'Salvato',
    ],

];
