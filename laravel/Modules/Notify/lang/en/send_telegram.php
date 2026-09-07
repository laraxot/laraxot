<?php

declare(strict_types=1);

return [
    'resource' => [
        'name' => 'Invio Telegram',
<<<<<<< HEAD
<<<<<<< HEAD
        'plural' => 'Invio Telegram'],
=======
        'plural' => 'Invio Telegram',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'plural' => 'Invio Telegram'],
>>>>>>> a988596b (first)
    'navigation' => [
        'name' => 'Invio Telegram',
        'plural' => 'Invio Telegram',
        'group' => [
            'name' => 'Sistema',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => 'Funzionalità per l\'invio di messaggi attraverso Telegram'],
        'label' => 'Invio Telegram',
        'icon' => 'notify-telegram-animated',
        'sort' => '50'],
<<<<<<< HEAD
=======
            'description' => 'Funzionalità per l\'invio di messaggi attraverso Telegram',
        ],
        'label' => 'Invio Telegram',
        'icon' => 'notify-telegram-animated',
        'sort' => '50',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'fields' => [
        'chat_id' => [
            'label' => 'ID Chat',
            'placeholder' => 'Inserisci l\'ID della chat',
            'helper_text' => 'ID della chat Telegram di destinazione',
            'description' => 'Identificativo univoco della chat Telegram',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'message' => [
            'label' => 'Messaggio',
            'placeholder' => 'Inserisci il messaggio da inviare',
            'helper_text' => 'Contenuto del messaggio Telegram',
            'description' => 'Testo del messaggio da inviare tramite Telegram',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'parse_mode' => [
            'label' => 'Formato',
            'placeholder' => 'Seleziona il formato',
            'helper_text' => 'Formato di interpretazione del messaggio',
            'description' => 'Modalità di formattazione del messaggio',
            'options' => [
                'text' => 'Testo semplice',
                'html' => 'HTML',
<<<<<<< HEAD
<<<<<<< HEAD
                'markdown' => 'Markdown'],
            'tooltip' => '']],
=======
                'markdown' => 'Markdown',
            ],
            'tooltip' => '',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'markdown' => 'Markdown'],
            'tooltip' => '']],
>>>>>>> a988596b (first)
    'actions' => [
        'send' => [
            'label' => 'Invia Messaggio',
            'tooltip' => 'Invia un messaggio tramite Telegram',
            'success_message' => 'Messaggio inviato con successo',
            'error_message' => 'Errore nell\'invio del messaggio',
            'success' => 'Messaggio inviato con successo',
<<<<<<< HEAD
<<<<<<< HEAD
            'error' => 'Errore durante l\'invio del messaggio'],
=======
            'error' => 'Errore durante l\'invio del messaggio',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error' => 'Errore durante l\'invio del messaggio'],
>>>>>>> a988596b (first)
        'preview' => [
            'label' => 'Anteprima',
            'tooltip' => 'Visualizza un\'anteprima del messaggio',
            'success_message' => 'Anteprima generata',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'error_message' => 'Errore nella generazione dell\'anteprima']],
    'messages' => [
        'success' => 'Messaggio Telegram inviato con successo',
        'error' => 'Si è verificato un errore durante l\'invio del messaggio Telegram',
        'confirmation' => 'Sei sicuro di voler inviare questo messaggio Telegram?'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
<<<<<<< HEAD
=======
            'error_message' => 'Errore nella generazione dell\'anteprima',
        ],
    ],
    'messages' => [
        'success' => 'Messaggio Telegram inviato con successo',
        'error' => 'Si è verificato un errore durante l\'invio del messaggio Telegram',
        'confirmation' => 'Sei sicuro di voler inviare questo messaggio Telegram?',
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
