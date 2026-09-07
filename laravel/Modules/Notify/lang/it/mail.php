<?php

declare(strict_types=1);

return [
    'template' => [
        'navigation' => [
            'group' => 'Notifiche',
            'label' => 'Template Email',
            'plural' => 'Template Email',
            'singular' => 'Template Email',
            'icon' => 'heroicon-o-envelope',
<<<<<<< HEAD
<<<<<<< HEAD
            'sort' => 1],
        'sections' => [
            'main' => 'Informazioni Principali'],
=======
            'sort' => 1,
        ],
        'sections' => [
            'main' => 'Informazioni Principali',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'sort' => 1],
        'sections' => [
            'main' => 'Informazioni Principali'],
>>>>>>> a988596b (first)
        'fields' => [
            'name' => [
                'label' => 'Nome',
                'placeholder' => 'Inserisci il nome del template',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'tooltip' => 'Il nome identificativo del template email'],
            'layout' => [
                'label' => 'Layout',
                'placeholder' => 'Seleziona il layout del template',
                'tooltip' => 'Il layout grafico che verrà utilizzato per l\'email'],
            'mailable' => [
                'label' => 'Classe Mailable',
                'placeholder' => 'Inserisci il nome della classe Mailable',
                'tooltip' => 'La classe PHP che gestisce l\'invio dell\'email'],
            'subject' => [
                'label' => 'Oggetto',
                'placeholder' => 'Inserisci l\'oggetto dell\'email',
                'tooltip' => 'L\'oggetto che apparirà nell\'email'],
            'body_html' => [
                'label' => 'Contenuto HTML',
                'placeholder' => 'Inserisci il contenuto HTML dell\'email',
                'tooltip' => 'Il contenuto dell\'email in formato HTML'],
            'body_text' => [
                'label' => 'Contenuto Testo',
                'placeholder' => 'Inserisci il contenuto testuale dell\'email',
                'tooltip' => 'Versione testuale dell\'email per client che non supportano HTML']],
        'actions' => [
            'preview' => [
                'label' => 'Anteprima',
                'tooltip' => 'Visualizza un\'anteprima del template']],
        'messages' => [
            'created' => 'Template email creato con successo',
            'updated' => 'Template email aggiornato con successo',
            'deleted' => 'Template email eliminato con successo']],
<<<<<<< HEAD
=======
                'tooltip' => 'Il nome identificativo del template email',
            ],
            'layout' => [
                'label' => 'Layout',
                'placeholder' => 'Seleziona il layout del template',
                'tooltip' => 'Il layout grafico che verrà utilizzato per l\'email',
            ],
            'mailable' => [
                'label' => 'Classe Mailable',
                'placeholder' => 'Inserisci il nome della classe Mailable',
                'tooltip' => 'La classe PHP che gestisce l\'invio dell\'email',
            ],
            'subject' => [
                'label' => 'Oggetto',
                'placeholder' => 'Inserisci l\'oggetto dell\'email',
                'tooltip' => 'L\'oggetto che apparirà nell\'email',
            ],
            'body_html' => [
                'label' => 'Contenuto HTML',
                'placeholder' => 'Inserisci il contenuto HTML dell\'email',
                'tooltip' => 'Il contenuto dell\'email in formato HTML',
            ],
            'body_text' => [
                'label' => 'Contenuto Testo',
                'placeholder' => 'Inserisci il contenuto testuale dell\'email',
                'tooltip' => 'Versione testuale dell\'email per client che non supportano HTML',
            ],
        ],
        'actions' => [
            'preview' => [
                'label' => 'Anteprima',
                'tooltip' => 'Visualizza un\'anteprima del template',
            ],
        ],
        'messages' => [
            'created' => 'Template email creato con successo',
            'updated' => 'Template email aggiornato con successo',
            'deleted' => 'Template email eliminato con successo',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'label' => 'Mail',
    'plural_label' => 'Mail (Plurale)',
    'navigation' => [
        'name' => 'Mail',
        'plural' => 'Mail',
        'group' => [
            'name' => 'General',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => 'General Settings'],
        'label' => 'Mail',
        'sort' => 1,
        'icon' => 'heroicon-o-collection'],
<<<<<<< HEAD
=======
            'description' => 'General Settings',
        ],
        'label' => 'Mail',
        'sort' => 1,
        'icon' => 'heroicon-o-collection',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'fields' => [
        'id' => [
            'label' => 'Identificativo',
            'tooltip' => 'Identificativo univoco del record',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'created_at' => [
            'label' => 'Data Creazione',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'updated_at' => [
            'label' => 'Ultima Modifica',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => '']],
    'actions' => [
        'create' => [
            'label' => 'Crea Mail'],
        'edit' => [
            'label' => 'Modifica Mail'],
        'delete' => [
            'label' => 'Elimina Mail']]];
<<<<<<< HEAD
=======
            'description' => '',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Crea Mail',
        ],
        'edit' => [
            'label' => 'Modifica Mail',
        ],
        'delete' => [
            'label' => 'Elimina Mail',
        ],
    ],
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
