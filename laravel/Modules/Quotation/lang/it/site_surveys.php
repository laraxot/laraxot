<?php

declare(strict_types=1);

return [
    'navigation' => 'Sopralluoghi',
    'site_survey' => 'Sopralluogo',
    'site_surveys' => 'Sopralluoghi',

    'fields' => [
        'code' => 'Codice',
        'customer' => 'Cliente',
        'customer_site' => 'Sede',
        'notes' => 'Note',
        'performed_at' => 'Effettuato il',
        'performed_by' => 'Effettuato da',
        'quotation' => 'Preventivo',
        'scheduled_at' => 'Programmato per',
        'status' => 'Stato',
        'title' => 'Titolo',
        'work_order' => 'Commessa',
        'attachments_count' => 'Allegati',
    ],

    'statuses' => [
        'planned' => 'Da fare',
        'done' => 'Rilevato',
        'quoted' => 'Preventivo emesso',
    ],

    'sections' => [
        'general' => 'Generale',
        'links' => 'Collegamenti',
        'outcome' => 'Esito',
    ],

    'hints' => [
        'no_attachments' => 'Nessun allegato: aggiungi foto, video o note vocali del rilievo.',
    ],

    'actions' => [
        'record' => 'Registra sopralluogo',
        'record_hint' => 'Segna il sopralluogo come effettuato e salva gli allegati.',
        'recorded_title' => 'Sopralluogo registrato',
        'recorded_body' => 'Il sopralluogo è stato registrato correttamente.',
        'attachments' => 'Allegati',
    ],

    'attachments' => [
        'title' => 'Allegati',
        'download' => 'Scarica',
        'file' => 'File',
        'file_title' => 'Titolo file',
        'kind' => 'Tipo',
        'kinds' => [
            'photo' => 'Foto',
            'video' => 'Video',
            'audio' => 'Nota vocale',
        ],
        'uploaded_at' => 'Caricato il',
    ],
];
