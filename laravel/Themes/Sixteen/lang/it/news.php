<?php

declare(strict_types=1);

return [
    'meta' => ['title' => 'Notizie e comunicati', 'description' => 'Notizie, comunicati e avvisi del Comune.', 'breadcrumb' => 'Notizie'],
    'hero' => ['title' => 'Notizie e comunicati', 'subtitle' => 'Aggiornamenti ufficiali e informazioni utili dalla città.'],
    'filters' => ['aria' => 'Filtri notizie', 'all' => 'Tutte le notizie', 'press' => 'Comunicati', 'notices' => 'Avvisi'],
    'latest' => ['title' => 'Ultime notizie', 'subtitle' => 'Resta aggiornato sulle novità del Comune.'],
    'search' => ['label' => 'Cerca notizie', 'placeholder' => 'Cerca notizie'],
    'read_more' => 'Leggi tutto',
    // Fallback degli elenchi blog ed eventi (blocks/blog/index, blocks/events/index): il JSON della pagina li puo' sostituire.
    'index' => [
        'default_category' => 'Notizie',
        'default_event_category' => 'Eventi',
        'search_label' => 'Cerca',
        'search_placeholder' => 'Cerca per parola chiave',
        'search_submit' => 'Invio',
        'result_one' => '1 notizia trovata',
        'result_many' => ':count notizie trovate',
        'feedback' => [
            'title' => 'Quanto sono chiare le informazioni su questa pagina?',
            'legend' => 'Valuta da 1 a 5 stelle la pagina',
            'star_label' => 'Valuta %d stelle su 5',
            'thanks' => 'Grazie, il tuo parere ci aiuterà a migliorare il servizio!',
            'positive' => [
                'question' => 'Quali sono stati gli aspetti che hai preferito?',
                'answers' => [
                    'clear' => 'Le indicazioni erano chiare',
                    'complete' => 'Le indicazioni erano complete',
                    'progress' => 'Capivo sempre che stavo procedendo correttamente',
                    'technical' => 'Non ho avuto problemi tecnici',
                    'other' => 'Altro',
                ],
            ],
            'negative' => [
                'question' => 'Dove hai incontrato le maggiori difficoltà?',
                'answers' => [
                    'clear' => 'A volte le indicazioni non erano chiare',
                    'complete' => 'A volte le indicazioni non erano complete',
                    'progress' => 'A volte non capivo se stavo procedendo correttamente',
                    'technical' => 'Ho avuto problemi tecnici',
                    'other' => 'Altro',
                ],
            ],
            'detail' => [
                'question' => 'Vuoi aggiungere altri dettagli?',
                'label' => 'Dettaglio',
                'help' => 'Inserire massimo 200 caratteri',
            ],
            'back' => 'Indietro',
            'next' => 'Avanti',
        ],
    ],
    'items' => [
        'one' => ['category' => 'Comunicati', 'date_iso' => '2026-09-01', 'date' => '1 settembre 2026', 'title' => 'Nuovi servizi digitali per i cittadini', 'body' => 'Sono disponibili nuovi percorsi online per accedere ai servizi comunali.'],
        'two' => ['category' => 'Avvisi', 'date_iso' => '2026-08-20', 'date' => '20 agosto 2026', 'title' => 'Aggiornamento dei servizi sul territorio', 'body' => 'Consulta gli aggiornamenti operativi e le informazioni per la comunità.'],
        'three' => ['category' => 'Eventi', 'date_iso' => '2026-08-05', 'date' => '5 agosto 2026', 'title' => 'Iniziative aperte alla cittadinanza', 'body' => 'Scopri le prossime iniziative e partecipa alla vita della città.'],
    ],
];
