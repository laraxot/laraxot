<?php

declare(strict_types=1);

return [
    'resource' => [
<<<<<<< HEAD
<<<<<<< HEAD
        'name' => 'Notifica'],
=======
        'name' => 'Notifica',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'name' => 'Notifica'],
>>>>>>> a988596b (first)
    'navigation' => [
        'name' => 'Notifica',
        'plural' => 'Notifiche',
        'group' => 'Sistema',
        'label' => 'Notifiche',
        'icon' => 'notify-bell-animated',
<<<<<<< HEAD
<<<<<<< HEAD
        'sort' => 45],
=======
        'sort' => 45,
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'sort' => 45],
>>>>>>> a988596b (first)
    'fields' => [
        'title' => [
            'label' => 'Titolo',
            'tooltip' => 'Titolo della notifica',
            'placeholder' => 'es: Aggiornamento sistema',
            'help' => 'Inserisci un titolo chiaro e conciso',
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
        'message' => [
            'label' => 'Messaggio',
            'tooltip' => 'Contenuto della notifica',
            'placeholder' => 'es: Il sistema verrà aggiornato alle ore...',
            'help' => 'Inserisci il messaggio completo della notifica',
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
        'type' => [
            'label' => 'Tipo',
            'tooltip' => 'Tipo di notifica',
            'options' => [
                'system' => [
                    'label' => 'Sistema',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    'tooltip' => 'Notifiche di sistema e manutenzione'],
                'alert' => [
                    'label' => 'Avviso',
                    'tooltip' => 'Avvisi importanti'],
                'info' => [
                    'label' => 'Informazione',
                    'tooltip' => 'Informazioni generali'],
                'success' => [
                    'label' => 'Successo',
                    'tooltip' => 'Operazioni completate con successo'],
                'warning' => [
                    'label' => 'Attenzione',
                    'tooltip' => 'Avvisi che richiedono attenzione'],
                'error' => [
                    'label' => 'Errore',
                    'tooltip' => 'Errori e problemi']],
            'helper_text' => '',
            'description' => ''],
<<<<<<< HEAD
=======
                    'tooltip' => 'Notifiche di sistema e manutenzione',
                ],
                'alert' => [
                    'label' => 'Avviso',
                    'tooltip' => 'Avvisi importanti',
                ],
                'info' => [
                    'label' => 'Informazione',
                    'tooltip' => 'Informazioni generali',
                ],
                'success' => [
                    'label' => 'Successo',
                    'tooltip' => 'Operazioni completate con successo',
                ],
                'warning' => [
                    'label' => 'Attenzione',
                    'tooltip' => 'Avvisi che richiedono attenzione',
                ],
                'error' => [
                    'label' => 'Errore',
                    'tooltip' => 'Errori e problemi',
                ],
            ],
            'helper_text' => '',
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'status' => [
            'label' => 'Stato',
            'tooltip' => 'Stato corrente della notifica',
            'options' => [
                'unread' => [
                    'label' => 'Non letta',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    'tooltip' => 'Notifica non ancora visualizzata'],
                'read' => [
                    'label' => 'Letta',
                    'tooltip' => 'Notifica già visualizzata'],
                'archived' => [
                    'label' => 'Archiviata',
                    'tooltip' => 'Notifica spostata nell\'archivio']],
            'helper_text' => '',
            'description' => ''],
<<<<<<< HEAD
=======
                    'tooltip' => 'Notifica non ancora visualizzata',
                ],
                'read' => [
                    'label' => 'Letta',
                    'tooltip' => 'Notifica già visualizzata',
                ],
                'archived' => [
                    'label' => 'Archiviata',
                    'tooltip' => 'Notifica spostata nell\'archivio',
                ],
            ],
            'helper_text' => '',
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'recipient' => [
            'label' => 'Destinatario',
            'tooltip' => 'Utente o gruppo destinatario della notifica',
            'placeholder' => 'es: mario.rossi@example.com',
            'help' => 'Seleziona uno o più destinatari',
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
        'sent_at' => [
            'label' => 'Inviata il',
            'tooltip' => 'Data e ora di invio della notifica',
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
        'read_at' => [
            'label' => 'Letta il',
            'tooltip' => 'Data e ora di lettura della notifica',
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
        'archived_at' => [
            'label' => 'Archiviata il',
            'tooltip' => 'Data e ora di archiviazione della notifica',
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
        'mark_as_read' => [
            'label' => 'Segna come letta',
            'tooltip' => 'Segna questa notifica come letta',
            'icon' => 'heroicon-o-check',
<<<<<<< HEAD
<<<<<<< HEAD
            'color' => 'success'],
=======
            'color' => 'success',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'color' => 'success'],
>>>>>>> a988596b (first)
        'mark_as_unread' => [
            'label' => 'Segna come non letta',
            'tooltip' => 'Segna questa notifica come non letta',
            'icon' => 'heroicon-o-x-circle',
<<<<<<< HEAD
<<<<<<< HEAD
            'color' => 'warning'],
=======
            'color' => 'warning',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'color' => 'warning'],
>>>>>>> a988596b (first)
        'archive' => [
            'label' => 'Archivia',
            'tooltip' => 'Sposta questa notifica nell\'archivio',
            'icon' => 'heroicon-o-archive-box',
<<<<<<< HEAD
<<<<<<< HEAD
            'color' => 'info'],
=======
            'color' => 'info',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'color' => 'info'],
>>>>>>> a988596b (first)
        'unarchive' => [
            'label' => 'Ripristina',
            'tooltip' => 'Ripristina questa notifica dall\'archivio',
            'icon' => 'heroicon-o-archive-box-arrow-down',
<<<<<<< HEAD
<<<<<<< HEAD
            'color' => 'primary'],
=======
            'color' => 'primary',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'color' => 'primary'],
