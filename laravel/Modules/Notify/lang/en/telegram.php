<?php

declare(strict_types=1);

return [
    'resource' => [
        'name' => 'Telegram',
<<<<<<< HEAD
<<<<<<< HEAD
        'plural' => 'Telegram'],
=======
        'plural' => 'Telegram',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'plural' => 'Telegram'],
>>>>>>> a988596b (first)
    'navigation' => [
        'name' => 'Invio Telegram',
        'plural' => 'Invio Telegram',
        'group' => [
            'name' => 'Notifiche',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => 'Gestione delle notifiche Telegram'],
        'label' => 'Invio Telegram',
        'icon' => 'notify-telegram-animated',
        'sort' => '30'],
<<<<<<< HEAD
=======
            'description' => 'Gestione delle notifiche Telegram',
        ],
        'label' => 'Invio Telegram',
        'icon' => 'notify-telegram-animated',
        'sort' => '30',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'fields' => [
        'chat_id' => [
            'label' => 'ID Chat',
            'placeholder' => 'Inserisci l\'ID della chat',
            'helper_text' => 'ID della chat Telegram a cui inviare il messaggio',
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
            'helper_text' => 'Testo del messaggio da inviare',
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
        'parse_mode' => [
            'label' => 'Formato',
            'placeholder' => 'Seleziona il formato',
            'helper_text' => 'Formato di parsing del messaggio',
            'options' => [
                'text' => 'Testo semplice',
                'html' => 'HTML',
<<<<<<< HEAD
<<<<<<< HEAD
                'markdown' => 'Markdown'],
            'tooltip' => '',
            'description' => ''],
=======
                'markdown' => 'Markdown',
            ],
            'tooltip' => '',
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'markdown' => 'Markdown'],
            'tooltip' => '',
            'description' => ''],
>>>>>>> a988596b (first)
        'driver' => [
            'label' => 'Provider Telegram',
            'placeholder' => 'Seleziona il provider Telegram',
            'helper_text' => 'Seleziona il provider Telegram da utilizzare',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => '']],
    'drivers' => [
        'telegram' => 'Telegram',
        'botapi' => 'Bot API',
        'laravel_telegram' => 'Laravel Telegram'],
    'actions' => [
        'send' => 'Invia Telegram',
        'cancel' => 'Annulla'],
    'messages' => [
        'success' => 'Messaggio Telegram inviato con successo',
        'error' => 'Si è verificato un errore durante l\'invio del messaggio Telegram'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
<<<<<<< HEAD
=======
            'description' => '',
        ],
    ],
    'drivers' => [
        'telegram' => 'Telegram',
        'botapi' => 'Bot API',
        'laravel_telegram' => 'Laravel Telegram',
    ],
    'actions' => [
        'send' => 'Invia Telegram',
        'cancel' => 'Annulla',
    ],
    'messages' => [
        'success' => 'Messaggio Telegram inviato con successo',
        'error' => 'Si è verificato un errore durante l\'invio del messaggio Telegram',
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
