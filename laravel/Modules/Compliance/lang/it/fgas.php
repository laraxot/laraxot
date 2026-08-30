<?php

declare(strict_types=1);

return [
    'navigation' => 'F-Gas e catasto',

    'circuit' => 'Circuito refrigerante',
    'circuits' => 'Circuiti refrigeranti',
    'leak_check' => 'Controllo perdite',
    'leak_checks' => 'Controlli perdite',

    'fields' => [
        'code' => 'Codice',
        'customer' => 'Cliente',
        'heating_system' => 'Impianto',
        'description' => 'Descrizione',
        'location' => 'Ubicazione',
        'refrigerant_code' => 'Gas refrigerante',
        'charge_kg' => 'Carica (kg)',
        'co2_equivalent_tonnes' => 'CO₂ equivalente (t)',
        'is_hermetically_sealed' => 'Ermeticamente sigillato',
        'has_leak_detection_system' => 'Rilevatore di perdite',
        'check_interval_months' => 'Frequenza controlli (mesi)',
        'last_leak_check_on' => 'Ultimo controllo',
        'next_leak_check_due_on' => 'Prossimo controllo',
        'performed_on' => 'Eseguito il',
        'outcome' => 'Esito',
        'method' => 'Metodo',
        'gas_added_kg' => 'Gas aggiunto (kg)',
        'gas_recovered_kg' => 'Gas recuperato (kg)',
        'operator_certificate' => 'Patentino operatore',
        'region' => 'Regione',
        'registry' => 'Catasto regionale',
        'registry_submitted_on' => 'Trasmesso al catasto il',
        'registry_reference' => 'Protocollo catasto',
    ],

    'outcomes' => [
        'no_leak' => 'Nessuna perdita',
        'leak_found' => 'Perdita rilevata',
        'repaired' => 'Riparato',
    ],

    'methods' => [
        'direct' => 'Diretto',
        'indirect' => 'Indiretto',
    ],

    'exemptions' => [
        'below_threshold' => 'Sotto le 5 t CO₂ equivalente: nessun obbligo di controllo',
        'hermetically_sealed' => 'Ermeticamente sigillato sotto le 10 t CO₂ equivalente: esente',
        'unknown_refrigerant' => 'Gas non riconosciuto: impossibile calcolare l\'obbligo finché non viene identificato',
    ],

    'registry' => [
        'not_submitted' => 'Da trasmettere',
        'submitted' => 'Trasmesso',
        'no_registry' => 'Nessun catasto configurato per questa regione',
        'manual_notice' => 'La trasmissione al catasto regionale resta manuale: qui si tiene traccia di cosa è già stato trasmesso e cosa no.',
    ],
];
