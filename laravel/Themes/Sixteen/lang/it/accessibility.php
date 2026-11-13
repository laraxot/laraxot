<?php

declare(strict_types=1);

/*
 * Dichiarazione di accessibilita' (L. 4/2004 art. 3-quater; DCP-132): cornice e testi fissi della pagina. Stato di conformita', date,
 * parti non accessibili e contatti NON stanno qui: li fornisce l'ente nel JSON `accessibilita.index` (blocco accessibility-statement).
 */
return [
    'high_contrast_toggle' => 'Attiva o disattiva il contrasto elevato',
    'contrast' => 'Contrasto',
    'home' => 'Home',
    'page' => ['title' => ['text' => 'Dichiarazione di accessibilità']],
    'title' => [
        'text' => 'Dichiarazione di accessibilità',
        'label' => 'Dichiarazione di accessibilità',
    ],
    'lead' => 'Dichiarazione resa ai sensi dell’art. 3-quater della legge 9 gennaio 2004, n. 4.',
    'incomplete' => [
        'title' => 'Dichiarazione non ancora completa',
        'intro' => 'L’ente deve ancora fornire i dati seguenti prima della messa in produzione. Finché mancano, questa dichiarazione non è valida.',
    ],
    'scope' => [
        'title' => 'Ambito della dichiarazione',
        'commitment' => ':entity si impegna a rendere accessibile questo sito web, conformemente alla legge 9 gennaio 2004, n. 4, che recepisce la direttiva (UE) 2016/2102.',
        'text' => 'La presente dichiarazione si applica al sito web :url (pagine pubbliche e area personale del cittadino).',
    ],
    'status' => [
        'title' => 'Stato di conformità',
        'compliant' => 'Questo sito web è conforme ai requisiti di accessibilità della legge 9 gennaio 2004, n. 4.',
        'partially_compliant' => 'Questo sito web è parzialmente conforme ai requisiti di accessibilità della legge 9 gennaio 2004, n. 4, a causa dei casi di non conformità elencati di seguito.',
        'non_compliant' => 'Questo sito web non è conforme ai requisiti di accessibilità della legge 9 gennaio 2004, n. 4. I casi di non conformità sono elencati di seguito.',
        'pending' => 'Non ancora determinato: l’ente indicherà lo stato dopo la valutazione di accessibilità.',
        'reference' => 'Riferimento tecnico: norma UNI CEI EN 301 549 V3.2.1 (punto 9), che richiede il livello AA delle WCAG 2.1, come previsto dalle linee guida AgID sull’accessibilità degli strumenti informatici.',
    ],
    'content' => [
        'title' => 'Contenuti non accessibili',
        'non_accessible' => 'Parti non conformi alla legge',
        'disproportionate_burden' => 'Onere sproporzionato',
        'out_of_scope' => 'Contenuti non soggetti alla legge',
        'reason' => 'Motivazione',
        'none' => 'L’ente non dichiara contenuti non accessibili.',
        'pending' => 'Da indicare a cura dell’ente.',
    ],
    'drafting' => [
        'title' => 'Redazione della dichiarazione',
        'method' => 'Metodo di valutazione',
        'methods' => ['self' => 'Autovalutazione', 'third_party' => 'Valutazione di terzi'],
        'evaluation_date' => 'Data della valutazione',
        'published_at' => 'Data di pubblicazione',
        'updated_at' => 'Ultimo aggiornamento',
        'pending' => 'Non ancora indicato',
        'agid' => 'Dichiarazione compilata sulla piattaforma AgID',
        'verified' => 'Che cosa è stato verificato',
        'not_verified' => 'Che cosa non è stato verificato',
    ],
    'feedback' => [
        'title' => 'Feedback e contatti',
        'intro' => 'Per segnalare un problema di accessibilità di questo sito o chiedere informazioni usa il modulo qui sotto.',
        'jump' => 'Segnala un problema di accessibilità',
        'email' => 'Puoi anche scrivere a',
        'pending' => 'L’indirizzo per le segnalazioni sarà indicato dall’ente.',
        'officer' => 'Responsabile dell’accessibilità',
    ],
    'procedure' => [
        'title' => 'Procedura di attuazione',
        'text' => 'Se non ricevi risposta entro trenta giorni, o la risposta non ti soddisfa, puoi attivare la procedura di attuazione davanti al difensore civico per il digitale presso AgID (legge 4/2004, art. 3-quinquies).',
        'link' => 'Procedura di attuazione (AgID)',
    ],
];
