<?php

declare(strict_types=1);

return [
    'send_notification_bulk' => [
        'label' => 'Invia notifiche',
        'form' => [
            'template_slug' => [
                'label' => 'Template',
                'placeholder' => 'Seleziona un template',
<<<<<<< HEAD
<<<<<<< HEAD
                'helper_text' => 'Seleziona il template di notifica da utilizzare'],
=======
                'helper_text' => 'Seleziona il template di notifica da utilizzare',
            ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'helper_text' => 'Seleziona il template di notifica da utilizzare'],
>>>>>>> a988596b (first)
            'channels' => [
                'label' => 'Canali',
                'helper_text' => 'Seleziona uno o più canali di invio',
                'options' => [
                    'mail' => 'Email',
                    'sms' => 'SMS',
<<<<<<< HEAD
<<<<<<< HEAD
                    'whatsapp' => 'WhatsApp']]],
=======
                    'whatsapp' => 'WhatsApp',
                ],
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'whatsapp' => 'WhatsApp']]],
>>>>>>> a988596b (first)
        'errors' => [
            'unsupported_channel' => 'Canale :channel non supportato',
            'email_not_available' => 'Email non disponibile per questo record',
            'phone_not_available' => 'Numero di telefono non disponibile per questo record',
            'whatsapp_not_available' => 'Numero WhatsApp non disponibile per questo record',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'channel_not_sent' => 'Canale non inviato (dati non disponibili]'],
        'notifications' => [
            'success' => [
                'title' => 'Notifiche inviate',
                'body' => 'Inviate :count notifiche su :total con successo'],
            'warning' => [
                'title' => 'Dati non validi',
                'invalid_data' => 'Template e almeno un canale devono essere selezionati'],
            'error' => [
                'title' => 'Alcune notifiche non sono state inviate',
                'item' => 'Record :record (canale :channel]: :error',
                'more_errors' => '... e altri :count errori']]],
<<<<<<< HEAD
=======
            'channel_not_sent' => 'Canale non inviato (dati non disponibili]',
        ],
        'notifications' => [
            'success' => [
                'title' => 'Notifiche inviate',
                'body' => 'Inviate :count notifiche su :total con successo',
            ],
            'warning' => [
                'title' => 'Dati non validi',
                'invalid_data' => 'Template e almeno un canale devono essere selezionati',
            ],
            'error' => [
                'title' => 'Alcune notifiche non sono state inviate',
                'item' => 'Record :record (canale :channel]: :error',
                'more_errors' => '... e altri :count errori',
            ],
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'label' => 'Actions',
    'plural_label' => 'Actions (Plurale)',
    'navigation' => [
        'name' => 'Actions',
        'plural' => 'Actions',
        'group' => [
            'name' => 'General',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => 'General Settings'],
        'label' => 'Actions',
        'sort' => 1,
        'icon' => 'heroicon-o-collection'],
<<<<<<< HEAD
=======
            'description' => 'General Settings',
        ],
        'label' => 'Actions',
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
            'label' => 'Crea Actions'],
        'edit' => [
            'label' => 'Modifica Actions'],
        'delete' => [
            'label' => 'Elimina Actions']]];
<<<<<<< HEAD
=======
            'description' => '',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Crea Actions',
        ],
        'edit' => [
            'label' => 'Modifica Actions',
        ],
        'delete' => [
            'label' => 'Elimina Actions',
        ],
    ],
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
