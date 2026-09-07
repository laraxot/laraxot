<?php

declare(strict_types=1);

return [
<<<<<<< HEAD
<<<<<<< HEAD
    'resource' => ['name' => 'Contact'],
    'navigation' => ['name' => 'contatto', 'plural' => 'contatti', 'group' => 'Sistema', 'label' => 'Contatto', 'sort' => 49, 'icon' => 'notify-contact-animated', 'description' => 'Gestione del singolo contatto per le notifiche'],
=======
    'resource' => [
        'name' => 'Contact',
    ],
    'navigation' => [
        'name' => 'contatto',
        'plural' => 'contatti',
        'group' => 'Sistema',
        'label' => 'Contatto',
        'sort' => 49,
        'icon' => 'notify-contact-animated',
        'description' => 'Gestione del singolo contatto per le notifiche',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    'resource' => ['name' => 'Contact'],
    'navigation' => ['name' => 'contatto', 'plural' => 'contatti', 'group' => 'Sistema', 'label' => 'Contatto', 'sort' => 49, 'icon' => 'notify-contact-animated', 'description' => 'Gestione del singolo contatto per le notifiche'],
>>>>>>> a988596b (first)
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'tooltip' => 'Nome del contatto',
            'placeholder' => 'es: Mario Rossi',
            'help' => 'Inserisci il nome completo del contatto',
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
        'email' => [
            'label' => 'Email',
            'tooltip' => 'Indirizzo email del contatto',
            'placeholder' => 'es: mario.rossi@example.com',
            'help' => 'Inserisci un indirizzo email valido',
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
        'phone' => [
            'label' => 'Telefono',
            'tooltip' => 'Numero di telefono del contatto',
            'placeholder' => 'es: +39 123 456 7890',
            'help' => 'Inserisci il numero con prefisso internazionale',
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
        'telegram_chat_id' => [
            'label' => 'Chat ID Telegram',
            'tooltip' => 'ID della chat Telegram del contatto',
            'placeholder' => 'es: 123456789',
            'help' => 'ID numerico fornito dal bot Telegram',
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
        'group' => [
            'label' => 'Gruppo',
            'tooltip' => 'Gruppo di appartenenza del contatto',
            'placeholder' => 'es: Amministrazione',
            'help' => 'Seleziona il gruppo di appartenenza',
            'options' => [
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'admin' => ['label' => 'Amministratore', 'tooltip' => 'Staff amministrativo'],
                'user' => ['label' => 'Utente', 'tooltip' => 'Utente standard'],
                'support' => ['label' => 'Supporto', 'tooltip' => 'Team di supporto']],
            'helper_text' => '',
            'description' => ''],
<<<<<<< HEAD
=======
                'admin' => [
                    'label' => 'Amministratore',
                    'tooltip' => 'Staff amministrativo',
                ],
                'user' => [
                    'label' => 'Utente',
                    'tooltip' => 'Utente standard',
                ],
                'support' => [
                    'label' => 'Supporto',
                    'tooltip' => 'Team di supporto',
                ],
            ],
            'helper_text' => '',
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'channels' => [
            'label' => 'Canali',
            'tooltip' => 'Canali di notifica preferiti',
            'help' => 'Seleziona i canali per l\'invio delle notifiche',
            'options' => [
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'email' => ['label' => 'Email', 'tooltip' => 'Notifiche via email'],
                'sms' => ['label' => 'SMS', 'tooltip' => 'Notifiche via SMS'],
                'telegram' => ['label' => 'Telegram', 'tooltip' => 'Notifiche via Telegram'],
                'push' => ['label' => 'Push', 'tooltip' => 'Notifiche push sul browser']],
            'helper_text' => '',
            'description' => ''],
<<<<<<< HEAD
=======
                'email' => [
                    'label' => 'Email',
                    'tooltip' => 'Notifiche via email',
                ],
                'sms' => [
                    'label' => 'SMS',
                    'tooltip' => 'Notifiche via SMS',
                ],
                'telegram' => [
                    'label' => 'Telegram',
                    'tooltip' => 'Notifiche via Telegram',
                ],
                'push' => [
                    'label' => 'Push',
                    'tooltip' => 'Notifiche push sul browser',
                ],
            ],
            'helper_text' => '',
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'preferences' => [
            'label' => 'Preferenze',
            'tooltip' => 'Preferenze di notifica',
            'help' => 'Configura le preferenze personali',
            'options' => [
                'frequency' => [
                    'label' => 'Frequenza',
                    'tooltip' => 'Frequenza di invio delle notifiche',
                    'options' => [
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                        'immediate' => ['label' => 'Immediata', 'tooltip' => 'Invia le notifiche immediatamente'],
                        'daily' => ['label' => 'Giornaliera', 'tooltip' => 'Raggruppa le notifiche in un digest giornaliero'],
                        'weekly' => ['label' => 'Settimanale', 'tooltip' => 'Raggruppa le notifiche in un digest settimanale']]],
                'quiet_hours' => ['label' => 'Ore di silenzio', 'tooltip' => 'Periodo in cui non inviare notifiche', 'help' => 'Le notifiche verranno inviate al termine del periodo']],
            'helper_text' => '',
            'description' => ''],
<<<<<<< HEAD
=======
                        'immediate' => [
                            'label' => 'Immediata',
                            'tooltip' => 'Invia le notifiche immediatamente',
                        ],
                        'daily' => [
                            'label' => 'Giornaliera',
                            'tooltip' => 'Raggruppa le notifiche in un digest giornaliero',
                        ],
                        'weekly' => [
                            'label' => 'Settimanale',
                            'tooltip' => 'Raggruppa le notifiche in un digest settimanale',
                        ],
                    ],
                ],
                'quiet_hours' => [
                    'label' => 'Ore di silenzio',
                    'tooltip' => 'Periodo in cui non inviare notifiche',
                    'help' => 'Le notifiche verranno inviate al termine del periodo',
                ],
            ],
            'helper_text' => '',
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'is_active' => [
            'label' => 'Attivo',
            'tooltip' => 'Stato di attivazione del contatto',
            'help' => 'Disattiva temporaneamente le notifiche',
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
        'last_notified_at' => [
            'label' => 'Ultima notifica',
            'tooltip' => 'Data e ora dell\'ultima notifica inviata',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => ''],
        'id' => [
            'label' => 'id'],
        'message' => [
            'label' => 'message'],
        'is_read' => [
            'label' => 'is_read'],
        'created_at' => [
            'label' => 'created_at'],
        'updated_at' => [
            'label' => 'updated_at'],
        'active' => [
            'label' => 'active'],
        'inactive' => [
            'label' => 'inactive'],
<<<<<<< HEAD
=======
            'description' => '',
        ],
        'id' => [
            'label' => 'id',
        ],
        'message' => [
            'label' => 'message',
        ],
        'is_read' => [
            'label' => 'is_read',
        ],
        'created_at' => [
            'label' => 'created_at',
        ],
        'updated_at' => [
            'label' => 'updated_at',
        ],
        'active' => [
            'label' => 'active',
        ],
        'inactive' => [
            'label' => 'inactive',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'isActive' => [
            'label' => 'isActive',
            'placeholder' => 'isActive',
            'helper_text' => 'isActive',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => 'isActive']],
    'actions' => [
        'import' => [
            'name' => 'Importa da file',
            'fields' => ['import_file' => 'Seleziona un file XLS o CSV da caricare']],
        'export' => [
            'name' => 'Esporta dati',
            'filename_prefix' => 'Aree al',
            'columns' => ['name' => 'Nome area', 'parent_name' => 'Nome area livello superiore']],
<<<<<<< HEAD
=======
            'description' => 'isActive',
        ],
    ],
    'actions' => [
        'import' => [
            'name' => 'Importa da file',
            'fields' => [
                'import_file' => 'Seleziona un file XLS o CSV da caricare',
            ],
        ],
        'export' => [
            'name' => 'Esporta dati',
            'filename_prefix' => 'Aree al',
            'columns' => [
                'name' => 'Nome area',
                'parent_name' => 'Nome area livello superiore',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'test_notification' => [
            'label' => 'Invia test',
            'tooltip' => 'Invia una notifica di test',
            'icon' => 'heroicon-o-paper-airplane',
            'color' => 'primary',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'confirmation' => ['title' => 'Conferma invio test', 'message' => 'Vuoi inviare una notifica di test?', 'confirm' => 'Sì, invia', 'cancel' => 'No, annulla']],
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
        'delete' => [
            'label' => 'delete',
            'icon' => 'delete',
            'tooltip' => 'delete'],
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
    'messages' => [
        'created' => ['title' => 'Contatto Creato', 'message' => 'Il contatto è stato creato con successo'],
        'updated' => ['title' => 'Contatto Aggiornato', 'message' => 'Il contatto è stato aggiornato con successo'],
        'deleted' => ['title' => 'Contatto Eliminato', 'message' => 'Il contatto è stato eliminato con successo'],
        'test_sent' => ['title' => 'Test Inviato', 'message' => 'La notifica di test è stata inviata con successo'],
        'test_failed' => ['title' => 'Errore Test', 'message' => 'Impossibile inviare la notifica di test: :error'],
        'verified' => ['title' => 'Verifica Completata', 'message' => 'Il contatto è stato verificato con successo'],
        'verification_failed' => ['title' => 'Errore Verifica', 'message' => 'Impossibile verificare il contatto: :error']],
    'label' => 'Contact',
    'plural_label' => 'Contact (Plurale)'];
<<<<<<< HEAD
=======
            'confirmation' => [
                'title' => 'Conferma invio test',
                'message' => 'Vuoi inviare una notifica di test?',
                'confirm' => 'Sì, invia',
                'cancel' => 'No, annulla',
            ],
        ],
        'verify' => [
            'label' => 'Verifica contatto',
            'tooltip' => 'Verifica la validità del contatto',
            'icon' => 'heroicon-o-check-circle',
            'color' => 'warning',
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
        'delete' => [
            'label' => 'delete',
            'icon' => 'delete',
            'tooltip' => 'delete',
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
    'messages' => [
        'created' => [
            'title' => 'Contatto Creato',
            'message' => 'Il contatto è stato creato con successo',
        ],
        'updated' => [
            'title' => 'Contatto Aggiornato',
            'message' => 'Il contatto è stato aggiornato con successo',
        ],
        'deleted' => [
            'title' => 'Contatto Eliminato',
            'message' => 'Il contatto è stato eliminato con successo',
        ],
        'test_sent' => [
            'title' => 'Test Inviato',
            'message' => 'La notifica di test è stata inviata con successo',
        ],
        'test_failed' => [
            'title' => 'Errore Test',
            'message' => 'Impossibile inviare la notifica di test: :error',
        ],
        'verified' => [
            'title' => 'Verifica Completata',
            'message' => 'Il contatto è stato verificato con successo',
        ],
        'verification_failed' => [
            'title' => 'Errore Verifica',
            'message' => 'Impossibile verificare il contatto: :error',
        ],
    ],
    'label' => 'Contact',
    'plural_label' => 'Contact (Plurale)',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
