<?php

declare(strict_types=1);

return [
    'resource' => ['name' => 'Invio Notifica Push'],
    'navigation' => [
        'name' => 'Invio Notifica Push',
        'plural' => 'Invio Notifiche Push',
        'group' => ['name' => 'Sistema', 'description' => 'Funzionalità per l\'invio di notifiche push tramite Firebase'],
        'label' => 'Invio Notifiche Push',
        'icon' => 'notify-push-animated',
<<<<<<< HEAD
<<<<<<< HEAD
        'sort' => 51],
=======
        'sort' => 51,
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'sort' => 51],
>>>>>>> a988596b (first)
    'fields' => [
        'device_token' => ['label' => 'Token Dispositivo', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'type' => [
            'label' => 'Tipo',
            'options' => ['notification' => 'Notifica', 'data' => 'Dati', 'both' => 'Entrambi'],
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'placeholder' => 'type'],
=======
            'placeholder' => 'type',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'placeholder' => 'type'],
>>>>>>> a988596b (first)
        'title' => ['label' => 'Titolo', 'tooltip' => '', 'helper_text' => '', 'description' => '', 'placeholder' => 'title'],
        'body' => ['label' => 'Contenuto', 'tooltip' => '', 'helper_text' => '', 'description' => '', 'placeholder' => 'body'],
        'data' => ['label' => 'Dati Aggiuntivi', 'description' => 'Dati in formato JSON da inviare con la notifica', 'tooltip' => '', 'helper_text' => '', 'placeholder' => 'data'],
        'deviceToken' => ['label' => 'deviceToken', 'placeholder' => 'deviceToken', 'helper_text' => 'deviceToken', 'description' => 'deviceToken'],
        'name' => ['label' => 'name', 'placeholder' => 'name', 'helper_text' => 'name', 'description' => 'name'],
<<<<<<< HEAD
<<<<<<< HEAD
        'value' => ['label' => 'value', 'placeholder' => 'value', 'helper_text' => 'value', 'description' => 'value']],
=======
        'value' => ['label' => 'value', 'placeholder' => 'value', 'helper_text' => 'value', 'description' => 'value'],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'value' => ['label' => 'value', 'placeholder' => 'value', 'helper_text' => 'value', 'description' => 'value']],
>>>>>>> a988596b (first)
    'actions' => [
        'send' => ['label' => 'Invia Notifica', 'success' => 'Notifica push inviata con successo', 'error' => 'Errore durante l\'invio della notifica push'],
        'preview' => ['label' => 'Anteprima'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
<<<<<<< HEAD
<<<<<<< HEAD
        'notificationFormActions' => ['label' => 'notificationFormActions', 'icon' => 'notificationFormActions', 'tooltip' => 'notificationFormActions']],
    'label' => 'Send Push Notification',
    'plural_label' => 'Send Push Notification (Plurale)'];
=======
        'notificationFormActions' => ['label' => 'notificationFormActions', 'icon' => 'notificationFormActions', 'tooltip' => 'notificationFormActions'],
    ],
    'label' => 'Send Push Notification',
    'plural_label' => 'Send Push Notification (Plurale)',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'notificationFormActions' => ['label' => 'notificationFormActions', 'icon' => 'notificationFormActions', 'tooltip' => 'notificationFormActions']],
    'label' => 'Send Push Notification',
    'plural_label' => 'Send Push Notification (Plurale)'];
>>>>>>> a988596b (first)
