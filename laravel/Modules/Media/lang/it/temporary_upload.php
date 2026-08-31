<?php

declare(strict_types=1);

return [
    'navigation' => ['label' => 'temporary upload', 'group' => 'temporary upload', 'icon' => 'temporary upload', 'sort' => 96],
    'label' => 'Temporary Upload',
    'plural_label' => 'Temporary Upload (Plurale)',
    'fields' => [
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'file' => ['label' => 'file', 'helper_text' => 'file', 'description' => 'file', 'placeholder' => 'file'],
        'folder' => ['label' => 'folder', 'helper_text' => 'folder', 'description' => 'folder', 'placeholder' => 'folder'],
        'expires_at' => ['label' => 'expires_at', 'helper_text' => 'expires_at', 'description' => 'expires_at', 'placeholder' => 'expires_at'],
        'filename' => ['label' => 'filename'],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Temporary Upload', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Temporary Upload', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Temporary Upload', 'icon' => 'delete', 'tooltip' => 'delete'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
    ],
];
