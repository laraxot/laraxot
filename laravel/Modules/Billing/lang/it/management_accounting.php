<?php

declare(strict_types=1);

return [
    'navigation' => 'Controllo di gestione',

    'aging' => [
        'title' => 'Chi ti deve pagare',
        'as_of' => 'Al :date',
        'total' => 'Da incassare',
        'overdue_total' => 'Scaduto',
        'empty' => 'Non c\'è niente da incassare. Tutte le fatture sono state pagate.',
    ],

    'buckets' => [
        'not_due' => 'Non ancora scadute',
        'days_1_30' => 'Scadute da 1–30 giorni',
        'days_31_60' => 'Scadute da 31–60 giorni',
        'days_61_90' => 'Scadute da 61–90 giorni',
        'over_90' => 'Scadute da oltre 90 giorni',
    ],

    'cash_flow' => [
        'title' => 'Flusso di cassa',
        'opening_balance' => 'Saldo di oggi',
        'closing_balance' => 'Saldo a fine periodo',
        'lowest_balance' => 'Punto più basso',
        'first_shortfall' => 'I soldi finiscono il',
        'no_shortfall' => 'Il saldo resta positivo per tutto il periodo.',
        'inflow' => 'Entrate',
        'outflow' => 'Uscite',
        'balance' => 'Saldo',
    ],

    'margin' => [
        'title' => 'Utile per commessa',
        'revenue' => 'Fatturato',
        'labour_cost' => 'Manodopera',
        'material_cost' => 'Materiali',
        'external_cost' => 'Terzisti',
        'total_cost' => 'Costo totale',
        'margin' => 'Utile',
        'margin_percent' => 'Utile %',
        'no_revenue' => 'Non ancora fatturata',
        'loss' => 'In perdita',
        'rate_in_use' => 'Manodopera calcolata a :rate €/ora.',
        'default_rate_warning' => 'Il costo orario aziendale non è ancora impostato: sto usando :rate €/ora come stima. Metti il tuo valore reale, altrimenti l\'utile per commessa è indicativo.',
    ],

    'fields' => [
        'due_on' => 'Scadenza',
        'paid_on' => 'Pagata il',
        'paid_amount' => 'Importo pagato',
        'outstanding' => 'Residuo',
        'days_overdue' => 'Giorni di ritardo',
    ],
];
