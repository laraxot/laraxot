<?php

declare(strict_types=1);

/*
 * Etichette della gestione utenti dentro il pannello WTS. Il file nasce dal
 * generatore del LangServiceProvider, che riempie le chiavi mancanti con il
 * nome della chiave stessa: qui sono state tradotte, altrimenti in impianto si
 * leggerebbe "export_xls" al posto di "Esporta in Excel".
 */
return [
    'navigation' => [
        'label' => 'Utenti',
        'group' => 'Amministrazione',
        'icon' => 'heroicon-o-users',
        'sort' => 20,
    ],
    'label' => 'Utente',
    'plural' => 'Utenti',
    'actions' => [
        'export_xls' => ['label' => 'Esporta in Excel', 'icon' => 'heroicon-o-table-cells', 'tooltip' => 'Scarica la lista in formato Excel'],
        'changePassword' => ['label' => 'Cambia password', 'icon' => 'heroicon-o-key', 'tooltip' => 'Imposta una nuova password'],
        'view' => ['label' => 'Dettaglio', 'icon' => 'heroicon-o-eye', 'tooltip' => 'Apri la scheda utente'],
        'edit' => ['label' => 'Modifica', 'icon' => 'heroicon-o-pencil-square', 'tooltip' => 'Modifica la scheda utente'],
        'delete' => ['label' => 'Elimina', 'icon' => 'heroicon-o-trash', 'tooltip' => 'Elimina l\'utente'],
        'deactivate' => ['label' => 'Disattiva', 'icon' => 'heroicon-o-no-symbol', 'tooltip' => 'Disattiva l\'utente senza eliminarlo'],
        'export' => ['label' => 'Esporta', 'icon' => 'heroicon-o-arrow-down-tray', 'tooltip' => 'Esporta i dati selezionati'],
        'create' => ['label' => 'Nuovo utente', 'icon' => 'heroicon-o-plus', 'tooltip' => 'Crea un utente di impianto'],
        'layout' => ['label' => 'Disposizione', 'icon' => 'heroicon-o-squares-2x2', 'tooltip' => 'Cambia la disposizione della lista'],
        'applyFilters' => ['label' => 'Applica filtri', 'icon' => 'heroicon-o-funnel', 'tooltip' => 'Applica i filtri impostati'],
        'openFilters' => ['label' => 'Filtri', 'icon' => 'heroicon-o-funnel', 'tooltip' => 'Apri i filtri'],
        'resetFilters' => ['label' => 'Azzera filtri', 'icon' => 'heroicon-o-x-mark', 'tooltip' => 'Rimuovi tutti i filtri'],
        'applyTableColumnManager' => ['label' => 'Applica colonne', 'icon' => 'heroicon-o-view-columns', 'tooltip' => 'Applica le colonne scelte'],
        'openColumnManager' => ['label' => 'Colonne', 'icon' => 'heroicon-o-view-columns', 'tooltip' => 'Scegli le colonne da mostrare'],
        'resetColumnManager' => ['label' => 'Azzera colonne', 'icon' => 'heroicon-o-x-mark', 'tooltip' => 'Torna alle colonne predefinite'],
        'reorderRecords' => ['label' => 'Riordina', 'icon' => 'heroicon-o-bars-arrow-down', 'tooltip' => 'Cambia l\'ordine delle righe'],
    ],
    'fields' => [
        'name' => ['label' => 'Nome', 'placeholder' => 'Nome e cognome', 'help' => 'Come compare in impianto e nei log di audit'],
        'email' => ['label' => 'Email', 'placeholder' => 'nome@azienda.it', 'help' => 'Serve per accedere al pannello'],
    ],
];
