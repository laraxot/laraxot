<?php

declare(strict_types=1);

return [
    'resource' => [
        'name' => 'WhatsApp',
<<<<<<< HEAD
<<<<<<< HEAD
        'plural' => 'WhatsApp'],
=======
        'plural' => 'WhatsApp',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'plural' => 'WhatsApp'],
>>>>>>> a988596b (first)
    'navigation' => [
        'name' => 'Invio WhatsApp',
        'plural' => 'Invio WhatsApp',
        'group' => [
            'name' => 'Notifiche',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => 'Gestione delle notifiche WhatsApp'],
        'label' => 'Invio WhatsApp',
        'icon' => 'heroicon-o-chat-bubble-left-right',
        'sort' => '20'],
<<<<<<< HEAD
=======
            'description' => 'Gestione delle notifiche WhatsApp',
        ],
        'label' => 'Invio WhatsApp',
        'icon' => 'heroicon-o-chat-bubble-left-right',
        'sort' => '20',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'fields' => [
        'to' => [
            'label' => 'Numero di telefono',
            'placeholder' => 'Inserisci il numero di telefono',
            'helper_text' => 'Inserisci il numero di telefono con prefisso internazionale (es. +39)',
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
            'placeholder' => 'Inserisci il messaggio',
            'helper_text' => 'Il messaggio non può superare i 4096 caratteri',
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
            'label' => 'Provider WhatsApp',
            'placeholder' => 'Seleziona il provider WhatsApp',
            'helper_text' => 'Seleziona il provider WhatsApp da utilizzare',
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
        'template' => [
            'label' => 'Template',
            'placeholder' => 'Inserisci il nome del template',
            'helper_text' => 'Nome del template (opzionale)',
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
        'parameters' => [
            'label' => 'Parametri',
            'placeholder' => 'Inserisci i parametri',
            'helper_text' => 'Parametri per il template (opzionale)',
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
        'media_url' => [
            'label' => 'URL Media',
            'placeholder' => 'Inserisci l\'URL del media',
            'helper_text' => 'URL del media (opzionale)',
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
        'media_type' => [
            'label' => 'Tipo Media',
            'placeholder' => 'Seleziona il tipo di media',
            'helper_text' => 'Seleziona il tipo di media',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => '']],
=======
            'description' => '',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => '']],
>>>>>>> a988596b (first)
    'drivers' => [
        'twilio' => 'Twilio',
        'messagebird' => 'MessageBird',
        'vonage' => 'Vonage',
<<<<<<< HEAD
<<<<<<< HEAD
        'infobip' => 'Infobip'],
=======
        'infobip' => 'Infobip',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'infobip' => 'Infobip'],
>>>>>>> a988596b (first)
    'media_types' => [
        'image' => 'Immagine',
        'video' => 'Video',
        'document' => 'Documento',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'audio' => 'Audio'],
    'actions' => [
        'send' => 'Invia WhatsApp',
        'cancel' => 'Annulla'],
    'messages' => [
        'success' => 'WhatsApp inviato con successo',
        'error' => 'Si è verificato un errore durante l\'invio del WhatsApp'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
<<<<<<< HEAD
=======
        'audio' => 'Audio',
    ],
    'actions' => [
        'send' => 'Invia WhatsApp',
        'cancel' => 'Annulla',
    ],
    'messages' => [
        'success' => 'WhatsApp inviato con successo',
        'error' => 'Si è verificato un errore durante l\'invio del WhatsApp',
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