>>>>>>> a988596b (first)
        'delete' => [
            'label' => 'Elimina',
            'tooltip' => 'Elimina definitivamente questa notifica',
            'icon' => 'heroicon-o-trash',
            'color' => 'danger',
            'confirmation' => [
                'title' => 'Conferma eliminazione',
                'message' => 'Sei sicuro di voler eliminare questa notifica?',
                'confirm' => 'Sì, elimina',
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
        'mark_all_read' => [
            'label' => 'Segna tutte come lette',
            'tooltip' => 'Segna tutte le notifiche come lette',
            'icon' => 'heroicon-o-check-circle',
<<<<<<< HEAD
<<<<<<< HEAD
            'color' => 'success'],
=======
            'color' => 'success',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'color' => 'success'],
>>>>>>> a988596b (first)
        'archive_all' => [
            'label' => 'Archivia tutte',
            'tooltip' => 'Sposta tutte le notifiche nell\'archivio',
            'icon' => 'heroicon-o-archive-box-arrow-down',
            'color' => 'info',
            'confirmation' => [
                'title' => 'Conferma archiviazione',
                'message' => 'Sei sicuro di voler archiviare tutte le notifiche?',
                'confirm' => 'Sì, archivia tutte',
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
        'delete_all' => [
            'label' => 'Elimina tutte',
            'tooltip' => 'Elimina definitivamente tutte le notifiche',
            'icon' => 'heroicon-o-trash',
            'color' => 'danger',
            'confirmation' => [
                'title' => 'Conferma eliminazione',
                'message' => 'Sei sicuro di voler eliminare tutte le notifiche?',
                'confirm' => 'Sì, elimina tutte',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'cancel' => 'No, annulla']]],
    'messages' => [
        'no_notifications' => 'Nessuna notifica',
        'success_sent' => 'Notifica inviata con successo',
        'error_sent' => 'Errore nell\'invio della notifica'],
    'filters' => [
        'all' => [
            'label' => 'Tutte',
            'tooltip' => 'Mostra tutte le notifiche'],
        'unread' => [
            'label' => 'Non lette',
            'tooltip' => 'Mostra solo le notifiche non lette'],
        'read' => [
            'label' => 'Lette',
            'tooltip' => 'Mostra solo le notifiche lette'],
        'archived' => [
            'label' => 'Archiviate',
            'tooltip' => 'Mostra solo le notifiche archiviate'],
        'type' => [
            'label' => 'Tipo',
            'tooltip' => 'Filtra per tipo di notifica'],
        'date' => [
            'label' => 'Data',
            'tooltip' => 'Filtra per data']],
    'badges' => [
        'unread' => [
            'label' => 'Non letta',
            'tooltip' => 'Questa notifica non è ancora stata letta'],
<<<<<<< HEAD
=======
                'cancel' => 'No, annulla',
            ],
        ],
    ],
    'messages' => [
        'no_notifications' => 'Nessuna notifica',
        'success_sent' => 'Notifica inviata con successo',
        'error_sent' => 'Errore nell\'invio della notifica',
    ],
    'filters' => [
        'all' => [
            'label' => 'Tutte',
            'tooltip' => 'Mostra tutte le notifiche',
        ],
        'unread' => [
            'label' => 'Non lette',
            'tooltip' => 'Mostra solo le notifiche non lette',
        ],
        'read' => [
            'label' => 'Lette',
            'tooltip' => 'Mostra solo le notifiche lette',
        ],
        'archived' => [
            'label' => 'Archiviate',
            'tooltip' => 'Mostra solo le notifiche archiviate',
        ],
        'type' => [
            'label' => 'Tipo',
            'tooltip' => 'Filtra per tipo di notifica',
        ],
        'date' => [
            'label' => 'Data',
            'tooltip' => 'Filtra per data',
        ],
    ],
    'badges' => [
        'unread' => [
            'label' => 'Non letta',
            'tooltip' => 'Questa notifica non è ancora stata letta',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'priority' => [
            'label' => 'Priorità',
            'tooltip' => 'Livello di priorità della notifica',
            'options' => [
                'high' => [
                    'label' => 'Alta priorità',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    'tooltip' => 'Richiede attenzione immediata'],
                'medium' => [
                    'label' => 'Media priorità',
                    'tooltip' => 'Richiede attenzione in giornata'],
                'low' => [
                    'label' => 'Bassa priorità',
                    'tooltip' => 'Può essere gestita in seguito']]]],
<<<<<<< HEAD
=======
                    'tooltip' => 'Richiede attenzione immediata',
                ],
                'medium' => [
                    'label' => 'Media priorità',
                    'tooltip' => 'Richiede attenzione in giornata',
                ],
                'low' => [
                    'label' => 'Bassa priorità',
                    'tooltip' => 'Può essere gestita in seguito',
                ],
            ],
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'template' => [
        'navigation' => [
            'label' => 'Template Notifiche',
            'plural' => 'Template Notifiche',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'group' => 'Sistema'],
        'form' => [
            'name' => [
                'label' => 'Nome',
                'helper' => 'Nome univoco del template'],
            'subject' => [
                'label' => 'Oggetto',
                'helper' => 'Oggetto della notifica'],
            'type' => [
                'label' => 'Tipo',
                'helper' => 'Tipo di notifica'],
            'body_text' => [
                'label' => 'Testo',
                'helper' => 'Versione testuale della notifica'],
            'body_html' => [
                'label' => 'HTML',
                'helper' => 'Versione HTML della notifica'],
            'preview_data' => [
                'label' => 'Dati Preview',
                'helper' => 'Dati JSON per il preview'],
            'attachments' => [
                'label' => 'Allegati',
                'helper' => 'Allegati alla notifica (max 5 file, 5MB ciascuno]']],
<<<<<<< HEAD
=======
            'group' => 'Sistema',
        ],
        'form' => [
            'name' => [
                'label' => 'Nome',
                'helper' => 'Nome univoco del template',
            ],
            'subject' => [
                'label' => 'Oggetto',
                'helper' => 'Oggetto della notifica',
            ],
            'type' => [
                'label' => 'Tipo',
                'helper' => 'Tipo di notifica',
            ],
            'body_text' => [
                'label' => 'Testo',
                'helper' => 'Versione testuale della notifica',
            ],
            'body_html' => [
                'label' => 'HTML',
                'helper' => 'Versione HTML della notifica',
            ],
            'preview_data' => [
                'label' => 'Dati Preview',
                'helper' => 'Dati JSON per il preview',
            ],
            'attachments' => [
                'label' => 'Allegati',
                'helper' => 'Allegati alla notifica (max 5 file, 5MB ciascuno]',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'preview' => [
            'title' => 'Anteprima Template',
            'subheading' => 'Visualizza come apparirà la notifica',
            'text_version' => 'Versione Testuale',
<<<<<<< HEAD
<<<<<<< HEAD
            'html_version' => 'Versione HTML']],
=======
            'html_version' => 'Versione HTML',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'html_version' => 'Versione HTML']],
>>>>>>> a988596b (first)
    'enums' => [
        'notification_type' => [
            'email' => 'Email',
            'sms' => 'SMS',
<<<<<<< HEAD
<<<<<<< HEAD
            'push' => 'Notifica Push']],
    'label' => 'Notify',
    'plural_label' => 'Notify (Plurale)'];
=======
            'push' => 'Notifica Push',
        ],
    ],
    'label' => 'Notify',
    'plural_label' => 'Notify (Plurale)',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'push' => 'Notifica Push']],
    'label' => 'Notify',
    'plural_label' => 'Notify (Plurale)'];
>>>>>>> a988596b (first)
