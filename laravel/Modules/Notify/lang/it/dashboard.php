<?php

declare(strict_types=1);

return [
    'resource' => [
        'name' => 'Dashboard',
<<<<<<< HEAD
<<<<<<< HEAD
        'plural' => 'Dashboard'],
=======
        'plural' => 'Dashboard',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'plural' => 'Dashboard'],
>>>>>>> a988596b (first)
    'navigation' => [
        'name' => 'Dashboard',
        'plural' => 'Dashboard',
        'group' => [
            'name' => 'Notifiche',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'description' => 'Panoramica delle notifiche'],
        'label' => 'Dashboard',
        'sort' => 49,
        'icon' => 'notify-dashboard-animated',
        'description' => 'Panoramica del sistema di notifiche'],
    'widgets' => [
        'total_notifications' => [
            'label' => 'Totale Notifiche',
            'description' => 'Numero totale di notifiche nel sistema'],
        'unread_notifications' => [
            'label' => 'Notifiche Non Lette',
            'description' => 'Numero di notifiche ancora da leggere'],
        'notifications_by_type' => [
            'label' => 'Notifiche per Tipo',
            'description' => 'Distribuzione delle notifiche per tipologia'],
        'recent_notifications' => [
            'label' => 'Notifiche Recenti',
            'description' => 'Elenco delle notifiche più recenti'],
        'notification_trends' => [
            'label' => 'Trend Notifiche',
            'description' => 'Andamento delle notifiche nel tempo'],
        'channel_status' => [
            'label' => 'Stato Canali',
            'description' => 'Stato operativo dei canali di notifica'],
        'top_recipients' => [
            'label' => 'Destinatari Principali',
            'description' => 'Utenti che ricevono più notifiche']],
    'cards' => [
        'overall_status' => [
            'label' => 'Stato Generale',
            'description' => 'Panoramica dello stato del sistema di notifiche'],
        'channels' => [
            'label' => 'Canali',
            'description' => 'Configurazione dei canali di notifica'],
        'templates' => [
            'label' => 'Template',
            'description' => 'Template disponibili per le notifiche'],
        'logs' => [
            'label' => 'Log',
            'description' => 'Registri delle attività di notifica']],
<<<<<<< HEAD
=======
            'description' => 'Panoramica delle notifiche',
        ],
        'label' => 'Dashboard',
        'sort' => 49,
        'icon' => 'notify-dashboard-animated',
        'description' => 'Panoramica del sistema di notifiche',
    ],
    'widgets' => [
        'total_notifications' => [
            'label' => 'Totale Notifiche',
            'description' => 'Numero totale di notifiche nel sistema',
        ],
        'unread_notifications' => [
            'label' => 'Notifiche Non Lette',
            'description' => 'Numero di notifiche ancora da leggere',
        ],
        'notifications_by_type' => [
            'label' => 'Notifiche per Tipo',
            'description' => 'Distribuzione delle notifiche per tipologia',
        ],
        'recent_notifications' => [
            'label' => 'Notifiche Recenti',
            'description' => 'Elenco delle notifiche più recenti',
        ],
        'notification_trends' => [
            'label' => 'Trend Notifiche',
            'description' => 'Andamento delle notifiche nel tempo',
        ],
        'channel_status' => [
            'label' => 'Stato Canali',
            'description' => 'Stato operativo dei canali di notifica',
        ],
        'top_recipients' => [
            'label' => 'Destinatari Principali',
            'description' => 'Utenti che ricevono più notifiche',
        ],
    ],
    'cards' => [
        'overall_status' => [
            'label' => 'Stato Generale',
            'description' => 'Panoramica dello stato del sistema di notifiche',
        ],
        'channels' => [
            'label' => 'Canali',
            'description' => 'Configurazione dei canali di notifica',
        ],
        'templates' => [
            'label' => 'Template',
            'description' => 'Template disponibili per le notifiche',
        ],
        'logs' => [
            'label' => 'Log',
            'description' => 'Registri delle attività di notifica',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    'actions' => [
        'refresh' => [
            'label' => 'Aggiorna',
            'tooltip' => 'Aggiorna i dati della dashboard',
            'success_message' => 'Dashboard aggiornata con successo',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Errore nell\'aggiornamento della dashboard'],
=======
            'error_message' => 'Errore nell\'aggiornamento della dashboard',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Errore nell\'aggiornamento della dashboard'],
>>>>>>> a988596b (first)
        'export' => [
            'label' => 'Esporta Dati',
            'tooltip' => 'Esporta i dati statistici in formato CSV',
            'success_message' => 'Dati esportati con successo',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'Errore nell\'esportazione dei dati']],
=======
            'error_message' => 'Errore nell\'esportazione dei dati',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'Errore nell\'esportazione dei dati']],
>>>>>>> a988596b (first)
    'messages' => [
        'success' => 'Operazione completata con successo',
        'error' => 'Si è verificato un errore durante l\'operazione',
        'no_data' => 'Nessun dato disponibile per il periodo selezionato',
<<<<<<< HEAD
<<<<<<< HEAD
        'loading' => 'Caricamento dati in corso...'],
=======
        'loading' => 'Caricamento dati in corso...',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'loading' => 'Caricamento dati in corso...'],
>>>>>>> a988596b (first)
    'label' => 'Dashboard',
    'plural_label' => 'Dashboard (Plurale)',
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
            'description' => '']]];
=======
            'description' => '',
        ],
    ],
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => '']]];
>>>>>>> a988596b (first)
