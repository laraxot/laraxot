<?php

declare(strict_types=1);

return [
    'navigation' => ['label' => 'WhatsApp', 'group' => 'Notify', 'icon' => 'heroicon-o-chat-bubble-left-right', 'sort' => 10],
    'fields' => [
        'phone_number' => ['label' => 'Numero Telefono', 'placeholder' => 'Inserisci numero WhatsApp', 'helper_text' => 'Numero di telefono per l\'invio WhatsApp', 'tooltip' => '', 'description' => ''],
        'message' => ['label' => 'Messaggio', 'placeholder' => 'Inserisci messaggio WhatsApp', 'helper_text' => 'Testo del messaggio da inviare', 'tooltip' => '', 'description' => ''],
        'template' => ['label' => 'Template', 'placeholder' => 'Seleziona template', 'help' => 'Template predefinito per il messaggio', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'recipient' => ['label' => 'recipient', 'placeholder' => 'recipient', 'helper_text' => 'recipient', 'description' => 'recipient'],
        'driver' => ['label' => 'driver', 'placeholder' => 'driver', 'helper_text' => 'driver', 'description' => 'driver'],
        'parameters' => ['label' => 'parameters', 'placeholder' => 'parameters', 'helper_text' => 'parameters', 'description' => 'parameters'],
        'media_url' => ['label' => 'media_url', 'placeholder' => 'media_url', 'helper_text' => 'media_url', 'description' => 'media_url'],
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'media_type' => ['label' => 'media_type', 'placeholder' => 'media_type', 'helper_text' => 'media_type', 'description' => 'media_type']],
    'actions' => [
        'send' => ['label' => 'Invia WhatsApp', 'tooltip' => 'Invia messaggio WhatsApp', 'success' => 'Messaggio WhatsApp inviato con successo', 'error' => 'Errore nell\'invio del messaggio WhatsApp'],
        'whatsappFormActions' => ['label' => 'whatsappFormActions', 'icon' => 'whatsappFormActions', 'tooltip' => 'whatsappFormActions'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save']],
    'label' => 'Send Whats App',
    'plural_label' => 'Send Whats App (Plurale)'];
<<<<<<< HEAD
=======
        'media_type' => ['label' => 'media_type', 'placeholder' => 'media_type', 'helper_text' => 'media_type', 'description' => 'media_type'],
    ],
    'actions' => [
        'send' => ['label' => 'Invia WhatsApp', 'tooltip' => 'Invia messaggio WhatsApp', 'success' => 'Messaggio WhatsApp inviato con successo', 'error' => 'Errore nell\'invio del messaggio WhatsApp'],
        'whatsappFormActions' => ['label' => 'whatsappFormActions', 'icon' => 'whatsappFormActions', 'tooltip' => 'whatsappFormActions'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
    ],
    'label' => 'Send Whats App',
    'plural_label' => 'Send Whats App (Plurale)',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
