<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Documenti',
        'plural' => 'Documenti',
        'group' => [
            'name' => 'Sistema',
            'description' => 'Gestione documenti e allegati',
        ],
        'label' => 'documenti',
        'sort' => 20,
        'icon' => 'heroicon-o-document',
    ],
    'fields' => [
        'mediable_type' => ['label' => 'Tipo oggetto'],
        'mediable_id' => ['label' => 'ID oggetto'],
        'uploaded_by_user_id' => ['label' => 'Caricato da'],
        'kind' => ['label' => 'Tipo'],
        'title' => ['label' => 'Titolo'],
        'disk' => ['label' => 'Disco'],
        'path' => ['label' => 'Percorso'],
        'mime_type' => ['label' => 'Tipo MIME'],
        'size_bytes' => ['label' => 'Dimensione (byte)'],
        'meta' => ['label' => 'Metadati'],
        'created_at' => ['label' => 'Creato il'],
        'updated_at' => ['label' => 'Aggiornato il'],
    ],
    'label' => 'Documento',
    'plural_label' => 'Documenti',
];
