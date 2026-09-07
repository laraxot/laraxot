<?php

declare(strict_types=1);

return [
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
    'resource' => ['name' => 'Notifica', 'plural' => 'Notifiche'],
    'navigation' => [
        'name' => 'Gestione Notifiche',
        'plural' => 'Gestione Notifiche',
        'group' => ['name' => 'Sistema', 'description' => 'Gestione centralizzata delle notifiche di sistema'],
        'label' => 'Gestione Notifiche',
        'icon' => 'notify-notification-animated',
        'sort' => 46],
<<<<<<< HEAD
=======
    'resource' => [
        'name' => 'Notifica',
        'plural' => 'Notifiche',
    ],
    'navigation' => [
        'name' => 'Gestione Notifiche',
        'plural' => 'Gestione Notifiche',
        'group' => [
            'name' => 'Sistema',
            'description' => 'Gestione centralizzata delle notifiche di sistema',
        ],
        'label' => 'Gestione Notifiche',
        'icon' => 'notify-notification-animated',
        'sort' => 46,
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'fields' => [
        'title' => [
            'label' => 'Titolo',
            'helper_text' => 'Titolo della notifica',
            'placeholder' => 'Inserisci il titolo',
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
            'helper_text' => 'Contenuto della notifica',
            'placeholder' => 'Inserisci il messaggio',
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
        'type' => [
            'label' => 'Tipo',
            'helper_text' => 'Tipologia di notifica',
            'placeholder' => 'Seleziona il tipo',
            'options' => [
                'system' => 'Sistema',
                'alert' => 'Avviso',
                'info' => 'Informazione',
                'success' => 'Successo',
                'warning' => 'Attenzione',
<<<<<<< HEAD
<<<<<<< HEAD
                'error' => 'Errore'],
            'tooltip' => '',
            'description' => ''],
=======
                'error' => 'Errore',
            ],
            'tooltip' => '',
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'error' => 'Errore'],
            'tooltip' => '',
            'description' => ''],
>>>>>>> a988596b (first)
        'status' => [
            'label' => 'Stato',
            'helper_text' => 'Stato corrente della notifica',
            'placeholder' => 'Seleziona lo stato',
            'options' => [
                'unread' => 'Non letta',
                'read' => 'Letta',
<<<<<<< HEAD
<<<<<<< HEAD
                'archived' => 'Archiviata'],
            'tooltip' => '',
            'description' => ''],
=======
                'archived' => 'Archiviata',
            ],
            'tooltip' => '',
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'archived' => 'Archiviata'],
            'tooltip' => '',
            'description' => ''],
