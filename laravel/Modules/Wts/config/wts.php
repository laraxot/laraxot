<?php

declare(strict_types=1);

/*
 * Solo valori di default: niente env() nei config di modulo (Larastan lo vieta
 * fuori dalla config/ di root).
 */
return [
    'ecos' => [
        'sync_ingresses' => false,
        'sync_stock' => false,
        'connection' => 'ecos',
        'company_code' => 'WTS',
        'unit' => 1,
        'site_unit' => 1,
        'excluded_origin_code' => 'WTS',
        'excluded_origin_unit' => 99,
        'ingress_limit' => 80,
        // 0 = nessun limite: la giacenza va sempre restituita completa.
        'stock_limit' => 0,
    ],
    'legacy' => [
        'connection' => null,
        'table' => null,
        'sheet_code_column' => 'sheet_code',
        'columns' => [
            'sheet_code' => 'sheet_code',
            'producer_name' => 'producer_name',
            'waste_code' => 'waste_code',
            'hp_codes' => 'hp_codes',
            'packaging_description' => 'packaging_description',
            'production_description' => 'production_description',
            'note_1' => 'note_1',
            'note_2' => 'note_2',
        ],
        'fallback_records' => [
            'SR2024-04817' => [
                'sheet_code' => 'SR2024-04817',
                'producer_name' => 'Grafica Veneta Spa',
                'waste_code' => '15 01 02',
                'hp_codes' => 'HP4, HP14',
                'packaging_description' => 'Big bag e bancali misti',
                'production_description' => 'Sfridi da linea stampa',
                'note_1' => 'Linea trattamento 3',
                'note_2' => 'Ciclo produzione cartotecnica',
            ],
            'ST-TERM-001' => [
                'sheet_code' => 'ST-TERM-001',
                'producer_name' => 'Impianti Termoidraulici Estensi Srl',
                'waste_code' => '17 04 05',
                'hp_codes' => 'HP5',
                'packaging_description' => 'Fusti e bancali',
                'production_description' => 'Residui metallici da smontaggio',
                'note_1' => 'Linea cesoiatura 2',
                'note_2' => 'Ciclo recupero metalli',
            ],
        ],
    ],
    // Token per l'agente di stampa locale (Mojito) che pesca i job dalla coda via API.
    'print_agent_token' => '',

    // Formato del PDF etichetta (per stampa su etichettatrice, es. Honeywell PM42).
    // Dimensioni del supporto in mm: impostare secondo il rotolo caricato.
    'label_pdf' => [
        'width_mm' => 140,
        'height_mm' => 50,
        // Risoluzione dell'etichettatrice per lo ZPL (PM42 = 203 dpi = 8 dot/mm).
        'dpi' => 203,
        // Rotazione dell'etichetta ZPL in gradi (0, 90, 180, 270) per adattarla al
        // verso del supporto sulla PM42. 90 = etichetta verticale.
        'zpl_rotate' => 90,
        // Modalità supporto a fine stampa: C=taglierina (taglia ogni etichetta),
        // T=strappo, P=peel, D=taglio ritardato, ''=non impostare (lascia com'è).
        'zpl_media_mode' => 'C',
        // Tracking del supporto (^MN): N=continuo (ignora il gap/fustella e stampa tutta la
        // lunghezza, anche sulla parte "appendi"), W=gap/web, M=black mark, ''=non impostare.
        // N serve se la stampante si ferma al taglio del cartellino lasciando bianca la
        // parte con l'asola. Nota: in continuo non c'è registrazione sul gap.
        // '' = non impostare (sensore gap/tacca normale: si riallinea a ogni cartellino).
        // NON usare 'N' (continuo): senza riallineamento il contenuto DERIVA a ogni stampa.
        'zpl_media_tracking' => '',
        // Forza ^PW/^LL (dimensioni area di stampa). La PM42 senza queste NON stampa,
        // quindi di default true.
        'zpl_force_size' => true,
        // Dimensione REALE del supporto in dot (dalla taratura della stampante). Se >0,
        // sovrascrive ^PW/^LL calcolati: serve per i cartellini fustellati dove il supporto
        // è più lungo della zona bianca. PM42 WTS: larghezza 400, lunghezza 2203.
        'zpl_label_width_dots' => 400,
        'zpl_label_length_dots' => 2203,
        // Offset di posizionamento del contenuto in mm (per centrarlo sulla zona bianca
        // sopra i fori). Valori anche negativi. X = larghezza, Y = lunghezza/avanzamento.
        'zpl_offset_x_mm' => 4,
        // Sposta dati+QR di 135mm lungo il nastro per portarli DOPO il foro (il logo NON
        // riceve questo offset: resta in testa, prima del foro). Il logo prima "spariva"
        // proprio perché veniva spostato anche lui: ora è escluso.
        'zpl_offset_y_mm' => 135,
        // QR: magnificazione (dimensione, 1-10) e posizione X in dot. Il QR non viene
        // ruotato (è quadrato) ed è ancorato a sinistra per non uscire dalla larghezza.
        'zpl_qr_magnification' => 5,
        'zpl_qr_x_dots' => 116,
        // Posizione del QR lungo il nastro (destra/sinistra nel verso di lettura), indipendente
        // dal testo. 60 = +5mm a destra rispetto alla base (20).
        'zpl_qr_y_dots' => 60,
        // Logo W.T.S. (grafica ZPL pre-generata in resources/wts/label-logo.gfa),
        // posizionato nella zona bianca verso il foro. Posizione X/Y in dot.
        'zpl_logo_enabled' => true,
        'zpl_logo_x_dots' => 116,
        // Logo in TESTA (dove sta il QR), prima del foro. Non riceve l'offset Y.
        'zpl_logo_y_dots' => 20,
    ],

    // Tipo collo usato per la generazione automatica alla presa in carico,
    // quando ECOS dichiara un numero di colli ma non la tipologia. L'operatore
    // puo' comunque correggere tipo e quantita' in UI-03.
    'default_unit_type' => 'big_bag',

    'unit_types' => [
        'big_bag' => 'Big bag',
        'drum' => 'Fusto',
        'dirty_drum' => 'Fusto sporco',
        'pallet' => 'Bancale',
        'ibc' => 'IBC',
        'tank' => 'Cisternetta',
    ],
    'processing_step_types' => [
        'sorting' => 'Selezione e cernita',
        'unpackaging' => 'Sconfezionamento',
        'grinding' => 'Triturazione',
        'washing' => 'Lavaggio',
        'pressing' => 'Pressatura',
    ],
    'grinding_output_materials' => [
        '191202' => '191202 ferro',
        '191211*' => '191211* imballaggi pericolosi',
        '191212' => '191212 imballaggi misti',
        '191204' => '191204 plastica',
        '191205' => '191205 vetro',
    ],
    'legacy_ingress_document_prefixes' => [
        'ING',
        'INV',
    ],
    'legacy_ingress_document_keys' => [
        'document_type',
        'doc_type',
        'movement_type',
        'tipo_doc',
        'tipo_documento',
    ],
    'legacy_movement_date_keys' => [
        'mte_dat_part',
        'movement_departure_date',
        'movement_date',
        'data_movimento',
        'mte_dat_r',
        'registration_date',
        'received_date',
    ],
    'operational_parameters' => [
        'standard_drum_weight_kg' => [
            'label' => 'Peso standard fusto',
            'value' => 18,
            'unit' => 'kg',
        ],
        'standard_dirty_drum_weight_kg' => [
            'label' => 'Peso standard fusto sporco',
            'value' => 22,
            'unit' => 'kg',
        ],
        'standard_pallet_weight_kg' => [
            'label' => 'Peso standard bancale',
            'value' => 20,
            'unit' => 'kg',
        ],
        'non_conformity_recipient' => [
            'label' => 'Email non conformita',
            'value' => 'qualita@wts.example',
            'unit' => 'email',
        ],
    ],
    'stock_views' => [
        'incoming' => 'In ingresso',
        'to_process' => 'Da lavorare',
        'in_process' => 'In lavorazione',
        'finished' => 'Lavorato/finito',
    ],
];
