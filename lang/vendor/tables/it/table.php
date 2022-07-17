<?php

return [

    'columns' => [

        'tags' => [
            'more' => 'e altri :count',
        ],

    ],

    'fields' => [

        'search_query' => [
            'label' => 'Cerca',
            'placeholder' => 'Cerca',
        ],

    ],

    'pagination' => [

        'label' => 'Navigazione paginazione',

        'overview' => 'Mostrati i risultati da :first a :last di :total',

        'fields' => [

            'records_per_page' => [
                'label' => 'per pagina',
            ],

        ],

        'buttons' => [

            'go_to_page' => [
                'label' => 'Vai a pagina :page',
            ],

            'next' => [
                'label' => 'Successivo',
            ],

            'previous' => [
                'label' => 'Precedente',
            ],

        ],

    ],

    'buttons' => [

        'filter' => [
            'label' => 'Filtra',
        ],

        'open_actions' => [
            'label' => 'Azioni aperte',
        ],

        'toggle_columns' => [
            'label' => 'Alterna colonne',
        ],

    ],

    'empty' => [
        'heading' => 'Nessun risultato trovato',
    ],

    'filters' => [

        'buttons' => [

            'reset' => [
                'label' => 'Azzera filtri',
            ],

            'close' => [
                'label' => 'Chiudi',
            ],

        ],

        'multi_select' => [
            'placeholder' => 'Tutti',
        ],

        'select' => [
            'placeholder' => 'Tutti',
        ],

        'trashed' => [

            'label' => 'Elementi eliminati',

            'only_trashed' => 'Solo eliminati',

            'with_trashed' => 'Con eliminati',

            'without_trashed' => 'Senza eliminati',

        ],
    ],

    'selection_indicator' => [
        'selected_count' => '1 elemento selezionato.|:count elementi selezionati.',
        'buttons' => [

            'select_all' => [
                'label' => 'Seleziona tutti e :count',
            ],

            'deselect_all' => [
                'label' => 'Deseleziona tutti',
            ],

        ],

    ],

];
