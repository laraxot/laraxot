<?php

declare(strict_types=1);

return [
    'meta' => ['title' => 'Servizi per la città', 'description' => 'Invia una segnalazione, consulta quelle pubbliche o segui una pratica con il codice di tracciamento.', 'breadcrumb' => 'Servizi'],
    'hero' => ['title' => 'Servizi per la città', 'subtitle' => 'Invia una segnalazione, consulta quelle pubbliche o segui una pratica con il codice di tracciamento.', 'search_label' => 'Cerca tra le attività disponibili', 'search_placeholder' => 'Cerca un’attività...'],
    'nav' => ['aria' => 'Attività disponibili', 'report' => 'Invia una segnalazione', 'browse' => 'Consulta le segnalazioni', 'track' => 'Segui una pratica'],
    'featured' => ['title' => 'Cosa puoi fare', 'subtitle' => 'Scegli l’attività più adatta: ogni collegamento apre un percorso disponibile.'],
    'card' => [
        'featured' => 'In evidenza',
        'access' => 'Accedi al servizio',
        'access_aria' => 'Accedi al servizio: :title',
        'status' => ['active' => 'Attivo', 'inactive' => 'Non disponibile', 'maintenance' => 'In manutenzione'],
    ],
    'tasks' => [
        'report_title' => 'Segnala un problema', 'report_body' => 'Descrivi un disservizio nello spazio pubblico e invialo agli uffici competenti.', 'report_auth_note' => 'Per inviare una segnalazione è necessario accedere al proprio account.', 'report_action' => 'Invia una segnalazione',
        'browse_title' => 'Esplora la mappa e l’elenco', 'browse_body' => 'Consulta le segnalazioni pubbliche e scopri gli interventi in corso nella città.', 'browse_action' => 'Apri le segnalazioni',
        'track_title' => 'Segui una segnalazione', 'track_body' => 'Controlla gli aggiornamenti usando il codice di tracciamento ricevuto dopo l’invio.', 'track_action' => 'Vai al tracciamento',
    ],
    'results' => ['singular' => 'attività disponibile', 'plural' => 'attività disponibili', 'empty' => 'Nessuna attività corrisponde alla ricerca. Prova con parole diverse.'],
    'categories' => [
        'title' => 'Categorie per le segnalazioni',
        'subtitle' => 'Scegli il tipo di problema che vuoi segnalare oppure consulta le segnalazioni già pubblicate.',
        'actions' => ['aria' => 'Azioni sulle segnalazioni', 'report' => 'Segnala un problema', 'browse' => 'Consulta le segnalazioni'],
        'directory' => ['title' => 'Esplora per categoria', 'subcategories' => 'Sottocategorie', 'empty' => 'Le categorie non sono ancora disponibili. Puoi comunque consultare le segnalazioni pubbliche.'],
        'items' => [
            'reports' => ['title' => 'Segnalazioni pubbliche', 'description' => 'Consulta le segnalazioni che il Comune ha reso pubbliche e lo stato degli interventi.'],
        ],
    ],
];
