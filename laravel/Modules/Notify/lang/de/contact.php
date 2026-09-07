<?php

declare(strict_types=1);

return [
    'resource' => [
<<<<<<< HEAD
<<<<<<< HEAD
        'name' => 'Contact'],
=======
        'name' => 'Contact',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'name' => 'Contact'],
>>>>>>> a988596b (first)
    'navigation' => [
        'name' => 'contatto',
        'plural' => 'contatti',
        'group' => 'Sistema',
        'label' => 'Contatto',
        'sort' => '49',
        'icon' => 'notify-contact-animated',
<<<<<<< HEAD
<<<<<<< HEAD
        'description' => 'Gestione del singolo contatto per le notifiche'],
=======
        'description' => 'Gestione del singolo contatto per le notifiche',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'description' => 'Gestione del singolo contatto per le notifiche'],
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
                'admin' => [
                    'label' => 'Amministratore',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    'tooltip' => 'Staff amministrativo'],
                'user' => [
                    'label' => 'Utente',
                    'tooltip' => 'Utente standard'],
                'support' => [
                    'label' => 'Supporto',
                    'tooltip' => 'Team di supporto']],
            'helper_text' => '',
            'description' => ''],
<<<<<<< HEAD
=======
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
                'email' => [
                    'label' => 'Email',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    'tooltip' => 'Notifiche via email'],
                'sms' => [
                    'label' => 'SMS',
                    'tooltip' => 'Notifiche via SMS'],
                'telegram' => [
                    'label' => 'Telegram',
                    'tooltip' => 'Notifiche via Telegram'],
                'push' => [
                    'label' => 'Push',
                    'tooltip' => 'Notifiche push sul browser']],
            'helper_text' => '',
            'description' => ''],
<<<<<<< HEAD
=======
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
                        'immediate' => [
                            'label' => 'Immediata',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                            'tooltip' => 'Invia le notifiche immediatamente'],
                        'daily' => [
                            'label' => 'Giornaliera',
                            'tooltip' => 'Raggruppa le notifiche in un digest giornaliero'],
                        'weekly' => [
                            'label' => 'Settimanale',
                            'tooltip' => 'Raggruppa le notifiche in un digest settimanale']]],
                'quiet_hours' => [
                    'label' => 'Ore di silenzio',
                    'tooltip' => 'Periodo in cui non inviare notifiche',
                    'help' => 'Le notifiche verranno inviate al termine del periodo']],
            'helper_text' => '',
            'description' => ''],
<<<<<<< HEAD
=======
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
        'import' => [
            'name' => 'Importa da file',
            'fields' => [
<<<<<<< HEAD
<<<<<<< HEAD
                'import_file' => 'Seleziona un file XLS o CSV da caricare']],
=======
                'import_file' => 'Seleziona un file XLS o CSV da caricare',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'import_file' => 'Seleziona un file XLS o CSV da caricare']],
>>>>>>> a988596b (first)
        'export' => [
            'name' => 'Esporta dati',
            'filename_prefix' => 'Aree al',
            'columns' => [
                'name' => 'Nome area',
<<<<<<< HEAD
<<<<<<< HEAD
                'parent_name' => 'Nome area livello superiore']],
=======
                'parent_name' => 'Nome area livello superiore',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'parent_name' => 'Nome area livello superiore']],
>>>>>>> a988596b (first)
        'test_notification' => [
            'label' => 'Invia test',
            'tooltip' => 'Invia una notifica di test',
            'icon' => 'heroicon-o-paper-airplane',
            'color' => 'primary',
            'confirmation' => [
                'title' => 'Conferma invio test',
                'message' => 'Vuoi inviare una notifica di test?',
                'confirm' => 'Sì, invia',
<<<<<<< HEAD
<<<<<<< HEAD
                'cancel' => 'No, annulla']],
=======
                'cancel' => 'No, annulla',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'cancel' => 'No, annulla']],
>>>>>>> a988596b (first)
        'verify' => [
            'label' => 'Verifica contatto',
            'tooltip' => 'Verifica la validità del contatto',
            'icon' => 'heroicon-o-check-circle',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'color' => 'warning']],
    'messages' => [
        'created' => [
            'title' => 'Contatto Creato',
            'message' => 'Il contatto è stato creato con successo'],
        'updated' => [
            'title' => 'Contatto Aggiornato',
            'message' => 'Il contatto è stato aggiornato con successo'],
        'deleted' => [
            'title' => 'Contatto Eliminato',
            'message' => 'Il contatto è stato eliminato con successo'],
        'test_sent' => [
            'title' => 'Test Inviato',
            'message' => 'La notifica di test è stata inviata con successo'],
        'test_failed' => [
            'title' => 'Errore Test',
            'message' => 'Impossibile inviare la notifica di test: :error'],
        'verified' => [
            'title' => 'Verifica Completata',
            'message' => 'Il contatto è stato verificato con successo'],
        'verification_failed' => [
            'title' => 'Errore Verifica',
            'message' => 'Impossibile verificare il contatto: :error']],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
<<<<<<< HEAD
=======
            'color' => 'warning',
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
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
