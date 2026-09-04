<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Commesse',
        'group' => 'Commesse',
        'icon' => 'heroicon-o-briefcase',
        'sort' => 10,
    ],
    'fields' => [
        'code' => [
            'label' => 'Codice',
        ],
        'status_id' => [
            'label' => 'Stato',
        ],
        'customer_id' => [
            'label' => 'Cliente',
        ],
        'customer_site_id' => [
            'label' => 'Sede',
        ],
        'primary_contact_id' => [
            'label' => 'Referente Principale',
        ],
        'title' => [
            'label' => 'Titolo',
        ],
        'description' => [
            'label' => 'Descrizione',
        ],
        'notes' => [
            'label' => 'Note',
        ],
        'opened_at' => [
            'label' => 'Aperta il',
        ],
        'scheduled_at' => [
            'label' => 'Programmata il',
        ],
        'started_at' => [
            'label' => 'Iniziata il',
        ],
        'completed_at' => [
            'label' => 'Completata il',
        ],
        'planned_minutes' => [
            'label' => 'Minuti Pianificati',
        ],
        'effort_summary' => [
            'label' => 'Scostamento',
        ],
        'status.name' => [
            'label' => 'Stato',
        ],
        'customer.legal_name' => [
            'label' => 'Cliente',
        ],
        'site.name' => [
            'label' => 'Sede',
        ],
        'primaryContact.full_name' => [
            'label' => 'Referente Principale',
        ],
        'effort_variance' => [
            'label' => 'Scostamento',
        ],
        'from' => [
            'label' => 'Report Dal',
        ],
        'to' => [
            'label' => 'Report Al',
        ],
        'format' => [
            'label' => 'Formato Report',
        ],
    ],
    'actions' => [
        'exportReport' => [
            'label' => 'Esporta Report',
        ],
        'downloadPdf' => [
            'label' => 'Scarica PDF',
        ],
        'restoreCascade' => [
            'label' => 'Ripristina Commessa e Dati Collegati',
        ],
    ],
];
