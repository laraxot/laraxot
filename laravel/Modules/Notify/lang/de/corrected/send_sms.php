<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'SMS senden',
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
            'label' => 'Empfänger',
            'placeholder' => 'Telefonnummer eingeben',
            'helper_text' => 'Telefonnummer mit internationaler Vorwahl eingeben (z.B. +49)',
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
            'label' => 'Nachricht',
            'placeholder' => 'Nachrichtentext eingeben',
            'helper_text' => 'Nachricht darf 160 Zeichen nicht überschreiten',
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
            'label' => 'Anbieter',
            'placeholder' => 'SMS-Anbieter auswählen',
            'helper_text' => 'Wählen Sie den Anbieter für den Versand',
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
            'label' => 'SMS senden',
            'tooltip' => 'SMS-Nachricht an den Empfänger senden']],
    'messages' => [
        'success' => 'SMS erfolgreich gesendet',
        'error' => 'Fehler beim Senden der SMS: :error'],
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
            'label' => 'SMS senden',
            'tooltip' => 'SMS-Nachricht an den Empfänger senden',
        ],
    ],
    'messages' => [
        'success' => 'SMS erfolgreich gesendet',
        'error' => 'Fehler beim Senden der SMS: :error',
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
