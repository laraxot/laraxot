<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Invia SMS',
<<<<<<< HEAD
<<<<<<< HEAD
        'group' => 'Test'],
=======
        'group' => 'Test',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'group' => 'Test'],
>>>>>>> a988596b (first)
    'fields' => [
        'to' => [
            'label' => 'Destinatario',
            'placeholder' => 'Inserisci numero di telefono',
            'helper_text' => 'Inserisci il numero con prefisso internazionale (es. +39)',
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
            'placeholder' => 'Inserisci testo del messaggio',
            'helper_text' => 'Il messaggio non può superare i 160 caratteri',
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
            'label' => 'Provider',
            'placeholder' => 'Seleziona provider SMS',
            'helper_text' => 'Seleziona il provider da utilizzare per l\'invio',
            'options' => [
                'smsfactor' => 'SMSFactor',
                'twilio' => 'Twilio',
                'nexmo' => 'Nexmo',
                'plivo' => 'Plivo',
                'gammu' => 'Gammu',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'netfun' => 'Netfun'],
            'tooltip' => '',
            'description' => '']],
    'actions' => [
        'send' => [
            'label' => 'Invia SMS',
            'tooltip' => 'Invia un messaggio SMS al destinatario']],
    'messages' => [
        'success' => 'SMS inviato con successo',
        'error' => 'Errore nell\'invio dell\'SMS: :error'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
<<<<<<< HEAD
=======
                'netfun' => 'Netfun',
            ],
            'tooltip' => '',
            'description' => '',
        ],
    ],
    'actions' => [
        'send' => [
            'label' => 'Invia SMS',
            'tooltip' => 'Invia un messaggio SMS al destinatario',
        ],
    ],
    'messages' => [
        'success' => 'SMS inviato con successo',
        'error' => 'Errore nell\'invio dell\'SMS: :error',
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