>>>>>>> a988596b (first)
        'recipient' => [
            'label' => 'Destinatario',
            'helper_text' => 'Utente destinatario della notifica',
            'placeholder' => 'Seleziona il destinatario',
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
        'sent_at' => [
            'label' => 'Inviata il',
            'helper_text' => 'Data e ora di invio della notifica',
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
        'read_at' => [
            'label' => 'Letta il',
            'helper_text' => 'Data e ora di lettura della notifica',
            'tooltip' => '',
            'description' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'placeholder' => 'read_at'],
=======
            'placeholder' => 'read_at',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'placeholder' => 'read_at'],
>>>>>>> a988596b (first)
        'archived_at' => [
            'label' => 'Archiviata il',
            'helper_text' => 'Data e ora di archiviazione della notifica',
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
        'channel' => [
            'label' => 'Canale',
            'tooltip' => 'Canale di invio della notifica',
            'helper_text' => 'Seleziona il canale attraverso cui inviare la notifica',
            'placeholder' => 'Seleziona un canale',
            'options' => [
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'email' => ['label' => 'Email', 'tooltip' => 'Invia tramite email'],
                'sms' => ['label' => 'SMS', 'tooltip' => 'Invia tramite SMS'],
                'push' => ['label' => 'Push', 'tooltip' => 'Invia come notifica push'],
                'telegram' => ['label' => 'Telegram', 'tooltip' => 'Invia tramite Telegram']],
            'description' => ''],
<<<<<<< HEAD
=======
                'email' => [
                    'label' => 'Email',
                    'tooltip' => 'Invia tramite email',
                ],
                'sms' => [
                    'label' => 'SMS',
                    'tooltip' => 'Invia tramite SMS',
                ],
                'push' => [
                    'label' => 'Push',
                    'tooltip' => 'Invia come notifica push',
                ],
                'telegram' => [
                    'label' => 'Telegram',
                    'tooltip' => 'Invia tramite Telegram',
                ],
            ],
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'template' => [
            'label' => 'Template',
            'tooltip' => 'Template da utilizzare per la notifica',
            'helper_text' => 'Scegli il modello predefinito per questa notifica',
            'placeholder' => 'Seleziona un template',
            'options' => [
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'subject' => ['label' => 'Oggetto', 'tooltip' => 'Oggetto della notifica', 'placeholder' => 'es: Notifica importante'],
                'body' => ['label' => 'Corpo', 'tooltip' => 'Contenuto principale della notifica', 'placeholder' => 'Inserisci il testo della notifica...'],
                'variables' => ['label' => 'Variabili disponibili', 'tooltip' => 'Variabili che possono essere utilizzate nel template', 'helper_text' => 'Usa {variable} per inserire valori dinamici']],
            'description' => ''],
<<<<<<< HEAD
=======
                'subject' => [
                    'label' => 'Oggetto',
                    'tooltip' => 'Oggetto della notifica',
                    'placeholder' => 'es: Notifica importante',
                ],
                'body' => [
                    'label' => 'Corpo',
                    'tooltip' => 'Contenuto principale della notifica',
                    'placeholder' => 'Inserisci il testo della notifica...',
                ],
                'variables' => [
                    'label' => 'Variabili disponibili',
                    'tooltip' => 'Variabili che possono essere utilizzate nel template',
                    'helper_text' => 'Usa {variable} per inserire valori dinamici',
                ],
            ],
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'schedule' => [
            'label' => 'Programmazione',
            'tooltip' => 'Quando inviare la notifica',
            'helper_text' => 'Imposta quando la notifica deve essere inviata',
            'placeholder' => 'Seleziona l\'opzione di programmazione',
            'options' => [
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'immediate' => ['label' => 'Immediata', 'tooltip' => 'Invia subito la notifica'],
                'scheduled' => ['label' => 'Programmata', 'tooltip' => 'Programma l\'invio per una data specifica'],
                'date' => ['label' => 'Data', 'tooltip' => 'Data di invio programmato', 'placeholder' => 'es: 01/01/2024'],
                'time' => ['label' => 'Ora', 'tooltip' => 'Ora di invio programmato', 'placeholder' => 'es: 14:30']],
            'description' => ''],
<<<<<<< HEAD
=======
                'immediate' => [
                    'label' => 'Immediata',
                    'tooltip' => 'Invia subito la notifica',
                ],
                'scheduled' => [
                    'label' => 'Programmata',
                    'tooltip' => 'Programma l\'invio per una data specifica',
                ],
                'date' => [
                    'label' => 'Data',
                    'tooltip' => 'Data di invio programmato',
                    'placeholder' => 'es: 01/01/2024',
                ],
                'time' => [
                    'label' => 'Ora',
                    'tooltip' => 'Ora di invio programmato',
                    'placeholder' => 'es: 14:30',
                ],
            ],
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'applyFilters' => [
            'label' => 'applyFilters',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => ''],
        'id' => [
            'label' => 'id'],
        'notifiable' => [
            'name' => [
                'label' => 'notifiable.name']],
<<<<<<< HEAD
=======
            'description' => '',
        ],
        'id' => [
            'label' => 'id',
        ],
        'notifiable' => [
            'name' => [
                'label' => 'notifiable.name',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'data' => [
            'label' => 'data',
            'placeholder' => 'data',
            'helper_text' => 'data',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => 'data'],
        'created_at' => [
            'label' => 'created_at'],
        'updated_at' => [
            'label' => 'updated_at'],
        'is_read' => [
            'label' => 'is_read'],
        'is_unread' => [
            'label' => 'is_unread'],
<<<<<<< HEAD
=======
            'description' => 'data',
        ],
        'created_at' => [
            'label' => 'created_at',
        ],
        'updated_at' => [
            'label' => 'updated_at',
        ],
        'is_read' => [
            'label' => 'is_read',
        ],
        'is_unread' => [
            'label' => 'is_unread',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'notifiable_type' => [
            'label' => 'notifiable_type',
            'placeholder' => 'notifiable_type',
            'helper_text' => 'notifiable_type',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => 'notifiable_type'],
=======
            'description' => 'notifiable_type',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => 'notifiable_type'],
>>>>>>> a988596b (first)
        'notifiable_id' => [
            'label' => 'notifiable_id',
            'placeholder' => 'notifiable_id',
            'helper_text' => 'notifiable_id',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => 'notifiable_id'],
=======
            'description' => 'notifiable_id',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => 'notifiable_id'],
