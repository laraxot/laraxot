<?php

declare(strict_types=1);

return [
    'actions' => [
        'create' => ['label' => 'Crea', 'tooltip' => 'Crea nuovo file di traduzione', 'success' => 'File di traduzione creato con successo', 'icon' => 'create'],
        'lang' => ['label' => 'Lingua', 'tooltip' => 'Seleziona lingua', 'icon' => 'lang'],
    ],
    'fields' => [
        'edit' => ['label' => 'Modifica', 'tooltip' => 'Modifica file di traduzione', 'helper_text' => '', 'description' => ''],
        'toggleColumns' => ['label' => 'Mostra/Nascondi Colonne', 'tooltip' => 'Mostra o nascondi colonne della tabella', 'helper_text' => '', 'description' => ''],
        'reorderRecords' => ['label' => 'Riordina Record', 'tooltip' => 'Riordina i record nella tabella', 'helper_text' => '', 'description' => ''],
        'resetFilters' => ['label' => 'Reset Filtri', 'tooltip' => 'Ripristina i filtri ai valori predefiniti', 'helper_text' => '', 'description' => ''],
        'content' => [
            'description' => 'Contenuto del file di traduzione',
            'helper_text' => '',
            'placeholder' => 'Inserisci contenuto traduzione',
            'label' => 'Contenuto',
            'tooltip' => '',
            'a' => ['label' => 'content.a', 'placeholder' => 'content.a', 'helper_text' => 'content.a', 'description' => 'content.a'],
            'b' => [
                'c' => ['label' => 'content.b.c', 'placeholder' => 'content.b.c', 'helper_text' => 'content.b.c', 'description' => 'content.b.c'],
            ],
            'hello' => ['label' => 'content.hello', 'placeholder' => 'content.hello', 'helper_text' => 'content.hello', 'description' => 'content.hello'],
            'nested' => [
                'x' => ['label' => 'content.nested.x', 'placeholder' => 'content.nested.x', 'helper_text' => 'content.nested.x', 'description' => 'content.nested.x'],
            ],
        ],
        'applyFilters' => ['label' => 'Applica Filtri', 'tooltip' => 'Applica i filtri selezionati', 'helper_text' => '', 'description' => ''],
        'snapshots' => [
            'fields' => [
                'updated_at' => [
                    'help' => ['description' => 'Data e ora dell\'ultimo aggiornamento', 'helper_text' => '', 'placeholder' => 'Data aggiornamento', 'label' => 'Data Aggiornamento'],
                    'label' => ['description' => 'Etichetta per la data di aggiornamento', 'helper_text' => '', 'placeholder' => 'Etichetta data', 'label' => 'Etichetta Data'],
                ],
            ],
            'label' => '',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'openFilters' => ['label' => 'Apri Filtri', 'tooltip' => 'Apri il pannello dei filtri', 'helper_text' => '', 'description' => ''],
        'key' => ['label' => 'Chiave', 'placeholder' => 'Inserisci chiave traduzione', 'help' => 'Chiave identificativa della traduzione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'delete' => ['label' => 'delete', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'title' => ['label' => 'title', 'placeholder' => 'title', 'helper_text' => 'title', 'description' => 'title'],
        'meta' => [
            'description' => ['label' => 'meta.description', 'placeholder' => 'meta.description', 'helper_text' => 'meta.description', 'description' => 'meta.description'],
        ],
    ],
    'navigation' => ['label' => 'File Traduzione', 'group' => 'Lang', 'icon' => 'heroicon-o-language', 'sort' => 73],
    'model' => ['label' => 'File Traduzione', 'placeholder' => 'Seleziona file traduzione', 'helper_text' => 'File di traduzione per la gestione delle lingue'],
    'label' => 'Translation File',
    'plural_label' => 'Translation File (Plurale)',
    'sections' => [
        'meta' => ['label' => 'meta', 'heading' => 'meta'],
        'content' => ['label' => 'content', 'heading' => 'content'],
        'b' => ['label' => 'b', 'heading' => 'b'],
        'nested' => ['label' => 'nested', 'heading' => 'nested'],
    ],
];
