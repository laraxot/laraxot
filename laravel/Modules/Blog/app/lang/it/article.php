<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Articolo',
        'plural' => 'Articoli',
        'group' => [
            'name' => 'Contenuti',
        ],
    ],
    'rating' => [
        'no_import' => 'Nessuna cifra inserita',
        'import_zero' => 'Nessuna cifra inserita',
        'import_min' => 'Hai superato la cifra di :credits: crediti',
        'no_choice' => 'Nessuna opzione scelta',
    ],
    'status' => [
        'single_expired' => 'Scaduto',
        'expired' => 'Articolo scaduto, non si possono fare più scommesse',
        'no_vote' => 'Siamo spiacenti, ma questa votazione è chiusa da :TIME, per favore prova a fare un\'altra previsione',
    ],
    'betting' => [
        'your_bet' => 'La tua previsione',
        'your_amount' => 'Importo scommesso',
        'if_win' => 'Potenziale vincita',
    ],
    'fields' => [
        'id' => [
            'label' => 'ID',
            'help' => 'Identificativo univoco dell\'articolo',
        ],
        'title' => [
            'label' => 'Titolo',
            'help' => 'Titolo dell\'articolo',
        ],
        'category' => [
            'title' => [
                'label' => 'Categoria',
                'help' => 'Categoria dell\'articolo',
            ],
        ],
        'published_at' => [
            'label' => 'Data pubblicazione',
            'help' => 'Data di pubblicazione dell\'articolo',
        ],
        'closed_at' => [
            'label' => 'Data chiusura',
            'help' => 'Data di chiusura dell\'articolo',
        ],
        'rewarded_at' => [
            'label' => 'Premiato il',
            'help' => 'Data di premiazione dell\'articolo',
        ],
        'is_featured' => [
            'label' => 'In evidenza',
            'help' => 'Indica se l\'articolo è in evidenza',
        ],
        'fileContent' => [
            'label' => 'Contenuto file',
            'help' => 'Contenuto del file allegato',
        ],
        'file' => [
            'label' => 'File',
            'help' => 'File allegato all\'articolo',
        ],
        'main_image_upload' => [
            'label' => 'Carica immagine principale',
            'help' => 'Carica l\'immagine principale dell\'articolo',
        ],
        'main_image_url' => [
            'label' => 'URL immagine principale',
            'help' => 'URL dell\'immagine principale dell\'articolo',
        ],
        'category_id' => [
            'label' => 'ID categoria',
            'help' => 'Identificativo della categoria',
        ],
        'slug' => [
            'label' => 'Slug',
            'help' => 'URL-friendly versione del titolo',
        ],
    ],
    'actions' => [
        'import' => [
            'label' => 'Importa',
            'help' => 'Importa articoli da fonte esterna',
        ],
        'create' => [
            'label' => 'Crea',
            'help' => 'Crea nuovo articolo',
        ],
        'edit' => [
            'label' => 'Modifica',
            'help' => 'Modifica articolo esistente',
        ],
        'view' => [
            'label' => 'Visualizza',
            'help' => 'Visualizza dettagli articolo',
        ],
        'delete' => [
            'label' => 'Elimina',
            'help' => 'Elimina articolo',
        ],
        'activeLocale' => [
            'label' => 'Lingua attiva',
            'help' => 'Imposta lingua attiva per l\'articolo',
        ],
    ],
];
