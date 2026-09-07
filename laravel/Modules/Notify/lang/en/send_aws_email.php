<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Invio Email (AWS)',
        'group' => 'Notifiche',
        'icon' => 'heroicon-o-envelope',
        'color' => 'primary',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'sort' => '10'],
    'model' => [
        'label' => 'Email AWS',
        'plural' => 'Email AWS',
        'description' => 'Gestione invio email tramite servizio Amazon SES'],
<<<<<<< HEAD
=======
        'sort' => '10',
    ],
    'model' => [
        'label' => 'Email AWS',
        'plural' => 'Email AWS',
        'description' => 'Gestione invio email tramite servizio Amazon SES',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'fields' => [
        'to' => [
            'label' => 'Destinatario Email',
            'placeholder' => 'Inserisci indirizzo email destinatario',
            'help' => 'Indirizzo email del destinatario principale del messaggio',
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
        'subject' => [
            'label' => 'Oggetto Email',
            'placeholder' => 'Inserisci l\'oggetto del messaggio',
            'help' => 'Testo che apparirà come oggetto dell\'email ricevuta',
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
        'body_html' => [
            'label' => 'Corpo HTML',
            'placeholder' => 'Inserisci il contenuto HTML dell\'email',
            'help' => 'Contenuto formattato in HTML per email con layout avanzato',
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
        'template' => [
            'label' => 'Template Email',
            'placeholder' => 'Seleziona un template predefinito',
            'help' => 'Template predefinito da utilizzare per la formattazione dell\'email',
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
        'add_attachments' => [
            'label' => 'Allegati Email',
            'placeholder' => 'Carica file da allegare al messaggio',
            'help' => 'File allegati che verranno inviati insieme all\'email',
            'tooltip' => '',
            'helper_text' => '',
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
    'actions' => [
        'send_email' => [
            'label' => 'Invia Email',
            'icon' => 'heroicon-o-paper-airplane',
            'color' => 'success',
            'modal_heading' => 'Conferma invio email',
            'modal_description' => 'Sei sicuro di voler inviare questa email?',
            'success' => 'Email inviata con successo tramite AWS SES',
            'error' => 'Errore durante l\'invio dell\'email',
<<<<<<< HEAD
<<<<<<< HEAD
            'confirmation' => 'L\'email verrà inviata immediatamente']],
=======
            'confirmation' => 'L\'email verrà inviata immediatamente',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'confirmation' => 'L\'email verrà inviata immediatamente']],
>>>>>>> a988596b (first)
    'messages' => [
        'loading' => 'Preparazione email in corso...',
        'sent' => 'Email inviata correttamente',
        'queue' => 'Email aggiunta alla coda di invio',
<<<<<<< HEAD
<<<<<<< HEAD
        'failed' => 'Invio email fallito'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
=======
        'failed' => 'Invio email fallito',
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'failed' => 'Invio email fallito'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
>>>>>>> a988596b (first)
