<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Utente Team',
        'plural' => 'Utenti Team',
        'label' => 'Utenti Team',
        'group' => ['name' => 'Teams', 'description' => 'Gestione degli utenti associati ai team'],
        'sort' => 65,
        'icon' => 'heroicon-o-user-group',
    ],
    'label' => 'Team User',
    'plural_label' => 'Team User (Plurale)',
    'fields' => [
        'edit' => ['label' => 'Modifica Team User'],
        'delete' => ['label' => 'Elimina Team User', 'icon' => 'delete', 'tooltip' => 'delete'],
        'logout' => ['tooltip' => 'logout', 'icon' => 'logout', 'label' => 'logout'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'applyFilters' => ['label' => 'applyFilters', 'icon' => 'applyFilters', 'tooltip' => 'applyFilters'],
        'openFilters' => ['label' => 'openFilters', 'icon' => 'openFilters', 'tooltip' => 'openFilters'],
        'resetFilters' => ['label' => 'resetFilters', 'icon' => 'resetFilters', 'tooltip' => 'resetFilters'],
        'applyTableColumnManager' => ['label' => 'applyTableColumnManager', 'icon' => 'applyTableColumnManager', 'tooltip' => 'applyTableColumnManager'],
        'openColumnManager' => ['label' => 'openColumnManager', 'icon' => 'openColumnManager', 'tooltip' => 'openColumnManager'],
        'resetColumnManager' => ['label' => 'resetColumnManager', 'icon' => 'resetColumnManager', 'tooltip' => 'resetColumnManager'],
        'reorderRecords' => ['label' => 'reorderRecords', 'icon' => 'reorderRecords', 'tooltip' => 'reorderRecords'],
        'profile' => ['label' => 'profile', 'icon' => 'profile', 'tooltip' => 'profile'],
    ],
    'sections' => [
        'empty' => ['label' => '', 'heading' => ''],
    ],
];
