<?php

declare(strict_types=1);

/*
 * Registro delle azioni sensibili. File nato dal generatore del LangServiceProvider,
 * che riempie le chiavi mancanti col nome della chiave: tradotto, altrimenti in
 * pagina si leggerebbe "subject_type" al posto di "Tipo oggetto".
 */
return [
    'navigation' => ['label' => 'Registro attivita', 'icon' => 'heroicon-o-clipboard-document-list', 'sort' => 78],
    'label' => 'Voce di registro',
    'plural' => 'Registro attivita',
    'actions' => [
        'exportCsv' => ['label' => 'Esporta CSV', 'icon' => 'heroicon-o-arrow-down-tray', 'tooltip' => 'Scarica il registro filtrato'],
        'view' => ['label' => 'Dettaglio', 'icon' => 'heroicon-o-eye', 'tooltip' => 'Apri la voce di registro'],
        'applyFilters' => ['label' => 'Applica filtri', 'icon' => 'heroicon-o-funnel', 'tooltip' => 'Applica i filtri impostati'],
        'openFilters' => ['label' => 'Filtri', 'icon' => 'heroicon-o-funnel', 'tooltip' => 'Apri i filtri'],
        'resetFilters' => ['label' => 'Azzera filtri', 'icon' => 'heroicon-o-x-mark', 'tooltip' => 'Rimuovi tutti i filtri'],
        'applyTableColumnManager' => ['label' => 'Applica colonne', 'icon' => 'heroicon-o-view-columns', 'tooltip' => 'Applica le colonne scelte'],
        'openColumnManager' => ['label' => 'Colonne', 'icon' => 'heroicon-o-view-columns', 'tooltip' => 'Scegli le colonne da mostrare'],
        'resetColumnManager' => ['label' => 'Azzera colonne', 'icon' => 'heroicon-o-x-mark', 'tooltip' => 'Torna alle colonne predefinite'],
        'reorderRecords' => ['label' => 'Riordina', 'icon' => 'heroicon-o-bars-arrow-down', 'tooltip' => "Cambia l'ordine delle righe"],
        'profile' => ['label' => 'Profilo', 'icon' => 'heroicon-o-user-circle', 'tooltip' => 'Apri il tuo profilo'],
        'logout' => ['label' => 'Esci', 'icon' => 'heroicon-o-arrow-right-on-rectangle', 'tooltip' => 'Chiudi la sessione'],
    ],
    'fields' => [
        'created_at' => ['label' => 'Data e ora'],
        'actor' => [
            'name' => ['label' => 'Autore'],
        ],
        'action' => ['label' => 'Azione'],
        'subject_type' => ['label' => 'Tipo oggetto'],
        'subject_id' => ['label' => 'ID oggetto'],
        'panel_id' => ['label' => 'Pannello'],
        'route' => ['label' => 'Rotta'],
        'ip' => ['label' => 'Indirizzo IP'],
        'created_between' => ['label' => 'Periodo'],
        'from' => ['label' => 'Dal', 'placeholder' => 'gg/mm/aaaa', 'helper_text' => 'Inizio del periodo', 'description' => 'Inizio del periodo'],
        'to' => ['label' => 'Al', 'placeholder' => 'gg/mm/aaaa', 'helper_text' => 'Fine del periodo', 'description' => 'Fine del periodo'],
        'value' => ['label' => 'Valore', 'placeholder' => 'Testo da cercare', 'helper_text' => 'Cerca nel registro', 'description' => 'Cerca nel registro'],
    ],
];
