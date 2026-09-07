<?php

declare(strict_types=1);

return [
    'fields' => [
        'recipient' => [
            'label' => 'Destinatario',
            'helper_text' => 'Inserisci il numero di telefono nel formato internazionale (es. +393401234567).',
            'tooltip' => '',
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
        'to' => [
            'label' => 'Destinatario',
            'helper_text' => 'Inserisci il numero di telefono nel formato internazionale (es. +393401234567).',
            'tooltip' => '',
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
        'message' => [
            'label' => 'Messaggio',
            'helper_text' => 'Inserisci il contenuto del messaggio (max 160 caratteri per un singolo SMS).',
            'tooltip' => '',
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
        'driver' => [
            'label' => 'Driver SMS',
            'helper_text' => 'Seleziona il provider per l\'invio dell\'SMS.',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => '']],
    'actions' => [
        'send' => 'Invia SMS'],
    'notifications' => [
        'sent' => [
            'title' => 'SMS Inviato',
            'body' => 'Il messaggio è stato preso in carico dal provider.'],
        'error' => [
            'title' => 'Errore Invio',
            'body' => 'Si è verificato un errore durante l\'invio dell\'SMS.']],
    'form' => [
        'to' => [
            'label' => 'Destinatario',
            'helper' => 'Numero di telefono con prefisso internazionale.'],
        'from' => [
            'label' => 'Mittente',
            'helper' => 'Nome o numero del mittente (max 11 caratteri).'],
        'body' => [
            'label' => 'Testo del Messaggio',
            'helper' => 'Contenuto dell\'SMS da inviare.'],
        'provider' => [
            'label' => 'Provider']],
<<<<<<< HEAD
=======
            'description' => '',
        ],
    ],
    'actions' => [
        'send' => 'Invia SMS',
    ],
    'notifications' => [
        'sent' => [
            'title' => 'SMS Inviato',
            'body' => 'Il messaggio è stato preso in carico dal provider.',
        ],
        'error' => [
            'title' => 'Errore Invio',
            'body' => 'Si è verificato un errore durante l\'invio dell\'SMS.',
        ],
    ],
    'form' => [
        'to' => [
            'label' => 'Destinatario',
            'helper' => 'Numero di telefono con prefisso internazionale.',
        ],
        'from' => [
            'label' => 'Mittente',
            'helper' => 'Nome o numero del mittente (max 11 caratteri).',
        ],
        'body' => [
            'label' => 'Testo del Messaggio',
            'helper' => 'Contenuto dell\'SMS da inviare.',
        ],
        'provider' => [
            'label' => 'Provider',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'navigation' => [
        'label' => 'Missing Navigation Label',
        'plural_label' => 'Missing Navigation Plural Label',
        'group' => 'Missing Group',
        'icon' => 'heroicon-o-puzzle-piece',
<<<<<<< HEAD
<<<<<<< HEAD
        'sort' => 100],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
=======
        'sort' => 100,
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'sort' => 100],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
>>>>>>> a988596b (first)
