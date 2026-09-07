<?php

declare(strict_types=1);

return [
    'fields' => [
        'name' => ['label' => 'Nome', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'name.placeholder' => ['label' => 'Inserisci il nome della feature', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'name.helper_text' => ['label' => 'Il nome della feature', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'scope' => ['label' => 'Ambito', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'scope.placeholder' => ['label' => 'Inserisci l\'ambito della feature', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'scope.helper_text' => ['label' => 'L\'ambito della feature (es. globale, utente, team]', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'value' => ['label' => 'Valore', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'value.placeholder' => ['label' => 'Inserisci il valore della feature', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'value.helper_text' => ['label' => 'Il valore o la configurazione della feature', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'id' => ['label' => 'id'],
        'uuid' => ['label' => 'uuid'],
        'description' => ['label' => 'description'],
        'created_at' => ['label' => 'created_at'],
        'updated_at' => ['label' => 'updated_at'],
        'deleted_at' => ['label' => 'deleted_at'],
        'updated_by' => ['label' => 'updated_by'],
        'created_by' => ['label' => 'created_by'],
        'deleted_by' => ['label' => 'deleted_by'],
    ],
    'navigation' => [
        'name' => 'Features',
        'plural' => 'Features',
        'group' => ['name' => 'General', 'description' => 'General Settings'],
        'label' => 'Features',
        'sort' => 1,
        'icon' => 'heroicon-o-collection',
    ],
    'label' => 'Features',
    'plural_label' => 'Features (Plurale)',
    'actions' => [
        'create' => ['label' => 'Crea Features', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Features'],
        'delete' => ['label' => 'Elimina Features'],
    ],
];