>>>>>>> a988596b (first)
        'created_by' => [
            'label' => 'created_by',
            'placeholder' => 'created_by',
            'helper_text' => 'created_by',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => 'created_by'],
=======
            'description' => 'created_by',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => 'created_by'],
>>>>>>> a988596b (first)
        'updated_by' => [
            'label' => 'updated_by',
            'placeholder' => 'updated_by',
            'helper_text' => 'updated_by',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => 'updated_by'],
=======
            'description' => 'updated_by',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => 'updated_by'],
>>>>>>> a988596b (first)
        'isActive' => [
            'label' => 'isActive',
            'placeholder' => 'isActive',
            'helper_text' => 'isActive',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => 'isActive'],
=======
            'description' => 'isActive',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => 'isActive'],
>>>>>>> a988596b (first)
        'values' => [
            'label' => 'values',
            'placeholder' => 'values',
            'helper_text' => 'values',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => 'values']],
=======
            'description' => 'values',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => 'values']],
>>>>>>> a988596b (first)
    'actions' => [
        'mark_as_read' => [
            'label' => 'Segna come letta',
            'tooltip' => 'Marca la notifica come letta',
            'success_message' => 'Notifica segnata come letta',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Errore nel segnare la notifica come letta'],
=======
            'error_message' => 'Errore nel segnare la notifica come letta',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Errore nel segnare la notifica come letta'],
>>>>>>> a988596b (first)
        'mark_as_unread' => [
            'label' => 'Segna come non letta',
            'tooltip' => 'Marca la notifica come non letta',
            'success_message' => 'Notifica segnata come non letta',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Errore nel segnare la notifica come non letta'],
=======
            'error_message' => 'Errore nel segnare la notifica come non letta',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Errore nel segnare la notifica come non letta'],
>>>>>>> a988596b (first)
        'archive' => [
            'label' => 'Archivia',
            'tooltip' => 'Archivia la notifica',
            'success_message' => 'Notifica archiviata con successo',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Errore nell\'archiviazione della notifica'],
=======
            'error_message' => 'Errore nell\'archiviazione della notifica',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Errore nell\'archiviazione della notifica'],
>>>>>>> a988596b (first)
        'unarchive' => [
            'label' => 'Ripristina',
            'tooltip' => 'Ripristina la notifica archiviata',
            'success_message' => 'Notifica ripristinata con successo',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Errore nel ripristino della notifica'],
=======
            'error_message' => 'Errore nel ripristino della notifica',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Errore nel ripristino della notifica'],
>>>>>>> a988596b (first)
        'send' => [
            'label' => 'Invia',
            'tooltip' => 'Invia la notifica',
            'success_message' => 'Notifica inviata con successo',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Errore nell\'invio della notifica'],
=======
            'error_message' => 'Errore nell\'invio della notifica',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Errore nell\'invio della notifica'],
>>>>>>> a988596b (first)
        'resend' => [
            'label' => 'Invia nuovamente',
            'tooltip' => 'Invia nuovamente la notifica',
            'success_message' => 'Notifica inviata nuovamente con successo',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Errore nell\'invio della notifica'],
=======
            'error_message' => 'Errore nell\'invio della notifica',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Errore nell\'invio della notifica'],
>>>>>>> a988596b (first)
        'delete' => [
            'label' => 'Elimina',
            'tooltip' => 'Elimina definitivamente la notifica',
            'success_message' => 'Notifica eliminata con successo',
            'error_message' => 'Errore nell\'eliminazione della notifica',
            'confirmation' => 'Sei sicuro di voler eliminare questa notifica? Questa azione non può essere annullata.',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'icon' => 'delete'],
        'create' => [
            'label' => 'create',
            'icon' => 'create',
            'tooltip' => 'create'],
        'createAnother' => [
            'label' => 'createAnother',
            'icon' => 'createAnother',
            'tooltip' => 'createAnother'],
        'save' => [
            'label' => 'save',
            'icon' => 'save',
            'tooltip' => 'save'],
        'view' => [
            'label' => 'view',
            'icon' => 'view',
            'tooltip' => 'view'],
        'edit' => [
            'label' => 'edit',
            'icon' => 'edit',
            'tooltip' => 'edit'],
        'layout' => [
            'label' => 'layout',
            'icon' => 'layout',
            'tooltip' => 'layout'],
        'applyFilters' => [
            'label' => 'applyFilters',
            'icon' => 'applyFilters',
            'tooltip' => 'applyFilters'],
        'openFilters' => [
            'label' => 'openFilters',
            'icon' => 'openFilters',
            'tooltip' => 'openFilters'],
        'resetFilters' => [
            'label' => 'resetFilters',
            'icon' => 'resetFilters',
            'tooltip' => 'resetFilters'],
        'applyTableColumnManager' => [
            'label' => 'applyTableColumnManager',
            'icon' => 'applyTableColumnManager',
            'tooltip' => 'applyTableColumnManager'],
        'openColumnManager' => [
            'label' => 'openColumnManager',
            'icon' => 'openColumnManager',
            'tooltip' => 'openColumnManager'],
        'resetColumnManager' => [
            'label' => 'resetColumnManager',
            'icon' => 'resetColumnManager',
            'tooltip' => 'resetColumnManager'],
        'reorderRecords' => [
            'label' => 'reorderRecords',
            'icon' => 'reorderRecords',
            'tooltip' => 'reorderRecords'],
        'profile' => [
            'label' => 'profile',
            'icon' => 'profile',
            'tooltip' => 'profile'],
        'logout' => [
            'label' => 'logout',
            'icon' => 'logout',
            'tooltip' => 'logout']],
