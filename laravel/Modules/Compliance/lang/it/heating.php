<?php

declare(strict_types=1);

return [
    'navigation' => 'Manutenzione caldaie',

    'system' => 'Impianto termico',
    'systems' => 'Impianti termici',
    'boilers' => 'Generatori / caldaie',
    'controls' => 'Controlli e manutenzioni',

    'fields' => [
        'code' => 'Codice',
        'customer' => 'Cliente',
        'site' => 'Sede/Ubicazione',
        'description' => 'Descrizione',
        'fuel_type' => 'Combustibile',
        'total_power_kw' => 'Potenza totale',
        'plant_registry_code' => 'Codice catasto impianto',
        'installation_date' => 'Data installazione',
        'is_active' => 'Attivo',
        'notes' => 'Note',
        'boilers' => 'Generatori',
        'status' => 'Stato',

        'brand' => 'Marca',
        'model' => 'Modello',
        'serial_number' => 'Matricola',
        'generator_type' => 'Tipo generatore',
        'power_kw' => 'Potenza',
        'maintenance_interval_months' => 'Intervallo manutenzione (mesi)',
        'efficiency_check_interval_months' => 'Intervallo RCEE (mesi)',
        'next_maintenance' => 'Prossima manutenzione',
        'next_efficiency' => 'Prossimo controllo RCEE',
        'next_due_on' => 'Prossima scadenza',

        'control_type' => 'Tipo controllo',
        'performed_on' => 'Eseguito il',
        'outcome' => 'Esito',
        'efficiency_percent' => 'Rendimento',
        'flue_gas_temp' => 'Temperatura fumi',
        'smoke_index' => 'Indice di fumosità',
        'bollino_code' => 'Codice bollino',
        'bollino' => 'Bollino',
        'scheduled_at' => 'Data/ora programmata',
        'technician' => 'Tecnico',
    ],

    'fuels' => [
        'metano' => 'Metano',
        'gpl' => 'GPL',
        'gasolio' => 'Gasolio',
        'biomassa' => 'Biomassa',
        'altro' => 'Altro',
    ],

    'generators' => [
        'standard' => 'Standard',
        'condensazione' => 'Condensazione',
        'pompa_calore' => 'Pompa di calore',
        'ibrido' => 'Ibrido',
        'altro' => 'Altro',
    ],

    'control_types' => [
        'maintenance' => 'Manutenzione',
        'efficiency_check' => 'Controllo efficienza (RCEE)',
    ],

    'outcomes' => [
        'conforme' => 'Conforme',
        'non_conforme' => 'Non conforme',
    ],

    'actions' => [
        'add_boiler' => 'Aggiungi generatore',
        'register_control' => 'Registra controllo',
        'control_registered' => 'Controllo registrato e scadenza aggiornata.',
        'generate_intervention' => 'Genera intervento',
        'intervention_generated' => 'Intervento programmato creato.',
        'download_rcee' => 'Scarica RCEE (PDF)',
    ],

    'deadlines' => [
        'title' => 'Scadenze manutenzioni',
        'empty' => 'Nessuna scadenza da mostrare.',
    ],

    'status' => [
        'overdue' => 'Scaduto',
        'expiring' => 'In scadenza',
        'ok' => 'In regola',
        'unknown' => 'Da pianificare',
    ],
];