<<<<<<< HEAD
=======
            'icon' => 'delete',
        ],
        'create' => [
            'label' => 'create',
            'icon' => 'create',
            'tooltip' => 'create',
        ],
        'createAnother' => [
            'label' => 'createAnother',
            'icon' => 'createAnother',
            'tooltip' => 'createAnother',
        ],
        'save' => [
            'label' => 'save',
            'icon' => 'save',
            'tooltip' => 'save',
        ],
        'view' => [
            'label' => 'view',
            'icon' => 'view',
            'tooltip' => 'view',
        ],
        'edit' => [
            'label' => 'edit',
            'icon' => 'edit',
            'tooltip' => 'edit',
        ],
        'layout' => [
            'label' => 'layout',
            'icon' => 'layout',
            'tooltip' => 'layout',
        ],
        'applyFilters' => [
            'label' => 'applyFilters',
            'icon' => 'applyFilters',
            'tooltip' => 'applyFilters',
        ],
        'openFilters' => [
            'label' => 'openFilters',
            'icon' => 'openFilters',
            'tooltip' => 'openFilters',
        ],
        'resetFilters' => [
            'label' => 'resetFilters',
            'icon' => 'resetFilters',
            'tooltip' => 'resetFilters',
        ],
        'applyTableColumnManager' => [
            'label' => 'applyTableColumnManager',
            'icon' => 'applyTableColumnManager',
            'tooltip' => 'applyTableColumnManager',
        ],
        'openColumnManager' => [
            'label' => 'openColumnManager',
            'icon' => 'openColumnManager',
            'tooltip' => 'openColumnManager',
        ],
        'resetColumnManager' => [
            'label' => 'resetColumnManager',
            'icon' => 'resetColumnManager',
            'tooltip' => 'resetColumnManager',
        ],
        'reorderRecords' => [
            'label' => 'reorderRecords',
            'icon' => 'reorderRecords',
            'tooltip' => 'reorderRecords',
        ],
        'profile' => [
            'label' => 'profile',
            'icon' => 'profile',
            'tooltip' => 'profile',
        ],
        'logout' => [
            'label' => 'logout',
            'icon' => 'logout',
            'tooltip' => 'logout',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'messages' => [
        'no_notifications' => 'Non hai notifiche',
        'all_read' => 'Tutte le notifiche sono state lette',
        'mark_all_read' => 'Segna tutte come lette',
        'notification_sent' => 'Notifica inviata con successo',
        'notification_error' => 'Si è verificato un errore durante l\'invio della notifica',
        'delete_confirmation' => 'Sei sicuro di voler eliminare questa notifica?',
        'batch_action_confirmation' => 'Sei sicuro di voler eseguire questa azione su tutte le notifiche selezionate?',
        'success' => 'Operazione completata con successo',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'error' => 'Si è verificato un errore durante l\'operazione'],
    'label' => 'Notification',
    'plural_label' => 'Notification (Plurale)',
    'sections' => [
        'empty' => ['label' => 'empty', 'heading' => 'empty']]];
<<<<<<< HEAD
=======
        'error' => 'Si è verificato un errore durante l\'operazione',
    ],
    'label' => 'Notification',
    'plural_label' => 'Notification (Plurale)',
    'sections' => [
        'empty' => ['label' => 'empty', 'heading' => 'empty'],
    ],
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
